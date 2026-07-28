<?php
// On cible directement le bon dossier includes/
require_once __DIR__ . '/../includes/header.php';

$page_description = "Contactez l'équipe du Repaire des Moustaches à Toulon. Une question sur le projet, les adoptions ou les ateliers ? Écrivez-nous !";

$succes = false;
$erreur = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $sujet = trim($_POST['sujet'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($nom && $email && $sujet && $message) {
        $succes = "Votre message a bien été envoyé ! L'équipe vous répondra très vite entre deux ronrons. 🐾";
    } else {
        $erreur = "Veuillez remplir tous les champs correctement.";
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
}

.btn-envoyer:hover {
    background-color: #e06666;
    transform: translateY(-2px);
}

.alert {
    padding: 12px 18px;
    border-radius: 12px;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.95rem;
    margin-bottom: 20px;
    text-align: center;
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
        <div class="alert alert-success"><?php echo $succes; ?></div>
    <?php endif; ?>

    <?php if ($erreur): ?>
        <div class="alert alert-error"><?php echo $erreur; ?></div>
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
                <div class="form-group">
                    <label for="nom">Votre nom & prénom</label>
                    <input type="text" id="nom" name="nom" placeholder="Ex: Marie Dupont" required>
                </div>

                <div class="form-group">
                    <label for="email">Votre adresse e-mail</label>
                    <input type="email" id="email" name="email" placeholder="marie@exemple.fr" required>
                </div>

                <div class="form-group">
                    <label for="sujet">Sujet de votre message</label>
                    <select id="sujet" name="sujet" required>
                        <option value="" disabled selected>Choisissez une option...</option>
                        <option value="Projet & Ouverture">🚀 Question sur le projet / L'ouverture</option>
                        <option value="Adoptions">🐱 Question sur les chats & adoptions</option>
                        <option value="Ateliers">🎨 Proposer un atelier</option>
                        <option value="Partenariat">🤝 Partenariat / Presse</option>
                        <option value="Autre">🐾 Autre demande</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">Votre message</label>
                    <textarea id="message" name="message" rows="4" placeholder="Écrivez-nous votre petit mot ici..." required></textarea>
                </div>

                <button type="submit" class="btn-envoyer">Envoyer le message 🐾</button>
            </form>
        </div>
    </div>
</div>

<?php 
if (file_exists(__DIR__ . '/../includes/footer.php')) {
    require_once __DIR__ . '/../includes/footer.php';
}
?>