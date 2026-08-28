<?php
declare(strict_types=1);

// On force le serveur à envoyer la page en UTF-8
header('Content-Type: text/html; charset=utf-8');

$sitePrefix = '';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();

// Génération CSRF sécurisée
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Récupérer tous les produits avec leurs catégories et leur image
$sql = 'SELECT p.id, p.nom, p.description, p.prix, p.image, cp.nom AS categorie
        FROM produits p
        JOIN categories_produits cp ON p.categorie_id = cp.id
        WHERE p.actif = 1
        ORDER BY cp.nom ASC, p.nom ASC';

$stmt = $pdo->prepare($sql);
$stmt->execute();
$produits = $stmt->fetchAll();

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
    'diner' => ['label' => 'Repas & Boissons', 'emoji' => '🍔🍽️'],
    'diner_retro' => ['label' => 'Dîner Rétro', 'emoji' => '🍔🎷'],
    'cat_lovers' => ['label' => 'Cat Lovers', 'emoji' => '🐱🐾'],
    'solidaire' => ['label' => 'Solidaire', 'emoji' => '❤️'],
];

function formatPrice(float $price): string
{
    return number_format($price, 2, ',', ' ') . ' €';
}

function getCategoryConfig(string $cat): array
{
    global $config_categories;
    return $config_categories[$cat] ?? ['label' => ucfirst(str_replace('_', ' ', $cat)), 'emoji' => '🍔🛍️'];
}

function getImagePath(string $productName): string
{
    $imageMap = [
        'Milkshake Fraise' => 'images/produits/milkshake-fraise.jpg',
        'Burger Veggie Moustache' => 'images/produits/burger-veggie.jpg',
        'Mug Diner' => 'images/produits/mug-diner.jpg',
        'Tablier Vintage' => 'images/produits/tablier-vintage.jpg',
        'Pins Émaillés' => 'images/produits/pins-emailles.jpg',
        'Pins Emailles' => 'images/produits/pins-emailles.jpg',
        'Tote Bag Solidaire' => 'images/produits/tote-bag.jpg',
        'Jouets Catnip Deluxe' => 'images/produits/jouets-catnip.jpg',
        'Planches de Stickers Rétro' => 'images/produits/stickers-retro.jpg',
        'Planches de Stickers Retro' => 'images/produits/stickers-retro.jpg',
        'Cartes Postales Polaroid' => 'images/produits/cartes-postales.jpg',
        'Badge Solidaire' => 'images/produits/badge-solidaire.jpg',
    ];
    
    return $imageMap[$productName] ?? 'images/placeholder.jpg';
}

// Calculer le nombre d'articles dans le panier
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    $cart_count = array_sum(array_column($_SESSION['cart'], 'quantite'));
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Surcharge prioritaire pour le menu déroulant */
        header {
            overflow: visible !important;
        }

        nav ul li.dropdown {
            position: relative !important;
        }

        nav ul li.dropdown .dropdown-content {
            display: none !important;
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            background-color: #ffffff !important;
            min-width: 180px !important;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.15) !important;
            border-radius: 8px !important;
            padding: 10px 0 !important;
            z-index: 9999 !important;
            list-style: none !important;
            margin: 0 !important;
        }

        nav ul li.dropdown .dropdown-content li {
            width: 100% !important;
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        nav ul li.dropdown .dropdown-content a {
            color: #2c3e50 !important;
            padding: 8px 16px !important;
            display: block !important;
            text-decoration: none !important;
            white-space: nowrap !important;
            font-family: inherit !important;
            font-size: 0.95rem !important;
            text-align: left !important;
        }

        nav ul li.dropdown .dropdown-content a:hover {
            background-color: #f0f8f7 !important;
            color: #ff7b7b !important;
        }

        nav ul li.dropdown:hover .dropdown-content {
            display: block !important;
        }

        /* Styles specifiques boutique */
        .boutique-principale {
            max-width: 1100px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .boutique-hero-catalog {
            text-align: center;
            margin-bottom: 40px;
        }

        .boutique-hero-catalog h1 {
            color: #ff7b7b;
            font-size: 2.4rem;
            margin-bottom: 10px;
        }

        .boutique-hero-catalog .sous-titre {
            color: #2c3e50;
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .boutique-section {
            margin-bottom: 50px;
        }

        .boutique-section-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .boutique-section-header h2 {
            color: #ff7b7b;
            font-size: 1.8rem;
            margin-bottom: 5px;
            font-family: 'Pacifico', cursive;
        }

        .boutique-section-description {
            color: #666;
            font-style: italic;
            font-size: 0.95rem;
        }

        .grille-produits-boutique {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .carte-produit-boutique {
            background: #ffffff;
            border: 2px solid #82ceca;
            border-radius: 18px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .carte-produit-boutique:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(130, 206, 202, 0.25);
        }

        .produit-image-boutique {
            width: 100%;
            height: 230px;
            overflow: hidden;
            background-color: #fcf8f2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .produit-image-boutique img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.4s ease;
        }

        .carte-produit-boutique:hover .produit-image-boutique img {
            transform: scale(1.05);
        }

        .produit-info-boutique {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .produit-info-boutique h3 {
            color: #ff7b7b;
            font-size: 1.2rem;
            margin: 0 0 8px 0;
        }

        .produit-description-boutique {
            color: #555;
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0 0 15px 0;
            flex-grow: 1;
        }

        .produit-footer-boutique {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: auto;
            padding-top: 10px;
            border-top: 1px dashed #eef2f5;
        }

        .produit-prix-boutique {
            color: #2c3e50;
            font-size: 1.25rem;
            font-weight: bold;
        }

        .bouton-ajouter-panier {
            background: #82ceca;
            color: white;
            border: none;
            padding: 12px 15px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s ease;
            font-size: 0.95rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .bouton-ajouter-panier:hover {
            background: #6bc3b8;
            transform: translateY(-1px);
        }

        .panier-link {
            background: #82ceca;
            color: white;
            padding: 8px 16px;
            border-radius: 30px;
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
            background: #ff7b7b;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="logo"><img src="images/logo.png" alt="Logo du Repaire des Moustaches"></a>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li class="dropdown">
                    <a href="concept.php" class="dropbtn">Le Concept ▾</a>
                    <ul class="dropdown-content">
                        <li><a href="projet.php">Le Projet</a></li>
                        <li><a href="equipage.php">L'Équipage</a></li>
                    </ul>
                </li>
                <li><a href="ateliers.php">Les Ateliers</a></li>
                <li><a href="belles-histoires.php">Belles Histoires</a></li>
                <li class="dropdown">
                    <a href="boutique.php" class="dropbtn active">Boutique ▾</a>
                    <ul class="dropdown-content">
                        <li><a href="boutique.php">🛍️ Tous les produits</a></li>
                        <li><a href="douceurs.php">🍰 Douceurs</a></li>
                        <li><a href="vip.php">👑 VIP</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div class="action">
            <a href="cart.php" class="panier-link">
                🛒 Panier 
                <?php if ($cart_count > 0): ?>
                    <span class="panier-count"><?php echo $cart_count; ?></span>
                <?php endif; ?>
            </a>
            <a href="../login.php" class="btn-admin-lock" title="Accès administrateur">🔒</a>
        </div>
    </header>

    <main class="boutique-principale">
        <section class="boutique-hero-catalog">
            <h1 class="page-title">Notre Boutique</h1>
            <p class="sous-titre">Goodies rétro & solidaires pour soutenir le Repaire</p>
            <p style="text-align: center; margin-bottom: 40px; line-height: 1.7; max-width: 700px; margin-left: auto; margin-right: auto; font-size: 0.95rem; color: #555;">
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
                                'diner_retro' => "L'esprit Dîner américain années 50 chez toi",
                                'cat_lovers' => 'Pour les amoureux des chats',
                                'solidaire' => 'Chaque achat soutient directement le refuge',
                            ];
                            echo htmlspecialchars($descriptions[$cat] ?? '', ENT_QUOTES, 'UTF-8');
                            ?>
                        </p>
                    </div>
                    
                    <div class="grille-produits-boutique">
                        <?php foreach ($items as $produit): ?>
                            <?php 
                                $srcImage = !empty($produit['image']) 
                                    ? $produit['image'] 
                                    : getImagePath((string) $produit['nom']);
                            ?>
                            <article class="carte-produit-boutique">
                                <div class="produit-image-boutique">
                                    <img src="<?php echo htmlspecialchars($srcImage, ENT_QUOTES, 'UTF-8'); ?>" 
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
                                        
                                        <form method="POST" action="add-to-cart.php" class="form-add-to-cart" style="width: 100%; margin: 0;">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="produit_id" value="<?php echo (int)$produit['id']; ?>">
                                            <input type="hidden" name="quantite" value="1">
                                            <button type="submit" class="bouton-ajouter-panier">🛒 Ajouter au panier</button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>

        <section class="boutique-info-finales" style="padding: 40px 30px; background-color: white; border: 2px solid #82ceca; border-radius: 18px; margin-top: 40px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            <h2 style="font-family: 'Pacifico', cursive; color: #ff7b7b; font-size: 2rem; font-weight: normal; margin-bottom: 20px;">Besoin d'aide ?</h2>
            <div style="max-width: 700px; margin: 0 auto; color: #555;">
                <p style="margin-bottom: 12px; line-height: 1.6;">🚚 Livraison à domicile ou retrait gratuit au Repaire</p>
                <p style="margin-bottom: 12px; line-height: 1.6;">💳 Paiement 100% sécurisé</p>
                <p style="margin-bottom: 0; line-height: 1.6;">❓ Une question ? <a href="mailto:contact@repaire-des-moustaches.fr" style="color: #ff7b7b; font-weight: bold; text-decoration: none;">Contacte-nous !</a></p>
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

    <script src="../js/cart.js"></script>
</body>
</html>