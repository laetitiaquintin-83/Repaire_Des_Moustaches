<?php

declare(strict_types=1);

// ============================================================
// 📌 PAGE PUBLIQUE : Belles Histoires
// ============================================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getPDO();

// Définir la meta description pour cette page (utilisée dans header.php)
$page_description = 'Découvrez les belles histoires d\'adoption des moustaches du Repaire. Témoignages, photos et récits d\'adoptants heureux à Toulon.';

// Récupérer les histoires publiées
$sql = 'SELECT bh.id, bh.titre, bh.contenu, bh.date_publication, u.prenom, u.nom
        FROM belles_histoires bh
        LEFT JOIN utilisateurs u ON bh.utilisateur_id = u.id
        WHERE bh.statut = "publiee"
        ORDER BY bh.date_publication DESC';

$histoires = $pdo->query($sql)->fetchAll();

// Fonction de formatage de date (utilisée dans la page)
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

// Déterminer le préfixe de chemin pour les assets
$sitePrefix = strpos($_SERVER['SCRIPT_NAME'] ?? '', '/public/') !== false ? '../' : '';

// Inclure le header
include __DIR__ . '/../includes/header.php';
?>

<main class="page-liste belles-histoires-liste">
    <section class="liste-header">
        <h1>Belles Histoires</h1>
        <p>Les aventures de nos moustachus adoptés et leurs nouvelles vies.</p>
        
        <?php 
        $image_path = $sitePrefix . 'images/souvenir.jpg';
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $image_path) || file_exists(__DIR__ . '/../' . $image_path)): 
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

<?php
// Inclure le footer
include __DIR__ . '/../includes/footer.php';
?>