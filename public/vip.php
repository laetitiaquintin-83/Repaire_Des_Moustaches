<?php

// Charge le fichier de config
if (file_exists(__DIR__ . '/../config/config.php')) {
    require_once __DIR__ . '/../config/config.php';
} elseif (file_exists(__DIR__ . '/../config/database.php')) {
    require_once __DIR__ . '/../config/database.php';
}

// Initialisation de la BDD
$pdo = getPDO();

$page_title = "Espace VIP — Le Repaire des Moustaches";
$page_description = "Découvrez la collection Haute Couture pour félins exigeants. Produits d'exception et luxe sur mesure.";

// Récupération STRICTE de la catégorie VIP (categorie_id = 5)
$produits_vip = [];
try {
    $stmt = $pdo->prepare("
        SELECT * FROM produits 
        WHERE categorie_id = 5 
          AND actif = 1 
        ORDER BY id DESC
    ");
    $stmt->execute();
    $produits_vip = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_msg = "Erreur SQL : " . $e->getMessage();
}

// Inclusion du header
include __DIR__ . '/../includes/header.php';
?>

<!-- Feuille de style dédiée VIP -->
<link rel="stylesheet" href="<?php echo $sitePrefix ?? ''; ?>css/vip.css">

<main class="vip-main-wrapper">
    <!-- BANNIÈRE HERO FEAT. CAT LAGERFELD -->
    <section class="vip-hero">
        <div class="vip-hero-container">
            <div class="vip-hero-text">
                <span class="vip-badge">✨ ÉDITION HAUTE COUTURE</span>
                <h1>L'Espace VIP</h1>
                <p>Une collection d'exception pensée pour les félins raffinés. Le luxe ultime et le style dandy, signés <em>Le Repaire des Moustaches</em>.</p>
            </div>
            <div class="vip-hero-image">
                <img src="<?php echo $sitePrefix ?? ''; ?>images/produits/cat-lagerfield.png" alt="Cat Lagerfeld - Égérie VIP" class="cat-lagerfeld">
            </div>
        </div>
    </section>

    <!-- GRILLE DE PRODUITS VIP -->
    <section class="vip-catalog">
        <div class="vip-container">
            
            <?php if (isset($error_msg)): ?>
                <div style="background: #900; color: #fff; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    ⚠️ <?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <div class="vip-grid">
                <?php if (!empty($produits_vip)): ?>
                    <?php foreach ($produits_vip as $produit): ?>
                        <article class="vip-card">
                            <div class="vip-card-image">
                                <?php 
                                    $img_src = !empty($produit['image']) ? $produit['image'] : 'images/produits/default.jpg';
                                    if (strpos($img_src, 'http') === false && strpos($img_src, '/') !== 0) {
                                        $img_src = ($sitePrefix ?? '') . $img_src;
                                    }
                                ?>
                                <img src="<?php echo htmlspecialchars($img_src, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($produit['nom'] ?? 'Produit VIP', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="vip-card-content">
                                <h3><?php echo htmlspecialchars($produit['nom'] ?? 'Produit Sans Nom', ENT_QUOTES, 'UTF-8'); ?></h3>
                                <p class="vip-description"><?php echo htmlspecialchars($produit['description'] ?? 'Aucune description disponible.', ENT_QUOTES, 'UTF-8'); ?></p>
                                <div class="vip-card-footer">
                                    <span class="vip-price"><?php echo number_format((float)($produit['prix'] ?? 0), 2, ',', ' '); ?> €</span>
                                    <a href="<?php echo $sitePrefix ?? ''; ?>cart.php?action=add&id=<?php echo $produit['id']; ?>" class="vip-btn">Acquérir</a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="vip-empty" style="text-align: center; color: #aaa; padding: 40px 0;">
                        <p>La collection VIP est en cours de réassortiment dans nos ateliers.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>