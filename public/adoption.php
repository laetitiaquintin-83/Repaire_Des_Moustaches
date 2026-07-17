<?php
declare(strict_types=1);

$sitePrefix = ''; 
session_start();

// On inclut le header du dossier public/includes (ou includes s'il est au même niveau)
include_once __DIR__ . '/../includes/header.php'; 
?>

<section class="concept-container">
    <h1 class="page-title neon-effect" style="text-align: center; margin-top: 30px;">🍔🐾 Comment adopter nos moustachus ?</h1>

    <div class="concept-card" style="background-color: #fffaf0; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin: 30px auto; max-width: 800px; border: 2px solid #f9d3d3;">
        <h2 style="color: var(--gris-fonce, #2c3e50);">Un Tiers-Lieu engagé pour les chats sans abri</h2>
        <p><strong>Le Repaire des Moustaches</strong> n'est pas un refuge de passage classique. Nous fonctionnons comme une <strong>famille d'accueil géante</strong> ! Tous les chats en liberté dans notre salon de thé viennent de nos associations et refuges partenaires.</p>
        <p>Nous les hébergeons, les choyons et leur permettons de s'habituer à  la présence humaine pour faciliter leur future adoption.</p>
        
        <hr style="border: 0; border-top: 1px solid #f9d3d3; margin: 25px 0;">

        <h3 style="color: var(--gris-fonce, #2c3e50);">Le parcours de l'adoptant en 3 étapes :</h3>
        
        <div class="step-list" style="margin-top: 25px; display: flex; flex-direction: column; gap: 20px;">
            <div class="step-item" style="display: flex; gap: 15px; align-items: flex-start;">
                <div class="step-number" style="background-color: var(--rose-corail, #f7a8b8); color: white; font-weight: bold; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">1</div>
                <div class="step-content">
                    <h4 style="margin: 0 0 5px 0;">Venez faire connaissance</h4>
                    <p style="margin: 0;">Plutà´t que de choisir un animal derrière des grilles, venez passer un moment avec lui autour d'un café. Réservez l'un de nos ateliers pour voir si le courant passe naturellement entre vous !</p>
                </div>
            </div>

            <div class="step-item" style="display: flex; gap: 15px; align-items: flex-start;">
                <div class="step-number" style="background-color: var(--rose-corail, #f7a8b8); color: white; font-weight: bold; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">2</div>
                <div class="step-content">
                    <h4 style="margin: 0 0 5px 0;">Déclarez votre coup de cœur</h4>
                    <p style="margin: 0;">Si vous tombez sous le charme de l'un de nos pensionnaires, parlez-en à  notre équipe sur place. Nous vous transmettrons le dossier complet du chat et vous mettrons en relation avec le refuge partenaire qui s'occupe de lui.</p>
                </div>
            </div>

            <div class="step-item" style="display: flex; gap: 15px; align-items: flex-start;">
                <div class="step-number" style="background-color: var(--rose-corail, #f7a8b8); color: white; font-weight: bold; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">3</div>
                <div class="step-content">
                    <h4 style="margin: 0 0 5px 0;">Adoption responsable en refuge</h4>
                    <p style="margin: 0;">Les formalités administratives et de suivi de l'adoption sont directement gérées par le refuge d'origine (SPA ou association locale partenaire). Ce sont eux qui valident le dossier final pour s'assurer du bien-être de l'animal.</p>
                </div>
            </div>
        </div>

        <div class="btn-group-action" style="display: flex; gap: 15px; justify-content: center; margin-top: 30px; flex-wrap: wrap;">
            <a href="ateliers.php" class="bouton-chat" style="background-color: var(--rose-corail) !important; color: white !important; text-decoration: none; padding: 12px 25px; border-radius: 50px; font-weight: bold;">🍔“… Réserver un Atelier</a>
            <a href="belles-histoires.php" class="bouton-chat secondaire" style="text-decoration: none; padding: 12px 25px; border-radius: 50px; font-weight: bold;">🍔“– Lire leurs belles histoires</a>
        </div>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="index.php" class="btn-return" style="color: var(--gris-fonce); text-decoration: none; font-weight: bold;">← Retour à  l'accueil</a>
    </div>
</section>

<?php 
include_once __DIR__ . '/../includes/footer.php'; 
?>
