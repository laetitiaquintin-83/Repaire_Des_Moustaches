<?php
// public/admin/pensionnaires.php
require_once __DIR__ . '/../../config/database.php';

// Verification de la session admin
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$message = '';
$error = '';

// ----------------------------------------------------
// TRAITEMENT DU FORMULAIRE : AJOUT / ACTION
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // 1. AJOUT D'UN PENSIONNAIRE
    if ($action === 'add') {
        $nom = trim($_POST['nom'] ?? '');
        $age = (int)($_POST['age'] ?? 0);
        $caractere = trim($_POST['caractere'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $photo_url = trim($_POST['photo_url'] ?? 'images/chats/default.jpg');
        $statut = $_POST['statut'] ?? 'a_l_adoption';
        $refuge_id = !empty($_POST['refuge_id']) ? (int)$_POST['refuge_id'] : null;
        $admin_id = $_SESSION['admin_id'] ?? 1;

        if (!empty($nom) && $age >= 0) {
            $stmt = $pdo->prepare("
                INSERT INTO pensionnaires (nom, age, description, caractere, photo_url, statut, refuge_id, admin_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            if ($stmt->execute([$nom, $age, $description, $caractere, $photo_url, $statut, $refuge_id, $admin_id])) {
                $message = "Le pensionnaire <strong>" . htmlspecialchars($nom) . "</strong> a été ajouté avec succès !";
            } else {
                $error = "Erreur lors de l'ajout du pensionnaire.";
            }
        } else {
            $error = "Veuillez remplir correctement les champs obligatoires (Nom, Âge).";
        }
    }

    // 2. CHANGEMENT DE STATUT
    if ($action === 'update_statut') {
        $id = (int)($_POST['id'] ?? 0);
        $nouveau_statut = $_POST['statut'] ?? 'a_l_adoption';

        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE pensionnaires SET statut = ? WHERE id = ?");
            if ($stmt->execute([$nouveau_statut, $id])) {
                $message = "Le statut du pensionnaire a été mis à jour.";
            }
        }
    }

    // 3. SUPPRESSION
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM pensionnaires WHERE id = ?");
            if ($stmt->execute([$id])) {
                $message = "Pensionnaire supprimé avec succès.";
            }
        }
    }
}

// ----------------------------------------------------
// RÉCUPÉRATION DES DONNÉES
// ----------------------------------------------------
$pensionnaires = $pdo->query("
    SELECT p.*, r.nom AS refuge_nom 
    FROM pensionnaires p 
    LEFT JOIN refuges_partenaires r ON p.refuge_id = r.id 
    ORDER BY p.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

$refuges = $pdo->query("SELECT id, nom FROM refuges_partenaires ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Pensionnaires | Le Repaire des Moustaches</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-container { max-width: 1100px; margin: 30px auto; padding: 20px; }
        .admin-nav { display: flex; gap: 15px; margin-bottom: 25px; background: #f4f4f4; padding: 15px; border-radius: 8px; }
        .admin-nav a { text-decoration: none; color: #333; font-weight: bold; }
        .admin-nav a.active { color: #d9534f; }
        .alert-success { background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
        .card-admin { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .form-group { display: flex; flex-direction: column; gap: 5px; margin-bottom: 15px; }
        .form-group.full { grid-column: 1 / -1; }
        .form-group label { font-weight: bold; font-size: 0.9rem; }
        .form-group input, .form-group textarea, .form-group select { padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; }
        .btn-submit { background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-submit:hover { background: #218838; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: middle; }
        th { background: #f8f9fa; }
        .badge { padding: 4px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: bold; display: inline-block; }
        .badge-a_l_adoption { background: #d4edda; color: #155724; }
        .badge-adopte { background: #cce5ff; color: #004085; }
        .badge-en_soin { background: #fff3cd; color: #856404; }
        .img-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
        .inline-form { display: flex; gap: 5px; align-items: center; }
    </style>
</head>
<body>

<div class="admin-container">
    <h1>🐱 Back-Office : Gestion des Pensionnaires</h1>

    <div class="admin-nav">
        <a href="index.php">Tableau de bord</a>
        <a href="pensionnaires.php" class="active">Pensionnaires</a>
        <a href="ateliers.php">Ateliers</a>
        <a href="produits.php">Produits</a>
        <a href="commandes.php">Commandes</a>
        <a href="logout.php" style="margin-left: auto; color: red;">Déconnexion</a>
    </div>

    <?php if ($message): ?>
        <div class="alert-success"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <!-- FORMULAIRE D'AJOUT -->
    <div class="card-admin">
        <h2>Ajouter un nouveau pensionnaire</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="add">
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="nom">Nom du chat *</label>
                    <input type="text" id="nom" name="nom" required placeholder="Ex: Chaussette">
                </div>

                <div class="form-group">
                    <label for="age">Âge (en années) *</label>
                    <input type="number" id="age" name="age" min="0" value="1" required>
                </div>

                <div class="form-group">
                    <label for="caractere">Caractère / Traits</label>
                    <input type="text" id="caractere" name="caractere" placeholder="Ex: Joueur, câlin, un peu peureux...">
                </div>

                <div class="form-group">
                    <label for="statut">Statut d'adoption</label>
                    <select id="statut" name="statut">
                        <option value="a_l_adoption">À l'adoption</option>
                        <option value="en_soin">En soin / Famille d'accueil</option>
                        <option value="adopte">Adopté</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="photo_url">Chemin de la photo</label>
                    <input type="text" id="photo_url" name="photo_url" value="images/chats/chat1.jpg">
                </div>

                <div class="form-group">
                    <label for="refuge_id">Refuge partenaire</label>
                    <select id="refuge_id" name="refuge_id">
                        <option value="">-- Aucun --</option>
                        <?php foreach ($refuges as $refuge): ?>
                            <option value="<?= $refuge['id'] ?>"><?= htmlspecialchars($refuge['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group full">
                    <label for="description">Histoire / Description</label>
                    <textarea id="description" name="description" rows="3" placeholder="Racontez son histoire..."></textarea>
                </div>
            </div>

            <button type="submit" class="btn-submit">➕ Ajouter le pensionnaire</button>
        </form>
    </div>

    <!-- LISTE DES PENSIONNAIRES -->
    <div class="card-admin">
        <h2>Liste des chats (<?= count($pensionnaires) ?>)</h2>
        
        <?php if (empty($pensionnaires)): ?>
            <p>Aucun pensionnaire enregistré pour le moment.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Âge</th>
                        <th>Caractère</th>
                        <th>Statut</th>
                        <th>Refuge</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pensionnaires as $p): ?>
                        <tr>
                            <td>
                                <img src="../<?= htmlspecialchars($p['photo_url']) ?>" alt="<?= htmlspecialchars($p['nom']) ?>" class="img-thumb" onerror="this.src='../images/chats/chat1.jpg'">
                            </td>
                            <td><strong><?= htmlspecialchars($p['nom']) ?></strong></td>
                            <td><?= (int)$p['age'] ?> an(s)</td>
                            <td><small><?= htmlspecialchars($p['caractere'] ?? '-') ?></small></td>
                            <td>
                                <form method="POST" action="" class="inline-form">
                                    <input type="hidden" name="action" value="update_statut">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <select name="statut" onchange="this.form.submit()">
                                        <option value="a_l_adoption" <?= $p['statut'] === 'a_l_adoption' ? 'selected' : '' ?>>À l'adoption</option>
                                        <option value="en_soin" <?= $p['statut'] === 'en_soin' ? 'selected' : '' ?>>En soin</option>
                                        <option value="adopte" <?= $p['statut'] === 'adopte' ? 'selected' : '' ?>>Adopté</option>
                                    </select>
                                </form>
                            </td>
                            <td><small><?= htmlspecialchars($p['refuge_nom'] ?? 'Non spécifié') ?></small></td>
                            <td>
                                <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce pensionnaire ?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button type="submit" style="color:red; background:none; border:none; cursor:pointer;">❌ Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

</body>
</html>