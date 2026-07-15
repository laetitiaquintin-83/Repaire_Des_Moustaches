<?php declare(strict_types=1);

session_start();

// ============================================================
// 1. CONNEXION PDO DIRECTE (sans config/database.php)
// ============================================================
$host = 'localhost';
$dbname = 'repaire_des_moustaches';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('❌ Erreur BDD : ' . $e->getMessage());
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
        $error = '❌ CSRF invalide.';
    } else {
        $titre = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');
        $utilisateur_id = (int)($_POST['utilisateur_id'] ?? 0);

        if (strlen($titre) < 3) {
            $error = '❌ Titre trop court (min 3 caractères).';
        } elseif (strlen($contenu) < 20) {
            $error = '❌ Histoire trop courte (min 20 caractères).';
        } elseif ($utilisateur_id <= 0) {
            $error = '❌ Veuillez sélectionner un auteur.';
        } else {
            try {
                $stmt = $pdo->prepare('
                    INSERT INTO belles_histoires (utilisateur_id, titre, contenu, statut, date_publication)
                    VALUES (?, ?, ?, "en_attente", NOW())
                ');
                $stmt->execute([$utilisateur_id, $titre, $contenu]);
                $message = '✅ Histoire soumise avec succès ! Elle sera vérifiée.';
            } catch (PDOException $e) {
                $error = '❌ Erreur insertion : ' . $e->getMessage();
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
    $error = '❌ Erreur récupération utilisateurs : ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Soumettre une histoire</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>📝 Soumettre une histoire</h1>

    <?php if ($message): ?>
        <p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

        <label>Qui êtes-vous ? *</label>
        <select name="utilisateur_id" required>
            <option value="">-- Sélectionnez --</option>
            <?php foreach ($utilisateurs as $u): ?>
                <option value="<?php echo $u['id']; ?>">
                    <?php echo htmlspecialchars($u['prenom'] . ' ' . $u['nom']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Titre * (min 3 caractères)</label>
        <input type="text" name="titre" required minlength="3">
        <br><br>

        <label>Histoire * (min 20 caractères)</label>
        <textarea name="contenu" required minlength="20" rows="6"></textarea>
        <br><br>

        <button type="submit">📤 Soumettre</button>
    </form>

    <p><a href="belles-histoires.php">← Retour aux histoires</a></p>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>