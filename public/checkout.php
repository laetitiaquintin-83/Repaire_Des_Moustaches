<?php

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../config/stripe.php';
    require_once __DIR__ . '/../vendor/autoload.php';

    $pdo = getPDO();
    $message = '';
    $error = '';

    // Gestion CSRF
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    $csrf_token = $_SESSION['csrf_token'];

    // Panier vide -> retour boutique/panier
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) {
        header('Location: cart.php');
        exit;
    }

    // Utilisateur connecté
    $user = null;
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare('SELECT id, prenom, nom, email FROM utilisateurs WHERE id = ?');
        $stmt->execute([(int)$_SESSION['user_id']]);
        $user = $stmt->fetch();
    }

    // Soumission du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $csrf_check = $_POST['csrf_token'] ?? '';
        
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_check)) {
            $error = 'Erreur de sécurité : token CSRF invalide. Veuillez réessayer.';
        } else {
            $prenom = trim($_POST['prenom'] ?? '');
            $nom = trim($_POST['nom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $adresse = trim($_POST['adresse'] ?? '');
            $code_postal = trim($_POST['code_postal'] ?? '');
            $ville = trim($_POST['ville'] ?? '');

            if (!$prenom || !$nom || !$email || !$adresse || !$code_postal || !$ville) {
                $error = 'Tous les champs sont obligatoires.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Adresse email invalide.';
            } elseif (mb_strlen($prenom) < 2 || mb_strlen($nom) < 2) {
                $error = 'Le nom et le prénom doivent faire au moins 2 caractères.';
            } elseif (mb_strlen($adresse) < 5) {
                $error = 'L\'adresse doit faire au moins 5 caractères.';
            } elseif (!preg_match('/^[0-9]{5}$/', $code_postal)) {
                $error = 'Le code postal doit contenir 5 chiffres.';
            } elseif (mb_strlen($ville) < 2) {
                $error = 'La ville doit faire au moins 2 caractères.';
            } else {
                $utilisateur_id = $_SESSION['user_id'] ?? null;

                if (!$utilisateur_id) {
                    $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE email = ?');
                    $stmt->execute([$email]);
                    $existing = $stmt->fetch();

                    if ($existing) {
                        $utilisateur_id = (int)$existing['id'];
                    } else {
                        $mot_de_passe = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare('
                            INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, date_inscription)
                            VALUES (?, ?, ?, ?, NOW())
                        ');
                        $stmt->execute([$nom, $prenom, $email, $mot_de_passe]);
                        $utilisateur_id = (int)$pdo->lastInsertId();
                    }
                }

                $total = 0;
                foreach ($cart as $item) {
                    $total += (float)$item['prix'] * (int)$item['quantite'];
                }

                $stmt = $pdo->prepare('
                    INSERT INTO commandes (utilisateur_id, total, statut)
                    VALUES (?, ?, "panier")
                ');
                $stmt->execute([$utilisateur_id, $total]);
                $commande_id = (int)$pdo->lastInsertId();

                foreach ($cart as $produit_id => $item) {
                    $clean_id = (int) preg_replace('/[^0-9]/', '', (string) $produit_id);

                    $stmt = $pdo->prepare('
                        INSERT INTO lignes_commandes (commande_id, produit_id, quantite, prix_unitaire)
                        VALUES (?, ?, ?, ?)
                    ');
                    $stmt->execute([
                        $commande_id,
                        $clean_id,
                        (int)$item['quantite'],
                        (float)$item['prix']
                    ]);
                }

                // Stripe Checkout
                \Stripe\Stripe::setApiKey(getStripeSecretKey());

                $line_items = [];
                foreach ($cart as $item) {
                    $line_items[] = [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => $item['nom'],
                            ],
                            'unit_amount' => (int)round((float)$item['prix'] * 100),
                        ],
                        'quantity' => (int)$item['quantite'],
                    ];
                }

                $httpHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $siteProtocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
                $baseUrl = $siteProtocol . '://' . $httpHost;

                $checkout_session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => $line_items,
                    'mode' => 'payment',
                    'customer_email' => $email,
                    'metadata' => ['commande_id' => $commande_id],
                    'success_url' => $baseUrl . '/success.php?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => $baseUrl . '/checkout.php',
                ]);

                header("HTTP/1.1 303 See Other");
                header("Location: " . $checkout_session->url);
                exit;
            }
        }
    }

    $total_price = 0;
    foreach ($cart as $item) {
        $total_price += (float)$item['prix'] * (int)$item['quantite'];
    }

} catch (Throwable $e) {
    // Détails de l'erreur dans les logs uniquement — jamais exposés à l'utilisateur
    error_log('[checkout.php] Erreur : ' . $e->getMessage() . ' (' . $e->getFile() . ':' . $e->getLine() . ')');

    // Valeurs par défaut pour que la page puisse se rendre même en cas d'erreur précoce
    $cart = $_SESSION['cart'] ?? [];
    $total_price = 0;
    $csrf_token = $_SESSION['csrf_token'] ?? '';
    $user = null;
    $error = 'Une erreur est survenue lors du traitement de votre commande. Veuillez réessayer.';
    // (l'erreur est affichée via le bloc $error existant dans le HTML)
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande - Le Repaire des Moustaches</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .checkout-container { max-width: 900px; margin: 40px auto; padding: 20px; }
        .checkout-header { text-align: center; margin-bottom: 40px; }
        .checkout-header h1 { font-size: 2.5rem; color: #2B2B2B; margin-bottom: 10px; }
        .checkout-grid { display: grid; grid-template-columns: 1fr 350px; gap: 30px; }
        .checkout-form { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 30px; }
        .form-section { margin-bottom: 30px; }
        .form-section h3 { color: #2B2B2B; font-size: 1.1rem; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #85D6CD; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #2B2B2B; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; font-size: 14px; box-sizing: border-box; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .checkout-resume { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 20px; height: fit-content; }
        .resume-title { font-weight: 600; color: #2B2B2B; margin-bottom: 15px; font-size: 1.1rem; border-bottom: 2px solid #85D6CD; padding-bottom: 10px; }
        .resume-item { display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #eee; font-size: 13px; }
        .resume-total { font-size: 1.3rem; font-weight: 700; color: #2B2B2B; border-top: 2px solid #eee; padding-top: 12px; margin-top: 12px; display: flex; justify-content: space-between; }
        .btn { display: block; width: 100%; padding: 14px; margin-top: 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; text-align: center; text-decoration: none; box-sizing: border-box; }
        .btn-primary { background: #85D6CD; color: white; }
        .btn-secondary { background: #ddd; color: #2B2B2B; margin-top: 10px; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 12px; border-radius: 4px; margin-bottom: 20px; }
        @media (max-width: 768px) { .checkout-grid { grid-template-columns: 1fr; } .form-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="checkout-container">
        <div class="checkout-header">
            <h1>📋 Finaliser la commande</h1>
        </div>
        
        <?php if ($error): ?>
            <div class="alert-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <div class="checkout-grid">
            <form method="POST" class="checkout-form">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars((string)$csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="form-section">
                    <h3>Informations personnelles</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="prenom">Prénom *</label>
                            <input type="text" id="prenom" name="prenom" required value="<?php echo htmlspecialchars($_POST['prenom'] ?? ($user['prenom'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="nom">Nom *</label>
                            <input type="text" id="nom" name="nom" required value="<?php echo htmlspecialchars($_POST['nom'] ?? ($user['nom'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ($user['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Adresse de livraison</h3>
                    <div class="form-group">
                        <label for="adresse">Adresse *</label>
                        <input type="text" id="adresse" name="adresse" required value="<?php echo htmlspecialchars($_POST['adresse'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="code_postal">Code postal *</label>
                            <input type="text" id="code_postal" name="code_postal" required value="<?php echo htmlspecialchars($_POST['code_postal'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="ville">Ville *</label>
                            <input type="text" id="ville" name="ville" required value="<?php echo htmlspecialchars($_POST['ville'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">💳 Procéder au paiement</button>
                <a href="cart.php" class="btn btn-secondary">Retour au panier</a>
            </form>

            <div class="checkout-resume">
                <div class="resume-title">Résumé du panier</div>
                <?php foreach ($cart as $item): ?>
                    <div class="resume-item">
                        <div><?php echo htmlspecialchars($item['nom'], ENT_QUOTES, 'UTF-8'); ?></div>
                        <div>x<?php echo (int)$item['quantite']; ?></div>
                        <div><?php echo number_format((float)$item['prix'] * (int)$item['quantite'], 2, ',', ' '); ?> €</div>
                    </div>
                <?php endforeach; ?>
                <div class="resume-total">
                    <span>Total:</span>
                    <span><?php echo number_format((float)$total_price, 2, ',', ' '); ?> €</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>