<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();

// Récupérer les histoires publiées
$sql = 'SELECT bh.id, bh.titre, bh.contenu, bh.date_publication, u.prenom, u.nom
        FROM belles_histoires bh
        LEFT JOIN utilisateurs u ON bh.utilisateur_id = u.id
        WHERE bh.statut = "publiee"
        ORDER BY bh.date_publication DESC';

$histoires = $pdo->query($sql)->fetchAll();

function formatDate(string $date): string
{
    $dt = new DateTime($date);
    $mois_fr = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 
                'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    $jour = (int)$dt->format('d');
    $mois = $mois_fr[(int)$dt->format('m') - 1];
    $annee = $dt->format('Y');
    return "$jour $mois $annee";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belles Histoires - Le Repaire des Moustaches</title>
    <meta name="description" content="Découvrez les belles histoires d'adoption des moustaches du Repaire. Témoignages, photos et récits d'adoptants heureux à Toulon.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <!-- Header intégré -->
    <header>
        <a href="../index.php" class="logo"><img src="../images/logo.png" alt="Logo du Repaire des Moustaches"></a>
        <nav>
            <ul>
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="../concept.php">Le Concept</a></li>
                <li><a href="../equipage.php">L'équipage</a></li>
                <li><a href="../ateliers.php">Les Ateliers</a></li>
                <li><a href="belles-histoires.php">Histoires</a></li>
                <li><a href="boutique.php">Boutique</a></li>
            </ul>
        </nav>
        <div class="action">
            <a href="../formulaire.php" class="bouton-reserver">Réserver</a>
            <a href="../login.php" class="btn-admin-lock" title="Accès administrateur">🔐</a>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="page-liste belles-histoires-liste">
        <section class="liste-header">
            <h1>Belles Histoires</h1>
            <p>Les aventures de nos moustachus adoptés et leurs nouvelles vies.</p>
            
            <!-- ✅ IMAGE (vérification automatique) -->
            <?php 
            $image_path = '../images/souvenir.jpg';
            if (file_exists(__DIR__ . '/../images/souvenir.jpg')): 
            ?>
                <img src="<?php echo htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8'); ?>" 
                     alt="Mur des souvenirs du Repaire des Moustaches" 
                     style="width: 100%; max-width: 600px; margin-top: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"
                     loading="lazy">
            <?php endif; ?>
        </section>

        <section class="grille-histoires">
            <?php if (empty($histoires)): ?>
                <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px;">
                    <p style="font-size: 1.2rem; color: #666;">
                        🐱 Aucune histoire n'est encore partagée. 
                        <br>Revenez bientôt pour découvrir les aventures de nos moustachus !
                    </p>
                </div>
            <?php else: ?>
                <?php foreach ($histoires as $histoire): ?>
                    <article class="carte-histoire">
                        <div class="histoire-header">
                            <h2><?php echo htmlspecialchars($histoire['titre'], ENT_QUOTES, 'UTF-8'); ?></h2>
                            <p class="histoire-date">
                                <span style="font-weight: 600;"><?php echo htmlspecialchars($histoire['prenom'] . ' ' . $histoire['nom'], ENT_QUOTES, 'UTF-8'); ?></span>
                                — 
                                <span>Publié le <?php echo htmlspecialchars(formatDate((string) $histoire['date_publication']), ENT_QUOTES, 'UTF-8'); ?></span>
                            </p>
                        </div>
                        <div class="histoire-contenu">
                            <p><?php echo nl2br(htmlspecialchars($histoire['contenu'], ENT_QUOTES, 'UTF-8')); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <section style="text-align: center; margin-top: 60px; padding: 40px; background: #f9f9f9; border-radius: 12px;">
            <h2 style="margin-bottom: 20px; font-family: 'Pacifico', cursive; color: #FE7B7E; font-weight: normal;">
                📝 Vous aussi, partagez votre histoire !
            </h2>
            <p style="margin-bottom: 20px; color: #666; max-width: 600px; margin-left: auto; margin-right: auto;">
                Vous avez adopté un moustachu du Repaire ? Racontez-nous son histoire, ses nouvelles aventures, 
                et inspirez d'autres familles à franchir le pas !
            </p>
            <a href="soumettre-histoire.php" class="bouton-reserver" style="background-color: #85D6CD; text-decoration: none; padding: 12px 30px; border-radius: 4px; color: white; font-weight: 700; display: inline-block;">
                ✨ Partager mon histoire
            </a>
        </section>
    </main>

    <!-- Footer intégré -->
    <footer>
        <p>&copy; 2026 Le Repaire des Moustaches. Un tiers-lieu solidaire pour les chats et les humains.</p>
        <div class="reseaux-sociaux">
            <a href="#">Facebook</a> |
            <a href="#">Instagram</a> |
            <a href="../login.php">Admin</a>
        </div>
    </footer>
</body>
</html>