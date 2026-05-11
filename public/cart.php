<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();
$message = '';
$error = '';

// Traiter les actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'remove') {
            $produit_id = (int)($_POST['produit_id'] ?? 0);
            if (isset($_SESSION['cart'][$produit_id])) {
                unset($_SESSION['cart'][$produit_id]);
                $message = 'Article retiré du panier';
            }
        } elseif ($action === 'update_quantity') {
            $produit_id = (int)($_POST['produit_id'] ?? 0);
            $nouvelle_quantite = (int)($_POST['quantite'] ?? 0);
            
            if (isset($_SESSION['cart'][$produit_id])) {
                if ($nouvelle_quantite <= 0) {
                    unset($_SESSION['cart'][$produit_id]);
                    $message = 'Article retiré du panier';
                } else {
                    $_SESSION['cart'][$produit_id]['quantite'] = $nouvelle_quantite;
                    $message = 'Quantité mise à jour';
                }
            }
        } elseif ($action === 'clear') {
            $_SESSION['cart'] = [];
            $message = 'Panier vidé';
        }
    }
}

// Calculer les totaux
$cart = $_SESSION['cart'] ?? [];
$total_items = count($cart);
$total_price = 0;

foreach ($cart as $item) {
    $total_price += (float)$item['prix'] * (int)$item['quantite'];
}

// Fonction pour mapper les noms de produits à leurs images
function getImagePath(string $productName): string
{
    $imageMap = [
        'Milkshake Fraise' => '../images/produits/milkshake-fraise.jpg',
        'Burger Veggie Moustache' => '../images/produits/burger-veggie.jpg',
        'Mug Diner' => '../images/produits/mug-diner.jpg',
        'Tablier Vintage' => '../images/produits/tablier-vintage.jpg',
        'Pins Emailles' => '../images/produits/pins-emailles.jpg',
        'Tote Bag Solidaire' => '../images/produits/tote-bag.jpg',
        'Jouets Catnip Deluxe' => '../images/produits/jouets-catnip.jpg',
        'Planches de Stickers Retro' => '../images/produits/stickers-retro.jpg',
        'Cartes Postales Polaroid' => '../images/produits/cartes-postales.jpg',
        'Badge Solidaire' => '../images/produits/badge-solidaire.jpg',
    ];
    
    return $imageMap[$productName] ?? '../images/placeholder.jpg';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - Le Repaire des Moustaches</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;0,700;1,400&family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
    
    <header>
        <div class="logo"><img src="../images/logo.png" alt="Logo du Repaire des Moustaches"></div>
        <nav><ul><li><a href="../index.html">Accueil</a></li><li><a href="../concept.html">Le Concept</a></li><li><a href="boutique.php">Boutique</a></li><li><a href="cart.php">🛒 Panier</a></li></ul></nav>
        <div class="action"><a href="../formulaire.html" class="bouton-reserver">Réserver</a></div>
    </header>

    <div style="padding: 20px; text-align: center;">
        <?php if ($message): ?>
            <div style="background-color: var(--vert-menthe); color: var(--gris-fonce); padding: 15px; border-radius: 10px; display: inline-block; font-weight: bold;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
    </div>

    <main class="panier-section">
        
        <?php if (empty($cart)): ?>
            <div class="panier-vide">
                <h2>Votre panier est vide</h2>
                <p style="margin-bottom: 30px;">Découvrez nos produits et remplissez votre panier avec des douceurs !</p>
                <a href="boutique.php" class="bouton-principal">Continuer les achats</a>
            </div>
            
        <?php else: ?>
            <div class="panier-articles">
                <h1 class="page-title" style="text-align: left; font-size: 2.5rem;">🛒 Mon Panier</h1>
                
                <?php foreach ($cart as $produit_id => $item): ?>
                    <div class="carte-panier">
                        <div class="panier-img-container">
                            <img src="<?php echo htmlspecialchars(getImagePath((string) $item['nom']), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['nom']); ?>" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\'><rect width=\'100\' height=\'100\' fill=\'%23FFF8E7\'/><text x=\'50\' y=\'50\' font-family=\'sans-serif\' font-size=\'30\' text-anchor=\'middle\' dominant-baseline=\'middle\'>🛍️</text></svg>'">
                        </div>
                        
                        <div class="panier-info">
                            <h3><?php echo htmlspecialchars($item['nom']); ?></h3>
                            <div class="panier-prix-unitaire"><?php echo number_format((float)$item['prix'], 2, ',', ' '); ?> €</div>
                        </div>
                        
                        <form method="POST" class="panier-quantite">
                            <input type="hidden" name="action" value="update_quantity">
                            <input type="hidden" name="produit_id" value="<?php echo $produit_id; ?>">
                            <input type="number" name="quantite" class="input-quantite" value="<?php echo $item['quantite']; ?>" min="1" max="100">
                            <button type="submit" class="btn-quantite" title="Mettre à jour">↻</button>
                        </form>
                        
                        <div class="panier-prix-total">
                            <?php echo number_format((float)$item['prix'] * (int)$item['quantite'], 2, ',', ' '); ?> €
                        </div>
                        
                        <form method="POST" style="margin: 0;">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="produit_id" value="<?php echo $produit_id; ?>">
                            <button type="submit" class="btn-supprimer" title="Retirer l'article">🗑️</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <aside class="panier-resume">
                <h3>Résumé</h3>
                
                <div class="resume-ligne">
                    <span>Articles :</span>
                    <span><?php echo $total_items; ?></span>
                </div>
                
                <div class="resume-ligne">
                    <span>Sous-total :</span>
                    <span><?php echo number_format((float)$total_price, 2, ',', ' '); ?> €</span>
                </div>
                
                <div class="resume-total">
                    <span>Total :</span>
                    <span><?php echo number_format((float)$total_price, 2, ',', ' '); ?> €</span>
                </div>
                
                <a href="checkout.php" class="bouton-checkout">Passer la commande</a>
                <a href="boutique.php" class="lien-continuer">Continuer les achats</a>
                
                <form method="POST" onsubmit="return confirm('Vider complètement le panier ?');" style="margin-top: 20px;">
                    <input type="hidden" name="action" value="clear">
                    <button type="submit" class="lien-continuer" style="width: 100%; border: none; background: transparent; cursor: pointer; color: var(--rose-corail);">Vider le panier</button>
                </form>
            </aside>
        <?php endif; ?>
        
    </main>

    <footer>
        <p>Le Repaire des Moustaches - Le bar à chats le plus cosy de la ville.</p>
        <p>&copy; 2024 Tous droits réservés.</p>
        <div class="reseaux-sociaux">
            <a href="#">Instagram</a>
            <a href="#">Facebook</a>
            <a href="#">TikTok</a>
        </div>
    </footer>

</body>
</html>