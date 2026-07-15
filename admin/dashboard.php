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

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();

// Récupérer quelques stats pour le dashboard
$stats = [];

// Nombre de commandes
$stmt = $pdo->query('SELECT COUNT(*) FROM commandes');
$stats['commandes'] = (int)$stmt->fetchColumn();

// Nombre de produits
$stmt = $pdo->query('SELECT COUNT(*) FROM produits');
$stats['produits'] = (int)$stmt->fetchColumn();

// Nombre de réservations ateliers
$stmt = $pdo->query('SELECT COUNT(*) FROM reservations_ateliers');
$stats['reservations'] = (int)$stmt->fetchColumn();

// Nombre d'utilisateurs
$stmt = $pdo->query('SELECT COUNT(*) FROM utilisateurs');
$stats['utilisateurs'] = (int)$stmt->fetchColumn();

// Nombre d'histoires en attente de modération
$stmt = $pdo->query("SELECT COUNT(*) FROM belles_histoires WHERE statut = 'en_attente'");
$stats['histoires_attente'] = (int)$stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .dashboard-header h1 {
            font-family: 'Pacifico', cursive;
            color: #FE7B7E;
            font-size: 2.5rem;
            font-weight: normal;
        }
        
        .logout-btn {
            background: #FE7B7E;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: #e06468;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card .number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2B2B2B;
        }
        
        .stat-card .label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .admin-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .menu-item {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-decoration: none;
            color: #2B2B2B;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }
        
        .menu-item .icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .menu-item .title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        
        .menu-item .desc {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>🧔 Dashboard</h1>
            <a href="../logout.php" class="logout-btn">🚪 Déconnexion</a>
        </div>
        
        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="number"><?php echo $stats['commandes']; ?></div>
                <div class="label">Commandes</div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo $stats['produits']; ?></div>
                <div class="label">Produits</div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo $stats['reservations']; ?></div>
                <div class="label">Réservations ateliers</div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo $stats['utilisateurs']; ?></div>
                <div class="label">Utilisateurs</div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo $stats['histoires_attente']; ?></div>
                <div class="label">Histoires à modérer</div>
            </div>
        </div>
        
        <!-- Menu admin -->
        <div class="admin-menu">
            <a href="produits.php" class="menu-item">
                <span class="icon">📦</span>
                <span class="title">Gestion des produits</span>
                <span class="desc">Ajouter, modifier, supprimer</span>
            </a>
            <a href="ateliers.php" class="menu-item">
                <span class="icon">🎨</span>
                <span class="title">Gestion des ateliers</span>
                <span class="desc">Programmer, gérer les places</span>
            </a>
            <a href="pensionnaires.php" class="menu-item">
                <span class="icon">🐱</span>
                <span class="title">Gestion des pensionnaires</span>
                <span class="desc">Chats à l'adoption</span>
            </a>
            <a href="moderer-histoires.php" class="menu-item">
                <span class="icon">📝</span>
                <span class="title">Modérer les histoires</span>
                <span class="desc">Valider ou refuser les témoignages</span>
                <?php if ($stats['histoires_attente'] > 0): ?>
                    <span style="background: #FE7B7E; color: white; padding: 2px 10px; border-radius: 12px; font-size: 0.8rem; margin-top: 5px;">
                        <?php echo $stats['histoires_attente']; ?> en attente
                    </span>
                <?php endif; ?>
            </a>
            <a href="utilisateurs.php" class="menu-item">
                <span class="icon">👤</span>
                <span class="title">Gestion des utilisateurs</span>
                <span class="desc">Voir, modifier, suspendre</span>
            </a>
            <a href="commandes.php" class="menu-item">
                <span class="icon">📋</span>
                <span class="title">Gestion des commandes</span>
                <span class="desc">Suivi des commandes</span>
            </a>
        </div>
    </div>
</body>
</html>