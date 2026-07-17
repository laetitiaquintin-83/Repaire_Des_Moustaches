<?php
declare(strict_types=1);

$sitePrefix = '';
session_start();

// ============================================================
// 1. CONNEXION PDO DIRECTE (sans ../config/database.php)
// ============================================================
$host = 'localhost';
$dbname = 'repaire_des_moustaches';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('âŒ Erreur BDD : ' . $e->getMessage());
}

// ============================================================
// 2. TOKEN CSRF SIMPLIFIÉ (sans functions.php)
// ============================================================
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

$csrf_token = generateCSRFToken();

// ============================================================
// 3. TRAITEMENT DU FORMULAIRE
// ============================================================
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_check = $_POST['csrf_token'] ?? '';
    if (!validateCSRFToken($csrf_check)) {
        $error = 'âŒ CSRF invalide.';
    } else {
        $titre = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');
        $utilisateur_id = (int)($_POST['utilisateur_id'] ?? 0);

        if (strlen($titre) < 3) {
            $error = 'âŒ Titre trop court (min 3 caractères).';
        } elseif (strlen($contenu) < 20) {
            $error = 'âŒ Histoire trop courte (min 20 caractères).';
        } elseif ($utilisateur_id <= 0) {
            $error = 'âŒ Veuillez sélectionner un auteur.';
        } else {
            try {
                $stmt = $pdo->prepare('
                    INSERT INTO belles_histoires (utilisateur_id, titre, contenu, statut, date_publication)
                    VALUES (?, ?, ?, "en_attente", NOW())
                ');
                $stmt->execute([$utilisateur_id, $titre, $contenu]);
                $message = 'œ… Histoire soumise avec succès ! Elle sera vérifiée.';
            } catch (PDOException $e) {
                $error = 'âŒ Erreur insertion : ' . $e->getMessage();
            }
        }
    }
}

// ============================================================
// 4. RÉCUPÉRATION DES UTILISATEURS
// ============================================================
$utilisateurs = [];
try {
    $stmt = $pdo->query('SELECT id, nom, prenom FROM utilisateurs ORDER BY nom');
    $utilisateurs = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'âŒ Erreur récupération utilisateurs : ' . $e->getMessage();
}

// On inclut le header global propre et dynamique !
include_once __DIR__ . '/../includes/header.php';
?>

<main class="page-liste">
    <section class="page-section" style="max-width: 600px; margin: 40px auto; padding: 0 20px;">
        
        <h1 class="page-title neon-effect" style="text-align: center; margin-bottom: 30px;">🍔“ Soumettre une histoire</h1>

        <?php if ($message): ?>
            <div class="message-success" style="padding: 15px; background-color: #d4edda; color: #155724; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message-error" style="padding: 15px; background-color: #f8d7da; color: #721c24; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="formulaire-box" style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                <div class="form-groupe" style="margin-bottom: 20px;">
                    <label for="utilisateur_id" style="display: block; font-weight: bold; margin-bottom: 8px;">Qui êtes-vous ? *</label>
                    <select name="utilisateur_id" id="utilisateur_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        <option value="">-- Sélectionnez --</option>
                        <?php foreach ($utilisateurs as $u): ?>
                            <option value="<?php echo $u['id']; ?>">
                                <?php echo htmlspecialchars($u['prenom'] . ' ' . $u['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-groupe" style="margin-bottom: 20px;">
                    <label for="titre" style="display: block; font-weight: bold; margin-bottom: 8px;">Titre * <span style="font-size: 0.8rem; font-weight: normal; color: #777;">(min 3 caractères)</span></label>
                    <input type="text" name="titre" id="titre" required minlength="3" placeholder="Ex: Ma rencontre avec Minou..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box;">
                </div>

                <div class="form-groupe" style="margin-bottom: 25px;">
                    <label for="contenu" style="display: block; font-weight: bold; margin-bottom: 8px;">Histoire * <span style="font-size: 0.8rem; font-weight: normal; color: #777;">(min 20 caractères)</span></label>
                    <textarea name="contenu" id="contenu" required minlength="20" rows="6" placeholder="Racontez votre plus belle anecdote au Repaire des Moustaches..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; resize: vertical;"></textarea>
                </div>

                <button type="submit" class="bouton-reserver" style="background-color: #FE7B7E; color: white; border: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; width: 100%; cursor: pointer;">🍔“¤ Soumettre l'histoire</button>
            </form>
        </div>

        <div style="margin-top: 25px; text-align: center;">
            <a href="belles-histoires.php" class="btn-return" style="text-decoration: none; color: #666; font-weight: bold;">← Retour aux histoires</a>
        </div>

    </section>
</main>

<?php 
// Inclusion automatique de ton footer global
require_once __DIR__ . '/../includes/footer.php'; 
?>
