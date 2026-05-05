<?php
declare(strict_types=1);

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();
$message = '';

// Traiter les actions
$action = (string) ($_GET['action'] ?? '');
$id = (int) ($_GET['id'] ?? 0);

if ($action === 'publier' && $id > 0) {
    $stmt = $pdo->prepare('
        UPDATE belles_histoires 
        SET statut = "publiee", admin_id = ?, date_publication = NOW()
        WHERE id = ?
    ');
    $stmt->execute([$_SESSION['admin_id'], $id]);
    $message = '✓ Histoire publiée !';
}

if ($action === 'rejeter' && $id > 0) {
    $stmt = $pdo->prepare('DELETE FROM belles_histoires WHERE id = ?');
    $stmt->execute([$id]);
    $message = '✓ Histoire supprimée !';
}

// Récupérer les histoires
$tab = (string) ($_GET['tab'] ?? 'attente');
$where = $tab === 'attente' ? 'WHERE statut = "en_attente"' : 'WHERE statut = "publiee"';

$stmt = $pdo->query('
    SELECT bh.id, bh.titre, bh.contenu, bh.statut, bh.date_publication, u.nom, u.prenom
    FROM belles_histoires bh
    LEFT JOIN utilisateurs u ON bh.utilisateur_id = u.id
    ' . $where . '
    ORDER BY bh.date_publication DESC
');
$histoires = $stmt->fetchAll();
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
        
        .admin-logo img {
            max-width: 60px;
            margin-bottom: 10px;
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
        
        .message {
            background: #E0FFE0;
            border: 1px solid #00A000;
            color: #009900;
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
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <img src="../images/logo.png" alt="Logo">
                <h2>Admin</h2>
            </div>
            
            <ul class="admin-menu">
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="moderer-histoires.php" class="active">📖 Belles Histoires</a></li>
                <li><a href="ateliers.php">🎨 Ateliers</a></li>
                <li><a href="produits.php">🛍️ Produits</a></li>
                <li><a href="commandes.php">📦 Commandes</a></li>
                <li><a href="utilisateurs.php">👥 Utilisateurs</a></li>
            </ul>
            
            <div class="admin-user-info">
                <p>Connecté en tant que:</p>
                <strong><?php echo htmlspecialchars($_SESSION['admin_email'], ENT_QUOTES, 'UTF-8'); ?></strong>
                <a href="../logout.php">Déconnexion</a>
            </div>
        </aside>
        
        <main class="admin-main">
            <div class="admin-header">
                <h1>📖 Gestion des Belles Histoires</h1>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
            
            <div class="tabs">
                <a href="?tab=attente" class="tab-btn <?php echo $tab === 'attente' ? 'active' : ''; ?>">
                    ⏳ À modérer (<?php echo count(array_filter($histoires, fn($h) => $h['statut'] === 'en_attente')); ?>)
                </a>
                <a href="?tab=publiees" class="tab-btn <?php echo $tab === 'publiees' ? 'active' : ''; ?>">
                    ✓ Publiées (<?php echo count(array_filter($histoires, fn($h) => $h['statut'] === 'publiee')); ?>)
                </a>
            </div>
            
            <div class="section">
                <?php if (empty($histoires)): ?>
                    <div class="empty-state">
                        <?php echo $tab === 'attente' ? 'Aucune histoire à modérer.' : 'Aucune histoire publiée.'; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($histoires as $histoire): ?>
                        <div class="histoire-card">
                            <h3><?php echo htmlspecialchars($histoire['titre'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <div class="histoire-meta">
                                Par <?php echo htmlspecialchars($histoire['prenom'] . ' ' . $histoire['nom'], ENT_QUOTES, 'UTF-8'); ?> 
                                | Soumise le <?php echo date('d/m/Y H:i', strtotime($histoire['date_publication'])); ?>
                            </div>
                            <div class="histoire-contenu">
                                <?php echo nl2br(htmlspecialchars($histoire['contenu'], ENT_QUOTES, 'UTF-8')); ?>
                            </div>
                            <div class="actions">
                                <?php if ($histoire['statut'] === 'en_attente'): ?>
                                    <a href="?tab=attente&action=publier&id=<?php echo $histoire['id']; ?>" class="btn btn-success">✓ Publier</a>
                                    <a href="?tab=attente&action=rejeter&id=<?php echo $histoire['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr ?')">✕ Rejeter</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
