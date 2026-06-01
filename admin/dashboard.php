<?php
declare(strict_types=1);

session_start();

// Vérifier si connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();

// Récupérer les stats
$stats = [];

// Histoires en attente
$stmt = $pdo->query('SELECT COUNT(*) FROM belles_histoires WHERE statut = "en_attente"');
$stats['histoires_attente'] = $stmt->fetchColumn();

// Histoires publiées
$stmt = $pdo->query('SELECT COUNT(*) FROM belles_histoires WHERE statut = "publiee"');
$stats['histoires_publiees'] = $stmt->fetchColumn();

// Ateliers à venir
$stmt = $pdo->query('SELECT COUNT(*) FROM ateliers WHERE date_heure > NOW()');
$stats['ateliers_a_venir'] = $stmt->fetchColumn();

// Produits
$stmt = $pdo->query('SELECT COUNT(*) FROM produits');
$stats['produits_total'] = $stmt->fetchColumn();

// Commandes
$stmt = $pdo->query('SELECT COUNT(*) FROM commandes');
$stats['commandes_total'] = $stmt->fetchColumn();

// Utilisateurs
$stmt = $pdo->query('SELECT COUNT(*) FROM utilisateurs');
$stats['utilisateurs_total'] = $stmt->fetchColumn();

// Histoires récentes
$stmt = $pdo->query('
    SELECT id, titre, utilisateur_id, statut, date_publication
    FROM belles_histoires
    ORDER BY date_publication DESC
    LIMIT 5
');
$histoires_recentes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Le Repaire des Moustaches</title>
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
        
        .admin-user-info a:hover {
            text-decoration: underline;
        }
        
        .admin-main {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
        }
        
        .admin-header h1 {
            font-size: 1.8rem;
            color: #2B2B2B;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-card .number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #85D6CD;
        }
        
        .stat-card.alert .number {
            color: #FE7B7E;
        }
        
        .section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .section h2 {
            color: #2B2B2B;
            margin-bottom: 20px;
            font-size: 1.3rem;
            border-bottom: 2px solid #85D6CD;
            padding-bottom: 10px;
        }
        
        .histoires-list {
            list-style: none;
        }
        
        .histoires-list li {
            padding: 15px;
            border-bottom: 1px solid #F0F0F0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .histoires-list li:last-child {
            border-bottom: none;
        }
        
        .histoire-info {
            flex: 1;
        }
        
        .histoire-info strong {
            display: block;
            color: #2B2B2B;
            margin-bottom: 5px;
        }
        
        .histoire-info small {
            color: #999;
        }
        
        .histoire-status {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .status-attente {
            background: #FFE0E0;
            color: #C00;
        }
        
        .status-publiee {
            background: #E0FFE0;
            color: #009900;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            margin-left: 10px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #85D6CD;
            color: white;
        }
        
        .btn-primary:hover {
            background: #6FC1B0;
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
        
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 200px;
            }
            
            .admin-main {
                margin-left: 200px;
                padding: 20px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
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
                <li><a href="../index.php" style="color: #FE7B7E; font-weight: 700;">🏠 Retour à l'accueil</a></li>
                <li style="margin-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 20px;"></li>
                <li><a href="dashboard.php" class="active">📊 Dashboard</a></li>
                <li><a href="moderer-histoires.php">📖 Belles Histoires</a></li>
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
                <h1>Dashboard</h1>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card alert">
                    <h3>📖 À modérer</h3>
                    <div class="number"><?php echo (int) $stats['histoires_attente']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>📖 Histoires</h3>
                    <div class="number"><?php echo (int) $stats['histoires_publiees']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>🎨 Ateliers</h3>
                    <div class="number"><?php echo (int) $stats['ateliers_a_venir']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>🛍️ Produits</h3>
                    <div class="number"><?php echo (int) $stats['produits_total']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>📦 Commandes</h3>
                    <div class="number"><?php echo (int) $stats['commandes_total']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>👥 Utilisateurs</h3>
                    <div class="number"><?php echo (int) $stats['utilisateurs_total']; ?></div>
                </div>
            </div>
            
            <div class="section">
                <h2>📖 Belles Histoires Récentes</h2>
                <?php if (empty($histoires_recentes)): ?>
                    <div class="empty-state">Aucune histoire n'a été soumise.</div>
                <?php else: ?>
                    <ul class="histoires-list">
                        <?php foreach ($histoires_recentes as $histoire): ?>
                            <li>
                                <div class="histoire-info">
                                    <strong><?php echo htmlspecialchars($histoire['titre'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                    <small>Soumise le <?php echo date('d/m/Y H:i', strtotime($histoire['date_publication'])); ?></small>
                                </div>
                                <span class="histoire-status status-<?php echo $histoire['statut']; ?>">
                                    <?php echo ucfirst($histoire['statut']); ?>
                                </span>
                                <a href="moderer-histoires.php" class="btn btn-primary">Voir</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
