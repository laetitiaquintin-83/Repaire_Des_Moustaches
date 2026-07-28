<?php
declare(strict_types=1);

// Force l'encodage UTF-8 au niveau du serveur pour supprimer les caractères bizarres
header('Content-Type: text/html; charset=utf-8');

$sitePrefix = ''; 
include_once '../includes/header.php'; 
?>

<main>
    <section class="page-section concept">
        <h1 class="page-title">Le Concept</h1>
        <p class="sous-titre">Un dîner rétro, des ateliers solidaires et des chats à rencontrer dans un lieu libre et vivant.</p>
        <div class="grille-concept">
            <article class="concept-item">
                <div class="icon">
                    <picture>
                        <source srcset="images/concept.webp" type="image/webp">
                        <source srcset="images/concept.svg" type="image/svg+xml">
                        <img src="images/concept.png" alt="Icône Dîner" width="80" height="80" loading="lazy">
                    </picture>
                </div>
                <h3>Dîner &amp; Goodies</h3>
                <p>L'entrée du Repaire est totalement libre ! Installer notre modèle économique sur la restauration et la boutique est un vrai choix. En craquant pour nos plats maison ou nos goodies, vous faites bien plus que vous faire plaisir : vous financez directement le lieu et l'entretien de nos petits protégés.</p>
            </article>
            <article class="concept-item">
                <div class="icon">
                    <picture>
                        <source srcset="images/pelote.webp" type="image/webp">
                        <source srcset="images/pelote.svg" type="image/svg+xml">
                        <img src="images/pelote.png" alt="Icône Ateliers" width="80" height="80" loading="lazy">
                    </picture>
                </div>
                <h3>Ateliers Solidaires</h3>
                <p>Envie d'apprendre ou de transmettre votre passion ? Notre espace atelier est fait pour ça. Pour y accéder, en tant que participant ou animateur, nous demandons juste une adhésion annuelle à l'association. La participation aux ateliers se fait ensuite au chapeau, donc à prix libre.</p>
            </article>
            <article class="concept-item">
                <div class="icon">
                    <picture>
                        <source srcset="images/foudre.webp" type="image/webp">
                        <source srcset="images/foudre.svg" type="image/svg+xml">
                        <img src="images/foudre.png" alt="Icône Coup de Foudre" width="80" height="80" loading="lazy">
                    </picture>
                </div>
                <h3>Le Coup de Foudre</h3>
                <p>Le Repaire est le lieu des belles rencontres. Nos moustachus vivent ici en liberté en attendant leur famille pour la vie. Si le coup de cœur opère entre deux ronrons, nous vous mettons en lien direct avec notre refuge partenaire, qui se chargera de l'adoption officielle avec tout le sérieux nécessaire.</p>
            </article>
        </div>
    </section>
</main>

<style>
    /* Marges et conteneur de la section */
    .page-section.concept {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    /* Grille à 3 colonnes */
    .grille-concept {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
        margin-top: 35px;
    }

    /* Carte Individuelle avec PADDING interne généreux */
    .concept-item {
        background-color: #fdfbf7;
        border: 2px solid #7bcbc4;
        border-radius: 16px;
        padding: 30px 25px; /* Marge interne : 30px haut/bas, 25px gauche/droite */
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .concept-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }

    /* Icône */
    .concept-item .icon {
        margin-bottom: 20px;
    }

    .concept-item .icon img {
        object-fit: contain;
    }

    /* Titres des cartes */
    .concept-item h3 {
        color: #e87a7a;
        font-size: 1.35rem;
        margin-top: 0;
        margin-bottom: 15px;
    }

    /* Paragraphes dans les cartes */
    .concept-item p {
        color: #4a4a4a;
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 0;
    }

    /* Responsive mobile/tablette */
    @media (max-width: 900px) {
        .grille-concept {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php include_once '../includes/footer.php'; ?>