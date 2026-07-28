<?php
declare(strict_types=1);

// Force l'encodage UTF-8 au niveau du serveur
header('Content-Type: text/html; charset=utf-8');

// Détection automatique du préfixe de chemin
if (!isset($sitePrefix)) {
    $scriptPath = $_SERVER['SCRIPT_NAME'] ?? '';
    $sitePrefix = (strpos($scriptPath, '/public/') !== false) ? '../' : '';
}

// Variables SEO pour la page d'accueil
$page_title = "Accueil | Le Repaire des Moustaches";
$page_description = "Bienvenue au Repaire des Moustaches, le tiers-lieu vintage et solidaire dédié aux chats et aux humains à Toulon.";

require_once __DIR__ . '/../includes/header.php'; 
?>

<main>
    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-texte">
            <h1 class="neon-effect">Le Repaire des Moustaches</h1>
            <p>Bienvenue dans un univers où les ronrons des chats accompagnent le meilleur des années 50. Un havre de douceur où solidarité, créativité et tendresse fusionnent autour d'une bonne table.</p>
            <a href="<?php echo $sitePrefix; ?>concept.php" class="bouton-principal neon-effect button-style">Vivre l'expérience</a>
        </div>

        <div class="hero-image">
            <picture>
                <source srcset="<?php echo $sitePrefix; ?>images/diner-jukebox.webp" type="image/webp">
                <source srcset="<?php echo $sitePrefix; ?>images/diner-jukebox.svg" type="image/svg+xml">
                <img src="<?php echo $sitePrefix; ?>images/diner-jukebox.png" alt="Intérieur du Repaire des Moustaches avec jukebox et chats" width="500" height="350">
            </picture>
        </div>
    </section>

    <!-- BANNIÈRE FLASH : LA PATTE SUSPENDUE -->
    <section class="banniere-solidaire" style="background: #FFF8E7; border: 2px dashed #72C2BB; border-radius: 20px; padding: 25px; margin: 40px auto; max-width: 1000px; text-align: center;">
        <h2 style="font-family: 'Pacifico', cursive; color: #FF7B7B; font-weight: normal; margin-bottom: 10px; font-size: 2rem;">
            🐾 Un geste du cœur : La Patte Suspendue
        </h2>
        <p style="font-family: 'Montserrat', sans-serif; color: #5A4A42; max-width: 750px; margin: 0 auto 15px auto;">
            Inspiré du café suspendu, vous pouvez offrir un café, un goûter ou un atelier à un visiteur dans le besoin. Anonyme, solidaire et réconfortant.
        </p>
        <a href="<?php echo $sitePrefix; ?>solidaire.php" class="btn-don" style="background-color: #FF7B7B; color: #FFF; font-weight: bold; padding: 10px 20px; border-radius: 25px; text-decoration: none; display: inline-block;">
            Découvrir le projet solidaire 💛
        </a>
    </section>

    <!-- GRILLE DU CONCEPT -->
    <section class="concept" id="concept">
        <h2>Entrer dans le Repaire</h2>
        <div class="grille-concept">
            
            <article class="concept-item">
                <div class="icon">
                    <picture>
                        <source srcset="<?php echo $sitePrefix; ?>images/concept.webp" type="image/webp">
                        <img src="<?php echo $sitePrefix; ?>images/concept.png" alt="Icône concept" width="80" height="80">
                    </picture>
                </div>
                <h3><a href="<?php echo $sitePrefix; ?>concept.php">Le Concept</a></h3>
                <p>Découvrez notre tiers-lieu solidaire et les règles de bien-être pour nos moustachus.</p>
            </article>

            <article class="concept-item">
                <div class="icon">
                    <picture>
                        <source srcset="<?php echo $sitePrefix; ?>images/equipage.webp" type="image/webp">
                        <img src="<?php echo $sitePrefix; ?>images/equipage.png" alt="Icône équipage" width="80" height="80">
                    </picture>
                </div>
                <h3><a href="<?php echo $sitePrefix; ?>equipage.php">L'Équipage</a></h3>
                <p>Velours, Biscuit, Moonlight : nos résidents à quatre pattes vous attendent pour des câlins.</p>
            </article>

            <article class="concept-item">
                <div class="icon">
                    <picture>
                        <source srcset="<?php echo $sitePrefix; ?>images/pelote.webp" type="image/webp">
                        <img src="<?php echo $sitePrefix; ?>images/pelote.png" alt="Icône ateliers" width="80" height="80">
                    </picture>
                </div>
                <h3><a href="<?php echo $sitePrefix; ?>formulaire.php">Ateliers & Loisirs</a></h3>
                <p>Créer, apprendre, transmettre. Participez à des ateliers créatifs au cœur du Repaire.</p>
            </article>

            <article class="concept-item">
                <div class="icon">
                    <picture>
                        <source srcset="<?php echo $sitePrefix; ?>images/repaire.webp" type="image/webp">
                        <img src="<?php echo $sitePrefix; ?>images/repaire.png" alt="Icône repaire" width="80" height="80">
                    </picture>
                </div>
                <h3><a href="<?php echo $sitePrefix; ?>repaire.php">Le Repaire</a></h3>
                <p>Explorez les espaces rétro inspirés des célèbres diners américains des 50's.</p>
            </article>

            <article class="concept-item">
                <div class="icon">🧁</div>
                <h3><a href="<?php echo $sitePrefix; ?>douceurs.php">Nos Douceurs</a></h3>
                <p>Gourmandises artisanales, laits frappés et pâtisseries thématiques à savourer.</p>
            </article>

            <article class="concept-item">
                <div class="icon">🕵️‍♂️</div>
                <h3><a href="<?php echo $sitePrefix; ?>escape-game.php">Escape Game</a></h3>
                <p>Résolvez le mystère du Jukebox en équipe dans un défi ludique et immersif.</p>
            </article>

        </div>
    </section>
</main>

<?php 
if (file_exists(__DIR__ . '/includes/footer.php')) {
    require_once __DIR__ . '/includes/footer.php';
} else {
    require_once __DIR__ . '/../includes/footer.php';
}
?>