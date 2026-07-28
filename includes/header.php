<?php
// On force le serveur à envoyer la page en UTF-8 pour éradiquer les caractères bizarres
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Repaire des Moustaches</title>
    
    <meta name="description" content="<?php echo htmlspecialchars($page_description ?? 'Le Repaire des Moustaches - Tiers-lieu solidaire et boutique en ligne. Adoptez, partagez des histoires et soutenez la cause animale à Toulon.', ENT_QUOTES, 'UTF-8'); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Pacifico&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/style.css">

    <!-- Styles CSS du Header & Sous-menus Compacts & Macaron Flottant -->
    <style>
        /* --- STYLES DES SOUS-MENUS DÉROULANTS COMPACTS --- */
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        nav li {
            position: relative;
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

        /* Conteneur du sous-menu (Compact & Élégant) */
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #ffffff;
            border: 1px solid rgba(130, 206, 202, 0.4);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            min-width: 180px;
            padding: 6px 0;
            z-index: 1000;
        }

        /* Affichage au survol */
        .has-dropdown:hover .dropdown-menu {
            display: block;
            animation: fadeIn 0.15s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Liens dans le sous-menu */
        .dropdown-menu li {
            width: 100%;
            display: block;
        }

        .dropdown-menu a {
            display: block;
            padding: 7px 14px;
            color: #4a5568;
            font-family: 'Montserrat', sans-serif !important; /* Typo plus lisible et compacte */
            font-size: 0.82rem;
            font-weight: 500;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.15s ease;
        }

        .dropdown-menu a:hover {
            background-color: #fff0f3;
            color: #ff7b7b;
            padding-left: 18px; /* Petit effet de glissement au survol */
        }

        /* --- STYLES DU MACARON FLOTTANT --- */
        .macaron-sticker {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 9999;
            
            width: 120px;
            height: 120px;
            border-radius: 50%;
            
            background-color: #F7B2B7; /* Rose pastel */
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

        .macaron-sticker .pattes {
            font-size: 10px;
        }

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
            background-color: #75B898; /* Vert pastel */
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
        <a href="index.php" class="logo">
            <picture>
                <source srcset="images/logo.webp" type="image/webp">
                <source srcset="images/logo.svg" type="image/svg+xml">
                <img src="images/logo.png" alt="Logo du Repaire des Moustaches" width="100" height="55">
            </picture>
        </a>

        <nav>
            <ul>
                <!-- Menu 1 : Le Concept & Projet -->
                <li class="has-dropdown">
                    <a href="concept.php">Le Concept</a>
                    <ul class="dropdown-menu">
                        <li><a href="concept.php">💡 Notre Concept</a></li>
                        <li><a href="repaire.php">☕ Le Repaire (Le Lieu)</a></li>
                        <li><a href="projet.php">🚀 Le Projet</a></li>
                    </ul>
                </li>

                <!-- Menu 2 : L'Équipage & Chats -->
                <li class="has-dropdown">
                    <a href="equipage.php">L'Équipage</a>
                    <ul class="dropdown-menu">
                        <li><a href="equipage.php">🐱 Nos Moustachus</a></li>
                        <li><a href="repos-des-moustachus.php">💤 Le Repos des Moustachus</a></li>
                        <li><a href="adoption.php">🐾 Comment Adopter ?</a></li>
                    </ul>
                </li>

                <!-- Menu 3 : Les Ateliers -->
                <li><a href="ateliers.php">Les Ateliers</a></li>

                <!-- Menu 4 : Belles Histoires -->
                <li><a href="belles-histoires.php">Belles Histoires</a></li>

                <!-- Menu 5 : Boutique & Gourmandises -->
                <li class="has-dropdown">
                    <a href="boutique.php">Boutique</a>
                    <ul class="dropdown-menu">
                        <li><a href="boutique.php">🛍️ La Boutique Solidaire</a></li>
                        <li><a href="douceurs.php">🧁 Nos Douceurs Rétro</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="action">
            <a href="formulaire.php" class="bouton-reserver">Réserver</a>
            <a href="login.php" class="btn-admin-lock" title="Accès administrateur">🔐</a>
        </div>
    </header>

    <!-- MACARON FLOTTANT "ADHÉSION" -->
    <a href="adhesion.php" class="macaron-sticker">
      <span class="pattes">🐾🐾</span>
      <span class="titre-club">CLUB DES<br>MOUSTACHES</span>
      <span class="badge-prix">ADHÉSION 5€</span>
    </a>