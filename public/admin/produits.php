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

// Remonte de 2 niveaux : admin -> public -> racine
require_once __DIR__ . '/../../config/database.php';

$pdo = getPDO();
$message = '';
$error = '';

// Génération du token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Traitement des actions (Ajouter, Modifier, Supprimer)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posted_token = trim((string) ($_POST['csrf_token'] ?? ''));
    
    if (!hash_equals($_SESSION['csrf_token'], $posted_token)) {
        $error = 'Erreur de sécurité : token CSRF invalide';
    } else {
        $action = trim((string) ($_POST['action'] ?? ''));

        if ($action === 'ajouter' || $action === 'modifier') {
            $nom = trim($_POST['nom'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $prix = (float)($_POST['prix'] ?? 0.0);
            $categorie_id = (int)($_POST['categorie_id'] ?? 1);
            $id = (int)($_POST['id'] ?? 0);

            // Récupération de l'image actuelle
            $image_url = '';
            if ($action === 'modifier' && $id > 0) {
                $stmtImg = $pdo->prepare('SELECT image_url FROM produits WHERE id = ?');
                $stmtImg->execute([$id]);
                $image_url = $stmtImg->fetchColumn() ?: '';
            }

            // Upload d'image sécurisé
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_name = $_FILES['image']['name'];
                $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

                if (in_array($ext, $allowed_extensions, true)) {
                    $upload_dir = __DIR__ . '/../images/produits/';
                    
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }

                    $new_name = uniqid('prod_', true) . '.' . $ext;
                    
                    if (move_uploaded_file($file_tmp, $upload_dir . $new_name)) {
                        if ($action === 'modifier' && !empty($image_url)) {
                            $old_file_path = __DIR__ . '/../' . $image_url;
                            if (file_exists($old_file_path)) {
                                @unlink($old_file_path);
                            }
                        }
                        $image_url = 'images/produits/' . $new_name;
                    } else {
                        $error = "Erreur lors du déplacement de l'image.";
                    }
                } else {
                    $error = "Format d'image invalide (JPG, PNG, WEBP autorisés).";
                }
            }

            if (empty($error)) {
                if (empty($nom) || $prix <= 0) {
                    $error = 'Le nom et un prix valide sont requis.';
                } else {
                    try {
                        if ($action === 'ajouter') {
                            $stmt = $pdo->prepare('
                                INSERT INTO produits (nom, description, prix, categorie_id, image_url)
                                VALUES (?, ?, ?, ?, ?)
                            ');
                            $stmt->execute([$nom, $description, $prix, $categorie_id, $image_url]);
                            $message = '✓ Produit ajouté avec succès !';
                        } else {
                            $stmt = $pdo->prepare('
                                UPDATE produits 
                                SET nom = ?, description = ?, prix = ?, categorie_id = ?, image_url = ?
                                WHERE id = ?
                            ');
                            $stmt->execute([$nom, $description, $prix, $categorie_id, $image_url, $id]);
                            $message = '✓ Produit mis à jour avec succès !';
                        }
                        
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                        $csrf_token = $_SESSION['csrf_token'];
                    } catch (PDOException $e) {
                        $error = 'Erreur lors de l\'enregistrement en base de données.';
                    }
                }
            }
        } elseif ($action === 'supprimer') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                try {
                    $stmtImg = $pdo->prepare('SELECT image_url FROM produits WHERE id = ?');
                    $stmtImg->execute([$id]);
                    $old_image = $stmtImg->fetchColumn();

                    if ($old_image) {
                        $file_path = __DIR__ . '/../' . $old_image;
                        if (file_exists($file_path)) {
                            @unlink($file_path);
                        }
                    }

                    $stmt = $pdo->prepare('DELETE FROM produits WHERE id = ?');
                    $stmt->execute([$id]);
                    $message = '✓ Produit supprimé avec succès !';
                    
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    $csrf_token = $_SESSION['csrf_token'];
                } catch (PDOException $e) {
                    $error = 'Erreur lors de la suppression du produit.';
                }
            }
        }
    }
}

// Récupération du produit à modifier
$edit_produit = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare('SELECT * FROM produits WHERE id = ?');
    $stmt->execute([$edit_id]);
    $edit_produit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupération de tous les produits
$stmt = $pdo->prepare('SELECT * FROM produits ORDER BY id DESC');
$stmt->execute();
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container { display: flex; min-height: 100vh; background-color: #f9f9f9; }
        .admin-sidebar { width: 250px; background-color: #2b2b2b; color: white; display: flex; flex-direction: column; justify-content: space-between; padding: 20px 0; }
        .admin-sidebar h2 { font-family: 'Pacifico', cursive; text-align: center; color: #FE7B7E; margin-bottom: 30px; }
        .admin-sidebar nav a { display: block; color: #ccc; padding: 12px 25px; text-decoration: none; font-family: 'Montserrat', sans-serif; font-weight: 600; transition: all 0.3s; }
        .admin-sidebar nav a:hover, .admin-sidebar nav a.active { color: white; background-color: #3e3e3e; border-left: 4px solid #85D6CD; }
        .admin-main { flex-grow: 1; padding: 40px; font-family: 'Montserrat', sans-serif; }
        .admin-main h1 { font-family: 'Pacifico', cursive; color: #FE7B7E; margin-bottom: 30px; }
        .admin-form { background: white; padding: 25px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .admin-form h3 { margin-top: 0; color: #2B2B2B; border-bottom: 3px solid #85D6CD; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #2B2B2B; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-family: inherit; font-size: 14px; box-sizing: border-box; }
        .form-group textarea { resize: vertical; min-height: 80px; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #85D6CD; box-shadow: 0 0 0 3px rgba(133, 214, 205, 0.1); }
        .form-actions { display: flex; gap: 10px; margin-top: 20px; }
        .btn { padding: 10px 20px; border: none; border-radius: 30px; cursor: pointer; font-weight: 600; transition: all 0.3s ease; }
        .btn-primary { background: #85D6CD; color: white; }
        .btn-primary:hover { background: #6bc3b8; }
        .btn-secondary { background: #ddd; color: #2B2B2B; }
        .btn-secondary:hover { background: #ccc; }
        .btn-edit { background: #FE7B7E; color: white; padding: 8px 15px; font-size: 13px; border-radius: 20px; text-decoration: none; }
        .btn-edit:hover { background: #e66769; }
        .alert { padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; font-weight: 600; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .produits-grid { display: grid; gap: 15px; }
        .produit-card { background: white; padding: 15px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-left: 4px solid #85D6CD; display: flex; gap: 15px; align-items: center; }
        .produit-img-preview { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; background: #f4f4f4; }
        .produit-details { flex-grow: 1; }
        .produit-title { font-weight: 700; color: #2B2B2B; font-size: 16px; }
        .produit-info { display: flex; gap: 20px; font-size: 14px; margin-top: 5px; }
        .info-label { font-weight: 600; color: #666; }
        .produit-actions { display: flex; gap: 8px; }
        .produit-actions form { display: inline; }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div>
                <h2>Admin</h2>
                <nav>
                    <a href="../index.php" style="color: #FE7B7E;">🏠 Retour à l'accueil</a>
                    <a href="dashboard.php">📊 Dashboard</a>
                    <a href="moderer-histoires.php">📖 Belles Histoires</a>
                    <a href="ateliers.php">🎨 Ateliers</a>
                    <a href="produits.php" class="active">🛍️ Produits</a>
                    <a href="commandes.php">📦 Commandes</a>
                    <a href="utilisateurs.php">👥 Utilisateurs</a>
                </nav>
            </div>
            <div style="padding: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                <p style="margin: 0 0 5px 0; font-size: 12px; color: #aaa;">Connecté:</p>
                <p style="margin: 0 0 15px 0; font-weight: 600; color: white; font-size: 14px;"><?= htmlspecialchars((string) ($_SESSION['admin_email'] ?? 'Admin'), ENT_QUOTES, 'UTF-8') ?></p>
                <a href="../logout.php" style="color: #FE7B7E; text-decoration: none; font-weight: 600; font-size: 14px;">🚪 Déconnexion</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <h1>🛍️ Gestion des Produits</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <!-- Formulaire -->
            <div class="admin-form">
                <h3><?= $edit_produit ? 'Modifier le produit' : 'Ajouter un nouveau produit' ?></h3>
                
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="action" value="<?= $edit_produit ? 'modifier' : 'ajouter' ?>">
                    <?php if ($edit_produit): ?>
                        <input type="hidden" name="id" value="<?= $edit_produit['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="nom">Nom du produit *</label>
                        <input type="text" id="nom" name="nom" required 
                               value="<?= $edit_produit ? htmlspecialchars((string) $edit_produit['nom'], ENT_QUOTES, 'UTF-8') : '' ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"><?= $edit_produit ? htmlspecialchars((string) ($edit_produit['description'] ?? ''), ENT_QUOTES, 'UTF-8') : '' ?></textarea>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="prix">Prix (€) *</label>
                            <input type="number" id="prix" name="prix" step="0.01" min="0.01" required
                                   value="<?= $edit_produit ? $edit_produit['prix'] : '' ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="categorie_id">Catégorie ID</label>
                            <input type="number" id="categorie_id" name="categorie_id" min="1" required
                                   value="<?= $edit_produit ? $edit_produit['categorie_id'] : '1' ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image">Image du produit</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <?php if ($edit_produit && !empty($edit_produit['image_url'])): ?>
                            <p style="font-size: 12px; margin-top: 5px; color: #666;">Image actuelle : <?= htmlspecialchars((string) $edit_produit['image_url'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?= $edit_produit ? '✓ Modifier le produit' : '+ Ajouter le produit' ?>
                        </button>
                        <?php if ($edit_produit): ?>
                            <a href="produits.php" class="btn btn-secondary">Annuler</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Liste des produits -->
            <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <h3 style="margin-top: 0; color: #2B2B2B; border-bottom: 3px solid #85D6CD; padding-bottom: 10px;">
                    Produits en boutique (<?= count($produits) ?>)
                </h3>
                
                <?php if (empty($produits)): ?>
                    <p style="color: #666; text-align: center; padding: 40px 20px;">Aucun produit en boutique actuellement.</p>
                <?php else: ?>
                    <div class="produits-grid">
                        <?php foreach ($produits as $produit): ?>
                            <div class="produit-card">
                                <?php 
                                    $img_file = !empty($produit['image_url']) ? htmlspecialchars((string) $produit['image_url'], ENT_QUOTES, 'UTF-8') : '';
                                    $image_path = !empty($img_file) ? "../" . $img_file : "../images/produits/default-product.png";
                                ?>
                                <img src="<?= $image_path ?>" 
                                     alt="Aperçu" class="produit-img-preview" 
                                     onerror="this.onerror=null; this.src='../images/produits/default-product.png';">
                                
                                <div class="produit-details">
                                    <div class="produit-title"><?= htmlspecialchars((string) $produit['nom'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="produit-info">
                                        <div><span class="info-label">Prix:</span> <?= number_format((float)$produit['prix'], 2, ',', ' ') ?> €</div>
                                        <div><span class="info-label">Catégorie:</span> N°<?= (int)$produit['categorie_id'] ?></div>
                                    </div>
                                </div>
                                
                                <div class="produit-actions">
                                    <a href="produits.php?edit=<?= $produit['id'] ?>" class="btn btn-edit">✏️ Modifier</a>
                                    <form method="POST" onsubmit="return confirm('Supprimer ce produit définitivement ?');">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="id" value="<?= $produit['id'] ?>">
                                        <button type="submit" class="btn btn-edit" style="background: #FE7B7E; border: none; cursor: pointer;">🗑️ Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php 
    $footer_path = __DIR__ . '/../../includes/footer.php';
    if (file_exists($footer_path)) {
        require_once $footer_path; 
    }
    ?>
</body>
</html>