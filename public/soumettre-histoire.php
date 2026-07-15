<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// ============================================================
// 📌 PAGE PUBLIQUE : Soumettre une Belle Histoire
// ============================================================

// Définir la meta description pour cette page (utilisée dans header.php)
$page_description = 'Partagez votre histoire d\'adoption avec la communauté du Repaire des Moustaches. Racontez la nouvelle vie de votre chat adopté.';

// Déterminer le préfixe de chemin pour les assets
$sitePrefix = strpos($_SERVER['SCRIPT_NAME'] ?? '', '/public/') !== false ? '../' : '';

$pdo = getPDO();
$csrf_token = generateCSRFToken();
$message = '';
$error = '';

// Traiter le formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Valider le token CSRF
    $csrf_check = $_POST['csrf_token'] ?? '';
    if (!validateCSRFToken($csrf_check)) {
        $error = 'Erreur de sécurité : token CSRF invalide. Veuillez réessayer.';
    } else {
        $titre = isset($_POST['titre']) ? trim((string) $_POST['titre']) : '';
        $contenu = isset($_POST['contenu']) ? trim((string) $_POST['contenu']) : '';
        $utilisateur_id = isset($_POST['utilisateur_id']) ? (int) $_POST['utilisateur_id'] : 0;

        // Validations
        if (empty($titre)) {
            $error = 'Le titre est obligatoire.';
        } elseif (strlen($titre) < 3) {
            $error = 'Le titre doit comporter au moins 3 caractères.';
        } elseif (empty($contenu)) {
            $error = 'L\'histoire est obligatoire.';
        } elseif (strlen($contenu) < 20) {
            $error = 'L\'histoire doit comporter au moins 20 caractères pour être publiée.';
        } elseif ($utilisateur_id <= 0) {
            $error = 'Veuillez sélectionner un auteur.';
        } else {
            // Vérifier que l'utilisateur existe bien
            $stmtCheck = $pdo->prepare('SELECT id FROM utilisateurs WHERE id = ?');
            $stmtCheck->execute([$utilisateur_id]);
            if (!$stmtCheck->fetch()) {
                $error = 'L\'utilisateur sélectionné n\'existe pas.';
            } else {
                try {
                    // Insérer l'histoire en base (statut "en_attente" pour modération)
                    $sql = 'INSERT INTO belles_histoires (utilisateur_id, titre, contenu, statut, date_publication)
                            VALUES (:utilisateur_id, :titre, :contenu, "en_attente", NOW())';
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':utilisateur_id' => $utilisateur_id,
                        ':titre' => $titre,
                        ':contenu' => $contenu,
                    ]);
                    
                    $message = '✨ Votre histoire a été soumise avec succès ! Elle sera vérifiée par l\'équipe avant publication.';
                    
                    // Nettoyer les champs après soumission (optionnel)
                    // On ne réaffiche pas le formulaire pour éviter les doublons
                } catch (PDOException $e) {
                    error_log('Erreur soumission histoire : ' . $e->getMessage());
                    $error = 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer.';
                }
            }
        }
    }
}

// Récupérer la liste des utilisateurs (adoptants)
$sqlUsers = 'SELECT id, nom, prenom FROM utilisateurs ORDER BY nom, prenom';
$utilisateurs = $pdo->query($sqlUsers)->fetchAll();

// Inclure le header
include __DIR__ . '/../includes/header.php';
?>

<main class="page-liste">
    <section class="liste-header">
        <h1>📝 Soumettre une histoire</h1>
        <p>Partagez les aventures de votre chat adopté et inspirez les autres !</p>
    </section>

    <div class="formulaire-histoire">
        <?php if (!empty($message)): ?>
            <div class="message-succes">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="message-erreur">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($utilisateurs)): ?>
            <div class="message-erreur" style="background: #FFF3CD; color: #856404; border-left-color: #FFC107;">
                ⚠️ Aucun utilisateur enregistré. Veuillez contacter l'administrateur pour ajouter des adoptants.
            </div>
        <?php endif; ?>

        <div class="infos-importantes">
            <h3>📝 Avant de soumettre</h3>
            <ul>
                <li>Votre histoire sera modérée avant d'être publiée sur le mur.</li>
                <li>Respectez les valeurs du Repaire : bienveillance et solidarité.</li>
                <li>Décrivez la vie quotidienne, les moments amusants, les progrès de votre chat.</li>
                <li>Évitez les contenus offensants ou publicitaires.</li>
            </ul>
        </div>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
            
            <div class="form-group">
                <label for="utilisateur_id">Qui êtes-vous ? *</label>
                <select id="utilisateur_id" name="utilisateur_id" required <?php echo empty($utilisateurs) ? 'disabled' : ''; ?>>
                    <option value="">-- Sélectionnez votre nom --</option>
                    <?php foreach ($utilisateurs as $user): ?>
                        <option value="<?php echo (int) $user['id']; ?>">
                            <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($utilisateurs)): ?>
                    <small style="color: #999; display: block; margin-top: 5px;">
                        Aucun adoptant enregistré pour le moment.
                    </small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="titre">Titre de l'histoire *</label>
                <input type="text" id="titre" name="titre" 
                       placeholder="Ex: Velours découvre son nouveau jardin" 
                       required maxlength="150"
                       <?php if (!empty($message)): ?>value="<?php echo htmlspecialchars($_POST['titre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"<?php endif; ?>>
                <small style="color: #999; display: block; margin-top: 5px;">
                    Minimum 3 caractères, maximum 150 caractères.
                </small>
            </div>

            <div class="form-group">
                <label for="contenu">L'histoire *</label>
                <textarea id="contenu" name="contenu" 
                          placeholder="Racontez comment se passe la vie de votre chat adopté. Qu'aime-t-il faire ? Y a-t-il des anecdotes amusantes ?" 
                          required minlength="20"><?php if (!empty($message)): ?><?php echo htmlspecialchars($_POST['contenu'] ?? '', ENT_QUOTES, 'UTF-8'); ?><?php endif; ?></textarea>
                <small style="color: #999; display: block; margin-top: 5px;">
                    Minimum 20 caractères.
                </small>
            </div>

            <button type="submit" <?php echo empty($utilisateurs) ? 'disabled style="background: #ccc; cursor: not-allowed;"' : ''; ?>>
                📤 Soumettre mon histoire
            </button>
        </form>
    </div>
</main>

<?php
// Inclure le footer
include __DIR__ . '/../includes/footer.php';
?>