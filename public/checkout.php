<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();
$message = '';
$error = '';
$csrf_token = generateCSRFToken();

// Vérifier que le panier n'est pas vide
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

// Récupérer les informations de l'utilisateur connecté si applicable
$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare('SELECT id, prenom, nom, email FROM utilisateurs WHERE id = ?');
    $stmt->execute([(int)$_SESSION['user_id']]);
    $user = $stmt->fetch();
}

// Traiter le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Valider le CSRF token
    $csrf_check = $_POST['csrf_token'] ?? '';
    if (!validateCSRFToken($csrf_check)) {
        $error = 'Erreur de sécurité : token CSRF invalide. Veuillez réessayer.';
    } else {
        // Récupération brute (pas d'échappement HTML ici)
        $prenom = trim($_POST['prenom'] ?? '');
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $adresse = trim($_POST['adresse'] ?? '');
        $code_postal = trim($_POST['code_postal'] ?? '');
        $ville = trim($_POST['ville'] ?? '');

        // ============================================================
        // 🔍 VALIDATIONS MÉTIER RENFORCÉES
        // ============================================================

        // 1. Champs obligatoires
        if (!$prenom || !$nom || !$email || !$adresse || !$code_postal || !$ville) {
            $error = 'Tous les champs sont obligatoires.';
        }
        // 2. Email valide
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Adresse email invalide.';
        }
        // 3. Prénom (au moins 2 caractères, lettres et accents autorisés)
        elseif (strlen($prenom) < 2 || !preg_match('/^[a-zA-ZÀ-ÿ\s\-]+$/', $prenom)) {
            $error = 'Le prénom doit comporter au moins 2 caractères et ne contenir que des lettres, des espaces ou des tirets.';
        }
        // 4. Nom (au moins 2 caractères, lettres et accents autorisés)
        elseif (strlen($nom) < 2 || !preg_match('/^[a-zA-ZÀ-ÿ\s\-]+$/', $nom)) {
            $error = 'Le nom doit comporter au moins 2 caractères et ne contenir que des lettres, des espaces ou des tirets.';
        }
        // 5. Adresse (au moins 5 caractères, pas de restriction stricte)
        elseif (strlen($adresse) < 5) {
            $error = 'L\'adresse doit comporter au moins 5 caractères.';
        }
        // 6. Code postal français (5 chiffres uniquement)
        elseif (!preg_match('/^[0-9]{5}$/', $code_postal)) {
            $error = 'Le code postal doit être composé de 5 chiffres (ex: 83000).';
        }
        // 7. Ville (au moins 2 caractères, lettres, espaces, tirets, accents)
        elseif (strlen($ville) < 2 || !preg_match('/^[a-zA-ZÀ-ÿ\s\-]+$/', $ville)) {
            $error = 'La ville doit comporter au moins 2 caractères et ne contenir que des lettres, des espaces ou des tirets.';
        }
        // ✅ Toutes les validations sont passées
        else {
            try {
                // Déterminer l'utilisateur_id
                $utilisateur_id = $_SESSION['user_id'] ?? null;

                if (!$utilisateur_id) {
                    // Vérifier si l'email existe déjà
                    $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE email = ?');
                    $stmt->execute([$email]);
                    $existing = $stmt->fetch();

                    if ($existing) {
                        $utilisateur_id = (int)$existing['id'];
                    } else {
                        // Créer un nouvel utilisateur avec mot de passe aléatoire
                        $mot_de_passe = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare('
                            INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, date_inscription)
                            VALUES (?, ?, ?, ?, NOW())
                        ');
                        $stmt->execute([$nom, $prenom, $email, $mot_de_passe]);
                        $utilisateur_id = (int)$pdo->lastInsertId();
                    }
                }

                // Calcul du total
                $total = 0;
                foreach ($cart as $item) {
                    $total += (float)$item['prix'] * (int)$item['quantite'];
                }

                // Insérer la commande
                $stmt = $pdo->prepare('
                    INSERT INTO commandes (utilisateur_id, date_commande, montant_total, statut)
                    VALUES (?, NOW(), ?, "en_attente")
                ');
                $stmt->execute([$utilisateur_id, $total]);
                $commande_id = (int)$pdo->lastInsertId();

                // Insérer les lignes de commande
                foreach ($cart as $produit_id => $item) {
                    $stmt = $pdo->prepare('
                        INSERT INTO lignes_commandes (commande_id, produit_id, quantite, prix_unitaire)
                        VALUES (?, ?, ?, ?)
                    ');
                    $stmt->execute([
                        $commande_id,
                        $produit_id,
                        (int)$item['quantite'],
                        (float)$item['prix']
                    ]);
                }

                // Vider le panier
                unset($_SESSION['cart']);

                // Redirection vers la confirmation
                header('Location: confirmation.php?commande_id=' . $commande_id);
                exit;

            } catch (PDOException $e) {
                // Log de l'erreur (optionnel)
                error_log('Erreur checkout : ' . $e->getMessage());
                $error = 'Une erreur est survenue lors de la création de la commande. Veuillez réessayer.';
            }
        }
    }
}

// Recalcul du total pour l'affichage
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
    <title>Commande - Le Repaire des Moustaches</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f5f5f5;
        }
        
        .checkout-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .checkout-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .checkout-header h1 {
            font-size: 2.5rem;
            color: #2B2B2B;
            margin-bottom: 10px;
        }
        
        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
        }
        
        .checkout-form {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .form-section h3 {
            color: #2B2B2B;
            font-size: 1.1rem;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #85D6CD;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #2B2B2B;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            font-size: 14px;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #85D6CD;
            box-shadow: 0 0 0 3px rgba(133, 214, 205, 0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .checkout-resume {
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
            margin-bottom: 15px;
            font-size: 1.1rem;
            border-bottom: 2px solid #85D6CD;
            padding-bottom: 10px;
        }
        
        .resume-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        
        .resume-item:last-child {
            border-bottom: none;
        }
        
        .resume-item-name {
            flex: 1;
            color: #666;
        }
        
        .resume-item-qty {
            color: #999;
            min-width: 40px;
            text-align: right;
        }
        
        .resume-item-total {
            font-weight: 600;
            color: #2B2B2B;
            min-width: 70px;
            text-align: right;
        }
        
        .resume-total {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2B2B2B;
            border-top: 2px solid #eee;
            padding-top: 12px;
            margin-top: 12px;
            display: flex;
            justify-content: space-between;
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            margin-top: 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 16px;
        }
        
        .btn-primary {
            background: #85D6CD;
            color: white;
        }
        
        .btn-primary:hover {
            background: #6bc3b8;
        }
        
        .btn-secondary {
            background: #ddd;
            color: #2B2B2B;
            margin-top: 10px;
        }
        
        .btn-secondary:hover {
            background: #ccc;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .info-box {
            background: #f0f8f7;
            border-left: 4px solid #85D6CD;
            padding: 12px;
            border-radius: 4px;
            margin-top: 15px;
            font-size: 13px;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
            
            .checkout-resume {
                position: static;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header style="background: white; border-bottom: 2px solid #85D6CD; position: sticky; top: 0; z-index: 100;">
        <nav style="max-width: 1200px; margin: 0 auto; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
            <a href="../index.php" style="font-family: 'Pacifico', cursive; font-size: 1.5rem; color: #2B2B2B; text-decoration: none; font-weight: bold;">🧔 Repaire</a>
            <div style="display: flex; gap: 20px; align-items: center;">
                <a href="../index.php">Accueil</a>
                <a href="boutique.php">Boutique</a>
                <a href="belles-histoires.php">Histoires</a>
                <a href="cart.php" style="color: #2B2B2B;">🛒 Panier</a>
                <a href="../login.php" style="color: #85D6CD; font-weight: bold;">Admin</a>
            </div>
        </nav>
    </header>

    <div class="checkout-container">
        <div class="checkout-header">
            <h1>📦 Finaliser la commande</h1>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <div class="checkout-grid">
            <form method="POST" class="checkout-form">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-section">
                    <h3>Informations personnelles</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="prenom">Prénom *</label>
                            <input type="text" id="prenom" name="prenom" required 
                                   value="<?php echo $user ? htmlspecialchars($user['prenom'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="nom">Nom *</label>
                            <input type="text" id="nom" name="nom" required 
                                   value="<?php echo $user ? htmlspecialchars($user['nom'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo $user ? htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Adresse de livraison</h3>
                    
                    <div class="form-group">
                        <label for="adresse">Adresse *</label>
                        <input type="text" id="adresse" name="adresse" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="code_postal">Code postal *</label>
                            <input type="text" id="code_postal" name="code_postal" required>
                        </div>
                        <div class="form-group">
                            <label for="ville">Ville *</label>
                            <input type="text" id="ville" name="ville" required>
                        </div>
                    </div>
                </div>
                
                <div class="info-box">
                    💡 <strong>À savoir:</strong> En demo, le paiement ne sera pas débité. Vous recevrez un email de confirmation.
                </div>
                
                <button type="submit" class="btn btn-primary">✓ Confirmer la commande</button>
                <a href="cart.php" class="btn btn-secondary">Retour au panier</a>
            </form>

            <div class="checkout-resume">
                <div class="resume-title">Résumé du panier</div>
                
                <?php foreach ($cart as $item): ?>
                    <div class="resume-item">
                        <div class="resume-item-name"><?php echo htmlspecialchars($item['nom'], ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="resume-item-qty">x<?php echo (int)$item['quantite']; ?></div>
                        <div class="resume-item-total"><?php echo number_format((float)$item['prix'] * (int)$item['quantite'], 2, ',', ' '); ?> €</div>
                    </div>
                <?php endforeach; ?>
                
                <div class="resume-total">
                    <span>Total:</span>
                    <span><?php echo number_format((float)$total_price, 2, ',', ' '); ?> €</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background: #2B2B2B; color: white; padding: 30px; text-align: center; margin-top: 60px;">
        <p>&copy; 2026 Le Repaire des Moustaches. Un tiers-lieu solidaire pour les chats et les humains.</p>
        <div style="margin-top: 15px;">
            <a href="#" style="color: white; text-decoration: none; margin: 0 15px;">Facebook</a> |
            <a href="#" style="color: white; text-decoration: none; margin: 0 15px;">Instagram</a> |
            <a href="../login.php" style="color: #85D6CD; text-decoration: none; margin: 0 15px;">Admin</a>
        </div>
    </footer>

    <!-- Validation JavaScript côté client -->
    <script src="../js/form-validation.js"></script>
</body>
</html>