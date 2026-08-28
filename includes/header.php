<?php
// Encodage UTF-8
header('Content-Type: text/html; charset=utf-8');

// Détection du préfixe
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
    <base href="http://repaire_des_moustaches.test" />
    <title><?php echo htmlspecialchars($page_title ?? 'Le Repaire des Moustaches', ENT_QUOTES, 'UTF-8'); ?></title>
    
    <meta name="description" content="<?php echo htmlspecialchars($page_description ?? 'Le Repaire des Moustaches - Tiers-lieu solidaire et boutique en ligne. Adoptez, partagez des histoires et soutenez la cause animale à Toulon.', ENT_QUOTES, 'UTF-8'); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Pacifico&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="/css/style.css">

    <!-- Styles du header -->
    <style>
        /* En-tête */
        header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 10px 20px;
            box-sizing: border-box;
            width: 100%;
            position: relative;
        }

        /* Logo */
        header .logo img {
            width: auto;
            height: 70px;
            transition: transform 0.2s ease;
            display: block;
        }

        header .logo:hover img {
            transform: scale(1.05);
        }

        /* Menu mobile */
        .burger-btn {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 30px;
            height: 21px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            z-index: 1001;
        }

        .burger-btn span {
            width: 100%;
            height: 3px;
            background-color: #ff7b7b;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        /* Animation du menu */
        .burger-btn.active span:nth-child(1) {
            transform: translateY(9px) rotate(45deg);
        }
        .burger-btn.active span:nth-child(2) {
            opacity: 0;
        }
        .burger-btn.active span:nth-child(3) {
            transform: translateY(-9px) rotate(-45deg);
        }

        /* Navigation */
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        nav ul > li > a {
            transition: color 0.2s ease, transform 0.2s ease, text-shadow 0.2s ease !important;
            display: inline-block;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        nav ul > li > a:hover {
            color: #FFFFFF !important;
            text-shadow: 0 2px 6px rgba(255, 123, 123, 0.3);
            transform: scale(1.04);
        }

        nav ul li.has-dropdown {
            position: relative !important;
        }

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

        .has-dropdown:hover .dropdown-menu {
            display: block;
            animation: fadeIn 0.15s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translate(-50%, -6px); }
            to { opacity: 1; transform: translate(-50%, 0); }
        }

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

        .nav-patte-suspendue {
            font-family: 'Pacifico', cursive !important;
            font-size: 0.95rem;
            font-weight: 400 !important;
        }

        .action {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .bouton-reserver {
            padding: 8px 16px;
            white-space: nowrap;
        }

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

        /* Responsive mobile */
        @media screen and (max-width: 900px) {
            .burger-btn {
                display: flex; /* Affiche l'icône burger */
            }

            nav {
                display: none; /* Cache le menu par défaut */
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: #ffffff;
                box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
                padding: 20px 0;
                z-index: 999;
            }

            /* Menu ouvert */
            nav.mobile-open {
                display: block;
            }

            nav ul {
                flex-direction: column;
                gap: 15px;
            }

            nav ul li {
                width: 100%;
                text-align: center;
            }

            .dropdown-menu {
                position: static;
                transform: none;
                box-shadow: none;
                border: none;
                background-color: #fafafa;
                margin-top: 5px;
            }

            .dropdown-menu a {
                text-align: center;
            }
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

        <!-- Menu mobile -->
        <button class="burger-btn" id="burgerBtn" aria-label="Ouvrir le menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav id="mainNav">
            <ul>
                <li><a href="<?php echo $sitePrefix; ?>index.php">Accueil</a></li>
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
                <li class="has-dropdown">
                    <a href="<?php echo $sitePrefix; ?>equipage.php">L'Équipage</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo $sitePrefix; ?>equipage.php">🐱 Nos Moustachus</a></li>
                        <li><a href="<?php echo $sitePrefix; ?>repos-des-moustachus.php">💤 Le Repos des Moustachus</a></li>
                        <li><a href="<?php echo $sitePrefix; ?>adoption.php">🐾 Comment Adopter ?</a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo $sitePrefix; ?>solidaire.php" class="nav-patte-suspendue">
                        🐾 La Patte Suspendue
                    </a>
                </li>
                <li class="has-dropdown">
                    <a href="<?php echo $sitePrefix; ?>ateliers.php">Ateliers</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo $sitePrefix; ?>formulaire.php">🙋‍♀️ Proposer / Participer</a></li>
                        <li><a href="<?php echo $sitePrefix; ?>escape-game.php">🕵️‍♂️ Escape Game Le Jukebox</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo $sitePrefix; ?>belles-histoires.php">Belles Histoires</a></li>
                <li class="has-dropdown">
                    <a href="<?php echo $sitePrefix; ?>boutique.php">Boutique</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo $sitePrefix; ?>boutique.php">🛍️ La Boutique Solidaire</a></li>
                        <li><a href="<?php echo $sitePrefix; ?>douceurs.php">🧁 Nos Douceurs Rétro</a></li>
                        <li><a href="<?php echo $sitePrefix; ?>vip.php">👑 Espace VIP</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="action">
            <a href="<?php echo $sitePrefix; ?>formulaire.php" class="bouton-reserver">Réserver</a>
            <a href="<?php echo $sitePrefix; ?>login.php" class="btn-admin-lock" title="Accès administrateur">🔐</a>
        </div>
    </header>

    <!-- Script du menu mobile -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const burgerBtn = document.getElementById('burgerBtn');
            const mainNav = document.getElementById('mainNav');

            if (burgerBtn && mainNav) {
                burgerBtn.addEventListener('click', function() {
                    burgerBtn.classList.toggle('active');
                    mainNav.classList.toggle('mobile-open');
                });
            }
        });
    </script>

    <!-- Accès adhésion -->
    <a href="<?php echo $sitePrefix; ?>adhesion.php" class="macaron-sticker">
      <span class="pattes">🐾🐾</span>
      <span class="titre-club">CLUB DES<br>MOUSTACHES</span>
      <span class="badge-prix">ADHÉSION 5€</span>
    </a>