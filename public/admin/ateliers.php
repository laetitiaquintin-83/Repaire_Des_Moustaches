<?php
declare(strict_types=1);

// Inclusion du header
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

try {
    $pdo = getPDO();
    // Récupération des ateliers à venir
    $stmt = $pdo->query('SELECT * FROM ateliers ORDER BY date_heure ASC');
    $ateliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $ateliers = [];
}
?>

<main class="page-section">
    <h1 class="page-title">
        🎨 Nos Ateliers & Événements
    </h1>
    <p class="sous-titre-ateliers">Rejoignez-nous pour un moment convivial au Repaire !</p>

    <?php if (empty($ateliers)): ?>
        <p style="text-align: center; color: #666; font-size: 1.1rem;">Aucun atelier n'est programmé pour le moment.</p>
    <?php else: ?>
        <div class="grille-atelier-visuels">
            <?php foreach ($ateliers as $atelier): ?>
                <article class="visuel-card">
                    <div class="contenu-atelier">
                        <div>
                            <h3><?php echo htmlspecialchars($atelier['titre']); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($atelier['description'] ?? '')); ?></p>
                        </div>
                        
                        <div>
                            <p style="font-size: 0.85rem; color: #718096; margin-bottom: 15px;">
                                📅 <?php echo date('d/m/Y à H:i', strtotime($atelier['date_heure'])); ?><br>
                                👥 Max <?php echo (int)$atelier['capacite_max']; ?> pers.
                            </p>

                            <a href="formulaire.php?atelier_id=<?php echo $atelier['id']; ?>" class="bouton-secondaire">
                                S'inscrire
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php
if (file_exists(__DIR__ . '/includes/footer.php')) {
    require_once __DIR__ . '/includes/footer.php';
}
?>