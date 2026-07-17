<?php
declare(strict_types=1);

// Force l'encodage UTF-8 au niveau du serveur pour éliminer les caractères bizarres
header('Content-Type: text/html; charset=utf-8');

$sitePrefix = '';
include_once '../includes/header.php'; 
?>
<main>
    <section class="hero">
        <div class="hero-texte">
            <h1 class="neon-effect">Le Repaire des Moustaches</h1>
            <p>Bienvenue dans un univers où les ronrons des chats accompagnent le meilleur des années 50. Un havre de douceur où solidarité, créativité et tendresse fusionnent autour d'une bonne table.</p>
            <a href="concept.php" class="bouton-principal neon-effect button-style">Vivre l'expérience</a>
        </div>

        <div class="hero-image">
            <picture>
                <source srcset="images/diner-jukebox.webp" type="image/webp">
                <source srcset="images/diner-jukebox.svg" type="image/svg+xml">
                <img src="images/diner-jukebox.png" alt="Intérieur du Repaire des Moustaches avec jukebox et arbres à chat" width="500" height="350">
            </picture>
        </div>
    </section>

    <section class="concept" id="concept">
        <h2>Entrer dans le Repaire</h2>
        <div class="grille-concept">
            <article class="concept-item">
                <div class="icon">
                    <picture>
                        <source srcset="images/concept.webp" type="image/webp">
                        <source srcset="images/concept.svg" type="image/svg+xml">
                        <img src="images/concept.png" alt="Icône concept" width="80" height="80">
                    </picture>
                </div>
                <h3><a href="concept.php">Le Concept</a></h3>
                <p>Une version simple du lieu et de ses règles.</p>
            </article>

            <article class="concept-item">
                <div class="icon">
                    <picture>
                        <source srcset="images/equipage.webp" type="image/webp">
                        <source srcset="images/equipage.svg" type="image/svg+xml">
                        <img src="images/equipage.png" alt="Icône équipage" width="80" height="80">
                    </picture>
                </div>
                <h3><a href="equipage.php">L'équipage</a></h3>
                <p>Velours, Biscuit, Moonlight : trois histoires de ronrons et de tendresse qui attendent leur happy end.</p>
            </article>

            <article class="concept-item">
                <div class="icon">
                    <picture>
                        <source srcset="images/pelote.webp" type="image/webp">
                        <source srcset="images/pelote.svg" type="image/svg+xml">
                        <img src="images/pelote.png" alt="Icône ateliers" width="80" height="80">
                    </picture>
                </div>
                <h3><a href="ateliers.php">Les Ateliers</a></h3>
                <p>Créer, apprendre, transmettre. Des moments solidaires à prix libre, au cœur du Repaire.</p>
            </article>

            <article class="concept-item">
                <div class="icon">
                    <picture>
                        <source srcset="images/repaire.webp" type="image/webp">
                        <source srcset="images/repaire.svg" type="image/svg+xml">
                        <img src="images/repaire.png" alt="Icône repaire" width="80" height="80">
                    </picture>
                </div>
                <h3><a href="repaire.php">Le repaire</a></h3>
                <p>Voir l'ambiance et les espaces du lieu.</p>
            </article>

            <article class="concept-item">
                <div class="icon">🍔</div>
                <h3><a href="douceurs.php">Nos douceurs</a></h3>
                <p>Les visuels gourmands du dîner.</p>
            </article>
        </div>
    </section>
</main>
<?php include_once '../includes/footer.php'; ?>