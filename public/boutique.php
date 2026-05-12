<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();
$csrf_token = generateCSRFToken();

// Récupérer tous les produits avec leurs catégories
$sql = 'SELECT p.id, p.nom, p.description, p.prix, cp.nom AS categorie
        FROM produits p
        JOIN categories_produits cp ON p.categorie_id = cp.id
        ORDER BY cp.nom ASC, p.nom ASC';

$produits = $pdo->query($sql)->fetchAll();

// Grouper par catégories
$produits_par_categorie = [];
foreach ($produits as $produit) {
    $cat = (string) $produit['categorie'];
    if (!isset($produits_par_categorie[$cat])) {
        $produits_par_categorie[$cat] = [];
    }
    $produits_par_categorie[$cat][] = $produit;
}

// Labels & émojis pour chaque catégorie
$config_categories = [
    'diner' => ['label' => 'Repas & Boissons', 'emoji' => '🍔'],
    'diner_retro' => ['label' => 'Dîner Rétro', 'emoji' => '🥤'],
    'cat_lovers' => ['label' => 'Cat Lovers', 'emoji' => '🐾'],
    'solidaire' => ['label' => 'Solidaire', 'emoji' => '❤️'],
];

function formatPrice(float $price): string
{
    return number_format($price, 2, ',', ' ') . ' €';
}

function getCategoryConfig(string $cat): array
{
    global $config_categories;
    return $config_categories[$cat] ?? ['label' => ucfirst(str_replace('_', ' ', $cat)), 'emoji' => '📦'];
}

function getImagePath(string $productName): string
{
    // Mapping direct produit → image (toutes dans images/produits/)
    $imageMap = [
        'Milkshake Fraise' => 'images/produits/milkshake-fraise.jpg',
        'Burger Veggie Moustache' => 'images/produits/burger-veggie.jpg',
        'Mug Diner' => 'images/produits/mug-diner.jpg',
        'Tablier Vintage' => 'images/produits/tablier-vintage.jpg',
        'Pins Emailles' => 'images/produits/pins-emailles.jpg',
        'Tote Bag Solidaire' => 'images/produits/tote-bag.jpg',
        'Jouets Catnip Deluxe' => 'images/produits/jouets-catnip.jpg',
        'Planches de Stickers Retro' => 'images/produits/stickers-retro.jpg',
        'Cartes Postales Polaroid' => 'images/produits/cartes-postales.jpg',
        'Badge Solidaire' => 'images/produits/badge-solidaire.jpg',
    ];
    
    return $imageMap[$productName] ?? 'images/placeholder.jpg';
}

// Calculer le nombre d'articles dans le panier
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique - Le Repaire des Moustaches</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <style>
        .panier-link {
            background: #85D6CD;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .panier-link:hover {
            background: #6bc3b8;
        }
        
        .panier-count {
            background: #FE7B7E;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            min-width: 20px;
            text-align: center;
        }
        
        .bouton-ajouter-panier {
            background: #85D6CD;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 14px;
            width: 100%;
        }
        
        .bouton-ajouter-panier:hover {
            background: #6bc3b8;
        }
    </style>
</head>
<body>
    <header>
        <a href="../index.html" class="logo"><img src="../images/logo.png" alt="Logo du Repaire des Moustaches"></a>
        <nav><ul><li><a href="../index.html">Accueil</a></li><li><a href="../concept.html">Le Concept</a></li><li><a href="../equipage.html">L'équipage</a></li><li><a href="../ateliers.html">Les Ateliers</a></li><li><a href="belles-histoires.php">Histoires</a></li><li><a href="boutique.php">Boutique</a></li></ul></nav>
        <div class="action">
            <a href="cart.php" class="panier-link">
                🛒 Panier 
                <?php if ($cart_count > 0): ?>
                    <span class="panier-count"><?php echo $cart_count; ?></span>
                <?php endif; ?>
            </a>
        </div>
    </header>

    <main class="boutique-principale">
        <section class="boutique-hero-catalog">
            <h1 class="page-title">Notre Boutique</h1>
            <p class="sous-titre">Goodies rétro & solidaires pour soutenir le Repaire</p>
            <p style="text-align: center; margin-bottom: 40px; line-height: 1.7; max-width: 700px; margin-left: auto; margin-right: auto; font-size: 0.95rem;">
                Chaque achat finance les soins de nos moustachus et le fonctionnement du tiers-lieu. 
                <strong>Ramène chez toi un petit morceau du Repaire !</strong>
            </p>
        </section>

        <?php if (empty($produits_par_categorie)): ?>
            <p style="text-align: center; padding: 40px;">Aucun produit disponible pour le moment.</p>
        <?php else: ?>
            <?php foreach ($produits_par_categorie as $cat => $items): ?>
                <section class="boutique-section">
                    <div class="boutique-section-header">
                        <h2>
                            <?php 
                            $config = getCategoryConfig($cat); 
                            echo htmlspecialchars($config['emoji'], ENT_QUOTES, 'UTF-8') . ' ';
                            echo htmlspecialchars($config['label'], ENT_QUOTES, 'UTF-8'); 
                            ?>
                        </h2>
                        <p class="boutique-section-description">
                            <?php
                            $descriptions = [
                                'diner' => 'Nos plats et boissons maison pour le dîner',
                                'diner_retro' => 'L\'esprit Dîner américain années 50 chez toi',
                                'cat_lovers' => 'Pour les amoureux des chats',
                                'solidaire' => 'Chaque achat soutient directement le refuge',
                            ];
                            echo htmlspecialchars($descriptions[$cat] ?? '', ENT_QUOTES, 'UTF-8');
                            ?>
                        </p>
                    </div>
                    
                    <div class="grille-produits-boutique">
                        <?php foreach ($items as $produit): ?>
                            <article class="carte-produit-boutique">
                                <div class="produit-image-boutique">
                                    <img src="../<?php echo htmlspecialchars(getImagePath((string) $produit['nom']), ENT_QUOTES, 'UTF-8'); ?>" 
                                         alt="<?php echo htmlspecialchars((string) $produit['nom'], ENT_QUOTES, 'UTF-8'); ?>"
                                         loading="lazy">
                                </div>
                                <div class="produit-info-boutique">
                                    <h3><?php echo htmlspecialchars((string) $produit['nom'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p class="produit-description-boutique">
                                        <?php echo htmlspecialchars((string) $produit['description'], ENT_QUOTES, 'UTF-8'); ?>
                                    </p>
                                    <div class="produit-footer-boutique">
                                        <span class="produit-prix-boutique">
                                            <?php echo htmlspecialchars(formatPrice((float) $produit['prix']), ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                        <form method="POST" action="add-to-cart.php" style="width: 100%; margin: 0;">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                                            <input type="hidden" name="produit_id" value="<?php echo (int)$produit['id']; ?>">
                                            <input type="hidden" name="quantite" value="1">
                                            <button type="submit" class="bouton-ajouter-panier">🛒 Ajouter</button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>

        <section class="boutique-info-finales" style="padding: 60px 50px; background-color: white; margin-top: 40px; text-align: center;">
            <h2 style="font-family: 'Pacifico', cursive; color: #FE7B7E; font-size: 2rem; font-weight: normal; margin-bottom: 20px;">Besoin d'aide ?</h2>
            <div style="max-width: 700px; margin: 0 auto;">
                <p style="margin-bottom: 15px; line-height: 1.7;">📦 Livraison à domicile ou retrait au Repaire</p>
                <p style="margin-bottom: 15px; line-height: 1.7;">💳 Paiement sécurisé</p>
                <p style="margin-bottom: 20px; line-height: 1.7;">💬 Questions ? <a href="mailto:contact@repaire-des-moustaches.fr" style="color: #FE7B7E; text-decoration: none;">Contacte-nous !</a></p>
            </div>
        </section>
    </main>

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
