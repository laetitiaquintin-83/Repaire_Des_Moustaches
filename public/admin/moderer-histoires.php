<?php
declare(strict_types=1);

session_start();

// ============================================================
// 🔒 VÉRIFICATION D'ACCÈS ADMIN (rôle requis)
// ============================================================
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
// ============================================================

require_once __DIR__ . '/../../config/database.php';

$pdo = getPDO();
$message = '';
$error = '';

// Générer un token CSRF pour les actions
$csrf_token = generateCSRFToken();

// Traiter les actions (uniquement via POST pour la sécurité)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? '');
    $id = (int) ($_POST['id'] ?? 0);
    $csrf_check = (string) ($_POST['csrf_token'] ?? '');

    // Vérification CSRF
    if (!validateCSRFToken($csrf_check)) {
        $error = 'Erreur de sécurité : token CSRF invalide.';
    } elseif ($action === 'publier' && $id > 0) {
        $stmt = $pdo->prepare('
            UPDATE belles_histoires 
            SET statut = "publiee", admin_id = ?, date_publication = NOW()
            WHERE id = ? AND statut = "en_attente"
        ');
        $stmt->execute([$_SESSION['admin_id'], $id]);
        if ($stmt->rowCount() > 0) {
            $message = '✓ Histoire publiée avec succès !';
        } else {
            $error = 'Histoire introuvable ou déjà traitée.';
        }
    } elseif ($action === 'rejeter' && $id > 0) {
        $stmt = $pdo->prepare('
            UPDATE belles_histoires 
            SET statut = "refusee", admin_id = ?, date_publication = NOW()
            WHERE id = ? AND statut = "en_attente"
        ');
        $stmt->execute([$_SESSION['admin_id'], $id]);
        if ($stmt->rowCount() > 0) {
            $message = '✓ Histoire rejetée.';
        } else {
            $error = 'Histoire introuvable ou déjà traitée.';
        }
    } else {
        $error = 'Action invalide.';
    }
}

// Récupérer les histoires selon l'onglet
$tab = (string) ($_GET['tab'] ?? 'attente');

$statut = match ($tab) {
    'publiees' => 'publiee',
    'refusees' => 'refusee',
    default => 'en_attente',
};

if ($statut === 'en_attente') {
    $tab = 'attente';
}

// Utilisation de COALESCE pour parer au strict_types=1 et au LEFT JOIN (évite les TypeErrors si NULL)
$stmt = $pdo->prepare('
    SELECT 
        bh.id, 
        COALESCE(bh.titre, "") AS titre, 
        COALESCE(bh.contenu, "") AS contenu, 
        bh.statut, 
        bh.date_publication, 
        COALESCE(u.nom, "") AS nom, 
        COALESCE(u.prenom, "Anonyme") AS prenom
    FROM belles_histoires bh
    LEFT JOIN utilisateurs u ON bh.utilisateur_id = u.id
    WHERE bh.statut = ?
    ORDER BY bh.date_publication DESC
');
$stmt->execute([$statut]);
$histoires = $stmt->fetchAll();

// Statistiques pour les onglets (Correction : ajout de l'alias AS count)
$stats = [
    'en_attente' => 0,
    'publiee' => 0,
    'refusee' => 0
];
$stmt = $pdo->query("SELECT statut, COUNT(*) AS count FROM belles_histoires GROUP BY statut");
while ($row = $stmt->fetch()) {
    if (isset($stats[$row['statut']])) {
        $stats[$row['statut']] = (int)$row['count'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modérer les Histoires - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: #F5F5F5;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            width: 250px;
            background: #2B2B2B;
            color: white;
            padding: 30px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .admin-logo {
            padding: 0 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .admin-logo h2 {
            font-family: 'Pacifico', cursive;
            color: #85D6CD;
            font-size: 1.5rem;
            font-weight: normal;
        }
        
        .admin-menu {
            list-style: none;
        }
        
        .admin-menu li {
            margin: 0;
        }
        
        .admin-menu a {
            display: block;
            padding: 12px 20px;
            color: #ccc;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .admin-menu a:hover {
            background: rgba(133, 214, 205, 0.1);
            color: #85D6CD;
            border-left-color: #85D6CD;
        }
        
        .admin-menu a.active {
            background: rgba(133, 214, 205, 0.2);
            color: #85D6CD;
            border-left-color: #85D6CD;
            font-weight: 700;
        }
        
        .admin-user-info {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        
        .admin-user-info p {
            font-size: 0.85rem;
            color: #999;
            margin-bottom: 10px;
        }
        
        .admin-user-info a {
            display: block;
            color: #FE7B7E;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .admin-main {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
        }
        
        .admin-header {
            margin-bottom: 30px;
        }
        
        .admin-header h1 {
            font-size: 1.8rem;
            color: #2B2B2B;
            margin-bottom: 20px;
        }
        
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .tab-btn {
            padding: 10px 20px;
            background: white;
            border: 2px solid #E0E0E0;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #666;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .tab-btn.active {
            background: #85D6CD;
            color: white;
            border-color: #85D6CD;
        }
        
        .tab-btn:hover {
            border-color: #85D6CD;
        }
        
        .badge {
            background: #FE7B7E;
            color: white;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 700;
            margin-left: 5px;
        }
        
        .message {
            background: #E0FFE0;
            border: 1px solid #00A000;
            color: #009900;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .error-message {
            background: #FFE0E0;
            border: 1px solid #FE7B7E;
            color: #C00;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .histoire-card {
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #FAFAFA;
        }
        
        .histoire-card h3 {
            color: #2B2B2B;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        
        .histoire-meta {
            font-size: 0.85rem;
            color: #999;
            margin-bottom: 15px;
        }
        
        .histoire-contenu {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
            padding: 15px;
            background: white;
            border-radius: 4px;
            border-left: 3px solid #85D6CD;
        }
        
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .btn-success {
            background: #00A000;
            color: white;
        }
        
        .btn-success:hover {
            background: #009000;
        }
        
        .btn-danger {
            background: #FE7B7E;
            color: white;
        }
        
        .btn-danger:hover {
            background: #E66367;
        }
        
        .btn-secondary {
            background: #E0E0E0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #ccc;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        
        .statut-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-right: 10px;
        }
        
        .statut-attente {
            background: #FFF3CD;
            color: #856404;
        }
        
        .statut-publiee {
            background: #D4EDDA;
            color: #155724;
        }
        
        .statut-refusee {
            background: #F8D7DA;
            color: #721C24;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <h2>🧔 Admin</h2>
            </div>
            
            <ul class="admin-menu">
                <li><a href="../index.php" style="color: #FE7B7E; font-weight: 700;">🏠 Retour à l'accueil</a></li>
                <li style="margin-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 20px;"></li>
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="moderer-histoires.php" class="active">📖 Belles Histoires</a></li>
                <li><a href="ateliers.php">🎨 Ateliers</a></li>
                <li><a href="produits.php">🛍️ Produits</a></li>
                <li><a href="commandes.php">📦 Commandes</a></li>
                <li><a href="utilisateurs.php">👥 Utilisateurs</a></li>
            </ul>
            
            <div class="admin-user-info">
                <p>Connecté en tant que:</p>
                <strong><?php echo htmlspecialchars($_SESSION['admin_email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong>
                <a href="../logout.php">🚪 Déconnexion</a>
            </div>
        </aside>
        
        <main class="admin-main">
            <div class="admin-header">
                <h1>📖 Gestion des Belles Histoires</h1>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
            
            <div class="tabs">
                <a href="?tab=attente" class="tab-btn <?php echo $tab === 'attente' ? 'active' : ''; ?>">
                    ⏳ À modérer
                    <?php if ($stats['en_attente'] > 0): ?>
                        <span class="badge"><?php echo $stats['en_attente']; ?></span>
                    <?php endif; ?>
                </a>
                <a href="?tab=publiees" class="tab-btn <?php echo $tab === 'publiees' ? 'active' : ''; ?>">
                    ✓ Publiées (<?php echo $stats['publiee']; ?>)
                </a>
                <a href="?tab=refusees" class="tab-btn <?php echo $tab === 'refusees' ? 'active' : ''; ?>">
                    ✕ Refusées (<?php echo $stats['refusee']; ?>)
                </a>
            </div>
            
            <div class="section">
                <?php if (empty($histoires)): ?>
                    <div class="empty-state">
                        <?php
                            if ($tab === 'attente') echo 'Aucune histoire en attente de modération. 🎉';
                            elseif ($tab === 'publiees') echo 'Aucune histoire publiée pour le moment.';
                            else echo 'Aucune histoire refusée.';
                        ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($histoires as $histoire): ?>
                        <div class="histoire-card">
                            <h3><?php echo htmlspecialchars($histoire['titre'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <div class="histoire-meta">
                                <span class="statut-badge statut-<?php echo $histoire['statut']; ?>">
                                    <?php
                                        $statutLabels = [
                                            'en_attente' => '⏳ En attente',
                                            'publiee' => '✓ Publiée',
                                            'refusee' => '✕ Refusées'
                                        ];
                                        echo $statutLabels[$histoire['statut']] ?? $histoire['statut'];
                                    ?>
                                </span>
                                Par <?php echo htmlspecialchars(trim(($histoire['prenom'] ?? '') . ' ' . ($histoire['nom'] ?? '')), ENT_QUOTES, 'UTF-8'); ?> 
                                | <?php 
                                    // Correction : Sécurité de formatage de date au cas où elle vaut NULL (en_attente)
                                    echo !empty($histoire['date_publication']) 
                                        ? 'Traitée le ' . date('d/m/Y à H:i', strtotime($histoire['date_publication'])) 
                                        : 'En attente de traitement'; 
                                ?>
                            </div>
                            <div class="histoire-contenu">
                                <?php echo nl2br(htmlspecialchars($histoire['contenu'], ENT_QUOTES, 'UTF-8')); ?>
                            </div>
                            <div class="actions">
                                <?php if ($histoire['statut'] === 'en_attente'): ?>
                                    <!-- Formulaire POST sécurisé pour publier -->
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="publier">
                                        <input type="hidden" name="id" value="<?php echo (int)$histoire['id']; ?>">
                                        <button type="submit" class="btn btn-success">✓ Publier</button>
                                    </form>
                                    <!-- Formulaire POST sécurisé pour rejeter -->
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir rejeter cette histoire ?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="rejeter">
                                        <input type="hidden" name="id" value="<?php echo (int)$histoire['id']; ?>">
                                        <button type="submit" class="btn btn-danger">✕ Rejeter</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>