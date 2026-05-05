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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - Le Repaire des Moustaches</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f5f5f5;
        }
        
        .panier-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .panier-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .panier-header h1 {
            font-size: 2.5rem;
            color: #2B2B2B;
            margin-bottom: 10px;
        }
        
        .panier-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .panier-items {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
        }
        
        .panier-vide {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        
        .panier-vide h2 {
            color: #2B2B2B;
            margin-bottom: 20px;
        }
        
        .panier-vide a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: #85D6CD;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .panier-vide a:hover {
            background: #6bc3b8;
        }
        
        .panier-item {
            display: grid;
            grid-template-columns: 1fr auto auto auto;
            gap: 15px;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .panier-item:last-child {
            border-bottom: none;
        }
        
        .item-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .item-nom {
            font-weight: 600;
            color: #2B2B2B;
        }
        
        .item-prix {
            font-size: 14px;
            color: #666;
        }
        
        .item-quantite {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .item-quantite input {
            width: 50px;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }
        
        .item-quantite button {
            padding: 6px 12px;
            border: none;
            background: #85D6CD;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .item-quantite button:hover {
            background: #6bc3b8;
        }
        
        .item-total {
            font-weight: 600;
            color: #2B2B2B;
            min-width: 80px;
            text-align: right;
        }
        
        .item-remove {
            background: #FE7B7E;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .item-remove:hover {
            background: #e66769;
        }
        
        .panier-resume {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        .resume-title {
            font-weight: 600;
            color: #2B2B2B;
            margin-bottom: 20px;
            font-size: 1.1rem;
            border-bottom: 2px solid #85D6CD;
            padding-bottom: 10px;
        }
        
        .resume-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            color: #666;
        }
        
        .resume-total {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2B2B2B;
            border-top: 2px solid #eee;
            padding-top: 12px;
            margin-top: 12px;
        }
        
        .resume-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            font-size: 14px;
        }
        
        .btn-checkout {
            background: #85D6CD;
            color: white;
        }
        
        .btn-checkout:hover {
            background: #6bc3b8;
        }
        
        .btn-continue {
            background: #ddd;
            color: #2B2B2B;
        }
        
        .btn-continue:hover {
            background: #ccc;
        }
        
        .btn-clear {
            background: #FE7B7E;
            color: white;
            font-size: 12px;
            padding: 8px 12px;
        }
        
        .btn-clear:hover {
            background: #e66769;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        @media (max-width: 768px) {
            .panier-grid {
                grid-template-columns: 1fr;
            }
            
            .panier-resume {
                position: static;
            }
            
            .panier-item {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .item-quantite,
            .item-total,
            .item-remove {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header style="background: white; border-bottom: 2px solid #85D6CD; position: sticky; top: 0; z-index: 100;">
        <nav style="max-width: 1200px; margin: 0 auto; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
            <a href="../index.html" style="font-family: 'Pacifico', cursive; font-size: 1.5rem; color: #2B2B2B; text-decoration: none; font-weight: bold;">🧔 Repaire</a>
            <div style="display: flex; gap: 20px; align-items: center;">
                <a href="../index.html">Accueil</a>
                <a href="../public/boutique.php">Boutique</a>
                <a href="cart.php" style="color: #85D6CD; font-weight: 600; border-bottom: 2px solid #85D6CD; padding-bottom: 3px;">🛒 Panier</a>
            </div>
        </nav>
    </header>

    <div class="panier-container">
        <div class="panier-header">
            <h1>🛒 Mon Panier</h1>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (empty($cart)): ?>
            <div class="panier-items">
                <div class="panier-vide">
                    <h2>Votre panier est vide</h2>
                    <p>Découvrez nos produits et remplissez votre panier !</p>
                    <a href="boutique.php">Continuer les achats</a>
                </div>
            </div>
        <?php else: ?>
            <div class="panier-grid">
                <div class="panier-items">
                    <?php foreach ($cart as $produit_id => $item): ?>
                        <div class="panier-item">
                            <div class="item-info">
                                <div class="item-nom"><?php echo htmlspecialchars($item['nom']); ?></div>
                                <div class="item-prix"><?php echo number_format((float)$item['prix'], 2, ',', ' '); ?> €</div>
                            </div>
                            <form method="POST" class="item-quantite" style="display: contents;">
                                <input type="hidden" name="action" value="update_quantity">
                                <input type="hidden" name="produit_id" value="<?php echo $produit_id; ?>">
                                <input type="number" name="quantite" value="<?php echo $item['quantite']; ?>" min="1" max="100">
                                <button type="submit">✓</button>
                            </form>
                            <div class="item-total"><?php echo number_format((float)$item['prix'] * (int)$item['quantite'], 2, ',', ' '); ?> €</div>
                            <form method="POST" style="display: contents;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="produit_id" value="<?php echo $produit_id; ?>">
                                <button type="submit" class="item-remove">🗑️</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="panier-resume">
                    <div class="resume-title">Résumé</div>
                    
                    <div class="resume-row">
                        <span>Articles:</span>
                        <span><?php echo $total_items; ?></span>
                    </div>
                    
                    <div class="resume-row">
                        <span>Sous-total:</span>
                        <span><?php echo number_format($total_price, 2, ',', ' '); ?> €</span>
                    </div>
                    
                    <div class="resume-total">
                        Total: <?php echo number_format($total_price, 2, ',', ' '); ?> €
                    </div>
                    
                    <div class="resume-actions">
                        <a href="checkout.php" class="btn btn-checkout">Passer la commande</a>
                        <a href="boutique.php" class="btn btn-continue">Continuer les achats</a>
                        <form method="POST" onsubmit="return confirm('Vider complètement le panier ?');">
                            <input type="hidden" name="action" value="clear">
                            <button type="submit" class="btn btn-clear" style="width: 100%;">Vider le panier</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer style="background: #2B2B2B; color: white; padding: 30px; text-align: center; margin-top: 60px;">
        <p>&copy; 2024 Le Repaire des Moustaches. Tous droits réservés.</p>
    </footer>
</body>
</html>
