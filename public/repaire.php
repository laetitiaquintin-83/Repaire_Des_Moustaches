<?php
declare(strict_types=1);

$sitePrefix = ''; 
include_once '../includes/header.php'; 
?>

<main class="repaire-page">
    <section class="le-repaire" id="le-repaire">
        <div class="repaire-header">
            
            <h2>Au cœur du futur Repaire</h2>
            <p class="sous-titre-repaire">Découvrez la vision d'un cocon rétro où la douceur de vivre rencontrera l'amour des chats à Toulon.</p>
        </div>

        <div class="repaire-container">
            
            <!-- Image mise en valeur à gauche façon photo vintage -->
            <div class="repaire-image-container">
                <div class="photo-frame">
                    <img src="images/espace2.jpg" alt="Aperçu 3D de l'intérieur rétro du Repaire des Moustaches" class="repaire-img">
                    <span class="photo-caption">✨ Projection 3D de notre futur Diner & Salon de thé</span>
                </div>
            </div>

            <!-- Textes rédigés au futur / ambition -->
            <div class="repaire-texte">
                
                <div class="bloc-card bloc-histoire">
                    <h3>Une Ambition Passionnée ☕✨</h3>
                    <p>
                        Tout a commencé par un rêve : imaginer à Toulon un lieu chaleureux et inédit, où le charme des années 50 croisera la bienveillance envers nos amis les chats. Demain, en franchissant les portes du Repaire, vous entrerez dans une bulle temporelle : banquettes douillettes, musique rétro apaisante et doux parfum de pâtisseries maison. Un futur salon de thé pensé comme une seconde maison pour se poser et partager de bons moments.
                    </p>
                </div>

                <div class="bloc-card bloc-valeurs">
                    <h3>Un Projet Engagé & Solidaire 🐾❤️</h3>
                    <p>
                        Le Repaire des Moustaches sera bien plus qu'un simple café : ce sera une famille d'accueil géante ! Nos futurs pensionnaires (comme Velours, Biscuit ou Moonlight) seront des rescapés confiés par nos refuges partenaires. Ils y vivront en liberté, entourés d'amour en attendant leur adoption définitive. Chaque réservation d'atelier et chaque gourmandise permettra de financer directement leurs soins et leur bien-être.
                    </p>
                </div>

            </div>
            
        </div>
    </section>
</main>

<style>
    .repaire-page {
        padding: 40px 20px;
        max-width: 1100px;
        margin: 0 auto;
    }

    .repaire-header {
        text-align: center;
        margin-bottom: 45px;
    }

    .badge-projet {
        background-color: #ffeaa7;
        color: #d63031;
        font-weight: bold;
        font-size: 0.85rem;
        padding: 6px 14px;
        border-radius: 20px;
        display: inline-block;
        margin-bottom: 12px;
        border: 1px solid #fdcb6e;
    }

    .repaire-header h2 {
        color: #ff7b7b;
        font-size: 2.3rem;
        margin: 0 0 10px 0;
    }

    .sous-titre-repaire {
        color: #666;
        font-size: 1.05rem;
        font-style: italic;
        max-width: 700px;
        margin: 0 auto;
    }

    /* Grille côte à côte : Image à gauche (45%), Textes à droite (55%) */
    .repaire-container {
        display: grid;
        grid-template-columns: 0.9fr 1.1fr;
        gap: 35px;
        align-items: start;
    }

    /* Cadre photo façon Polarose / Diner */
    .repaire-image-container {
        position: sticky;
        top: 20px;
    }

    .photo-frame {
        background-color: #ffffff;
        border: 2px solid #82ceca;
        border-radius: 18px;
        padding: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        text-align: center;
    }

    .repaire-img {
        width: 100%;
        height: auto;
        max-height: 420px;
        object-fit: cover;
        border-radius: 12px;
        display: block;
    }

    .photo-caption {
        display: block;
        margin-top: 10px;
        font-size: 0.85rem;
        color: #777;
        font-style: italic;
    }

    /* Bloc textes */
    .repaire-texte {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .bloc-card {
        background-color: #ffffff;
        border: 2px solid #82ceca;
        border-radius: 18px;
        padding: 28px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }

    .bloc-card h3 {
        color: #2c3e50;
        font-size: 1.3rem;
        margin-top: 0;
        margin-bottom: 12px;
    }

    .bloc-card p {
        color: #4a4a4a;
        line-height: 1.65;
        font-size: 0.98rem;
        margin: 0;
    }

    /* Adaptabilité mobile */
    @media (max-width: 850px) {
        .repaire-container {
            grid-template-columns: 1fr;
        }

        .repaire-image-container {
            position: relative;
            top: 0;
        }
    }
</style>

<?php include_once '../includes/footer.php'; ?>