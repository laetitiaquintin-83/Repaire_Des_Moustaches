<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$sitePrefix = '';

// Inclusion sécurisée de la base de données
require_once __DIR__ . '/../config/database.php';

$error = null;
$success = false;
$demand_id = null;
$email = '';

// Génération sécurisée du token CSRF
$csrf_token = function_exists('generateCSRFToken') ? generateCSRFToken() : ($_SESSION['csrf_token'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_check = $_POST['csrf_token'] ?? '';
    
    // Validation du token CSRF
    $isValidCsrf = function_exists('validateCSRFToken') 
        ? validateCSRFToken($csrf_check) 
        : hash_equals($_SESSION['csrf_token'] ?? '', $csrf_check);

    if (!$isValidCsrf) {
        $error = "⚠️ Erreur de sécurité : token CSRF invalide. Veuillez réessayer.";
    } else {
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $motif = trim($_POST['motif'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($nom)) {
            $error = "⚠️ Veuillez entrer votre nom et prénom.";
        } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "⚠️ Veuillez entrer une adresse email valide.";
        } elseif (empty($motif) || !in_array($motif, ['participer', 'animer', 'prive'], true)) {
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

// Inclusion du header
if (file_exists(__DIR__ . '/includes/header.php')) {
    require_once __DIR__ . '/includes/header.php';
} else {
    require_once __DIR__ . '/../includes/header.php';
}
?>

<style>
/* --- STYLES FORMULAIRE ATELIERS RÉTRO --- */
.page-form-container {
    max-width: 1050px;
    margin: 40px auto 70px auto;
    padding: 0 20px;
}

.form-header {
    text-align: center;
    margin-bottom: 45px;
}

.form-header h1 {
    font-family: 'Pacifico', cursive;
    color: #FF7B7B;
    font-size: 3rem;
    font-weight: normal;
    margin-bottom: 10px;
    text-shadow: 2px 2px 0px #fff, 4px 4px 0px rgba(0, 0, 0, 0.04);
}

.form-header p {
    font-family: 'Montserrat', sans-serif;
    color: #4A5568;
    font-size: 1.1rem;
    max-width: 620px;
    margin: 0 auto;
}

/* Grille 2 Colonnes (Image + Formulaire) */
.ateliers-grid {
    display: flex;
    gap: 40px;
    align-items: flex-start;
    justify-content: center;
    flex-wrap: wrap;
}

.ateliers-image {
    flex: 1;
    min-width: 300px;
    max-width: 420px;
    text-align: center;
}

.ateliers-image img {
    width: 100%;
    height: auto;
    border-radius: 20px;
    border: 4px solid #82CECA;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    object-fit: cover;
}

/* Carte Formulaire */
.formulaire-box {
    flex: 1.2;
    min-width: 320px;
    background: #FFFFFF;
    border: 3px solid #82CECA;
    border-radius: 24px;
    padding: 35px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
}

/* Groupes de champs */
.form-groupe {
    margin-bottom: 22px;
}

.form-groupe label {
    display: block;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    color: #802C38;
    font-size: 0.95rem;
    margin-bottom: 8px;
}

.form-groupe input[type="text"],
.form-groupe input[type="email"],
.form-groupe input[type="date"],
.form-groupe select,
.form-groupe textarea {
    width: 100%;
    padding: 12px 16px;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.95rem;
    color: #2D3748;
    background-color: #FFFDF8;
    border: 2px solid #E2E8F0;
    border-radius: 12px;
    box-sizing: border-box;
    transition: all 0.25s ease;
}

.form-groupe input:focus,
.form-groupe select:focus,
.form-groupe textarea:focus {
    outline: none;
    border-color: #82CECA;
    background-color: #FFFFFF;
    box-shadow: 0 0 0 4px rgba(130, 206, 202, 0.2);
}

.form-groupe textarea {
    resize: vertical;
    min-height: 120px;
}

/* Bouton d'envoi */
.btn-envoyer {
    width: 100%;
    background-color: #FF7B7B;
    color: #FFFFFF;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    font-size: 1.05rem;
    padding: 14px 20px;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(255, 123, 123, 0.35);
    transition: all 0.25s ease;
    margin-top: 10px;
}

.btn-envoyer:hover {
    background-color: #802C38;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(128, 44, 56, 0.35);
}

/* Messages d'Alerte */
.message-error {
    background-color: #FFF5F5;
    border-left: 5px solid #E53E3E;
    color: #C53030;
    padding: 14px 18px;
    border-radius: 10px;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 25px;
}

.message-success {
    background-color: #F0FFF4;
    border: 2px dashed #38A169;
    color: #276749;
    padding: 25px;
    border-radius: 16px;
    font-family: 'Montserrat', sans-serif;
    text-align: center;
    font-size: 1.1rem;
    font-weight: 600;
    line-height: 1.6;
    margin-bottom: 25px;
}

.message-success small {
    display: inline-block;
    margin-top: 8px;
    color: #4A5568;
    font-weight: normal;
}

/* Boutons Retour */
.btn-return-group {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-return {
    display: inline-block;
    padding: 10px 22px;
    background-color: #E2E8F0;
    color: #4A5568;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    font-size: 0.9rem;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-return:hover {
    background-color: #CBD5E0;
    transform: translateY(-2px);
}

.btn-return-primary {
    background-color: #82CECA;
    color: #2D3748;
}

.btn-return-primary:hover {
    background-color: #62B8B3;
    color: #FFFFFF;
}
</style>

<main class="page-form-container">
    <div class="form-header">
        <h1>Ateliers & Événements 🎨</h1>
        <p>Que vous souhaitiez participer à une animation ou privatiser un moment d'exception, dites-nous tout !</p>
    </div>

    <section class="ateliers-grid">
        <!-- Visual Illustration -->
        <div class="ateliers-image">
            <picture>
                <source srcset="images/formulaire.webp" type="image/webp">
                <img src="images/formulaire.jpg" alt="Illustration des ateliers au Repaire des Moustaches" width="400" height="400" loading="lazy">
            </picture>
        </div>

        <!-- Form Card -->
        <div class="formulaire-box">
            <?php if ($success): ?>
                <div class="message-success">
                    🐾 Merci ! Votre demande a bien été envoyée au Repaire.<br>
                    <small>Numéro de dossier : <strong>#<?php echo htmlspecialchars((string)$demand_id, ENT_QUOTES, 'UTF-8'); ?></strong></small><br>
                    <small>Une confirmation sera envoyée à : <strong><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></strong></small>
                </div>
                
                <div class="btn-return-group">
                    <a href="formulaire.php" class="btn-return">← Autre demande</a>
                    <a href="index.php" class="btn-return btn-return-primary">🏠 Retour à l'accueil</a>
                </div>
            <?php else: ?>
                <?php if ($error): ?>
                    <div class="message-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars((string)$csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="form-groupe">
                        <label for="nom">Nom & Prénom</label>
                        <input type="text" id="nom" name="nom" placeholder="Ex: Jane Doe" value="<?php echo htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-groupe">
                        <label for="email">Adresse Email</label>
                        <input type="email" id="email" name="email" placeholder="jane.doe@email.com" value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
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
                        <label for="date">Date souhaitée (optionnel)</label>
                        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($_POST['date'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="form-groupe">
                        <label for="message">Votre message</label>
                        <textarea id="message" name="message" rows="5" placeholder="Décrivez votre projet ou posez vos questions..." required><?php echo htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <button type="submit" class="btn-envoyer">Envoyer ma demande 🐾</button>
                </form>
            <?php endif; ?>
        </div>
    </section>
</main>

<script src="js/form-validation.js"></script>

<?php 
if (file_exists(__DIR__ . '/includes/footer.php')) {
    require_once __DIR__ . '/includes/footer.php';
} else {
    require_once __DIR__ . '/../includes/footer.php';
}
?>