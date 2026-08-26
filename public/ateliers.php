<?php
declare(strict_types=1);

// Force l'encodage UTF-8 au niveau du serveur
header('Content-Type: text/html; charset=utf-8');

$sitePrefix = '';

// Connexion à la base de données
require_once __DIR__ . '/../config/database.php';

try {
    $pdo = getPDO();
    
    // Récupérer tous les ateliers ordonnés par date (Correction des noms de colonnes : date_atelier, places_max)
    $stmt = $pdo->prepare('SELECT id, titre, description, image, date_atelier, places_max 
                           FROM ateliers 
                           ORDER BY date_atelier ASC');
    $stmt->execute();
    $ateliers = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('Erreur BD ateliers: ' . $e->getMessage());
    $ateliers = [];
}

// Inclusions du Header public
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
                // Correction du chemin d'accès à l'image
                $image = !empty($atelier['image']) ? $atelier['image'] : 'images/arbre-a-chat.jpg';
                
                // Si l'image n'a pas le préfixe "images/", on l'ajoute
                if (!str_starts_with($image, 'images/')) {
                    $image = 'images/' . $image;
                }
                
                $imagePath = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');
                $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $imagePath);
            ?>
            <article class="visuel-card">
                <picture>
                    <?php if (file_exists(__DIR__ . '/../public/' . $webpPath) || file_exists(__DIR__ . '/' . $webpPath)): ?>
                        <source srcset="<?php echo $webpPath; ?>" type="image/webp">
                    <?php endif; ?>
                    <img src="<?php echo $imagePath; ?>" 
                         alt="<?php echo htmlspecialchars($atelier['titre'], ENT_QUOTES, 'UTF-8'); ?>" 
                         width="300" height="200" loading="lazy"
                         onerror="this.src='images/arbre-a-chat.jpg';">
                </picture>
                <div class="contenu-atelier">
                    <h3><?php echo htmlspecialchars($atelier['titre'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars($atelier['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                    <small style="color: #999; font-size: 0.85rem; display: block; margin-bottom: 10px;">
                        📅 <?php echo !empty($atelier['date_atelier']) ? date('d/m/Y à H:i', strtotime($atelier['date_atelier'])) : 'Date à venir'; ?> 
                        | 👥 Max <?php echo htmlspecialchars((string)($atelier['places_max'] ?? 10), ENT_QUOTES, 'UTF-8'); ?> pers.
                    </small>
                    <a href="formulaire.php" class="bouton-secondaire">S'inscrire</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php 
// Inclusions du Footer public
include_once __DIR__ . '/../includes/footer.php'; 
?>