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
$message = '';
$error = '';

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'changer_statut') {
            $id = (int)($_POST['id'] ?? 0);
            $nouveau_statut = $_POST['statut'] ?? '';
            
            if ($id > 0 && in_array($nouveau_statut, ['en_attente', 'payee', 'annulee'])) {
                try {
                    $stmt = $pdo->prepare('UPDATE commandes SET statut = ? WHERE id = ?');
                    $stmt->execute([$nouveau_statut, $id]);
                    $message = '✓ Statut de la commande modifié !';
                } catch (PDOException $e) {
                    $error = 'Erreur lors de la modification';
                }
            }
        }
    }
}

// Récupérer le détail d'une commande si demandé
$commande_detail = null;
if (isset($_GET['view'])) {
    $view_id = (int)$_GET['view'];
    $stmt = $pdo->prepare('
        SELECT c.*, u.prenom, u.nom, u.email
        FROM commandes c
        JOIN utilisateurs u ON c.utilisateur_id = u.id
        WHERE c.id = ?
    ');
    $stmt->execute([$view_id]);
    $commande_detail = $stmt->fetch();
    
    if ($commande_detail) {
        // Récupérer les lignes de la commande
        $stmt = $pdo->prepare('
            SELECT lc.*, p.nom as produit_nom
            FROM lignes_commandes lc
            JOIN produits p ON lc.produit_id = p.id
            WHERE lc.commande_id = ?
        ');
        $stmt->execute([$view_id]);
        $commande_detail['lignes'] = $stmt->fetchAll();
    }
}

// Récupérer toutes les commandes
$stmt = $pdo->query('
    SELECT c.id, c.date_commande, c.montant_total, c.statut, u.prenom, u.nom
    FROM commandes c
    JOIN utilisateurs u ON c.utilisateur_id = u.id
    ORDER BY c.date_commande DESC
');
$commandes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes - Repaire Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
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
        
        .btn-secondary {
            background: #ddd;
            color: #2B2B2B;
        }
        
        .btn-secondary:hover {
            background: #ccc;
        }
        
        .commandes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .commandes-table thead {
            background: #f5f5f5;
        }
        
        .commandes-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #2B2B2B;
            border-bottom: 2px solid #85D6CD;
        }
        
        .commandes-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .commandes-table tr:hover {
            background: #f9f9f9;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-en_attente {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-payee {
            background: #d4edda;
            color: #155724;
        }
        
        .status-annulee {
            background: #f8d7da;
            color: #721c24;
        }
        
        .commandes-table .actions {
            display: flex;
            gap: 6px;
        }
        
        .detail-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .detail-header {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .detail-item {
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        
        .detail-item-label {
            font-size: 12px;
            color: #999;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .detail-item-value {
            font-size: 16px;
            color: #2B2B2B;
            font-weight: 600;
        }
        
        .lignes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .lignes-table th {
            background: #f5f5f5;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #85D6CD;
        }
        
        .lignes-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        
        @media (max-width: 768px) {
            .detail-header {
                grid-template-columns: 1fr;
            }
            
            .commandes-table {
                font-size: 12px;
            }
            
            .commandes-table th,
            .commandes-table td {
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
                    <a href="commandes.php" class="active">📦 Commandes</a>
                    <a href="utilisateurs.php">👥 Utilisateurs</a>
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
            <h1>📦 Gestion des Commandes</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($commande_detail): ?>
                <!-- Vue détaillée -->
                <div class="detail-container">
                    <h2>Détail de la commande #<?php echo $commande_detail['id']; ?></h2>
                    
                    <div class="detail-header">
                        <div class="detail-item">
                            <div class="detail-item-label">Client</div>
                            <div class="detail-item-value"><?php echo htmlspecialchars($commande_detail['prenom'] . ' ' . $commande_detail['nom']); ?></div>
                            <div style="font-size: 12px; color: #666; margin-top: 4px;"><?php echo htmlspecialchars($commande_detail['email']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-item-label">Date</div>
                            <div class="detail-item-value"><?php echo date('d/m/Y H:i', strtotime($commande_detail['date_commande'])); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-item-label">Montant total</div>
                            <div class="detail-item-value"><?php echo number_format($commande_detail['montant_total'], 2, ',', ' '); ?> €</div>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <form method="POST" style="display: flex; gap: 10px;">
                            <input type="hidden" name="action" value="changer_statut">
                            <input type="hidden" name="id" value="<?php echo $commande_detail['id']; ?>">
                            <select name="statut" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                <option value="en_attente" <?php echo $commande_detail['statut'] === 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                                <option value="payee" <?php echo $commande_detail['statut'] === 'payee' ? 'selected' : ''; ?>>Payée</option>
                                <option value="annulee" <?php echo $commande_detail['statut'] === 'annulee' ? 'selected' : ''; ?>>Annulée</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            <a href="commandes.php" class="btn btn-secondary">Retour</a>
                        </form>
                    </div>
                    
                    <h3 style="margin-top: 30px; margin-bottom: 15px;">Articles de la commande</h3>
                    <table class="lignes-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commande_detail['lignes'] as $ligne): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ligne['produit_nom']); ?></td>
                                    <td><?php echo $ligne['quantite']; ?></td>
                                    <td><?php echo number_format($ligne['prix_unitaire'], 2, ',', ' '); ?> €</td>
                                    <td><?php echo number_format($ligne['prix_unitaire'] * $ligne['quantite'], 2, ',', ' '); ?> €</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <!-- Liste des commandes -->
                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h3 style="margin-top: 0; color: #2B2B2B; border-bottom: 3px solid #85D6CD; padding-bottom: 10px;">
                        Commandes (<?php echo count($commandes); ?>)
                    </h3>
                    
                    <?php if (empty($commandes)): ?>
                        <p style="color: #666; text-align: center; padding: 40px 20px;">Aucune commande</p>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table class="commandes-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($commandes as $cmd): ?>
                                        <tr>
                                            <td><?php echo $cmd['id']; ?></td>
                                            <td><?php echo htmlspecialchars($cmd['prenom'] . ' ' . $cmd['nom']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($cmd['date_commande'])); ?></td>
                                            <td><?php echo number_format($cmd['montant_total'], 2, ',', ' '); ?> €</td>
                                            <td>
                                                <span class="status-badge status-<?php echo $cmd['statut']; ?>">
                                                    <?php echo ucfirst(str_replace('_', ' ', $cmd['statut'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="actions">
                                                    <a href="commandes.php?view=<?php echo $cmd['id']; ?>" class="btn btn-primary">Voir</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes - Admin</title>
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
                <li><a href="commandes.php" class="active">📦 Commandes</a></li>
                <li><a href="utilisateurs.php">👥 Utilisateurs</a></li>
            </ul>
            <div class="admin-user-info">
                <p>Connecté en tant que:</p>
                <strong><?php echo htmlspecialchars($_SESSION['admin_email'], ENT_QUOTES, 'UTF-8'); ?></strong>
                <a href="../logout.php">Déconnexion</a>
            </div>
        </aside>
        <main class="admin-main">
            <div class="admin-header"><h1>📦 Gestion des Commandes</h1></div>
            <div class="section">
                <div class="coming-soon">
                    <h2>⚙️ En développement</h2>
                    <p>Système de panier et paiement à venir</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
