<?php
declare(strict_types=1);

$sitePrefix = '../';
session_start();

// Configuration BDD
require_once __DIR__ . '/../../config/database.php';

$error = '';
$success = '';

// Vérification de session
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Email et mot de passe requis.';
    } else {
        try {
            $pdo = getPDO();

            // Recherche de l'utilisateur
            $stmt = $pdo->prepare('SELECT id, email, mot_de_passe, role FROM admin_users WHERE email = ?');
            $stmt->execute([$email]);
            $admin = $stmt->fetch();

            // Vérification des identifiants
            if ($admin && password_verify($password, $admin['mot_de_passe'])) {
                session_regenerate_id(true);
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['admin_role'] = $admin['role'];
                $_SESSION['admin_login_time'] = time();

                // Redirection admin
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Email ou mot de passe incorrect.';
            }

        } catch (Exception $e) {
            error_log('Erreur SQL Connexion Admin : ' . $e->getMessage());
            $error = 'Une erreur technique est survenue. Veuillez réessayer.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Le Repaire des Moustaches</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #FFF8E7 0%, #85D6CD 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            padding: 40px;
        }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header img { max-width: 90px; height: auto; margin-bottom: 15px; }
        .login-header h1 {
            font-family: 'Pacifico', cursive;
            color: #FE7B7E;
            font-size: 2rem;
            font-weight: normal;
            margin-bottom: 5px;
        }
        .login-header p { color: #666; font-size: 0.9rem; }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2B2B2B;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #E0E0E0;
            border-radius: 6px;
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
        }
        .form-group input:focus {
            outline: none;
            border-color: #85D6CD;
            box-shadow: 0 0 0 3px rgba(133, 214, 205, 0.1);
        }
        .error-message {
            background: #FFE0E0;
            border: 1px solid #FE7B7E;
            color: #C00;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #85D6CD 0%, #6FC1B0 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(133, 214, 205, 0.4);
        }
        .demo-credentials {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #E0E0E0;
            font-size: 0.85rem;
            color: #666;
        }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: #85D6CD; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="login-container">
            <div class="login-header">
                <img src="/images/logo.png" alt="Le Repaire des Moustaches Logo">
                <h1>Admin</h1>
                <p>Gestion du Repaire</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-login">Se connecter</button>
            </form>
            
            <div class="demo-credentials">
                <strong>Accès démo :</strong><br>
                Email: <code>admin@repaire.local</code><br>
                Mot de passe: <code>admin123</code>
            </div>
            
            <div class="back-link">
                <a href="../index.php">← Retour au site public</a>
            </div>
        </div>
    </div>
</body>
</html>