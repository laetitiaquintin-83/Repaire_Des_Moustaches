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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/style.css">

    <!-- Styles CSS du Macaron Flottant -->
    <style>
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

      /* Effet au survol du curseur */
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
                <li><a href="concept.php">Le Concept</a></li>
                <li><a href="projet.php">Le Projet</a></li>
                <li><a href="equipage.php">L'équipage</a></li>
                <li><a href="ateliers.php">Les Ateliers</a></li>
                <li><a href="belles-histoires.php">Belles Histoires</a></li>
                <li><a href="boutique.php">Boutique</a></li>
            </ul>
        </nav>

        <div class="action">
            <!-- Remplace ici si tu as un lien spécifique pour le panier (ex: <a href="panier.php">🛒</a>) -->
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