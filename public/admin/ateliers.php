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

require_once __DIR__ . '/../../config/database.php';

$pdo = getPDO();
$message = '';
$error = '';

// Génération / vérification du token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Récupérer l'admin courant
$admin_id = (int)$_SESSION['admin_id'];

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        // Valider le token CSRF
        $posted_token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'], $posted_token)) {
            $error = 'Erreur de sécurité : token CSRF invalide';
        } else {
            $action = $_POST['action'];
            
            if ($action === 'ajouter' || $action === 'modifier') {
                $titre = htmlspecialchars($_POST['titre'] ?? '');
                $description = htmlspecialchars($_POST['description'] ?? '');
                $date_heure = $_POST['date_heure'] ?? '';
                $capacite_max = (int)($_POST['capacite_max'] ?? 0);
                
                if (!$titre || !$date_heure || $capacite_max <= 0) {
                    $error = 'Tous les champs sont obligatoires';
                } else {
                    try {
                        if ($action === 'ajouter') {
                            $stmt = $pdo->prepare('
                                INSERT INTO ateliers (titre, description, date_heure, capacite_max, admin_id)
                                VALUES (?, ?, ?, ?, ?)
                            ');
                            $stmt->execute([$titre, $description, $date_heure, $capacite_max, $admin_id]);
                            $message = '✓ Atelier créé avec succès !';
                        } else {
                            $id = (int)($_POST['id'] ?? 0);
                            $stmt = $pdo->prepare('
                                UPDATE ateliers 
                                SET titre = ?, description = ?, date_heure = ?, capacite_max = ?
                                WHERE id = ?
                            ');
                            $stmt->execute([$titre, $description, $date_heure, $capacite_max, $id]);
                            $message = '✓ Atelier modifié avec succès !';
                        }
                    } catch (PDOException $e) {
                        $error = 'Erreur lors de l\'enregistrement';
                    }
                }
            } elseif ($action === 'supprimer') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    try {
                        // Supprimer d'abord les réservations
                        $stmt = $pdo->prepare('DELETE FROM reservations_ateliers WHERE atelier_id = ?');
                        $stmt->execute([$id]);
                        
                        // Puis l'atelier
                        $stmt = $pdo->prepare('DELETE FROM ateliers WHERE id = ?');
                        $stmt->execute([$id]);
                        $message = '✓ Atelier supprimé avec succès !';
                    } catch (PDOException $e) {
                        $error = 'Erreur lors de la suppression';
                    }
                }
            }
        }
    }
}

// Récupérer l'atelier à modifier (s'il y a un id)
$edit_atelier = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare('SELECT * FROM ateliers WHERE id = ?');
    $stmt->execute([$edit_id]);
    $edit_atelier = $stmt->fetch();
}

// Récupérer tous les ateliers
$stmt = $pdo->query('SELECT id, titre, date_heure, capacite_max FROM ateliers ORDER BY date_heure DESC');
$ateliers = $stmt->fetchAll();

// Récupérer les réservations pour chaque atelier
$reservations_count = [];
foreach ($ateliers as $atelier) {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM reservations_ateliers WHERE atelier_id = ?');
    $stmt->execute([$atelier['id']]);
    $reservations_count[$atelier['id']] = $stmt->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Ateliers - Repaire Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Force la disposition en deux colonnes indépendantes */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif !important;
            background-color: #f9f9f9;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar sombre de gauche */
        .admin-sidebar {
            width: 260px;
            min-width: 260px;
            background-color: #2b2b2b !important;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px 0;
            box-sizing: border-box;
        }

        .admin-sidebar h2 {
            font-family: 'Pacifico', cursive !important;
            text-align: center;
            color: #FE7B7E;
            margin: 0 0 30px 0;
            font-size: 28px;
        }

        .admin-sidebar nav {
            display: flex;
            flex-direction: column;
        }

        .admin-sidebar nav a {
            display: block;
            color: #ccc !important;
            padding: 12px 25px;
            text-decoration: none;
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }

        .admin-sidebar nav a:hover, 
        .admin-sidebar nav a.active {
            color: white !important;
            background-color: #3e3e3e;
            border-left: 4px solid #85D6CD;
        }

        /* Zone de contenu à droite */
        .admin-main {
            flex-grow: 1;
            padding: 40px;
            box-sizing: border-box;
        }

        .admin-main h1 {
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 700;
            color: #2B2B2B;
            margin-top: 0;
            margin-bottom: 30px;
        }

        /* Styles spécifiques des formulaires et cartes */
        .admin-form {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
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
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-family: inherit;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
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
            border-radius: 30px;
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
        
        .btn-danger {
            background: #FE7B7E;
            color: white;
        }
        
        .btn-danger:hover {
            background: #e66769;
        }
        
        .btn-edit {
            background: #FE7B7E;
            color: white;
            padding: 8px 15px;
            font-size: 13px;
            border-radius: 20px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }
        
        .btn-edit:hover {
            background: #e66769;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 600;
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
        
        .ateliers-grid {
            display: grid;
            gap: 15px;
        }
        
        .atelier-card {
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-left: 4px solid #85D6CD;
        }
        
        .atelier-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 10px;
        }
        
        .atelier-title {
            font-weight: 700;
            color: #2B2B2B;
            font-size: 16px;
        }
        
        .atelier-date {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }
        
        .atelier-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            font-size: 14px;
            margin-bottom: 12px;
        }
        
        .info-item {
            color: #666;
        }
        
        .info-label {
            font-weight: 600;
            color: #2B2B2B;
        }
        
        .atelier-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }
        
        .atelier-actions form {
            display: inline;
        }
        
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            .admin-sidebar {
                width: 100%;
                min-width: 100%;
            }
            .atelier-info {
                grid-template-columns: 1fr;
            }
            .atelier-actions {
                flex-direction: column;
            }
            .btn, .btn-edit {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div>
                <h2>Admin</h2>
                <nav>
                    <a href="../index.php" style="color: #FE7B7E !important;">🏠 Retour à l'accueil</a>
                    <a href="dashboard.php">📊 Dashboard</a>
                    <a href="moderer-histoires.php">📖 Belles Histoires</a>
                    <a href="ateliers.php" class="active">🎨 Ateliers</a>
                    <a href="produits.php">🛍️ Produits</a>
                    <a href="commandes.php">📦 Commandes</a>
                    <a href="utilisateurs.php">👥 Utilisateurs</a>
                </nav>
            </div>
            <div style="padding: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                <p style="margin: 0 0 5px 0; font-size: 12px; color: #aaa;">Connecté:</p>
                <p style="margin: 0 0 15px 0; font-weight: 600; color: white; font-size: 13px;"><?php echo htmlspecialchars($_SESSION['admin_email'] ?? 'Admin'); ?></p>
                <a href="../logout.php" style="color: #FE7B7E !important; text-decoration: none; font-weight: 600; font-size: 14px;">🚪 Déconnexion</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <h1>🎨 Gestion des Ateliers</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Formulaire -->
            <div class="admin-form">
                <h3><?php echo $edit_atelier ? 'Modifier l\'atelier' : 'Ajouter un nouvel atelier'; ?></h3>
                
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    <input type="hidden" name="action" value="<?php echo $edit_atelier ? 'modifier' : 'ajouter'; ?>">
                    <?php if ($edit_atelier): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_atelier['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="titre">Titre *</label>
                        <input type="text" id="titre" name="titre" required 
                               value="<?php echo $edit_atelier ? htmlspecialchars($edit_atelier['titre']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"><?php echo $edit_atelier ? htmlspecialchars($edit_atelier['description'] ?? '') : ''; ?></textarea>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="date_heure">Date et heure *</label>
                            <input type="datetime-local" id="date_heure" name="date_heure" required
                                   value="<?php echo $edit_atelier ? date('Y-m-d\TH:i', strtotime($edit_atelier['date_heure'])) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="capacite_max">Capacité maximale *</label>
                            <input type="number" id="capacite_max" name="capacite_max" min="1" required
                                   value="<?php echo $edit_atelier ? $edit_atelier['capacite_max'] : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $edit_atelier ? '✓ Modifier' : '+ Ajouter'; ?>
                        </button>
                        <?php if ($edit_atelier): ?>
                            <a href="ateliers.php" class="btn btn-secondary">Annuler</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Liste des ateliers -->
            <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <h3 style="margin-top: 0; color: #2B2B2B; border-bottom: 3px solid #85D6CD; padding-bottom: 10px;">
                    Ateliers (<?php echo count($ateliers); ?>)
                </h3>
                
                <?php if (empty($ateliers)): ?>
                    <p style="color: #666; text-align: center; padding: 40px 20px;">Aucun atelier créé</p>
                <?php else: ?>
                    <div class="ateliers-grid">
                        <?php foreach ($ateliers as $atelier): ?>
                            <div class="atelier-card">
                                <div class="atelier-header">
                                    <div class="atelier-title"><?php echo htmlspecialchars($atelier['titre']); ?></div>
                                </div>
                                <div class="atelier-date">
                                    📅 <?php echo date('d/m/Y à H:i', strtotime($atelier['date_heure'])); ?>
                                </div>
                                <div class="atelier-info">
                                    <div class="info-item">
                                        <span class="info-label">Capacité:</span> 
                                        <?php echo $atelier['capacite_max']; ?>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Réservations:</span> 
                                        <?php echo $reservations_count[$atelier['id']]; ?>
                                    </div>
                                </div>
                                <div class="atelier-actions">
                                    <a href="ateliers.php?edit=<?php echo $atelier['id']; ?>" class="btn btn-edit">✏️ Modifier</a>
                                    <form method="POST" onsubmit="return confirm('Êtes-vous sûr ?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="id" value="<?php echo $atelier['id']; ?>">
                                        <button type="submit" class="btn btn-edit" style="background: #FE7B7E;">🗑️ Supprimer</button>
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