<?php
declare(strict_types=1);

session_start();

// Contrôle d'accès
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';

$pdo = getPDO();
$message = '';
$error = '';

// Token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posted_token = trim((string) ($_POST['csrf_token'] ?? ''));
    if (!hash_equals($_SESSION['csrf_token'], $posted_token)) {
        $error = 'Erreur de sécurité : token CSRF invalide';
    } else {
        $action = trim((string) ($_POST['action'] ?? ''));

        if ($action === 'ajouter' || $action === 'modifier') {
            // Données du formulaire
$nom = trim($_POST['nom'] ?? '');
$age = (int)($_POST['age'] ?? 0);
$caractere = trim($_POST['caractere'] ?? '');
$description = trim($_POST['description'] ?? '');
            $statut = trim((string) ($_POST['statut'] ?? 'a_l_adoption'));
            $refuge_id = !empty($_POST['refuge_id']) ? (int)$_POST['refuge_id'] : null;
            $admin_id = $_SESSION['admin_id'];

            // Téléversement de l'image
            $photo_url = trim((string) ($_POST['current_image'] ?? ''));
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['photo']['tmp_name'];
                $file_name = basename($_FILES['photo']['name']);
                $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];

                if (in_array($ext, $allowed)) {
                    $upload_dir = __DIR__ . '/../images/'; // Dossier des images

                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    $new_filename = uniqid('chat_', true) . '.webp';
                    $target_path = $upload_dir . $new_filename;

                    $image_created = false;

                    // Conversion WebP
                    if ($ext === 'webp') {
                        $image_created = move_uploaded_file($file_tmp, $target_path);
                    } else {
                        $source_img = null;
                        if ($ext === 'jpg' || $ext === 'jpeg') {
                            $source_img = @imagecreatefromjpeg($file_tmp);
                        } elseif ($ext === 'png') {
                            $source_img = @imagecreatefrompng($file_tmp);
                            if ($source_img) {
                                imagepalettetotruecolor($source_img);
                                imagealphablending($source_img, true);
                                imagesavealpha($source_img, true);
                            }
                        }

                        if ($source_img) {
                            $image_created = imagewebp($source_img, $target_path, 80);
                            imagedestroy($source_img);
                        }
                    }

                    if ($image_created) {
                        $photo_url = 'images/' . $new_filename;
                    } else {
                        $error = "Erreur lors de la conversion de l'image en WebP.";
                    }
                } else {
                    $error = "Format d'image non supporté (utilisez JPG, PNG ou WebP).";
                }
            }

            if (!$nom || $age < 0) {
                $error = 'Le nom et un âge valide sont obligatoires.';
            } elseif (empty($error)) {
                try {
                    if ($action === 'ajouter') {
                        $stmt = $pdo->prepare('
                            INSERT INTO pensionnaires (nom, age, description, caractere, photo_url, statut, refuge_id, admin_id)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                        ');
                        $stmt->execute([$nom, $age, $description, $caractere, $photo_url, $statut, $refuge_id, $admin_id]);
                        $message = '✓ Pensionnaire ajouté avec succès !';
                    } else {
                        $id = (int)($_POST['id'] ?? 0);
                        $stmt = $pdo->prepare('
                            UPDATE pensionnaires 
                            SET nom = ?, age = ?, description = ?, caractere = ?, photo_url = ?, statut = ?, refuge_id = ?
                            WHERE id = ?
                        ');
                        $stmt->execute([$nom, $age, $description, $caractere, $photo_url, $statut, $refuge_id, $id]);
                        $message = '✓ Fiche du pensionnaire mise à jour avec succès !';
                    }
                } catch (PDOException $e) {
                    $error = 'Erreur lors de l\'enregistrement en base de données.';
                }
            }
        } elseif ($action === 'update_statut') {
            $id = (int)($_POST['id'] ?? 0);
            $nouveau_statut = $_POST['statut'] ?? 'a_l_adoption';

            if ($id > 0) {
                try {
                    $stmt = $pdo->prepare("UPDATE pensionnaires SET statut = ? WHERE id = ?");
                    $stmt->execute([$nouveau_statut, $id]);
                    $message = "✓ Le statut du pensionnaire a été mis à jour !";
                } catch (PDOException $e) {
                    $error = "Erreur lors de la mise à jour du statut.";
                }
            }
        } elseif ($action === 'supprimer') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                try {
                    $stmt = $pdo->prepare('DELETE FROM pensionnaires WHERE id = ?');
                    $stmt->execute([$id]);
                    $message = '✓ Pensionnaire supprimé avec succès !';
                } catch (PDOException $e) {
                    $error = 'Erreur lors de la suppression du pensionnaire.';
                }
            }
        }
    }
}

// Pensionnaire à modifier
$edit_pensionnaire = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare('SELECT * FROM pensionnaires WHERE id = ?');
    $stmt->execute([$edit_id]);
    $edit_pensionnaire = $stmt->fetch();
}

// Liste des pensionnaires
$stmt = $pdo->prepare('
    SELECT p.*, r.nom AS refuge_nom 
    FROM pensionnaires p 
    LEFT JOIN refuges_partenaires r ON p.refuge_id = r.id 
    ORDER BY p.id DESC
$');
$stmt->execute();
$pensionnaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT id, nom FROM refuges_partenaires ORDER BY nom ASC');
$stmt->execute();
$refuges = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Pensionnaires - Repaire Admin</title>
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
        .btn-secondary { background: #ddd; color: #2B2B2B; text-decoration: none; display: inline-block; line-height: 20px; }
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
        .produit-info { display: flex; gap: 15px; font-size: 13px; margin-top: 5px; flex-wrap: wrap; }
        .info-label { font-weight: 600; color: #666; }
        .produit-actions { display: flex; gap: 8px; align-items: center; }
        .produit-actions form { display: inline; }
        .select-statut { padding: 5px 8px; border-radius: 6px; border: 1px solid #ddd; font-size: 13px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Barre latérale -->
        <aside class="admin-sidebar">
            <div>
                <h2>Admin</h2>
                <nav>
                    <a href="../index.php" style="color: #FE7B7E;">🏠 Retour à l'accueil</a>
                    <a href="dashboard.php">📊 Dashboard</a>
                    <a href="pensionnaires.php" class="active">🐱 Pensionnaires</a>
                    <a href="moderer-histoires.php">📖 Belles Histoires</a>
                    <a href="ateliers.php">🎨 Ateliers</a>
                    <a href="produits.php">🛍️ Produits</a>
                    <a href="commandes.php">📦 Commandes</a>
                    <a href="utilisateurs.php">👥 Utilisateurs</a>
                </nav>
            </div>
            <div style="padding: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                <p style="margin: 0 0 5px 0; font-size: 12px; color: #aaa;">Connecté:</p>
                <p style="margin: 0 0 15px 0; font-weight: 600; color: white; font-size: 14px;"><?php echo htmlspecialchars((string) ($_SESSION['admin_email'] ?? 'Admin'), ENT_QUOTES, 'UTF-8'); ?></p>
                <a href="../logout.php" style="color: #FE7B7E; text-decoration: none; font-weight: 600; font-size: 14px;">🚪 Déconnexion</a>
            </div>
        </aside>

        <!-- Contenu principal -->
        <main class="admin-main">
            <h1>🐱 Gestion des Pensionnaires</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Formulaire pensionnaire -->
            <div class="admin-form">
                <h3><?php echo $edit_pensionnaire ? 'Modifier le pensionnaire' : 'Ajouter un nouveau pensionnaire'; ?></h3>
                
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="action" value="<?php echo $edit_pensionnaire ? 'modifier' : 'ajouter'; ?>">
                    <?php if ($edit_pensionnaire): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_pensionnaire['id']; ?>">
                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars((string) ($edit_pensionnaire['photo_url'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                    <?php endif; ?>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="nom">Nom du chat *</label>
                            <input type="text" id="nom" name="nom" required placeholder="Ex: Chaussette"
                                   value="<?php echo $edit_pensionnaire ? htmlspecialchars((string) $edit_pensionnaire['nom'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="age">Âge (en années) *</label>
                            <input type="number" id="age" name="age" min="0" required
                                   value="<?php echo $edit_pensionnaire ? (int)$edit_pensionnaire['age'] : '1'; ?>">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="caractere">Caractère / Traits</label>
                            <input type="text" id="caractere" name="caractere" placeholder="Ex: Joueur, câlin, peureux..."
                                   value="<?php echo $edit_pensionnaire ? htmlspecialchars((string) ($edit_pensionnaire['caractere'] ?? ''), ENT_QUOTES, 'UTF-8') : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="statut">Statut d'adoption</label>
                            <select id="statut" name="statut">
                                <option value="a_l_adoption" <?php echo ($edit_pensionnaire && $edit_pensionnaire['statut'] === 'a_l_adoption') ? 'selected' : ''; ?>>À l'adoption</option>
                                <option value="en_soin" <?php echo ($edit_pensionnaire && $edit_pensionnaire['statut'] === 'en_soin') ? 'selected' : ''; ?>>En soin / Famille d'accueil</option>
                                <option value="adopte" <?php echo ($edit_pensionnaire && $edit_pensionnaire['statut'] === 'adopte') ? 'selected' : ''; ?>>Adopté</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="refuge_id">Refuge partenaire</label>
                            <select id="refuge_id" name="refuge_id">
                                <option value="">-- Aucun --</option>
                                <?php foreach ($refuges as $refuge): ?>
                                    <option value="<?php echo $refuge['id']; ?>" <?php echo ($edit_pensionnaire && $edit_pensionnaire['refuge_id'] == $refuge['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars((string) $refuge['nom'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="photo">Photo du matou (convertie auto en WebP)</label>
                            <input type="file" id="photo" name="photo" accept="image/webp,image/png,image/jpeg">
                            <?php if ($edit_pensionnaire && !empty($edit_pensionnaire['photo_url'])): ?>
                                <p style="font-size: 12px; margin-top: 5px; color: #666;">Photo actuelle : <?php echo htmlspecialchars((string) $edit_pensionnaire['photo_url'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Histoire / Description</label>
                        <textarea id="description" name="description" placeholder="Racontez son histoire..."><?php echo $edit_pensionnaire ? htmlspecialchars((string) ($edit_pensionnaire['description'] ?? ''), ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $edit_pensionnaire ? '✓ Modifier la fiche' : '+ Ajouter le pensionnaire'; ?>
                        </button>
                        <?php if ($edit_pensionnaire): ?>
                            <a href="pensionnaires.php" class="btn btn-secondary">Annuler</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Liste -->
            <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <h3 style="margin-top: 0; color: #2B2B2B; border-bottom: 3px solid #85D6CD; padding-bottom: 10px;">
                    Matous enregistrés (<?php echo count($pensionnaires); ?>)
                </h3>
                
                <?php if (empty($pensionnaires)): ?>
                    <p style="color: #666; text-align: center; padding: 40px 20px;">Aucun pensionnaire enregistré pour le moment.</p>
                <?php else: ?>
                    <div class="produits-grid">
                        <?php foreach ($pensionnaires as $p): ?>
                            <div class="produit-card">
                                <?php 
                                    $img_file = !empty($p['photo_url']) ? htmlspecialchars((string) $p['photo_url'], ENT_QUOTES, 'UTF-8') : '';
                                    $image_path = !empty($img_file) ? '/' . ltrim($img_file, '/') : '/images/chat1.webp';
                                ?>
                                <img src="<?php echo $image_path; ?>" 
                                     alt="Aperçu" class="produit-img-preview" 
                                     onerror="this.onerror=null; this.src='/images/chat1.webp';">
                                
                                <div class="produit-details">
                                    <div class="produit-title"><?php echo htmlspecialchars((string) $p['nom'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo (int)$p['age']; ?> an<?php echo $p['age'] > 1 ? 's' : ''; ?>)</div>
                                    <div class="produit-info">
                                        <div><span class="info-label">Traits:</span> <?php echo htmlspecialchars($p['caractere'] ?: 'Non précisé'); ?></div>
                                        <div><span class="info-label">Refuge:</span> <?php echo htmlspecialchars($p['refuge_nom'] ?: 'Indépendant'); ?></div>
                                    </div>
                                </div>

                                <div class="produit-actions">
                                    <!-- Statut -->
                                    <form method="POST">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="update_statut">
                                        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                        <select name="statut" onchange="this.form.submit()" class="select-statut">
                                            <option value="a_l_adoption" <?php echo $p['statut'] === 'a_l_adoption' ? 'selected' : ''; ?>>🟢 À l'adoption</option>
                                            <option value="en_soin" <?php echo $p['statut'] === 'en_soin' ? 'selected' : ''; ?>>🟡 En soin</option>
                                            <option value="adopte" <?php echo $p['statut'] === 'adopte' ? 'selected' : ''; ?>>🔵 Adopté</option>
                                        </select>
                                    </form>

                                    <a href="pensionnaires.php?edit=<?php echo $p['id']; ?>" class="btn btn-edit">✏️ Modifier</a>

                                    <form method="POST" onsubmit="return confirm('Supprimer définitivement la fiche de ce pensionnaire ?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
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
    if (file_exists(__DIR__ . '/../../includes/footer.php')) {
        require_once __DIR__ . '/../../includes/footer.php'; 
    }
    ?>
</body>
</html>