<?php
$sitePrefix = '';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_description = "Conditions Générales d'Utilisation (CGU) du site Le Repaire des Moustaches à Toulon.";

if (file_exists(__DIR__ . '/includes/header.php')) {
    require_once __DIR__ . '/includes/header.php';
} else {
    require_once __DIR__ . '/../includes/header.php';
}
?>

<style>
.legal-container {
    max-width: 900px;
    margin: 40px auto 60px auto;
    padding: 0 20px;
}

.legal-header {
    text-align: center;
    margin-bottom: 40px;
}

.legal-header h1 {
    font-family: 'Pacifico', cursive;
    color: #FF7B7B;
    font-size: 2.6rem;
    font-weight: normal;
    margin-bottom: 10px;
}

.legal-card {
    background: #ffffff;
    border: 3px solid #82CECA;
    border-radius: 24px;
    padding: 35px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.04);
    font-family: 'Montserrat', sans-serif;
    color: #2c3e50;
    line-height: 1.7;
}

.legal-card h2 {
    font-family: 'Pacifico', cursive;
    color: #802C38;
    font-size: 1.4rem;
    font-weight: normal;
    margin-top: 25px;
    margin-bottom: 12px;
    border-bottom: 2px dashed #FCE185;
    padding-bottom: 5px;
}

.legal-card h2:first-child {
    margin-top: 0;
}

.legal-card p, .legal-card ul {
    font-size: 0.95rem;
    margin-bottom: 15px;
}

.legal-card ul {
    padding-left: 20px;
}
</style>

<div class="legal-container">
    <div class="legal-header">
        <h1>Conditions Générales d'Utilisation 📜</h1>
        <p>Les règles du jeu pour naviguer en toute sérénité sur le site du Repaire.</p>
    </div>

    <div class="legal-card">
        <h2>1. Objet</h2>
        <p>Les présentes CGU ont pour objet d'encadrer l'accès et l'utilisation du site <strong>Le Repaire des Moustaches</strong>. Tout accès ou utilisation du site implique l'acceptation sans réserve des présentes conditions.</p>

        <h2>2. Accès au site</h2>
        <p>Le site est accessible gratuitement en tout lieu à tout utilisateur ayant un accès à Internet. L'éditeur met en œuvre tous les moyens raisonnables pour assurer un accès de qualité, mais n'est tenu à aucune obligation d'y parvenir.</p>

        <h2>3. Propriété intellectuelle</h2>
        <p>Les marques, logos, visuels, textes et illustrations présents sur le site sont la propriété exclusive du <strong>Repaire des Moustaches</strong>. Toute reproduction, copie ou publication sans autorisation préalable écrite est strictement interdite.</p>

        <h2>4. Responsabilités</h2>
        <p>Les sources des informations diffusées sur le site sont réputées fiables. Toutefois, le site se réserve la faculté d'une non-garantie de la fiabilité des sources. L'utilisateur assume seul l'entière responsabilité de l'utilisation des informations et contenus du présent site.</p>

        <h2>5. Liens hypertextes</h2>
        <p>Le site peut contenir des liens vers des sites tiers (ex: réseaux sociaux, réservation Canva, etc.). Le Repaire des Moustaches n'exerce aucun contrôle sur ces sites et décline toute responsabilité quant à leur contenu.</p>

        <h2>6. Droit applicable</h2>
        <p>Les présentes CGU sont soumises au droit français. En cas de litige, et à défaut d'accord amiable, les tribunaux français seront seuls compétents.</p>
    </div>
</div>

<?php 
if (file_exists(__DIR__ . '/includes/footer.php')) {
    require_once __DIR__ . '/includes/footer.php';
} else {
    require_once __DIR__ . '/../includes/footer.php';
}
?>