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
$message = '';
$error = '';

// Génération / vérification du token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Traitement des actions (Ajouter, Modifier, Supprimer)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posted_token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $posted_token)) {
        $error = 'Erreur de sécurité : token CSRF invalide.';
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'ajouter' || $action === 'modifier') {
            $titre = trim($_POST['titre'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $date_heure = $_POST['date_heure'] ?? '';
            $capacite_max = (int)($_POST['capacite_max'] ?? 10);
            
            $image_url = $_POST['current_image'] ?? '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_name = basename($_FILES['image']['name']);
                $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
                
                if (in_array($ext, $allowed, true)) {
                    $new_name = uniqid('atelier_', true) . '.' . $ext;
                    $upload_dir = __DIR__ . '/../images/ateliers/';
                    
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0775, true);
                    }

                    if (move_uploaded_file($file_tmp, $upload_dir . $new_name)) {
                        $image_url = 'images/ateliers/' . $new_name;
                    }
                }
            }

            if (empty($titre) || empty($date_heure)) {
                $error = 'Le titre et la date/heure sont obligatoires.';
            } else {
                try {
                    if ($action === 'ajouter') {
                        $stmt = $pdo->prepare('
                            INSERT INTO ateliers (titre, description, date_heure, capacite_max, image)
                            VALUES (?, ?, ?, ?, ?)
                        ');
                        $stmt->execute([$titre, $description, $date_heure, $capacite_max, $image_url]);
                        $message = '✓ Atelier ajouté avec succès !';
                    } else {
                        $id = (int)($_POST['id'] ?? 0);
                        $stmt = $pdo->prepare('
                            UPDATE ateliers 
                            SET titre = ?, description = ?, date_heure = ?, capacite_max = ?, image = ?
                            WHERE id = ?
                        ');
                        $stmt->execute([$titre, $description, $date_heure, $capacite_max, $image_url, $id]);
                        $message = '✓ Atelier modifié avec succès !';
                    }
                } catch (PDOException $e) {
                    error_log($e->getMessage());
                    $error = 'Erreur lors de l\'enregistrement en base de données.';
                }
            }
        } elseif ($action === 'supprimer') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                try {
                    $stmt = $pdo->prepare('DELETE FROM ateliers WHERE id = ?');
                    $stmt->execute([$id]);
                    $message = '✓ Atelier supprimé avec succès !';
                } catch (PDOException $e) {
                    error_log($e->getMessage());
                    $error = 'Erreur lors de la suppression de l\'atelier.';
                }
            }
        }
    }
}

// Récupérer l'atelier à modifier si paramètre "edit" présent
$edit_atelier = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare('SELECT * FROM ateliers WHERE id = ?');
    $stmt->execute([$edit_id]);
    $edit_atelier = $stmt->fetch();
}

// Récupérer tous les ateliers
$stmt = $pdo->query('SELECT * FROM ateliers ORDER BY date_heure DESC');
$ateliers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Ateliers - Repaire Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Pacifico&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Montserrat', sans-serif !important;
            background-color: #fdfbf7;
            color: #2b2b2b;
        }

        .admin-container { display: flex; min-height: 100vh; }

        /* --- SIDEBAR --- */
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

        /* Forcer Montserrat sur le menu admin */
        .admin-menu { 
            list-style: none; 
            font-family: 'Montserrat', sans-serif !important;
        }
        
        .admin-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 25px;
            color: #d1d5db;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600 !important;
            font-family: 'Montserrat', sans-serif !important;
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
            font-family: 'Montserrat', sans-serif !important;
        }

        .admin-user-info p { color: #9ca3af; margin-bottom: 4px; }
        .admin-user-info strong { display: block; color: #ffffff; margin-bottom: 10px; word-break: break-all; }
        .admin-user-info a { color: #FE7B7E; text-decoration: none; font-weight: 600; }

        /* --- CONTENU PRINCIPAL --- */
        .admin-main {
            flex: 1;
            margin-left: 250px;
            padding: 40px;
            font-family: 'Montserrat', sans-serif !important;
        }

        .admin-header { margin-bottom: 30px; }
        
        /* Titre principal avec la police manuscrite uniquement si désiré */
        .admin-header h1 {
            font-family: 'Pacifico', cursive;
            font-size: 2.2rem;
            font-weight: normal;
            color: #FE7B7E;
        }

        /* MESSAGES DE NOTIFICATION */
        .alert {
            padding: 14px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 600;
            font-size: 0.95rem;
        }
        .alert-success { background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
        .alert-error { background: #f8d7da; color: #842029; border: 1px solid #f5c6cb; }

        /* FORMULAIRE & BLOCS */
        .admin-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 35px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }

        .admin-card h3 {
            font-family: 'Montserrat', sans-serif !important;
            font-size: 1.25rem;
            font-weight: 700;
            color: #2b2b2b;
            padding-bottom: 12px;
            margin-bottom: 20px;
            border-bottom: 3px solid #85D6CD;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group { margin-bottom: 20px; }
        .form-group.full-width { grid-column: span 2; }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #374151;
            font-family: 'Montserrat', sans-serif !important;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif !important;
            font-size: 0.95rem;
            background-color: #f9fafb;
            transition: all 0.2s;
        }

        .form-group textarea { resize: vertical; min-height: 100px; }

        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: #85D6CD;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(133, 214, 205, 0.2);
        }

        .form-actions { display: flex; gap: 12px; margin-top: 10px; }

        /* BOUTONS */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
            font-family: 'Montserrat', sans-serif !important;
        }

        .btn-primary { background: #85D6CD; color: white; }
        .btn-primary:hover { background: #6bc3b8; }

        .btn-secondary { background: #e5e7eb; color: #374151; }
        .btn-secondary:hover { background: #d1d5db; }

        .btn-edit { background: #3b82f6; color: white; }
        .btn-edit:hover { background: #2563eb; }

        .btn-delete { background: #ef4444; color: white; }
        .btn-delete:hover { background: #dc2626; }

        /* CARTES D'ATELIERS */
        .ateliers-list { display: flex; flex-direction: column; gap: 15px; }

        .atelier-item {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-left: 4px solid #85D6CD;
            border-radius: 10px;
            padding: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .atelier-preview {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            background: #f3f4f6;
            flex-shrink: 0;
        }

        .atelier-content { flex: 1; }

        .atelier-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 6px;
        }

        .atelier-meta {
            display: flex;
            gap: 20px;
            font-size: 0.85rem;
            color: #6b7280;
        }

        .atelier-meta span { font-weight: 600; color: #374151; }

        .atelier-actions { display: flex; gap: 8px; }
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
                    <li><a href="moderer-histoires.php">📖 Belles Histoires</a></li>
                    <li><a href="ateliers.php" class="active">🎨 Ateliers</a></li>
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
                <h1>🎨 Gestion des Ateliers</h1>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <!-- Formulaire d'ajout / modification -->
            <div class="admin-card">
                <h3><?= $edit_atelier ? 'Modifier l\'atelier' : 'Ajouter un nouvel atelier' ?></h3>
                
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="action" value="<?= $edit_atelier ? 'modifier' : 'ajouter' ?>">
                    <?php if ($edit_atelier): ?>
                        <input type="hidden" name="id" value="<?= (int)$edit_atelier['id'] ?>">
                        <input type="hidden" name="current_image" value="<?= htmlspecialchars($edit_atelier['image'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <?php endif; ?>

                    <div class="form-group full-width">
                        <label for="titre">Titre de l'atelier *</label>
                        <input type="text" id="titre" name="titre" required 
                               value="<?= $edit_atelier ? htmlspecialchars($edit_atelier['titre'], ENT_QUOTES, 'UTF-8') : '' ?>">
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"><?= $edit_atelier ? htmlspecialchars($edit_atelier['description'] ?? '', ENT_QUOTES, 'UTF-8') : '' ?></textarea>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="date_heure">Date et Heure *</label>
                            <input type="datetime-local" id="date_heure" name="date_heure" required
                                   value="<?= $edit_atelier ? date('Y-m-d\TH:i', strtotime($edit_atelier['date_heure'])) : '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="capacite_max">Capacité max (personnes)</label>
                            <input type="number" id="capacite_max" name="capacite_max" min="1" required
                                   value="<?= $edit_atelier ? (int)$edit_atelier['capacite_max'] : '10' ?>">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="image">Image de l'atelier</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <?php if ($edit_atelier && !empty($edit_atelier['image'])): ?>
                            <p style="font-size: 0.8rem; color: #6b7280; margin-top: 6px;">
                                Fichier actuel : <code><?= htmlspecialchars($edit_atelier['image'], ENT_QUOTES, 'UTF-8') ?></code>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?= $edit_atelier ? '✓ Modifier l\'atelier' : '+ Ajouter l\'atelier' ?>
                        </button>
                        <?php if ($edit_atelier): ?>
                            <a href="ateliers.php" class="btn btn-secondary">Annuler</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Liste des ateliers -->
            <div class="admin-card">
                <h3>Ateliers programmés (<?= count($ateliers) ?>)</h3>

                <?php if (empty($ateliers)): ?>
                    <p style="color: #6b7280; text-align: center; padding: 20px 0;">Aucun atelier programmé pour le moment.</p>
                <?php else: ?>
                    <div class="ateliers-list">
                        <?php foreach ($ateliers as $atelier): ?>
                            <?php 
                                $img_file = !empty($atelier['image']) ? htmlspecialchars($atelier['image'], ENT_QUOTES, 'UTF-8') : '';
                                $image_path = !empty($img_file) ? "../" . $img_file : "../images/ateliers/atelier-default.jpg";
                            ?>
                            <div class="atelier-item">
                                <img src="<?= $image_path ?>" 
                                     alt="Aperçu" class="atelier-preview" 
                                     onerror="this.onerror=null; this.src='../images/ateliers/atelier-default.jpg';">

                                <div class="atelier-content">
                                    <div class="atelier-title"><?= htmlspecialchars($atelier['titre'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="atelier-meta">
                                        <div>📅 Date : <span><?= date('d/m/Y à H:i', strtotime($atelier['date_heure'])) ?></span></div>
                                        <div>👥 Capacité : <span><?= (int)$atelier['capacite_max'] ?> pers.</span></div>
                                    </div>
                                </div>

                                <div class="atelier-actions">
                                    <a href="ateliers.php?edit=<?= (int)$atelier['id'] ?>" class="btn btn-edit">✏️ Modifier</a>
                                    <form method="POST" onsubmit="return confirm('Supprimer cet atelier définitivement ?');" style="display: inline;">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="id" value="<?= (int)$atelier['id'] ?>">
                                        <button type="submit" class="btn btn-delete">🗑️ Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>