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
$csrf_token = generateCSRFToken();
$message = '';
$error = '';

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        // Valider le token CSRF
        $csrf_check = $_POST['csrf_token'] ?? '';
        if (!validateCSRFToken($csrf_check)) {
            $error = 'Erreur de sécurité : token CSRF invalide';
        } else {
            $action = $_POST['action'];
            
            if ($action === 'ajouter' || $action === 'modifier') {
                $nom = htmlspecialchars($_POST['nom'] ?? '');
                $description = htmlspecialchars($_POST['description'] ?? '');
                $prix = (float)($_POST['prix'] ?? 0);
                $categorie_id = (int)($_POST['categorie_id'] ?? 0);
                
                if (!$nom || $prix <= 0 || $categorie_id <= 0) {
                    $error = 'Tous les champs sont obligatoires et le prix doit être > 0';
                } else {
                    try {
                        if ($action === 'ajouter') {
                            $stmt = $pdo->prepare('
                                INSERT INTO produits (nom, description, prix, categorie_id)
                                VALUES (?, ?, ?, ?)
                            ');
                            $stmt->execute([$nom, $description, $prix, $categorie_id]);
                            $message = '✓ Produit créé avec succès !';
                        } else {
                            $id = (int)($_POST['id'] ?? 0);
                            $stmt = $pdo->prepare('
                                UPDATE produits 
                                SET nom = ?, description = ?, prix = ?, categorie_id = ?
                                WHERE id = ?
                            ');
                            $stmt->execute([$nom, $description, $prix, $categorie_id, $id]);
                            $message = '✓ Produit modifié avec succès !';
                        }
                    } catch (PDOException $e) {
                        $error = 'Erreur lors de l\'enregistrement';
                    }
                }
            } elseif ($action === 'supprimer') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    try {
                        // Vérifier si le produit est utilisé dans une commande
                        $stmt = $pdo->prepare('SELECT COUNT(*) FROM lignes_commandes WHERE produit_id = ?');
                        $stmt->execute([$id]);
                        $count = $stmt->fetchColumn();
                        
                        if ($count > 0) {
                            $error = 'Impossible de supprimer ce produit car il est utilisé dans des commandes';
                        } else {
                            // Supprimer le produit
                            $stmt = $pdo->prepare('DELETE FROM produits WHERE id = ?');
                            $stmt->execute([$id]);
                            $message = '✓ Produit supprimé avec succès !';
                        }
                    } catch (PDOException $e) {
                        $error = 'Erreur lors de la suppression';
                    }
                }
            }
        }
    }
}

// Récupérer le produit à modifier (s'il y a un id)
$edit_produit = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare('SELECT * FROM produits WHERE id = ?');
    $stmt->execute([$edit_id]);
    $edit_produit = $stmt->fetch();
}

// Récupérer les catégories
$stmt = $pdo->query('SELECT id, nom FROM categories_produits ORDER BY nom');
$categories = $stmt->fetchAll();

// Récupérer tous les produits
$stmt = $pdo->query('
    SELECT p.id, p.nom, p.description, p.prix, c.nom as categorie, cp.id as categorie_id
    FROM produits p
    JOIN categories_produits c ON p.categorie_id = c.id
    JOIN categories_produits cp ON p.categorie_id = cp.id
    ORDER BY p.nom
');
$produits = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits - Repaire Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .admin-form h3 {
            margin-top: 0;
            color: #2B2B2B;
            border-bottom: 3px solid #85D6CD;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #2B2B2B;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            font-size: 14px;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #85D6CD;
            box-shadow: 0 0 0 3px rgba(133, 214, 205, 0.1);
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
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
        
        .btn-edit {
            background: #FE7B7E;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .btn-edit:hover {
            background: #e66769;
        }
        
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
        
        .produits-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .produits-table thead {
            background: #f5f5f5;
        }
        
        .produits-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #2B2B2B;
            border-bottom: 2px solid #85D6CD;
        }
        
        .produits-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .produits-table tr:hover {
            background: #f9f9f9;
        }
        
        .produits-table .actions {
            display: flex;
            gap: 8px;
        }
        
        .produits-table .actions form {
            display: inline;
        }
        
        @media (max-width: 768px) {
            .produits-table {
                font-size: 12px;
            }
            
            .produits-table th,
            .produits-table td {
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
                    <a href="../index.php" style="color: #FE7B7E; font-weight: 700; display: block; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">🏠 Retour à l'accueil</a>
                    <a href="dashboard.php">📊 Dashboard</a>
                    <a href="moderer-histoires.php">📖 Belles Histoires</a>
                    <a href="ateliers.php">🎨 Ateliers</a>
                    <a href="produits.php" class="active">🛍️ Produits</a>
                    <a href="commandes.php">📦 Commandes</a>
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
            <h1>🛍️ Gestion des Produits</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Formulaire -->
            <div class="admin-form">
                <h3><?php echo $edit_produit ? 'Modifier le produit' : 'Ajouter un nouveau produit'; ?></h3>
                
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    <input type="hidden" name="action" value="<?php echo $edit_produit ? 'modifier' : 'ajouter'; ?>">
                    <?php if ($edit_produit): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_produit['id']; ?>">
                    <?php endif; ?>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="nom">Nom du produit *</label>
                            <input type="text" id="nom" name="nom" required 
                                   value="<?php echo $edit_produit ? htmlspecialchars($edit_produit['nom']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="categorie_id">Catégorie *</label>
                            <select id="categorie_id" name="categorie_id" required>
                                <option value="">-- Sélectionner --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" 
                                            <?php echo ($edit_produit && $edit_produit['categorie_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="prix">Prix (€) *</label>
                            <input type="number" id="prix" name="prix" step="0.01" min="0" required
                                   value="<?php echo $edit_produit ? $edit_produit['prix'] : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"><?php echo $edit_produit ? htmlspecialchars($edit_produit['description'] ?? '') : ''; ?></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $edit_produit ? '✓ Modifier' : '+ Ajouter'; ?>
                        </button>
                        <?php if ($edit_produit): ?>
                            <a href="produits.php" class="btn btn-secondary">Annuler</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Liste des produits -->
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow-x: auto;">
                <h3 style="margin-top: 0; color: #2B2B2B; border-bottom: 3px solid #85D6CD; padding-bottom: 10px;">
                    Produits (<?php echo count($produits); ?>)
                </h3>
                
                <?php if (empty($produits)): ?>
                    <p style="color: #666; text-align: center; padding: 40px 20px;">Aucun produit</p>
                <?php else: ?>
                    <table class="produits-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Catégorie</th>
                                <th>Prix</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produits as $produit): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($produit['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($produit['categorie']); ?></td>
                                    <td><?php echo number_format((float)$produit['prix'], 2, ',', ' '); ?> €</td>
                                    <td><?php echo htmlspecialchars(substr($produit['description'] ?? '', 0, 50)); ?><?php echo strlen($produit['description'] ?? '') > 50 ? '...' : ''; ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="produits.php?edit=<?php echo $produit['id']; ?>" class="btn btn-edit">✏️</a>
                                            <form method="POST" onsubmit="return confirm('Êtes-vous sûr ?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                                                <input type="hidden" name="action" value="supprimer">
                                                <input type="hidden" name="id" value="<?php echo $produit['id']; ?>">
                                                <button type="submit" class="btn btn-edit" style="background: #FE7B7E;">🗑️</button>
                                            </form>
                                        </div>
                                    </td>
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
                <li><a href="utilisateurs.php">👥 Utilisateurs</a></li>
            </ul>
            <div class="admin-user-info">
                <p>Connecté en tant que:</p>
                <strong><?php echo htmlspecialchars($_SESSION['admin_email'], ENT_QUOTES, 'UTF-8'); ?></strong>
                <a href="../logout.php">Déconnexion</a>
            </div>
        </aside>
        <main class="admin-main">
            <div class="admin-header"><h1>🛍️ Gestion des Produits</h1></div>
            <div class="section">
                <div class="coming-soon">
                    <h2>⚙️ En développement</h2>
                    <p><?php echo count($produits); ?> produit(s) en base</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
