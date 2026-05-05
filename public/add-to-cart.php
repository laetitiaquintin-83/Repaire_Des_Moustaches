<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();
$response = ['success' => false, 'message' => ''];

// Récupérer l'ID du produit
$produit_id = (int)($_POST['produit_id'] ?? $_GET['produit_id'] ?? 0);
$quantite = (int)($_POST['quantite'] ?? $_GET['quantite'] ?? 1);

if ($produit_id <= 0) {
    $response['message'] = 'Produit invalide';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Vérifier que le produit existe et récupérer ses infos
$stmt = $pdo->prepare('SELECT id, nom, prix, image_url FROM produits WHERE id = ?');
$stmt->execute([$produit_id]);
$produit = $stmt->fetch();

if (!$produit) {
    $response['message'] = 'Produit introuvable';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Initialiser le panier s'il n'existe pas
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Ajouter le produit au panier
if (isset($_SESSION['cart'][$produit_id])) {
    $_SESSION['cart'][$produit_id]['quantite'] += $quantite;
} else {
    $_SESSION['cart'][$produit_id] = [
        'id' => $produit_id,
        'nom' => $produit['nom'],
        'prix' => $produit['prix'],
        'image_url' => $produit['image_url'],
        'quantite' => $quantite
    ];
}

$response['success'] = true;
$response['message'] = htmlspecialchars($produit['nom']) . ' ajouté au panier !';
// CORRECTION ICI : on additionne toutes les quantités pour avoir le vrai nombre d'articles
$response['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantite'));
$response['cart_total'] = array_sum(array_map(function($item) {
    return $item['prix'] * $item['quantite'];
}, $_SESSION['cart']));

// Si c'est une requête JSON, retourner JSON
if (isset($_POST['json']) || isset($_GET['json'])) {
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Sinon, rediriger vers le panier
    header('Location: cart.php');
}
exit;