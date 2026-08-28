<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/stripe.php';
require_once __DIR__ . '/../vendor/autoload.php';

$pdo = getPDO();
$session_id = $_GET['session_id'] ?? null;
$commande = null;

if ($session_id) {
    try {
        // Initialisation de Stripe pour vérifier la session
        \Stripe\Stripe::setApiKey(getStripeSecretKey());
        $session = \Stripe\Checkout\Session::retrieve($session_id);

        if ($session && isset($session->metadata->commande_id)) {
            $commande_id = (int)$session->metadata->commande_id;

            // Mise à jour du statut de la commande en "payee"
            $stmt = $pdo->prepare('UPDATE commandes SET statut = "payee" WHERE id = ?');
            $stmt->execute([$commande_id]);

            // Récupération des infos de la commande
            $stmt = $pdo->prepare('SELECT * FROM commandes WHERE id = ?');
            $stmt->execute([$commande_id]);
            $commande = $stmt->fetch();

            // Vider le panier après paiement réussi
            unset($_SESSION['cart']);
        }
    } catch (\Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande confirmée - Le Repaire des Moustaches</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; background: #f5f5f5; margin: 0; padding: 40px 20px; }
        .success-card { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; padding: 40px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .icon { font-size: 60px; color: #85D6CD; margin-bottom: 20px; }
        h1 { color: #2B2B2B; font-size: 2rem; margin-bottom: 10px; }
        p { color: #666; font-size: 1.1rem; line-height: 1.6; }
        .order-info { background: #f0f8f7; border-radius: 6px; padding: 20px; margin: 25px 0; text-align: left; }
        .btn { display: inline-block; padding: 12px 25px; background: #85D6CD; color: white; border-radius: 4px; text-decoration: none; font-weight: bold; margin-top: 10px; }
        .btn:hover { background: #6bc3b8; }
    </style>
</head>
<body>
    <div class="success-card">
        <div class="icon">🎉</div>
        <h1>Merci pour votre commande !</h1>
        <p>Le paiement a été validé avec succès. Vos petits compagnons vont être ravis !</p>

        <?php if ($commande): ?>
            <div class="order-info">
                <strong>Commande N° :</strong> #<?php echo htmlspecialchars($commande['id']); ?><br>
                <strong>Montant total :</strong> <?php echo number_format((float)$commande['total'], 2, ',', ' '); ?> €<br>
                <strong>Statut :</strong> Payée (en cours d'expédition)
            </div>
        <?php endif; ?>

        <a href="boutique.php" class="btn">Retourner à la boutique</a>
    </div>
</body>
</html>