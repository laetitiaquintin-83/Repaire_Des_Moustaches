<?php
declare(strict_types=1);

$sitePrefix = '';
// ============================================================
// 🍔”’ BLOCAGE DES REQUàŠTES GET (sécurité)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Méthode non autorisée. Utilisez POST pour ajouter au panier.');
}
// ============================================================

session_start();

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();
$response = ['success' => false, 'message' => ''];

// Vérification CSRF (toujours obligatoire en POST)
$csrf_check = $_POST['csrf_token'] ?? '';
if (!validateCSRFToken($csrf_check)) {
    $response['message'] = 'Erreur de sécurité : token CSRF invalide';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Récupérer l'ID du produit
$produit_id = (int)($_POST['produit_id'] ?? 0);
$quantite = (int)($_POST['quantite'] ?? 1);

if ($produit_id <= 0) {
    $response['message'] = 'Produit invalide';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Vérifier que le produit existe
$stmt = $pdo->prepare('SELECT id, nom, prix FROM produits WHERE id = ?');
$stmt->execute([$produit_id]);
$produit = $stmt->fetch();

if (!$produit) {
    $response['message'] = 'Produit introuvable';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Initialiser le panier
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Ajouter ou mettre à  jour
if (isset($_SESSION['cart'][$produit_id])) {
    $_SESSION['cart'][$produit_id]['quantite'] += $quantite;
} else {
    $_SESSION['cart'][$produit_id] = [
        'id' => $produit_id,
        'nom' => $produit['nom'],
        'prix' => $produit['prix'],
        'quantite' => $quantite
    ];
}

$response['success'] = true;
$response['message'] = htmlspecialchars($produit['nom']) . ' ajouté au panier !';
$response['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantite'));
$response['cart_total'] = array_sum(array_map(function($item) {
    return $item['prix'] * $item['quantite'];
}, $_SESSION['cart']));

// Détection AJAX fiable
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    header('Location: cart.php');
}
exit;
