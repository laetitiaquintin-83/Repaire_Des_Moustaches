<?php
require_once __DIR__ . '/../includes/header.php';

$page_description = "Politique de confidentialité et protection des données personnelles du Repaire des Moustaches à Toulon.";
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
    color: #ff7b7b;
    font-size: 2.6rem;
    font-weight: normal;
    margin-bottom: 10px;
}

.legal-header p {
    font-family: 'Montserrat', sans-serif;
    color: #555555;
    font-size: 1rem;
}

.legal-card {
    background: #ffffff;
    border: 3px solid #82ceca; /* Turquoise signature */
    border-radius: 24px;
    padding: 35px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.04);
    font-family: 'Montserrat', sans-serif;
    color: #2c3e50;
    line-height: 1.7;
}

.legal-card h2 {
    font-family: 'Pacifico', cursive;
    color: #802c38; /* Bordeaux signature */
    font-size: 1.5rem;
    font-weight: normal;
    margin-top: 25px;
    margin-bottom: 12px;
    border-bottom: 2px dashed #fce185; /* Jaune pastel */
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

.legal-card li {
    margin-bottom: 8px;
}

.legal-highlight {
    background-color: #fff8e7;
    border-left: 4px solid #fce185;
    padding: 12px 18px;
    border-radius: 8px;
    margin: 20px 0;
    font-size: 0.9rem;
}
</style>

<div class="legal-container">
    <div class="legal-header">
        <h1>Politique de Confidentialité 🔒</h1>
        <p>Vos données sont aussi bien gardées que le secret des meilleurs ronrons !</p>
    </div>

    <div class="legal-card">
        <h2>1. Responsable du Traitement</h2>
        <p>Le traitement des données personnelles collectées sur le site est effectué sous la responsabilité de l'équipe du <strong>Repaire des Moustaches</strong> (Toulon, France).</p>
        <p>Pour toute question relative à vos données : <strong>contact@repaire-des-moustaches.fr</strong></p>

        <h2>2. Les Données Collectées & Pourquoi</h2>
        <p>Nous ne collectons que le strict minimum nécessaire au bon fonctionnement du site :</p>
        <ul>
            <li><strong>Formulaire de contact :</strong> Nom, prénom, adresse e-mail, sujet et message. Ces informations servent exclusivement à vous répondre.</li>
            <li><strong>Boutique & Adhésions :</strong> Nom, adresse postale, e-mail et coordonnées de paiement (traitées de manière 100 % sécurisée via nos prestataires). Elles servent à expédier vos commandes et gérer vos cartes de membre.</li>
            <li><strong>Compte utilisateur :</strong> Identifiants et préférences pour gérer votre espace.</li>
        </ul>

        <h2>3. Durée de Conservation</h2>
        <p>Vos données de contact sont conservées au maximum 3 ans après votre dernier échange avec nous. Les données relatives à vos commandes sont conservées selon les obligations légales comptables (10 ans).</p>

        <h2>4. Partage des Données</h2>
        <div class="legal-highlight">
            🐾 <strong>Promesse de moustache :</strong> Nous ne revendons et ne louons JAMAIS vos données personnelles à des tiers. Vos informations restent entre vous et nous !
        </div>
        <p>Seuls nos prestataires techniques indispensables (hébergeur web, service de paiement sécurisé, transporteurs pour les colis) y ont accès dans la stricte mesure nécessaire à leur mission.</p>

        <h2>5. Vos Droits (RGPD)</h2>
        <p>Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez des droits suivants :</p>
        <ul>
            <li>Droit d'accès et de rectification de vos données.</li>
            <li>Droit à l'effacement ("droit à l'oubli").</li>
            <li>Droit à la limitation du traitement.</li>
        </ul>
        <p>Pour exercer l'un de ces droits, envoyez-nous simplement un e-mail à <strong>contact@repaire-des-moustaches.fr</strong>. Nous traiterons votre demande sous 30 jours maximum.</p>

        <h2>6. Cookies</h2>
        <p>Notre site utilise des cookies techniques essentiels à son bon fonctionnement (maintien de votre session, panier de commande). Vous pouvez gérer ou désactiver les cookies à tout moment via les paramètres de votre navigateur.</p>
    </div>
</div>

<?php 
if (file_exists(__DIR__ . '/../includes/footer.php')) {
    require_once __DIR__ . '/../includes/footer.php';
}
?>