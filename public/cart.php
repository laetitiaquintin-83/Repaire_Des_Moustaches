<?php
declare(strict_types=1);

// Force l'encodage UTF-8 au niveau du serveur
header('Content-Type: text/html; charset=utf-8');

$sitePrefix = '';
session_start();

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();
$message = '';
$error = '';

// Initialisation du panier en session si non existant
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Génération du token CSRF pour les formulaires
$csrf_token = generateCSRFToken();

// ---------------------------------------------------------------------
// 1. GESTION DE L'AJOUT AU PANIER VIA URL (GET) Ex: ?action=add&type=adhesion
// ---------------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'add') {
    $type = trim((string) ($_GET['type'] ?? 'produit'));

    if ($type === 'adhesion') {
        // Clé unique pour l'adhésion dans la session
        $cartKey = 'adhesion_club_moustaches';

        // Si l'adhésion n'est pas encore dans le panier
        if (!isset($_SESSION['cart'][$cartKey])) {
            $_SESSION['cart'][$cartKey] = [
                'id'       => 'adhesion',
                'nom'      => 'Adhésion - Club des Moustaches',
                'prix'     => 5.00,
                'quantite' => 1,
                'type'     => 'adhesion'
            ];
            $message = 'Carte d\'adhésion ajoutée à votre panier ! 🐾';
        } else {
            $message = 'La carte d\'adhésion est déjà dans votre panier.';
        }
    } elseif (isset($_GET['id'])) {
        // Traitement classique pour un produit physique par son ID BDD
        $produit_id = (int)$_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
        $stmt->execute([$produit_id]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($produit) {
            $cartKey = 'prod_' . $produit_id;
            if (isset($_SESSION['cart'][$cartKey])) {
                $_SESSION['cart'][$cartKey]['quantite']++;
            } else {
                $_SESSION['cart'][$cartKey] = [
                    'id'       => $produit['id'],
                    'nom'      => $produit['nom'],
                    'prix'     => (float)$produit['prix'],
                    'quantite' => 1,
                    'type'     => 'produit'
                ];
            }
            $message = 'Produit ajouté au panier !';
        }
    }
}

// ---------------------------------------------------------------------
// 2. TRAITER LES ACTIONS DU PANIER (POST)
// ---------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification CSRF
    $csrf_check = trim((string) ($_POST['csrf_token'] ?? ''));
    if (!validateCSRFToken($csrf_check)) {
        die('Erreur de sécurité : token CSRF invalide');
    }

    if (isset($_POST['action'])) {
        $action = trim((string) $_POST['action']);
        
        if ($action === 'remove') {
            $produit_id = trim((string) ($_POST['produit_id'] ?? ''));
            if (isset($_SESSION['cart'][$produit_id])) {
                unset($_SESSION['cart'][$produit_id]);
                $message = 'Article retiré du panier';
            }
        } elseif ($action === 'update_quantity') {
            $produit_id = trim((string) ($_POST['produit_id'] ?? ''));
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
$total_items = array_sum(array_column($cart, 'quantite'));
$total_price = 0;

foreach ($cart as $item) {
    $total_price += (float)$item['prix'] * (int)$item['quantite'];
}

// Fonction pour mapper les noms de produits à leurs images
function getImagePath(string $productName): string
{
    $imageMap = [
        'Adhésion - Club des Moustaches' => 'images/club-moustaches.png',
        'Milkshake Fraise'              => 'images/produits/milkshake-fraise.jpg',
        'Burger Veggie Moustache'       => 'images/produits/burger-veggie.jpg',
        'Mug Diner'                     => 'images/produits/mug-diner.jpg',
        'Tablier Vintage'               => 'images/produits/tablier-vintage.jpg',
        'Pins Emailles'                 => 'images/produits/pins-emailles.jpg',
        'Tote Bag Solidaire'            => 'images/produits/tote-bag.jpg',
        'Jouets Catnip Deluxe'          => 'images/produits/jouets-catnip.jpg',
        'Planches de Stickers Retro'    => 'images/produits/stickers-retro.jpg',
        'Cartes Postales Polaroid'      => 'images/produits/cartes-postales.jpg',
        'Badge Solidaire'               => 'images/produits/badge-solidaire.jpg',
    ];
    
    return $imageMap[$productName] ?? 'images/placeholder.jpg';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - Le Repaire des Moustaches</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;0,700;1,400&family=Pacifico&display=swap" rel="stylesheet">
    <style>
        :root {
            --coral: #FE7B7E;
            --coral-hover: #e06568;
            --menthe: #85D6CD;
            --menthe-light: #f0faf9;
            --gris-texte: #4A4A4A;
            --gris-leger: #F8F9FA;
            --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .notif-message {
            background-color: var(--menthe);
            color: var(--gris-texte);
            padding: 15px;
            border-radius: 10px;
            display: inline-block;
            font-weight: bold;
            box-shadow: var(--shadow-soft);
        }

        .panier-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            font-family: 'Montserrat', sans-serif;
            color: var(--gris-texte);
        }

        .panier-title {
            font-family: 'Pacifico', cursive;
            color: var(--coral);
            font-size: 2.5rem;
            margin-bottom: 30px;
            text-align: left;
        }

        .panier-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }

        @media (min-width: 992px) {
            .panier-grid {
                grid-template-columns: 2fr 1fr;
                align-items: start;
            }
        }

        .panier-items {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .carte-panier-moderne {
            display: flex;
            align-items: center;
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: var(--shadow-soft);
            gap: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .carte-panier-moderne:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
        }

        .panier-img-container-moderne {
            width: 100px;
            height: 100px;
            border-radius: 12px;
            overflow: hidden;
            background: var(--gris-leger);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .panier-img-container-moderne img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .panier-info-moderne {
            flex: 1;
        }

        .panier-info-moderne h3 {
            font-size: 1.15rem;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .panier-prix-unitaire {
            color: var(--coral);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .panier-quantite-moderne {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--gris-leger);
            padding: 5px 10px;
            border-radius: 30px;
            border: 1px solid #EFEFEF;
        }

        .input-quantite-moderne {
            width: 50px;
            border: none;
            background: transparent;
            text-align: center;
            font-weight: 700;
            font-size: 1rem;
            outline: none;
        }

        .btn-quantite-moderne {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            color: var(--menthe);
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }

        .btn-quantite-moderne:hover {
            transform: scale(1.2);
        }

        .panier-prix-total-moderne {
            font-weight: 700;
            font-size: 1.15rem;
            min-width: 90px;
            text-align: right;
        }

        .btn-supprimer-moderne {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.3rem;
            color: #C1C1C1;
            transition: color 0.2s, transform 0.2s;
        }

        .btn-supprimer-moderne:hover {
            color: var(--coral);
            transform: scale(1.1);
        }

        .summary-card {
            background: white;
            border-radius: 18px;
            padding: 30px;
            box-shadow: var(--shadow-soft);
            border-top: 5px solid var(--menthe);
        }

        .summary-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 25px;
            border-bottom: 2px solid var(--gris-leger);
            padding-bottom: 15px;
            font-family: 'Montserrat', sans-serif !important;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }

        .summary-line.total {
            border-top: 2px solid var(--gris-leger);
            padding-top: 15px;
            margin-top: 15px;
            font-size: 1.3rem;
            font-weight: 800;
        }

        .summary-line.total .price {
            color: var(--coral);
        }

        .btn-checkout {
            display: block;
            width: 100%;
            background-color: var(--coral);
            color: white !important;
            text-align: center;
            padding: 16px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 6px 20px rgba(254, 123, 126, 0.3);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-sizing: border-box;
            margin-top: 25px;
        }

        .btn-checkout:hover {
            background-color: var(--coral-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(254, 123, 126, 0.4);
        }

        .btn-continue {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: var(--menthe);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .btn-continue:hover {
            color: #5bb3a9;
            text-decoration: underline;
        }

        .btn-empty {
            display: block;
            text-align: center;
            margin-top: 25px;
            background: none;
            border: none;
            color: #999;
            font-size: 0.85rem;
            cursor: pointer;
            width: 100%;
            transition: color 0.2s;
        }

        .btn-empty:hover {
            color: var(--coral);
            text-decoration: underline;
        }

        .panier-vide {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 18px;
            box-shadow: var(--shadow-soft);
            max-width: 600px;
            margin: 40px auto;
        }

        .panier-vide h2 {
            font-family: 'Pacifico', cursive;
            color: var(--coral);
            font-size: 2rem;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    
    <header>
        <a href="index.php" class="logo"><img src="images/logo.png" alt="Logo du Repaire des Moustaches"></a>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="concept.php">Le Concept</a></li>
                <li><a href="belles-histoires.php">Histoires</a></li>
                <li><a href="boutique.php">Boutique</a></li>
                <li><a href="cart.php">🛒 Panier</a></li>
            </ul>
        </nav>
        <div class="action">
            <a href="formulaire.php" class="bouton-reserver">Réserver</a>
            <a href="login.php" class="btn-admin-lock" title="Accès administrateur">🔒</a>
        </div>
    </header>

    <div style="padding: 20px; text-align: center; min-height: 40px;">
        <?php if ($message): ?>
            <div class="notif-message">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
    </div>

    <main class="panier-container">
        
        <?php if (empty($cart)): ?>
            <div class="panier-vide">
                <h2>Votre panier est vide 😿</h2>
                <p style="margin-bottom: 30px; color: #777;">Découvrez nos produits et remplissez votre panier avec des douceurs !</p>
                <a href="boutique.php" class="btn-checkout" style="display: inline-block; width: auto; padding: 12px 40px;">Continuer les achats</a>
            </div>
            
        <?php else: ?>
            <h1 class="panier-title">🛒 Mon Panier</h1>

            <div class="panier-grid">
                
                <section class="panier-items">
                    <?php foreach ($cart as $key => $item): ?>
                        <div class="carte-panier-moderne">
                            <div class="panier-img-container-moderne">
                                <img src="<?php echo htmlspecialchars(getImagePath((string) $item['nom']), ENT_QUOTES, 'UTF-8'); ?>" 
                                     alt="<?php echo htmlspecialchars($item['nom'], ENT_QUOTES, 'UTF-8'); ?>" 
                                     onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\'><rect width=\'100\' height=\'100\' fill=\'%23FFF8E7\'/><text x=\'50\' y=\'50\' font-family=\'sans-serif\' font-size=\'30\' text-anchor=\'middle\' dominant-baseline=\'middle\'>🐾</text></svg>'">
                            </div>
                            
                            <div class="panier-info-moderne">
                                <h3><?php echo htmlspecialchars($item['nom'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <div class="panier-prix-unitaire"><?php echo number_format((float)$item['prix'], 2, ',', ' '); ?> €</div>
                            </div>
                            
                            <form method="POST" class="panier-quantite-moderne">
                                <input type="hidden" name="action" value="update_quantity">
                                <input type="hidden" name="produit_id" value="<?php echo htmlspecialchars((string)$key, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <input type="number" name="quantite" class="input-quantite-moderne" value="<?php echo $item['quantite']; ?>" min="1" max="100">
                                <button type="submit" class="btn-quantite-moderne" title="Mettre à jour">🔄</button>
                            </form>
                            
                            <div class="panier-prix-total-moderne">
                                <?php echo number_format((float)$item['prix'] * (int)$item['quantite'], 2, ',', ' '); ?> €
                            </div>
                            
                            <form method="POST" style="margin: 0;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="produit_id" value="<?php echo htmlspecialchars((string)$key, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <button type="submit" class="btn-supprimer-moderne" title="Retirer l'article">🗑️</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </section>

                <aside class="summary-card">
                    <h3 class="summary-title">Résumé</h3>
                    
                    <div class="summary-line">
                        <span>Articles :</span>
                        <span style="font-weight: 600;"><?php echo $total_items; ?></span>
                    </div>
                    
                    <div class="summary-line">
                        <span>Sous-total :</span>
                        <span><?php echo number_format((float)$total_price, 2, ',', ' '); ?> €</span>
                    </div>

                    <div class="summary-line">
                        <span>Frais de port :</span>
                        <span style="color: #2ecc71; font-weight: 600;">Offerts ✨</span>
                    </div>
                    
                    <div class="summary-line total">
                        <span>Total :</span>
                        <span class="price"><?php echo number_format((float)$total_price, 2, ',', ' '); ?> €</span>
                    </div>
                    
                    <a href="checkout.php" class="btn-checkout">💳 Passer la commande</a>
                    <a href="boutique.php" class="btn-continue">← Continuer mes achats</a>
                    
                    <form method="POST" onsubmit="return confirm('Voulez-vous vraiment vider complètement votre panier ?');" style="margin: 0;">
                        <input type="hidden" name="action" value="clear">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <button type="submit" class="btn-empty">Vider le panier</button>
                    </form>
                </aside>
                
            </div>
        <?php endif; ?>
        
    </main>

    <footer>
        <p>&copy; 2026 Le Repaire des Moustaches. Un tiers-lieu solidaire pour les chats et les humains.</p>
        <div class="reseaux-sociaux">
            <a href="#">Facebook</a> |
            <a href="#">Instagram</a> |
            <a href="login.php">Admin</a>
        </div>
    </footer>

</body>
</html>