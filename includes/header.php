<!DOCTYPE html>
<html lang="fr">
<head>
    <?php $sitePrefix = strpos($_SERVER['SCRIPT_NAME'] ?? '', '/public/') !== false ? '../' : ''; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Repaire des Moustaches</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $sitePrefix; ?>style.min.css">
    <!-- Preload critical image (logo) -->
    <link rel="preload" as="image" href="<?php echo $sitePrefix; ?>images/logo.webp" type="image/webp">
    <link rel="preload" as="image" href="<?php echo $sitePrefix; ?>images/logo.svg" type="image/svg+xml">
</head>
<body>
    <header>
        <a href="<?php echo $sitePrefix; ?>index.php" class="logo">
            <picture>
                <source srcset="<?php echo $sitePrefix; ?>images/logo.webp" type="image/webp">
                <source srcset="<?php echo $sitePrefix; ?>images/logo.svg" type="image/svg+xml">
                <img src="<?php echo $sitePrefix; ?>images/logo.png" alt="Logo du Repaire des Moustaches" width="100" height="55">
            </picture>
        </a>

        <nav>
            <ul>
                <li><a href="<?php echo $sitePrefix; ?>concept.php">Le Concept</a></li>
                <li><a href="<?php echo $sitePrefix; ?>projet.php">Le Projet</a></li>
                <li><a href="<?php echo $sitePrefix; ?>equipage.php">L'équipage</a></li>
                <li><a href="<?php echo $sitePrefix; ?>ateliers.php">Les Ateliers</a></li>
                <li><a href="<?php echo $sitePrefix; ?>public/belles-histoires.php">Belles Histoires</a></li>
                <li><a href="<?php echo $sitePrefix; ?>public/boutique.php">Boutique</a></li>
            </ul>
        </nav>

        <div class="action">
            <a href="<?php echo $sitePrefix; ?>formulaire.php" class="bouton-reserver">Réserver</a>
            <a href="<?php echo $sitePrefix; ?>login.php" class="btn-admin-lock" title="Accès administrateur">🔐</a>
        </div>
    </header>
