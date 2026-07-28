<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$sitePrefix = ''; 

// Inclusion du header
include_once __DIR__ . '/../includes/header.php'; 
?>

<main class="adoption-page">
    <section class="adoption-container">
        <h1 class="page-title">
            <span class="paws-icon">🐾</span> Comment adopter nos moustachus ?
        </h1>

        <!-- Bloc Intro Tiers-Lieu -->
        <div class="adoption-intro-card">
            <h2>Un Tiers-Lieu engagé pour les chats sans abri</h2>
            <p><strong>Le Repaire des Moustaches</strong> n'est pas un refuge de passage classique. Nous fonctionnons comme une <strong>famille d'accueil géante</strong> ! Tous les chats en liberté dans notre salon de thé viennent de nos associations et refuges partenaires.</p>
            <p>Nous les hébergeons, les choyons et leur permettons de s'habituer à la présence humaine pour faciliter leur future adoption.</p>
        </div>

        <!-- Titre de transition -->
        <h3 class="steps-title">Le parcours de l'adoptant en 3 étapes :</h3>

        <!-- Grille des 3 étapes -->
        <div class="steps-container">
            <!-- Étape 1 -->
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h4>Venez faire connaissance</h4>
                    <p>Plutôt que de choisir un animal derrière des grilles, venez passer un moment avec lui autour d'un café. Réservez l'un de nos ateliers pour voir si le courant passe naturellement entre vous !</p>
                </div>
            </div>

            <!-- Étape 2 -->
            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h4>Déclarez votre coup de cœur</h4>
                    <p>Si vous tombez sous le charme de l'un de nos pensionnaires, parlez-en à notre équipe sur place. Nous vous transmettrons le dossier complet du chat et vous mettrons en relation avec le refuge partenaire qui s'occupe de lui.</p>
                </div>
            </div>

            <!-- Étape 3 -->
            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h4>Adoption responsable en refuge</h4>
                    <p>Les formalités administratives et de suivi de l'adoption sont directement gérées par le refuge d'origine (SPA ou association locale partenaire). Ce sont eux qui valident le dossier final pour s'assurer du bien-être de l'animal.</p>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="action-buttons-group">
            <a href="ateliers.php" class="btn-primary">📅 Réserver un Atelier</a>
            <a href="belles-histoires.php" class="btn-secondary">📖 Lire leurs belles histoires</a>
        </div>

        <!-- Lien Retour -->
        <div class="back-link-container">
            <a href="index.php" class="btn-return">← Retour à l'accueil</a>
        </div>
    </section>
</main>

<style>
    /* Structure globale de la page */
    .adoption-page {
        padding: 40px 20px;
        max-width: 900px;
        margin: 0 auto;
    }

    .page-title {
        text-align: center;
        color: #ff7b7b;
        font-size: 2.2rem;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    /* Carte Intro Tiers-Lieu */
    .adoption-intro-card {
        background-color: #ffffff;
        border: 2px solid #82ceca;
        border-radius: 18px;
        padding: 30px 35px;
        margin-bottom: 35px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }

    .adoption-intro-card h2 {
        color: #2c3e50;
        font-size: 1.4rem;
        margin-top: 0;
        margin-bottom: 15px;
    }

    .adoption-intro-card p {
        color: #4a4a4a;
        line-height: 1.65;
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .adoption-intro-card p:last-child {
        margin-bottom: 0;
    }

    /* Titre des étapes */
    .steps-title {
        font-size: 1.3rem;
        color: #2c3e50;
        margin-bottom: 20px;
        text-align: center;
    }

    /* Conteneur des 3 étapes */
    .steps-container {
        display: flex;
        flex-direction: column;
        gap: 18px;
        margin-bottom: 40px;
    }

    /* Carte d'Étape individuelle */
    .step-card {
        background: #ffffff;
        border: 2px solid #82ceca;
        border-left: 6px solid #ff7b7b;
        border-radius: 14px;
        padding: 22px 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .step-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
    }

    /* Rond numéroté */
    .step-number {
        background-color: #ff7b7b;
        color: #ffffff;
        font-weight: bold;
        font-size: 1.25rem;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 3px 8px rgba(255, 123, 123, 0.3);
    }

    .step-content h4 {
        margin: 0 0 6px 0;
        color: #2c3e50;
        font-size: 1.15rem;
    }

    .step-content p {
        margin: 0;
        color: #555555;
        font-size: 0.95rem;
        line-height: 1.55;
    }

    /* Boutons d'action */
    .action-buttons-group {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .btn-primary {
        background-color: #ff7b7b;
        color: #ffffff !important;
        text-decoration: none;
        padding: 14px 28px;
        border-radius: 50px;
        font-weight: bold;
        transition: background-color 0.2s ease, transform 0.2s ease;
        box-shadow: 0 4px 10px rgba(255, 123, 123, 0.25);
    }

    .btn-primary:hover {
        background-color: #e06666;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: #ffffff;
        color: #2c3e50 !important;
        border: 2px solid #82ceca;
        text-decoration: none;
        padding: 12px 26px;
        border-radius: 50px;
        font-weight: bold;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .btn-secondary:hover {
        background-color: #f0fdfc;
        transform: translateY(-2px);
    }

    /* Lien retour */
    .back-link-container {
        text-align: center;
        margin-top: 20px;
    }

    .btn-return {
        color: #2c3e50;
        text-decoration: none;
        font-weight: bold;
        font-size: 0.95rem;
        transition: opacity 0.2s ease;
    }

    .btn-return:hover {
        opacity: 0.7;
    }

    @media (max-width: 600px) {
        .step-card {
            flex-direction: column;
            text-align: center;
            align-items: center;
        }
    }
</style>

<?php 
include_once __DIR__ . '/../includes/footer.php'; 
?>