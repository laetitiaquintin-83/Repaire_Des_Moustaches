<?php
declare(strict_types=1);

// Force l'encodage UTF-8 au niveau du serveur pour supprimer les caractères bizarres
header('Content-Type: text/html; charset=utf-8');

$sitePrefix = '';

// Connexion propre à la base de données (on remonte d'un dossier pour trouver config/)
require_once __DIR__ . '/../config/database.php';

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

// 1. ON APPELLE TON HEADER AUTOMATIQUE ICI !
include_once __DIR__ . '/../includes/header.php';
?>

    <main>
        <section class="page-section ateliers">
            <h1 class="page-title">Les Ateliers du Repaire</h1>
            <p class="sous-titre-ateliers">Apprendre, créer, se détendre, s'entraider. Quatre façons de changer la vie (la vôtre et celle de nos moustachus).</p>
            <p style="text-align: center; margin-bottom: 50px; line-height: 1.7; max-width: 700px; margin-left: auto; margin-right: auto;">
                Les ateliers sont au cœur de notre mission. Une adhésion annuelle à 5€ vous ouvre les portes. Ensuite, participez comme bon vous semble, au prix que vous décidez (prix libre). Ensemble, nous créons un espace de solidarité où chacun donne ce qu'il peut.
            </p>

            <div class="grille-atelier-visuels">
                <?php foreach ($ateliers as $atelier): 
                    $image = !empty($atelier['image']) ? $atelier['image'] : 'images/ateliers/atelier-default.jpg';
                    if (str_starts_with($image, 'images/atelier') && !str_starts_with($image, 'images/ateliers/')) {
                        $image = 'images/ateliers/' . basename($image);
                    }
                ?>
                <article class="visuel-card">
                    <picture>
                        <?php 
                            $imagePath = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');
                            $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $imagePath);
                        ?>
                        <source srcset="<?php echo $webpPath; ?>" type="image/webp">
                        <img src="<?php echo $imagePath; ?>" 
                             alt="<?php echo htmlspecialchars($atelier['titre'], ENT_QUOTES, 'UTF-8'); ?>" 
                             width="300" height="200" loading="lazy">
                    </picture>
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

<?php 
// 2. ON APPELLE TON FOOTER AUTOMATIQUE ICI !
include_once __DIR__ . '/../includes/footer.php'; 
?>