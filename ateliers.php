<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

try {
    $pdo = getPDO();
    
    // Récupérer tous les ateliers ordonnés par date
    $stmt = $pdo->prepare('SELECT id, titre, description, image, date_heure, capacite_max 
                           FROM ateliers 
                           ORDER BY date_heure ASC');
    $stmt->execute();
    $ateliers = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('Erreur BD ateliers: ' . $e->getMessage());
    $ateliers = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Ateliers - Le Repaire des Moustaches</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo"><img src="images/logo.png" alt="Logo du Repaire des Moustaches"></a>
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
        <div class="action"><a href="formulaire.php" class="bouton-reserver">Réserver</a><a href="login.php" class="btn-admin-lock" title="Accès administrateur">🔐</a></div>
    </header>

    <main>
        <section class="page-section ateliers">
            <h1 class="page-title">Les Ateliers du Repaire</h1>
            <p class="sous-titre-ateliers">Apprendre, créer, se détendre, s'entraider. Quatre façons de changer la vie (la vôtre et celle de nos moustachus).</p>
            <p style="text-align: center; margin-bottom: 50px; line-height: 1.7; max-width: 700px; margin-left: auto; margin-right: auto;">Les ateliers sont au cœur de notre mission. Une adhésion annuelle à 5€ vous ouvre les portes. Ensuite, participez comme bon vous semble, au prix que vous décidez (prix libre). Ensemble, nous créons un espace de solidarité où chacun donne ce qu'il peut.</p>

            <div class="grille-atelier-visuels">
                <?php foreach ($ateliers as $atelier): 
                    $image = !empty($atelier['image']) ? $atelier['image'] : 'images/atelier-default.jpg';
                ?>
                <article class="visuel-card">
                    <img src="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>" 
                         alt="<?php echo htmlspecialchars($atelier['titre'], ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="contenu-atelier">
                        <h3><?php echo htmlspecialchars($atelier['titre'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p><?php echo htmlspecialchars($atelier['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <small style="color: #999; font-size: 0.85rem;">
                            📅 <?php echo date('d/m/Y à H:i', strtotime($atelier['date_heure'])); ?> 
                            | 👥 Max <?php echo htmlspecialchars((string)$atelier['capacite_max'], ENT_QUOTES, 'UTF-8'); ?> pers.
                        </small>
                        <a href="formulaire.php" class="bouton-secondaire">S'inscrire</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2026 Le Repaire des Moustaches. Un tiers-lieu solidaire pour les chats et les humains.</p>
        <div class="reseaux-sociaux">
            <a href="#">Facebook</a> |
            <a href="#">Instagram</a> |
            <a href="login.php">Admin</a>
        </div>
    </footer>
</body>
</html>
