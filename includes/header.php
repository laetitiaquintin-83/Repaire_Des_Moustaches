<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Repaire des Moustaches</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.min.css">
    <!-- Preload critical image (logo) -->
    <link rel="preload" as="image" href="images/logo.webp" type="image/webp">
    <link rel="preload" as="image" href="images/logo.svg" type="image/svg+xml">
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
                <li><a href="public/belles-histoires.php">Belles Histoires</a></li>
                <li><a href="public/boutique.php">Boutique</a></li>
            </ul>
        </nav>

        <div class="action">
            <a href="formulaire.php" class="bouton-reserver">Réserver</a>
            <a href="login.php" class="btn-admin-lock" title="Accès administrateur">🔐</a>
        </div>
    </header>
