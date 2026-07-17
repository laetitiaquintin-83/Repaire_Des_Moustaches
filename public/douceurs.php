<?php
declare(strict_types=1);

$sitePrefix = ''; include_once '../includes/header.php'; ?>

    <main>
        <section class="page-section douceurs">
            <h1 class="page-title">Nos Douceurs</h1>
            <p class="sous-titre">Chaque bouchée raconte une histoire. Chaque achat soutient nos chats.</p>
            <p style="text-align: center; margin-bottom: 50px; line-height: 1.7; max-width: 700px; margin-left: auto; margin-right: auto;">Notre pà¢tisserie maison est le moteur économique du Repaire. Fabriquées sur place avec passion, ces créations sucrées sont bien plus que des gourmandises : ce sont des actes de solidarité. En savourant une pà¢tisserie du Repaire, vous nourrissez aussi nos moustachus.</p>
            <div class="grille-douceurs">
                <article class="douceur-card">
                    <picture>
                        <source srcset="images/cupcake.webp" type="image/webp">
                        <img src="images/cupcake.jpg" alt="Cupcakes rétro du Repaire" width="300" height="200" loading="lazy">
                    </picture>
                    <div class="douceur-info">
                        <h3>Les Cupcakes Rétro</h3>
                        <p>Glaçage couleur bonbon, paillettes comestibles, saveurs inspirées des années 50. Chaque cupcake est une petite fête dans la bouche, une célébration sucrée de l'amour des chats.</p>
                    </div>
                </article>
                <article class="douceur-card">
                    <picture>
                        <source srcset="images/Brownies.webp" type="image/webp">
                        <img src="images/Brownies.jpg" alt="Brownies maison du Repaire" width="300" height="200" loading="lazy">
                    </picture>
                    <div class="douceur-info">
                        <h3>Les Brownies Velours</h3>
                        <p>Nommés ainsi car ils sont aussi doux que Velours lui-même. Chocolat noir intense, cœur fondant, recette gardée secrète par notre pà¢tissière adorée.</p>
                    </div>
                </article>
                <article class="douceur-card">
                    <picture>
                        <source srcset="images/cookie.webp" type="image/webp">
                        <img src="images/cookie.jpg" alt="Cookies du Repaire" width="300" height="200" loading="lazy">
                    </picture>
                    <div class="douceur-info">
                        <h3>Les Cookies Moonlight</h3>
                        <p>Noisette, caramel et une touche de sel. Poétiques et mystérieuses comme leur homonyme, ces cookies sont la madeleine rétro du Repaire.</p>
                    </div>
                </article>
                <article class="douceur-card">
                    <picture>
                        <source srcset="images/nouveauté.webp" type="image/webp">
                        <img src="images/nouveauté.jpg" alt="Pà¢tisseries variées" width="300" height="200" loading="lazy">
                    </picture>
                    <div class="douceur-info">
                        <h3>Les Spécialités du Mois</h3>
                        <p>Éclairs, tartes, macarons... Chaque mois, notre équipe crée une nouvelle gourmandise. À vous de découvrir et de voter pour votre préférée !</p>
                    </div>
                </article>
            </div>
        </section>
    </main>

<?php include_once '../includes/footer.php'; ?>
