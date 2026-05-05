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

// Récupérer tous les utilisateurs avec leurs informations
$stmt = $pdo->query('
    SELECT 
        u.id,
        u.nom,
        u.prenom,
        u.email,
        u.date_inscription,
        COUNT(DISTINCT a.id) as nb_adhesions,
        COUNT(DISTINCT h.id) as nb_histoires,
        COUNT(DISTINCT c.id) as nb_commandes,
        COALESCE(SUM(c.montant_total), 0) as total_depenses
    FROM utilisateurs u
    LEFT JOIN adhesions a ON u.id = a.utilisateur_id
    LEFT JOIN belles_histoires h ON u.id = h.utilisateur_id
    LEFT JOIN commandes c ON u.id = c.utilisateur_id AND c.statut = "payee"
    GROUP BY u.id
    ORDER BY u.date_inscription DESC
');
$utilisateurs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Repaire Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 13px;
        }
        
        .btn-primary {
            background: #85D6CD;
            color: white;
        }
        
        .btn-primary:hover {
            background: #6bc3b8;
        }
        
        .utilisateurs-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .utilisateurs-table thead {
            background: #f5f5f5;
        }
        
        .utilisateurs-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #2B2B2B;
            border-bottom: 2px solid #85D6CD;
        }
        
        .utilisateurs-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .utilisateurs-table tr:hover {
            background: #f9f9f9;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            background: #85D6CD;
            color: white;
            font-size: 12px;
            font-weight: 600;
            margin-right: 4px;
        }
        
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #85D6CD;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #85D6CD;
        }
        
        .stat-label {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .utilisateurs-table {
                font-size: 12px;
            }
            
            .utilisateurs-table th,
            .utilisateurs-table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-menu">
                <h2>Admin</h2>
                <nav>
                    <a href="dashboard.php">📊 Dashboard</a>
                    <a href="moderer-histoires.php">📖 Belles Histoires</a>
                    <a href="ateliers.php">🎨 Ateliers</a>
                    <a href="produits.php">🛍️ Produits</a>
                    <a href="commandes.php">📦 Commandes</a>
                    <a href="utilisateurs.php" class="active">👥 Utilisateurs</a>
                </nav>
            </div>
            <div style="padding: 20px; border-top: 1px solid #444; margin-top: auto;">
                <p style="margin: 0 0 10px 0; font-size: 12px; color: #aaa;">Connecté:</p>
                <p style="margin: 0 0 15px 0; font-weight: 600; color: white;"><?php echo htmlspecialchars($_SESSION['admin_email']); ?></p>
                <a href="../logout.php" style="color: #FE7B7E; text-decoration: none; font-weight: 600;">Déconnexion</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <h1>👥 Gestion des Utilisateurs</h1>
            
            <!-- Stats rapides -->
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-value"><?php echo count($utilisateurs); ?></div>
                    <div class="stat-label">Total utilisateurs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        <?php 
                            $total_adhesions = 0;
                            foreach ($utilisateurs as $u) {
                                $total_adhesions += (int)$u['nb_adhesions'];
                            }
                            echo $total_adhesions;
                        ?>
                    </div>
                    <div class="stat-label">Adhésions actives</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        <?php 
                            $total_histoires = 0;
                            foreach ($utilisateurs as $u) {
                                $total_histoires += (int)$u['nb_histoires'];
                            }
                            echo $total_histoires;
                        ?>
                    </div>
                    <div class="stat-label">Histoires soumises</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        <?php 
                            $total_revenus = 0;
                            foreach ($utilisateurs as $u) {
                                $total_revenus += (float)$u['total_depenses'];
                            }
                            echo number_format($total_revenus, 0);
                        ?>€
                    </div>
                    <div class="stat-label">Revenu total</div>
                </div>
            </div>

            <!-- Liste des utilisateurs -->
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow-x: auto;">
                <h3 style="margin-top: 0; color: #2B2B2B; border-bottom: 3px solid #85D6CD; padding-bottom: 10px;">
                    Utilisateurs (<?php echo count($utilisateurs); ?>)
                </h3>
                
                <?php if (empty($utilisateurs)): ?>
                    <p style="color: #666; text-align: center; padding: 40px 20px;">Aucun utilisateur</p>
                <?php else: ?>
                    <table class="utilisateurs-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Inscription</th>
                                <th>Adhésions</th>
                                <th>Histoires</th>
                                <th>Commandes</th>
                                <th>Dépenses</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($utilisateurs as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($user['date_inscription'])); ?></td>
                                    <td>
                                        <?php if ($user['nb_adhesions'] > 0): ?>
                                            <span class="badge"><?php echo $user['nb_adhesions']; ?> ✓</span>
                                        <?php else: ?>
                                            <span style="color: #999;">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($user['nb_histoires'] > 0): ?>
                                            <span class="badge" style="background: #FE7B7E;"><?php echo $user['nb_histoires']; ?></span>
                                        <?php else: ?>
                                            <span style="color: #999;">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($user['nb_commandes'] > 0): ?>
                                            <span class="badge" style="background: #2B2B2B;"><?php echo $user['nb_commandes']; ?></span>
                                        <?php else: ?>
                                            <span style="color: #999;">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo number_format((float)$user['total_depenses'], 2, ',', ' '); ?> €</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Montserrat', sans-serif; background: #F5F5F5; }
        .admin-container { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 250px; background: #2B2B2B; color: white; padding: 30px 0; position: fixed; height: 100vh; overflow-y: auto; }
        .admin-logo { padding: 0 20px; margin-bottom: 30px; text-align: center; }
        .admin-logo img { max-width: 60px; margin-bottom: 10px; }
        .admin-logo h2 { font-family: 'Pacifico', cursive; color: #85D6CD; font-size: 1.5rem; font-weight: normal; }
        .admin-menu { list-style: none; }
        .admin-menu a { display: block; padding: 12px 20px; color: #ccc; text-decoration: none; transition: all 0.3s; border-left: 3px solid transparent; }
        .admin-menu a:hover { background: rgba(133, 214, 205, 0.1); color: #85D6CD; border-left-color: #85D6CD; }
        .admin-menu a.active { background: rgba(133, 214, 205, 0.2); color: #85D6CD; border-left-color: #85D6CD; font-weight: 700; }
        .admin-user-info { padding: 20px; border-top: 1px solid rgba(255, 255, 255, 0.1); margin-top: auto; position: absolute; bottom: 0; width: 100%; }
        .admin-user-info a { display: block; color: #FE7B7E; text-decoration: none; font-size: 0.9rem; }
        .admin-main { flex: 1; margin-left: 250px; padding: 30px; }
        .admin-header h1 { font-size: 1.8rem; color: #2B2B2B; margin-bottom: 20px; }
        .section { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); }
        .coming-soon { text-align: center; padding: 60px 20px; color: #999; }
        .coming-soon h2 { color: #2B2B2B; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="admin-logo"><img src="../images/logo.png" alt="Logo"><h2>Admin</h2></div>
            <ul class="admin-menu">
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="moderer-histoires.php">📖 Belles Histoires</a></li>
                <li><a href="ateliers.php">🎨 Ateliers</a></li>
                <li><a href="produits.php">🛍️ Produits</a></li>
                <li><a href="commandes.php">📦 Commandes</a></li>
                <li><a href="utilisateurs.php" class="active">👥 Utilisateurs</a></li>
            </ul>
            <div class="admin-user-info">
                <p>Connecté en tant que:</p>
                <strong><?php echo htmlspecialchars($_SESSION['admin_email'], ENT_QUOTES, 'UTF-8'); ?></strong>
                <a href="../logout.php">Déconnexion</a>
            </div>
        </aside>
        <main class="admin-main">
            <div class="admin-header"><h1>👥 Gestion des Utilisateurs</h1></div>
            <div class="section">
                <div class="coming-soon">
                    <h2>⚙️ En développement</h2>
                    <p><?php echo $count; ?> utilisateur(s) en base</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
