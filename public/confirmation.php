<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();

// Récupérer l'ID de la commande
$commande_id = (int)($_GET['commande_id'] ?? 0);

$commande = null;
if ($commande_id > 0) {
    $stmt = $pdo->prepare('
        SELECT c.*, u.prenom, u.nom, u.email
        FROM commandes c
        JOIN utilisateurs u ON c.utilisateur_id = u.id
        WHERE c.id = ?
    ');
    $stmt->execute([$commande_id]);
    $commande = $stmt->fetch();
    
    if ($commande) {
        // Récupérer les lignes
        $stmt = $pdo->prepare('
            SELECT lc.*, p.nom as produit_nom
            FROM lignes_commandes lc
            JOIN produits p ON lc.produit_id = p.id
            WHERE lc.commande_id = ?
        ');
        $stmt->execute([$commande_id]);
        $commande['lignes'] = $stmt->fetchAll();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation - Le Repaire des Moustaches</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f5f5f5;
        }
        
        .confirmation-container {
            max-width: 600px;
            margin: 60px auto;
            padding: 20px;
        }
        
        .confirmation-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
        }
        
        .confirmation-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        .confirmation-title {
            font-size: 2rem;
            color: #2B2B2B;
            margin-bottom: 10px;
        }
        
        .confirmation-subtitle {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 30px;
        }
        
        .confirmation-details {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            text-align: left;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #2B2B2B;
        }
        
        .detail-value {
            color: #666;
        }
        
        .items-list {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        
        .items-list h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #2B2B2B;
            border-bottom: 2px solid #85D6CD;
            padding-bottom: 10px;
        }
        
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        
        .item-row:last-child {
            border-bottom: none;
        }
        
        .info-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            color: #155724;
        }
        
        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
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
        }
        
        .btn-secondary:hover {
            background: #ccc;
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
                <a href="cart.php" style="color: #2B2B2B;">🛒 Panier</a>
            </div>
        </nav>
    </header>

    <div class="confirmation-container">
        <?php if ($commande): ?>
            <div class="confirmation-card">
                <div class="confirmation-icon">✓</div>
                <h1 class="confirmation-title">Commande confirmée !</h1>
                <p class="confirmation-subtitle">Merci pour votre achat</p>
                
                <div class="confirmation-details">
                    <div class="detail-row">
                        <span class="detail-label">Numéro de commande:</span>
                        <span class="detail-value">#<?php echo $commande['id']; ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Client:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($commande['email']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date:</span>
                        <span class="detail-value"><?php echo date('d/m/Y à H:i', strtotime($commande['date_commande'])); ?></span>
                    </div>
                </div>
                
                <div class="items-list">
                    <h3>Articles commandés</h3>
                    <?php foreach ($commande['lignes'] as $ligne): ?>
                        <div class="item-row">
                            <span><?php echo htmlspecialchars($ligne['produit_nom']); ?> (x<?php echo $ligne['quantite']; ?>)</span>
                            <span><?php echo number_format((float)$ligne['prix_unitaire'] * (int)$ligne['quantite'], 2, ',', ' '); ?> €</span>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div style="font-size: 1.3rem; font-weight: 700; color: #2B2B2B; padding: 20px; background: #f0f8f7; border-radius: 8px;">
                    Total: <?php echo number_format((float)$commande['montant_total'], 2, ',', ' '); ?> €
                </div>
                
                <div class="info-box">
                    📧 <strong>Confirmation envoyée!</strong> Un email de confirmation a été envoyé à <strong><?php echo htmlspecialchars($commande['email']); ?></strong>
                </div>
                
                <div class="actions">
                    <a href="boutique.php" class="btn btn-primary">Continuer les achats</a>
                    <a href="../index.html" class="btn btn-secondary">Retour à l'accueil</a>
                </div>
            </div>
        <?php else: ?>
            <div class="confirmation-card">
                <div style="font-size: 3rem; margin-bottom: 20px;">❌</div>
                <h1 class="confirmation-title">Commande introuvable</h1>
                <p class="confirmation-subtitle">Nous n'avons pas trouvé cette commande</p>
                <a href="boutique.php" class="btn btn-primary" style="margin-top: 30px;">Retour à la boutique</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer style="background: #2B2B2B; color: white; padding: 30px; text-align: center; margin-top: 60px;">
        <p>&copy; 2024 Le Repaire des Moustaches. Tous droits réservés.</p>
    </footer>
</body>
</html>
