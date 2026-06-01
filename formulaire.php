<?php
session_start();
require 'config/database.php';

$error = null;
$success = false;
$demand_id = null;
$email = '';
$csrf_token = generateCSRFToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_check = $_POST['csrf_token'] ?? '';
    if (!validateCSRFToken($csrf_check)) {
        $error = "⚠️ Erreur de sécurité : token invalide. Veuillez réessayer.";
    } else {
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $motif = trim($_POST['motif'] ?? '');
        $date = trim($_POST['date'] ?? null);
        $message = trim($_POST['message'] ?? '');
    
        if (empty($nom)) {
            $error = "⚠️ Veuillez entrer votre nom et prénom.";
        } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "⚠️ Veuillez entrer une adresse email valide.";
        } elseif (empty($motif) || !in_array($motif, ['participer', 'animer', 'prive'])) {
            $error = "⚠️ Veuillez choisir une option valide.";
        } elseif (empty($message)) {
            $error = "⚠️ Veuillez entrer votre message.";
        } else {
            $date_sql = null;
            if (!empty($date)) {
                $date_parsed = DateTime::createFromFormat('Y-m-d', $date);
                if ($date_parsed && $date_parsed->format('Y-m-d') === $date) {
                    $date_sql = $date;
                } else {
                    $error = "⚠️ La date n'est pas au bon format.";
                }
            }
            
            if (!$error) {
                try {
                    $pdo = getPDO();
                    $sql = "INSERT INTO demandes (nom, email, motif, date_souhaitee, message, statut, date_demande) 
                            VALUES (:nom, :email, :motif, :date, :message, 'nouvelle', NOW())";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':nom' => $nom,
                        ':email' => $email,
                        ':motif' => $motif,
                        ':date' => $date_sql,
                        ':message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8')
                    ]);
                    $demand_id = $pdo->lastInsertId();
                    $success = true;
                } catch (PDOException $e) {
                    $error = "⚠️ Erreur lors de l'enregistrement. Veuillez réessayer.";
                    error_log("Erreur formulaire.php: " . $e->getMessage());
                }
            }
        }
    }
}

require_once 'includes/header.php';
?>

<main>
        <div style="text-align: center; padding: 40px 20px 0;">
            <h1 style="font-family: 'Pacifico', cursive; color: #2B2B2B; font-size: 3rem; margin-bottom: 10px;">Ateliers & Événements</h1>
            <p style="font-size: 1.1rem; color: #555; max-width: 600px; margin: 0 auto;">Que vous souhaitiez participer ou proposer une animation, dites-nous tout !</p>
        </div>

        <section class="ateliers-container">
            <div class="ateliers-image">
                <img src="images/formulaire.jpg" alt="Illustration des ateliers au Repaire des Moustaches">
            </div>

            <div class="formulaire-box">
                <?php if ($success): ?>
                    <div class="message-success">
                        ✅ Merci ! Votre demande enregistrée.<br>
                        <small>Numéro: #<?php echo htmlspecialchars($demand_id); ?></small><br>
                        Réponse à <?php echo htmlspecialchars($email); ?>
                    </div>
                    <a href="formulaire.php" class="btn-return">← Nouveau formulaire</a>
                    <a href="index.php" class="btn-return" style="background-color: #85D6CD; color: #2B2B2B; margin-left: 10px;">Retour à l'accueil</a>
                <?php else: ?>
                    <?php if ($error): ?>
                        <div class="message-error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="form-groupe">
                            <label for="nom">Nom & Prénom</label>
                            <input type="text" id="nom" name="nom" placeholder="Ex: Jane Doe" value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>" required>
                        </div>
                        <div class="form-groupe">
                            <label for="email">Adresse Email</label>
                            <input type="email" id="email" name="email" placeholder="jane.doe@email.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                        </div>
                        <div class="form-groupe">
                            <label for="motif">Je souhaite...</label>
                            <select id="motif" name="motif" required>
                                <option value="">Choisissez une option...</option>
                                <option value="participer" <?php echo (($_POST['motif'] ?? '') === 'participer') ? 'selected' : ''; ?>>🙋‍♀️ Participer à un atelier</option>
                                <option value="animer" <?php echo (($_POST['motif'] ?? '') === 'animer') ? 'selected' : ''; ?>>🎨 Animer un atelier</option>
                                <option value="prive" <?php echo (($_POST['motif'] ?? '') === 'prive') ? 'selected' : ''; ?>>🎉 Privatiser un événement</option>
                            </select>
                        </div>
                        <div class="form-groupe">
                            <label for="date">Date (optionnel)</label>
                            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($_POST['date'] ?? ''); ?>">
                        </div>
                        <div class="form-groupe">
                            <label for="message">Votre message</label>
                            <textarea id="message" name="message" rows="5" placeholder="Dites-nous en plus..." required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" class="btn-envoyer">Envoyer 🐾</button>
                    </form>
                <?php endif; ?>
            </div>
        </section>
    </main>

<!-- Validation JavaScript côté client pour les formulaires -->
<script src="js/form-validation.js"></script>

<?php require_once 'includes/footer.php'; ?>
