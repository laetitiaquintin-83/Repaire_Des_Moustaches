<!DOCTYPE html>
<html lang="fr">
<head>
    <?php $sitePrefix = strpos($_SERVER['SCRIPT_NAME'] ?? '', '/public/') !== false ? '../' : ''; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Repaire des Moustaches</title>
    
    <!-- ============================================================
    ✅ META DESCRIPTION AJOUTÉE (SEO)
    ============================================================ -->
    <meta name="description" content="<?php echo htmlspecialchars($page_description ?? 'Le Repaire des Moustaches - Tiers-lieu solidaire et boutique en ligne. Adoptez, partagez des histoires et soutenez la cause animale à Toulon.', ENT_QUOTES, 'UTF-8'); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    
    <!-- ============================================================
    ✅ STYLE : on utilise style.css (pas style.min.css qui n'existe peut-être pas)
    ============================================================ -->
    <link rel="stylesheet" href="<?php echo $sitePrefix; ?>style.css">
</head>
<body>
    <header>
        <!-- ============================================================
        ✅ LOGO : fallback en texte si les images n'existent pas
        ============================================================ -->
        <a href="<?php echo $sitePrefix; ?>index.php" class="logo">
            <?php 
            // Vérifier si l'image existe
            $logo_path = __DIR__ . '/../' . $sitePrefix . 'images/logo.png';
            $logo_exists = file_exists($logo_path);
            ?>
            <?php if ($logo_exists): ?>
                <picture>
                    <source srcset="<?php echo $sitePrefix; ?>images/logo.webp" type="image/webp">
                    <source srcset="<?php echo $sitePrefix; ?>images/logo.svg" type="image/svg+xml">
                    <img src="<?php echo $sitePrefix; ?>images/logo.png" alt="Logo du Repaire des Moustaches" width="100" height="55">
                </picture>
            <?php else: ?>
                <!-- Fallback texte si l'image n'existe pas -->
                <span style="font-family: 'Pacifico', cursive; font-size: 1.8rem; color: #2B2B2B;">🧔 Repaire</span>
            <?php endif; ?>
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