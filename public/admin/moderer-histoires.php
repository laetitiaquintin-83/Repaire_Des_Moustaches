<?php
declare(strict_types=1);

session_start();

// ============================================================
// 🔒 VÉRIFICATION D'ACCÈS ADMIN
// ============================================================
if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}
// ============================================================

require_once __DIR__ . '/../../config/database.php';

$pdo = getPDO();
$message = '';
$error = '';

// Token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Actions POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? '');
    $id = (int) ($_POST['id'] ?? 0);
    $csrf_check = (string) ($_POST['csrf_token'] ?? '');

    if (!hash_equals($_SESSION['csrf_token'], $csrf_check)) {
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
    
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $csrf_token = $_SESSION['csrf_token'];
}

// Récupération selon l'onglet
$tab = (string) ($_GET['tab'] ?? 'attente');

$statut = match ($tab) {
    'publiees' => 'publiee',
    'refusees' => 'refusee',
    default => 'en_attente',
};

if ($statut === 'en_attente') {
    $tab = 'attente';
}

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
$histoires = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Stats
$stats = ['en_attente' => 0, 'publiee' => 0, 'refusee' => 0];
$stmt = $pdo->query("SELECT statut, COUNT(*) AS count FROM belles_histoires GROUP BY statut");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Pacifico&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #fdfbf7; /* Le fond crème doux */
            color: #2b2b2b;
        }

        .admin-container { display: flex; min-height: 100vh; }

        /* --- SIDEBAR SOMBRE ALIGNÉE SUR "COMMANDES" --- */
        .admin-sidebar {
            width: 250px;
            background: #2b2b2b;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 30px 0 20px 0;
            position: fixed;
            height: 100vh;
        }

        .admin-logo {
            text-align: center;
            padding-bottom: 25px;
        }

        .admin-logo h2 {
            font-family: 'Pacifico', cursive;
            font-size: 1.8rem;
            color: #85D6CD; /* Vert menthe */
            font-weight: normal;
        }

        .admin-menu { list-style: none; }

        .admin-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 25px;
            color: #d1d5db;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .admin-menu a:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }

        .admin-menu a.active {
            background: rgba(133, 214, 205, 0.15);
            color: #85D6CD;
            border-left: 4px solid #85D6CD;
        }

        .admin-menu a.home-link {
            color: #FE7B7E; /* Rouge Corail */
            margin-bottom: 15px;
        }

        .admin-user-info {
            padding: 20px 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.85rem;
        }

        .admin-user-info p { color: #9ca3af; margin-bottom: 4px; }
        .admin-user-info strong { display: block; color: #ffffff; margin-bottom: 10px; word-break: break-all; }
        .admin-user-info a { color: #FE7B7E; text-decoration: none; font-weight: 600; }

        /* --- CONTENU PRINCIPAL --- */
        .admin-main {
            flex: 1;
            margin-left: 250px;
            padding: 40px;
        }

        .admin-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #2b2b2b;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Onglets filtres */
        .tabs { display: flex; gap: 12px; margin-bottom: 25px; }

        .tab-btn {
            padding: 8px 20px;
            background: #ffffff;
            border: 2px solid #e5e7eb;
            border-radius: 20px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.9rem;
            color: #4b5563;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .tab-btn.active {
            background: #85D6CD;
            color: #ffffff;
            border-color: #85D6CD;
        }

        .tab-btn:hover:not(.active) { border-color: #85D6CD; color: #85D6CD; }

        /* Main Card */
        .admin-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }

        .admin-card h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2b2b2b;
            padding-bottom: 12px;
            margin-bottom: 25px;
            border-bottom: 3px solid #85D6CD;
        }

        /* Cartes Histoires */
        .histoire-card {
            border: 1px solid #e5e7eb;
            border-left: 4px solid #85D6CD;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #ffffff;
        }

        .histoire-card h3 {
            color: #1f2937;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .histoire-meta {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .histoire-contenu {
            color: #374151;
            line-height: 1.6;
            margin-bottom: 15px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 6px;
            font-size: 0.95rem;
        }

        /* Badges */
        .statut-badge {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .statut-en_attente { background: #fef3c7; color: #92400e; }
        .statut-publiee { background: #d1fae5; color: #065f46; }
        .statut-refusee { background: #fee2e2; color: #991b1b; }

        /* Boutons d'action */
        .actions { display: flex; gap: 10px; }
        .btn {
            padding: 8px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .btn-success { background: #85D6CD; color: white; }
        .btn-success:hover { background: #6bc3b8; }
        .btn-danger { background: #FE7B7E; color: white; }
        .btn-danger:hover { background: #e66769; }

        .empty-state { text-align: center; padding: 40px 20px; color: #6b7280; }
        
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; font-size: 0.9rem; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div>
                <div class="admin-logo">
                    <h2>Admin</h2>
                </div>
                <ul class="admin-menu">
                    <li><a href="../index.php" class="home-link">🏠 Retour à l'accueil</a></li>
                    <li><a href="dashboard.php">📊 Dashboard</a></li>
                    <li><a href="moderer-histoires.php" class="active">📖 Belles Histoires</a></li>
                    <li><a href="ateliers.php">🎨 Ateliers</a></li>
                    <li><a href="produits.php">🛍️ Produits</a></li>
                    <li><a href="commandes.php">📦 Commandes</a></li>
                    <li><a href="utilisateurs.php">👥 Utilisateurs</a></li>
                </ul>
            </div>
            
            <div class="admin-user-info">
                <p>Connecté :</p>
                <strong><?= htmlspecialchars($_SESSION['admin_email'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></strong>
                <a href="../logout.php">🚪 Déconnexion</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1>📖 Gestion des Belles Histoires</h1>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <!-- Onglets -->
            <div class="tabs">
                <a href="?tab=attente" class="tab-btn <?= $tab === 'attente' ? 'active' : '' ?>">
                    ⏳ À modérer <?= $stats['en_attente'] > 0 ? "({$stats['en_attente']})" : '' ?>
                </a>
                <a href="?tab=publiees" class="tab-btn <?= $tab === 'publiees' ? 'active' : '' ?>">
                    ✓ Publiées (<?= $stats['publiee'] ?>)
                </a>
                <a href="?tab=refusees" class="tab-btn <?= $tab === 'refusees' ? 'active' : '' ?>">
                    ✕ Refusées (<?= $stats['refusee'] ?>)
                </a>
            </div>

            <div class="admin-card">
                <h2>
                    <?php 
                        if ($tab === 'attente') echo 'Histoires en attente de modération';
                        elseif ($tab === 'publiees') echo 'Histoires publiées';
                        else echo 'Histoires refusées';
                    ?>
                </h2>

                <?php if (empty($histoires)): ?>
                    <div class="empty-state">
                        <?php
                            if ($tab === 'attente') echo 'Aucune histoire en attente de modération pour le moment ! 🎉';
                            elseif ($tab === 'publiees') echo 'Aucune histoire publiée pour le moment.';
                            else echo 'Aucune histoire refusée.';
                        ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($histoires as $histoire): ?>
                        <div class="histoire-card">
                            <h3><?= htmlspecialchars($histoire['titre'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <div class="histoire-meta">
                                <span class="statut-badge statut-<?= $histoire['statut'] ?>">
                                    <?php
                                        $statutLabels = [
                                            'en_attente' => '⏳ En attente',
                                            'publiee' => '✓ Publiée',
                                            'refusee' => '✕ Refusée'
                                        ];
                                        echo $statutLabels[$histoire['statut']] ?? $histoire['statut'];
                                    ?>
                                </span>
                                • Par <strong><?= htmlspecialchars(trim(($histoire['prenom'] ?? '') . ' ' . ($histoire['nom'] ?? '')), ENT_QUOTES, 'UTF-8') ?></strong>
                                • <?= !empty($histoire['date_publication']) ? 'Traitée le ' . date('d/m/Y à H:i', strtotime($histoire['date_publication'])) : 'En attente' ?>
                            </div>
                            <div class="histoire-contenu">
                                <?= nl2br(htmlspecialchars($histoire['contenu'], ENT_QUOTES, 'UTF-8')) ?>
                            </div>
                            <?php if ($histoire['statut'] === 'en_attente'): ?>
                                <div class="actions">
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="action" value="publier">
                                        <input type="hidden" name="id" value="<?= (int)$histoire['id'] ?>">
                                        <button type="submit" class="btn btn-success">✓ Publier</button>
                                    </form>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir rejeter cette histoire ?');">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="action" value="rejeter">
                                        <input type="hidden" name="id" value="<?= (int)$histoire['id'] ?>">
                                        <button type="submit" class="btn btn-danger">✕ Rejeter</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>