<?php
declare(strict_types=1);

$sitePrefix = ''; 
include_once '../includes/header.php'; 
?>

<main class="douceurs-page">
    <section class="page-section douceurs">
        
        <!-- En-tête de la page -->
        <div class="douceurs-header">
            <span class="badge-menu">🧁 La Carte Gourmande</span>
            <h1 class="page-title">Nos Douceurs Rétro</h1>
            <p class="sous-titre">Chaque bouchée raconte une histoire, chaque douceur soutient nos chats ! 🐾</p>
            <div class="intro-box">
                <p>
                    Nos pâtisseries maison sont le cœur gourmand du Repaire ! Préparées avec amour et des ingrédients locaux, ces créations sucrées sont bien plus que de simples gourmandises : ce sont de vrais actes de solidarité. En savourant un gâteau, vous contribuez directement aux soins de nos moustachus.
                </p>
            </div>
        </div>

        <!-- Grille des Pâtisseries -->
        <div class="grille-douceurs">
            
            <!-- Carte 1 : Cupcakes -->
            <article class="douceur-card">
                <div class="image-wrapper">
                    <span class="card-tag tag-rose">⭐ Coup de Cœur</span>
                    <picture>
                        <source srcset="images/cupcake.webp" type="image/webp">
                        <img src="images/cupcake.jpg" alt="Cupcakes rétro du Repaire" loading="lazy">
                    </picture>
                </div>
                <div class="douceur-info">
                    <div class="card-header-flex">
                        <h3>Les Cupcakes Rétro</h3>
                        <span class="price-badge">4,50 €</span>
                    </div>
                    <p>Glaçage pastel couleur bonbon, paillettes comestibles et saveurs gourmandes inspirées des diners fifties. Une véritable petite fête sucrée !</p>
                </div>
            </article>

            <!-- Carte 2 : Brownies -->
            <article class="douceur-card">
                <div class="image-wrapper">
                    <span class="card-tag tag-chocolat">🍫 Recette Secrète</span>
                    <picture>
                        <source srcset="images/Brownies.webp" type="image/webp">
                        <img src="images/Brownies.jpg" alt="Brownies maison du Repaire" loading="lazy">
                    </picture>
                </div>
                <div class="douceur-info">
                    <div class="card-header-flex">
                        <h3>Les Brownies Velours</h3>
                        <span class="price-badge">4,00 €</span>
                    </div>
                    <p>Aussi doux et irrésistibles que notre chat Velours ! Chocolat noir intense, cœur ultra fondant et pépites de noisettes torréfiées.</p>
                </div>
            </article>

            <!-- Carte 3 : Cookies -->
            <article class="douceur-card">
                <div class="image-wrapper">
                    <span class="card-tag tag-turquoise">🍪 Spécialité Moonlight</span>
                    <picture>
                        <source srcset="images/cookie.webp" type="image/webp">
                        <img src="images/cookie.jpg" alt="Cookies du Repaire" loading="lazy">
                    </picture>
                </div>
                <div class="douceur-info">
                    <div class="card-header-flex">
                        <h3>Les Cookies Moonlight</h3>
                        <span class="price-badge">3,50 €</span>
                    </div>
                    <p>Éclats de noisettes, cœur caramel fondant et une pincée de fleur de sel de Camargue. Poétiques et croquants à souhait !</p>
                </div>
            </article>

            <!-- Carte 4 : Nouveauté du mois -->
            <article class="douceur-card">
                <div class="image-wrapper">
                    <span class="card-tag tag-jaune">✨ Création du Mois</span>
                    <picture>
                        <source srcset="images/nouveauté.webp" type="image/webp">
                        <img src="images/nouveauté.jpg" alt="Pâtisseries variées du mois" loading="lazy">
                    </picture>
                </div>
                <div class="douceur-info">
                    <div class="card-header-flex">
                        <h3>La Surprise du Pâtissier</h3>
                        <span class="price-badge">5,00 €</span>
                    </div>
                    <p>Éclairs vanille-fraise, tartes rétro, macarons géants... Chaque mois, une nouvelle création est à découvrir et à voter sur place !</p>
                </div>
            </article>

        </div>

        <!-- Banner bas de page -->
        <div class="douceurs-footer-banner">
            <p>🥛 <strong>Une envie de boisson ?</strong> Retrouvez nos Milkshakes vintage, Cafés viennois et Thés parfumés directement sur place !</p>
        </div>

    </section>
</main>

<style>
    /* Global Page Structure */
    .douceurs-page {
        padding: 40px 20px 60px 20px;
        max-width: 1100px;
        margin: 0 auto;
    }

    .douceurs-header {
        text-align: center;
        margin-bottom: 45px;
    }

    .badge-menu {
        background-color: #ffeaa7;
        color: #d63031;
        font-weight: bold;
        font-size: 0.85rem;
        padding: 6px 16px;
        border-radius: 20px;
        display: inline-block;
        margin-bottom: 12px;
        border: 1px solid #fdcb6e;
    }

    .page-title {
        color: #ff7b7b;
        font-size: 2.4rem;
        margin: 0 0 10px 0;
    }

    .sous-titre {
        color: #2c3e50;
        font-size: 1.15rem;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .intro-box {
        background-color: #ffffff;
        border: 2px solid #82ceca;
        border-radius: 16px;
        padding: 20px 25px;
        max-width: 750px;
        margin: 0 auto;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .intro-box p {
        margin: 0;
        color: #555;
        line-height: 1.65;
        font-size: 0.98rem;
    }

    /* Grille Responsive (2 colonnes sur écran moyen/grand) */
    .grille-douceurs {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    /* Carte Pâtisserie */
    .douceur-card {
        background-color: #ffffff;
        border: 2px solid #82ceca;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.04);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .douceur-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 25px rgba(130, 206, 202, 0.2);
    }

    /* Image Wrapper & Badges */
    .image-wrapper {
        position: relative;
        width: 100%;
        height: 220px;
        overflow: hidden;
        background-color: #fdfaf7;
    }

    .image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .douceur-card:hover .image-wrapper img {
        transform: scale(1.06);
    }

    .card-tag {
        position: absolute;
        top: 12px;
        left: 12px;
        font-size: 0.78rem;
        font-weight: bold;
        padding: 5px 12px;
        border-radius: 20px;
        z-index: 2;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .tag-rose { background-color: #ff7b7b; color: #fff; }
    .tag-chocolat { background-color: #6c5ce7; color: #fff; }
    .tag-turquoise { background-color: #00cec9; color: #fff; }
    .tag-jaune { background-color: #fdcb6e; color: #2d3436; }

    /* Content & Details */
    .douceur-info {
        padding: 22px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .card-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .douceur-info h3 {
        margin: 0;
        color: #2c3e50;
        font-size: 1.25rem;
    }

    .price-badge {
        background-color: #fff0f3;
        color: #ff7b7b;
        font-weight: bold;
        font-size: 0.95rem;
        padding: 4px 10px;
        border-radius: 12px;
        border: 1px solid #ff7b7b;
    }

    .douceur-info p {
        margin: 0;
        color: #666;
        font-size: 0.93rem;
        line-height: 1.55;
    }

    /* Footer Banner */
    .douceurs-footer-banner {
        background-color: #ffffff;
        border: 2px dashed #ff7b7b;
        border-radius: 16px;
        padding: 18px 25px;
        text-align: center;
        color: #2c3e50;
        font-size: 1rem;
    }

    .douceurs-footer-banner strong {
        color: #ff7b7b;
    }
</style>

<?php include_once '../includes/footer.php'; ?>