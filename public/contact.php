<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// On cible directement le bon dossier includes/
require_once __DIR__ . '/../includes/header.php';

$page_description = "Contactez l'équipe du Repaire des Moustaches à Toulon. Une question sur le projet, les adoptions ou les ateliers ? Écrivez-nous !";

$succes = false;
$erreur = false;

// Génération sécurisée du token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_check = $_POST['csrf_token'] ?? '';

    // Validation du token CSRF
    if (!hash_equals($_SESSION['csrf_token'], $csrf_check)) {
        $erreur = "⚠️ Erreur de sécurité : token CSRF invalide. Veuillez réessayer.";
    } else {
        $nom = trim($_POST['nom'] ?? '');
        $email_input = trim($_POST['email'] ?? '');
        $email = filter_var($email_input, FILTER_VALIDATE_EMAIL);
        $sujet = trim($_POST['sujet'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Liste des sujets autorisés
        $sujets_valides = [
            'Projet & Ouverture',
            'Adoptions',
            'Ateliers',
            'Partenariat',
            'Autre'
        ];

        // Validation backend renforcée
        if (empty($nom) || mb_strlen($nom) < 2 || mb_strlen($nom) > 50) {
            $erreur = "⚠️ Le nom doit contenir entre 2 et 50 caractères.";
        } elseif (!$email) {
            $erreur = "⚠️ Veuillez entrer une adresse e-mail valide.";
        } elseif (empty($sujet) || !in_array($sujet, $sujets_valides, true)) {
            $erreur = "⚠️ Veuillez sélectionner un sujet valide dans la liste.";
        } elseif (empty($message) || mb_strlen($message) < 10 || mb_strlen($message) > 1000) {
            $erreur = "⚠️ Votre message doit contenir entre 10 et 1000 caractères.";
        } else {
            // Traitement (Envoi de mail, sauvegarde BDD...)
            $succes = "Votre message a bien été envoyé ! L'équipe vous répondra très vite entre deux ronrons. 🐾";
        }
    }
}
?>

<style>
.contact-container {
    max-width: 1000px;
    margin: 40px auto 60px auto;
    padding: 0 20px;
}

.contact-header {
    text-align: center;
    margin-bottom: 35px;
}

.contact-header h1 {
    font-family: 'Pacifico', cursive;
    color: #ff7b7b; /* Rose/Rouge corail signature */
    font-size: 2.8rem;
    margin-bottom: 10px;
    font-weight: normal;
}

.contact-header p {
    font-family: 'Montserrat', sans-serif;
    color: #2c3e50;
    font-size: 1.1rem;
}

/* Grille 2 colonnes */
.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 30px;
    align-items: start;
}

/* Carte Informations */
.info-card {
    background: #ffffff;
    border: 3px solid #82ceca; /* Turquoise du site */
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.05);
}

.info-card h2 {
    font-family: 'Pacifico', cursive;
    color: #ff7b7b;
    font-size: 1.6rem;
    margin-top: 15px;
    margin-bottom: 10px;
    font-weight: normal;
}

.info-card p {
    font-family: 'Montserrat', sans-serif;
    color: #2c3e50;
    line-height: 1.6;
    font-size: 0.95rem;
    margin-bottom: 15px;
}

.badge-encours {
    display: inline-block;
    background-color: #fce185;
    color: #5d4037;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    font-size: 0.85rem;
    padding: 6px 14px;
    border-radius: 15px;
}

.contact-details {
    list-style: none;
    padding: 0;
    margin: 15px 0 0 0;
}

.contact-details li {
    font-family: 'Montserrat', sans-serif;
    font-size: 0.95rem;
    color: #2c3e50;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Carte Formulaire */
.form-card {
    background: #ffffff;
    border: 3px solid #F7B2B7; /* Rose pastel */
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.05);
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    font-size: 0.9rem;
    color: #802C38;
    margin-bottom: 6px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 14px;
    border: 2px solid #82ceca;
    border-radius: 12px;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.95rem;
    box-sizing: border-box;
    background-color: #ffffff;
    color: #2c3e50;
    transition: all 0.2s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #ff7b7b;
    box-shadow: 0 0 8px rgba(255, 123, 123, 0.2);
}

/* Styles UX & Indications */
.field-help {
    display: block;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.8rem;
    color: #718096;
    margin-top: 5px;
}

.required-star {
    color: #E53E3E;
    font-weight: bold;
}

.field-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.char-counter {
    font-family: 'Montserrat', sans-serif;
    font-size: 0.8rem;
    color: #A0AEC0;
}

.btn-envoyer {
    width: 100%;
    background-color: #ff7b7b;
    color: #ffffff;
    border: none;
    padding: 14px;
    border-radius: 25px;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-top: 10px;
}

.btn-envoyer:hover {
    background-color: #e06666;
    transform: translateY(-2px);
}

.alert {
    padding: 14px 18px;
    border-radius: 12px;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.95rem;
    margin-bottom: 25px;
    text-align: center;
    font-weight: 600;
}
.alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

@media (max-width: 768px) {
    .contact-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="contact-container">
    <div class="contact-header">
        <h1>Une question ? Une idée ? 💌</h1>
        <p>Toute l'équipe est à votre écoute pour échanger sur le projet du Repaire.</p>
    </div>

    <?php if ($succes): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($succes, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <?php if ($erreur): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <div class="contact-grid">
        <!-- COLONNE GAUCHE -->
        <div class="info-card">
            <span class="badge-encours">🏗️ Tiers-Lieu en Préparation</span>
            
            <h2>Où nous trouver ?</h2>
            <p>Le Repaire des Moustaches est en cours de création à <strong>Toulon</strong>. Nous cherchons actuellement le cocon parfait pour accueillir nos futurs moustachus !</p>
            
            <h2>Parlons-en !</h2>
            <p>En attendant l'ouverture des portes, vous pouvez nous écrire pour :</p>
            
            <ul class="contact-details">
                <li>🐾 Des questions sur l'adoption</li>
                <li>🎨 Proposer un atelier créatif</li>
                <li>🤝 Proposer un partenariat ou de l'aide</li>
                <li>📧 <strong>contact@repaire-des-moustaches.fr</strong></li>
            </ul>
        </div>

        <!-- COLONNE DROITE -->
        <div class="form-card">
            <form action="" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars((string)$csrf_token, ENT_QUOTES, 'UTF-8'); ?>">

                <!-- NOM & PRÉNOM -->
                <div class="form-group">
                    <label for="nom">Votre nom & prénom <span class="required-star">*</span></label>
                    <input type="text" id="nom" name="nom" placeholder="Ex: Marie Dupont" 
                           minlength="2" maxlength="50" pattern="[A-Za-zÀ-ÿ\s'-]+"
                           value="<?php echo htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    <small class="field-help">2 à 50 caractères (Lettres, espaces et tirets).</small>
                </div>

                <!-- EMAIL -->
                <div class="form-group">
                    <label for="email">Votre adresse e-mail <span class="required-star">*</span></label>
                    <input type="email" id="email" name="email" placeholder="marie@exemple.fr" 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    <small class="field-help">Format attendu : nom@domaine.com</small>
                </div>

                <!-- SUJET -->
                <div class="form-group">
                    <label for="sujet">Sujet de votre message <span class="required-star">*</span></label>
                    <select id="sujet" name="sujet" required>
                        <option value="" disabled <?php echo empty($_POST['sujet']) ? 'selected' : ''; ?>>Choisissez une option...</option>
                        <option value="Projet & Ouverture" <?php echo (($_POST['sujet'] ?? '') === 'Projet & Ouverture') ? 'selected' : ''; ?>>🚀 Question sur le projet / L'ouverture</option>
                        <option value="Adoptions" <?php echo (($_POST['sujet'] ?? '') === 'Adoptions') ? 'selected' : ''; ?>>🐱 Question sur les chats & adoptions</option>
                        <option value="Ateliers" <?php echo (($_POST['sujet'] ?? '') === 'Ateliers') ? 'selected' : ''; ?>>🎨 Proposer un atelier</option>
                        <option value="Partenariat" <?php echo (($_POST['sujet'] ?? '') === 'Partenariat') ? 'selected' : ''; ?>>🤝 Partenariat / Presse</option>
                        <option value="Autre" <?php echo (($_POST['sujet'] ?? '') === 'Autre') ? 'selected' : ''; ?>>🐾 Autre demande</option>
                    </select>
                    <small class="field-help">Sélectionnez le sujet principal de votre prise de contact.</small>
                </div>

                <!-- MESSAGE -->
                <div class="form-group">
                    <label for="message">Votre message <span class="required-star">*</span></label>
                    <textarea id="message" name="message" rows="4" minlength="10" maxlength="1000"
                              placeholder="Écrivez-nous votre petit mot ici..." required><?php echo htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                    <div class="field-info-row">
                        <small class="field-help">Entre 10 et 1000 caractères.</small>
                        <small id="char-count" class="char-counter">0 / 1000</small>
                    </div>
                </div>

                <button type="submit" class="btn-envoyer">Envoyer le message 🐾</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const messageInput = document.getElementById('message');
    const charCounter = document.getElementById('char-count');

    if (messageInput && charCounter) {
        const updateCounter = () => {
            const length = messageInput.value.length;
            charCounter.textContent = `${length} / 1000`;
            
            if (length < 10 && length > 0) {
                charCounter.style.color = '#E53E3E';
            } else {
                charCounter.style.color = '#A0AEC0';
            }
        };

        messageInput.addEventListener('input', updateCounter);
        updateCounter();
    }
});
</script>

<?php 
if (file_exists(__DIR__ . '/../includes/footer.php')) {
    require_once __DIR__ . '/../includes/footer.php';
}
?>