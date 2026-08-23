<?php
declare(strict_types=1);

session_start();

// ============================================================
// 🔒 VÉRIFICATION D'ACCÈS ADMIN (rôle requis)
// ============================================================
if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}
// ============================================================

require_once __DIR__ . '/../../config/database.php';

$pdo = getPDO();

// Helper sécurisé pour exécuter un COUNT(*) sans faire planter la page
function getSafeCount(PDO $pdo, string $query): int {
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt ? (int)$stmt->fetchColumn() : 0;
    } catch (PDOException $e) {
        // Log la sous-erreur discrètement
        error_log('Erreur Stat Dashboard SQL : ' . $e->getMessage());
        return 0;
    }
}

// Récupérer les statistiques pour le dashboard
$stats = [
    'pensionnaires'     => getSafeCount($pdo, 'SELECT COUNT(*) FROM pensionnaires'),
    'commandes'         => getSafeCount($pdo, 'SELECT COUNT(*) FROM commandes'),
    'produits'          => getSafeCount($pdo, 'SELECT COUNT(*) FROM produits'),
    'reservations'      => getSafeCount($pdo, 'SELECT COUNT(*) FROM reservations_ateliers'),
    'utilisateurs'      => getSafeCount($pdo, 'SELECT COUNT(*) FROM utilisateurs'),
    'histoires_attente' => getSafeCount($pdo, "SELECT COUNT(*) FROM belles_histoires WHERE statut = 'en_attente'"),
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Le Repaire des Moustaches</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Pacifico&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #fdfbf7;
            color: #2b2b2b;
        }

        .admin-container { display: flex; min-height: 100vh; }

        /* --- SIDEBAR SOMBRE ALIGNÉE SUR LE RESTE DU PANNEAU --- */
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

        .admin-logo { text-align: center; padding-bottom: 25px; }
        .admin-logo h2 {
            font-family: 'Pacifico', cursive;
            font-size: 1.8rem;
            color: #85D6CD;
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
            color: #FE7B7E;
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

        .admin-header { margin-bottom: 30px; }
        .admin-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #2b2b2b;
        }

        /* METRIQUES / GRILLE STATS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            border-top: 3px solid #85D6CD;
            border-left: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }

        .stat-card.alert-card { border-top-color: #FE7B7E; }

        .stat-card .number {
            font-size: 2.2rem;
            font-weight: 800;
            color: #1f2937;
            line-height: 1.1;
        }

        .stat-card .label {
            color: #6b7280;
            font-size: 0.85rem;
            margin-top: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* MODULE ACCÈS RAPIDES PRO */
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
            margin-bottom: 20px;
            border-bottom: 3px solid #85D6CD;
        }

        .quick-actions-table {
            width: 100%;
            border-collapse: collapse;
        }

        .quick-actions-table th, .quick-actions-table td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid #f3f4f6;
        }

        .quick-actions-table th {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #9ca3af;
            letter-spacing: 0.5px;
            font-weight: 700;
        }

        .quick-actions-table td {
            font-size: 0.95rem;
            font-weight: 600;
        }

        .quick-actions-table tr:hover { background-color: #fafafa; }

        .btn-action {
            display: inline-block;
            padding: 8px 16px;
            background: #85D6CD;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: background 0.2s;
        }

        .btn-action:hover { background: #6bc3b8; }
        
        .badge-warning {
            background: #FE7B7E;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            margin-left: 8px;
        }
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
                    <li><a href="dashboard.php" class="active">📊 Dashboard</a></li>
                    <li><a href="moderer-histoires.php">📖 Belles Histoires</a></li>
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
                <h1>📊 Vue d'ensemble</h1>
            </div>

            <!-- Grille de métriques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="number"><?= htmlspecialchars((string)$stats['pensionnaires'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="label">Pensionnaires</div>
                </div>
                <div class="stat-card">
                    <div class="number"><?= htmlspecialchars((string)$stats['commandes'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="label">Commandes</div>
                </div>
                <div class="stat-card">
                    <div class="number"><?= htmlspecialchars((string)$stats['produits'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="label">Produits</div>
                </div>
                <div class="stat-card">
                    <div class="number"><?= htmlspecialchars((string)$stats['reservations'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="label">Réservations</div>
                </div>
                <div class="stat-card">
                    <div class="number"><?= htmlspecialchars((string)$stats['utilisateurs'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="label">Utilisateurs</div>
                </div>
                <div class="stat-card <?= $stats['histoires_attente'] > 0 ? 'alert-card' : '' ?>">
                    <div class="number"><?= htmlspecialchars((string)$stats['histoires_attente'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="label">À Modérer</div>
                </div>
            </div>

            <!-- Tableau des modules d'administration -->
            <div class="admin-card">
                <h2>Modules de Gestion</h2>
                <table class="quick-actions-table">
                    <thead>
                        <tr>
                            <th>Module</th>
                            <th>Description</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>🐱 Pensionnaires</td>
                            <td style="color: #6b7280;">Gestion de la liste des chats et mise à jour de leurs statuts.</td>
                            <td style="text-align: right;"><a href="pensionnaires.php" class="btn-action">Gérer</a></td>
                        </tr>
                        <tr>
                            <td>🛍️ Produits</td>
                            <td style="color: #6b7280;">Gestion du catalogue boutique (prix, stocks, descriptions).</td>
                            <td style="text-align: right;"><a href="produits.php" class="btn-action">Gérer</a></td>
                        </tr>
                        <tr>
                            <td>🎨 Ateliers</td>
                            <td style="color: #6b7280;">Programmation des événements et gestion des réservations.</td>
                            <td style="text-align: right;"><a href="ateliers.php" class="btn-action">Gérer</a></td>
                        </tr>
                        <tr>
                            <td>📖 Belles Histoires</td>
                            <td style="color: #6b7280;">
                                Modération et validation des histoires soumises.
                                <?php if ($stats['histoires_attente'] > 0): ?>
                                    <span class="badge-warning"><?= $stats['histoires_attente'] ?> en attente</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;"><a href="moderer-histoires.php" class="btn-action">Modérer</a></td>
                        </tr>
                        <tr>
                            <td>👥 Utilisateurs</td>
                            <td style="color: #6b7280;">Administration des comptes membres et autorisations.</td>
                            <td style="text-align: right;"><a href="utilisateurs.php" class="btn-action">Consulter</a></td>
                        </tr>
                        <tr>
                            <td>📋 Commandes</td>
                            <td style="color: #6b7280;">Suivi global des paiements et livraisons.</td>
                            <td style="text-align: right;"><a href="commandes.php" class="btn-action">Suivre</a></td>
                        </tr>
                        <tr>
                            <td>🔗 Supervision API</td>
                            <td style="color: #6b7280;">Contrôle du flux JSON des refuges partenaires.</td>
                            <td style="text-align: right;"><a href="../partenaires.php" class="btn-action">Accéder</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>