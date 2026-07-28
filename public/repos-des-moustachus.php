<?php
declare(strict_types=1);

// Configuration et en-tête
$sitePrefix = ''; 
include_once '../includes/header.php'; 
?>

<main class="repos-page">
    <section class="repos-section">
        
        <!-- En-tête de la page -->
        <div class="repos-header">
            <span class="badge-cocooning">💤 Espace Sieste & Cocooning</span>
            <h1 class="page-title">Le Repos des Moustachus</h1>
            <p class="sous-titre font-vintage">Parce qu'après une dure journée de câlins et de jeux, nos héros méritent le meilleur du confort rétro !</p>
            
            <div class="intro-card">
                <p>
                    Au Repaire des Moustaches, le bien-être animal se conjugue avec le design vintage ! 
                    Découvrez nos projets d'aménagements sur-mesure : des structures de repos uniques inspirées des icônes mécaniques des années 50, 60 et 70, spécialement pensées pour la sécurité, l'escalade et les siestes XXL de nos boules de poils.
                </p>
            </div>
        </div>

        <!-- Grille des 3 installations insolites -->
        <div class="repos-grid">

            <!-- Carte 1 : Combi VW -->
            <article class="repos-card">
                <div class="repos-img-wrapper">
                    <span class="card-badge badge-orange">🚌 Icône 70's</span>
                    <img src="images/repaire3.webp" alt="Arbre à chat Combi VW vintage" loading="lazy">
                </div>
                <div class="repos-card-body">
                    <h3>Le Combi "Peace & Purr" 🚌✨</h3>
                    <p class="card-subtitle">L'aventure vintage à hauteur de moustache</p>
                    <p class="card-text">
                        Véritable griffoir géant et aire d'escalade en bois noble, ce Combi emblématique propose une plateforme panoramique sur le toit pour surveiller le salon de thé, ainsi qu'un intérieur molletonné pour les siestes à l'abri des regards.
                    </p>
                    <div class="card-tags-list">
                        <span class="mini-tag">🐾 Griffoirs intégrés</span>
                        <span class="mini-tag">🔭 Vue panoramique</span>
                    </div>
                </div>
            </article>

            <!-- Carte 2 : La Villa Rétro / Voiture Pastel -->
            <article class="repos-card">
                <div class="repos-img-wrapper">
                    <span class="card-badge badge-vert">🚗 Hôtel 5 Étoiles</span>
                    <img src="images/repaire1.webp" alt="Villa pour chats style voiture pastel" loading="lazy">
                </div>
                <div class="repos-card-body">
                    <h3>La Résidence Belle Époque 🚗🌸</h3>
                    <p class="card-subtitle">Le palace collectif aux teintes menthe et rose pastel</p>
                    <p class="card-text">
                        Conçue comme une mini-résidence multi-étages, cette structure aux rondeurs douces offre une multitude de petites niches individuelles. C'est le lieu de rendez-vous préféré des chatons pour jouer à cache-cache ou faire une sieste groupée.
                    </p>
                    <div class="card-tags-list">
                        <span class="mini-tag">🛏️ Multi-cabines</span>
                        <span class="mini-tag">🎨 Design Pastel</span>
                    </div>
                </div>
            </article>

            <!-- Carte 3 : Le Scooter Side-Car -->
            <article class="repos-card">
                <div class="repos-img-wrapper">
                    <span class="card-badge badge-rose">🛵 Cocon Douillet</span>
                    <img src="images/repaire2.webp" alt="Niche pour chat scooter vespa vintage" loading="lazy">
                </div>
                <div class="repos-card-body">
                    <h3>Le Side-Car "Dolce Vita" 🛵💖</h3>
                    <p class="card-subtitle">Le repaire le plus moelleux de la côte varoise</p>
                    <p class="card-text">
                        Un nid de douceur habillé de fausse fourrure rose poudré et d'un revêtement vert d'eau velouté. Idéal pour nos chats les plus timides ou les grands séniors qui cherchent un petit coin chaleureux, rembourré et parfaitement isolé.
                    </p>
                    <div class="card-tags-list">
                        <span class="mini-tag">☁️ Moumoute ultra-douce</span>
                        <span class="mini-tag">💤 Isolant & Calme</span>
                    </div>
                </div>
            </article>

        </div>

        <!-- Note de bas de page -->
        <div class="repos-note">
            <p>💡 <em>Projections 3D de nos futurs équipements sur-mesure. Chaque module sera fabriqué avec des matériaux éco-responsables et adaptés aux normes sanitaires et félines !</em></p>
        </div>

    </section>
</main>

<style>
    /* Styles spécifiques de la page Repos */
    .repos-page {
        padding: 40px 20px 60px 20px;
        max-width: 1150px;
        margin: 0 auto;
    }

    .repos-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .badge-cocooning {
        background-color: #ffeaa7;
        color: #d63031;
        font-weight: bold;
        font-size: 0.88rem;
        padding: 6px 16px;
        border-radius: 20px;
        display: inline-block;
        margin-bottom: 12px;
        border: 1px solid #fdcb6e;
    }

    .page-title {
        color: #ff7b7b;
        font-size: 2.5rem;
        margin: 0 0 10px 0;
        font-weight: 700;
    }

    .font-vintage {
        font-style: italic;
    }

    .sous-titre {
        color: #2c3e50;
        font-size: 1.15rem;
        max-width: 750px;
        margin: 0 auto 25px auto;
        line-height: 1.5;
    }

    .intro-card {
        background-color: #ffffff;
        border: 2px solid #82ceca;
        border-radius: 18px;
        padding: 22px 30px;
        max-width: 800px;
        margin: 0 auto;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }

    .intro-card p {
        margin: 0;
        color: #4a4a4a;
        line-height: 1.65;
        font-size: 0.98rem;
    }

    /* Grille des cartes */
    .repos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 32px;
        margin-bottom: 45px;
    }

    .repos-card {
        background-color: #ffffff;
        border: 2px solid #82ceca;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .repos-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 12px 25px rgba(130, 206, 202, 0.25);
    }

    /* Images */
    .repos-img-wrapper {
        position: relative;
        width: 100%;
        height: 280px;
        background-color: #f7f9fa;
        overflow: hidden;
    }

    .repos-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.4s ease;
    }

    .repos-card:hover .repos-img-wrapper img {
        transform: scale(1.05);
    }

    /* Badges */
    .card-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        font-size: 0.8rem;
        font-weight: bold;
        padding: 5px 14px;
        border-radius: 20px;
        z-index: 2;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .badge-orange { background-color: #e17055; color: #ffffff; }
    .badge-vert { background-color: #00b894; color: #ffffff; }
    .badge-rose { background-color: #ff7b7b; color: #ffffff; }

    /* Contenu Carte */
    .repos-card-body {
        padding: 25px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .repos-card-body h3 {
        color: #2c3e50;
        font-size: 1.35rem;
        margin: 0 0 6px 0;
    }

    .card-subtitle {
        color: #ff7b7b;
        font-weight: 600;
        font-size: 0.9rem;
        margin: 0 0 14px 0;
    }

    .card-text {
        color: #555555;
        font-size: 0.94rem;
        line-height: 1.6;
        margin: 0 0 20px 0;
        flex-grow: 1;
    }

    /* Mini-tags */
    .card-tags-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .mini-tag {
        background-color: #f0fdfc;
        color: #2c3e50;
        border: 1px solid #82ceca;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 12px;
    }

    /* Note bas de page */
    .repos-note {
        background-color: #ffffff;
        border: 2px dashed #ff7b7b;
        border-radius: 16px;
        padding: 16px 25px;
        text-align: center;
        color: #555555;
        font-size: 0.92rem;
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }

        .repos-img-wrapper {
            height: 230px;
        }
    }
</style>

<?php include_once '../includes/footer.php'; ?>