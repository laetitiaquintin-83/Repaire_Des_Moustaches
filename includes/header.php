<?php
// On force le serveur à envoyer la page en UTF-8
header('Content-Type: text/html; charset=utf-8');

// Détection automatique du préfixe si non défini
if (!isset($sitePrefix)) {
    $scriptPath = $_SERVER['SCRIPT_NAME'] ?? '';
    $sitePrefix = (strpos($scriptPath, '/public/') !== false) ? '../' : '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title ?? 'Le Repaire des Moustaches', ENT_QUOTES, 'UTF-8'); ?></title>
    
    <meta name="description" content="<?php echo htmlspecialchars($page_description ?? 'Le Repaire des Moustaches - Tiers-lieu solidaire et boutique en ligne. Adoptez, partagez des histoires et soutenez la cause animale à Toulon.', ENT_QUOTES, 'UTF-8'); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Pacifico&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?php echo $sitePrefix; ?>css/style.css">

    <!-- Styles CSS du Header & Effets de survol uniformes -->
    <style>
        /* Alignment global de l'en-tête pour éviter tout débordement */
        header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            flex-wrap: nowrap !important;
            padding: 10px 20px;
            box-sizing: border-box;
            width: 100%;
        }

        /* Agrandissement et mise en valeur du logo */
        header .logo img {
            width: auto;
            height: 70px;
            transition: transform 0.2s ease;
            display: block;
        }

        header .logo:hover img {
            transform: scale(1.05);
        }

        /* Navigation resserrée */
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 10px; /* Réduit pour éviter de pousser le bouton hors de l'écran */
        }

        /* Styles de base pour TOUS les liens du menu principal */
        nav ul > li > a {
            transition: color 0.2s ease, transform 0.2s ease, text-shadow 0.2s ease !important;
            display: inline-block;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        /* Effet au survol UNIFORME pour tous les onglets principaux */
        nav ul > li > a:hover {
            color: #FFFFFF !important;
            text-shadow: 0 2px 6px rgba(255, 123, 123, 0.3);
            transform: scale(1.04);
        }

        /* Ancrage du sous-menu à son parent exact */
        nav ul li.has-dropdown {
            position: relative !important;
        }

        /* Indicateur flèche discrète */
        .has-dropdown > a::after {
            content: " ▾";
            font-size: 0.75rem;
            display: inline-block;
            transition: transform 0.2s ease;
            opacity: 0.8;
        }

        .has-dropdown:hover > a::after {
            transform: rotate(180deg);
        }

        /* Conteneur du sous-menu */
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #ffffff;
            border: 1px solid rgba(130, 206, 202, 0.4);
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            min-width: 210px;
            padding: 8px 0;
            z-index: 1000;
        }

        /* Affichage au survol */
        .has-dropdown:hover .dropdown-menu {
            display: block;
            animation: fadeIn 0.15s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translate(-50%, -6px); }
            to { opacity: 1; transform: translate(-50%, 0); }
        }

        /* Liens dans le sous-menu */
        .dropdown-menu li {
            width: 100%;
            display: block;
            position: static !important;
        }

        .dropdown-menu a {
            display: block;
            padding: 9px 16px;
            color: #4a5568;
            font-family: 'Montserrat', sans-serif !important;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.15s ease;
            text-align: left;
        }

        .dropdown-menu a:hover {
            background-color: #fff0f3;
            color: #ff7b7b !important;
            padding-left: 20px;
            transform: none !important;
        }

        /* Typographie Pacifico pour La Patte Suspendue */
        .nav-patte-suspendue {
            font-family: 'Pacifico', cursive !important;
            font-size: 0.95rem; /* Ajusté légèrement */
            font-weight: 400 !important;
        }

        /* Zone d'action (Bouton Réserver + Admin) */
        .action {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0; /* Empêche le bloc bouton de se réduire ou de sortir */
        }

        .bouton-reserver {
            padding: 8px 16px;
            white-space: nowrap;
        }

        /* STYLES DU MACARON FLOTTANT */
        .macaron-sticker {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 9999;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #F7B2B7;
            border: 4px solid #FFFFFF;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            text-align: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .macaron-sticker:hover {
            transform: scale(1.08);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .macaron-sticker .pattes { font-size: 10px; }
        .macaron-sticker .titre-club {
            font-size: 11px;
            font-weight: 800;
            font-style: italic;
            color: #802C38;
            line-height: 1.1;
            margin: 3px 0;
            font-family: serif;
        }
        .macaron-sticker .badge-prix {
            background-color: #75B898;
            color: #FFFFFF;
            font-size: 9px;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 10px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <header>
        <a href="<?php echo $sitePrefix; ?>index.php" class="logo" title="Retour à l'accueil">
            <picture>
                <source srcset="<?php echo $sitePrefix; ?>images/logo.webp" type="image/webp">
                <source srcset="<?php echo $sitePrefix; ?>images/logo.svg" type="image/svg+xml">
                <img src="<?php echo $sitePrefix; ?>images/logo.png" alt="Logo du Repaire des Moustaches" width="130" height="75">
            </picture>
        </a>

        <nav>
            <ul>
                <!-- Menu 0 : Accueil -->
                <li>
                    <a href="<?php echo $sitePrefix; ?>index.php">Accueil</a>
                </li>

                <!-- Menu 1 : Le Concept & Projet -->
                <li class="has-dropdown">
                    <a href="<?php echo $sitePrefix; ?>concept.php">Le Concept</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo $sitePrefix; ?>concept.php">💡 Notre Concept</a></li>
                        <li><a href="<?php echo $sitePrefix; ?>repaire.php">☕ Le Repaire (Le Lieu)</a></li>
                        <li><a href="<?php echo $sitePrefix; ?>projet.php">🚀 Le Projet</a></li>
                        <li><a href="<?php echo $sitePrefix; ?>faq.php">❓ FAQ & Ronrons</a></li>
                        <li><a href="<?php echo $sitePrefix; ?>contact.php">💌 Nous Contacter</a></li>
                    </ul>
                </li>

                <!-- Menu 2 : L'Équipage & Chats -->
                <li class="has-dropdown">
                    <a href="<?php echo $sitePrefix; ?>equipage.php">L'Équipage</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo $sitePrefix; ?>equipage.php">🐱 Nos Moustachus</a></li>
                        <li><a href="<?php echo $sitePrefix; ?>repos-des-moustachus.php">💤 Le Repos des Moustachus</a></li>
                        <li><a href="<?php echo $sitePrefix; ?>adoption.php">🐾 Comment Adopter ?</a></li>
                    </ul>
                </li>

                <!-- Menu 3 : La Patte Suspendue -->
                <li>
                    <a href="<?php echo $sitePrefix; ?>solidaire.php" class="nav-patte-suspendue">
                        🐾 La Patte Suspendue
                    </a>
                </li>

                <!-- Menu 4 : Les Ateliers & Événements -->
                <li class="has-dropdown">
                    <a href="<?php echo $sitePrefix; ?>formulaire.php">Ateliers </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo $sitePrefix; ?>formulaire.php">🙋‍♀️ Proposer / Participer</a></li>
                        <li><a href="<?php echo $sitePrefix; ?>escape-game.php">🕵️‍♂️ Escape Game Le Jukebox</a></li>
                    </ul>
                </li>

                <!-- Menu 5 : Belles Histoires -->
                <li><a href="<?php echo $sitePrefix; ?>belles-histoires.php">Belles Histoires</a></li>

                <!-- Menu 6 : Boutique & Gourmandises -->
                <li class="has-dropdown">
                    <a href="<?php echo $sitePrefix; ?>boutique.php">Boutique</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo $sitePrefix; ?>boutique.php">🛍️ La Boutique Solidaire</a></li>
                        <li><a href="<?php echo $sitePrefix; ?>douceurs.php">🧁 Nos Douceurs Rétro</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="action">
            <a href="<?php echo $sitePrefix; ?>formulaire.php" class="bouton-reserver">Réserver</a>
            <a href="<?php echo $sitePrefix; ?>login.php" class="btn-admin-lock" title="Accès administrateur">🔐</a>
        </div>
    </header>

    <!-- MACARON FLOTTANT "ADHÉSION" -->
    <a href="<?php echo $sitePrefix; ?>adhesion.php" class="macaron-sticker">
      <span class="pattes">🐾🐾</span>
      <span class="titre-club">CLUB DES<br>MOUSTACHES</span>
      <span class="badge-prix">ADHÉSION 5€</span>
    </a>