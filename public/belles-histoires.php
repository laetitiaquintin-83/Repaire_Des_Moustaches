<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();

$sql = 'SELECT id, titre, contenu, date_publication, utilisateur_id
        FROM belles_histoires
        WHERE statut = "publiee"
        ORDER BY date_publication DESC';

$histoires = $pdo->query($sql)->fetchAll();

function formatDate(string $date): string
{
    $dt = new DateTime($date);
    return $dt->format('d F Y');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belles Histoires - Le Repaire des Moustaches</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <div class="logo"><img src="../images/logo.png" alt="Logo du Repaire des Moustaches"></div>
        <nav><ul><li><a href="../index.html">Accueil</a></li><li><a href="../concept.html">Le Concept</a></li><li><a href="../equipage.html">L'équipage</a></li><li><a href="../ateliers.html">Les Ateliers</a></li><li><a href="../boutique.html">Boutique</a></li></ul></nav>
        <div class="action"><a href="../ateliers.html" class="bouton-reserver">Réserver</a></div>
    </header>
    <main class="page-liste belles-histoires-liste">
        <section class="liste-header">
            <h1>Belles Histoires</h1>
            <p>Les aventures de nos moustachus adoptés et leurs nouvelles vies.</p>
            <img src="../images/souvenir.jpg" alt="Mur des souvenirs" style="width: 100%; max-width: 600px; margin-top: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
        </section>

        <section class="grille-histoires">
            <?php if ($histoires === []): ?>
                <p>Aucune histoire n'est encore partagée. Revenez bientôt !</p>
            <?php else: ?>
                <?php foreach ($histoires as $histoire): ?>
                    <article class="carte-histoire">
                        <div class="histoire-header">
                            <h2><?php echo htmlspecialchars($histoire['titre'], ENT_QUOTES, 'UTF-8'); ?></h2>
                            <p class="histoire-date">
                                Publié le <?php echo htmlspecialchars(formatDate((string) $histoire['date_publication']), ENT_QUOTES, 'UTF-8'); ?>
                            </p>
                        </div>
                        <div class="histoire-contenu">
                            <p><?php echo htmlspecialchars($histoire['contenu'], ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2026 Le Repaire des Moustaches. Un tiers-lieu solidaire pour les chats et les humains.</p>
        <div class="reseaux-sociaux">
            <a href="#">Facebook</a>
            <a href="#">Instagram</a>
            <a href="#">Contact</a>
        </div>
    </footer>
</body>
</html>
