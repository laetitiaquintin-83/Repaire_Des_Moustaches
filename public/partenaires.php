<?php
declare(strict_types=1);
session_start();

// Définir le chemin vers le fichier JSON (dans le dossier public/api/)
$json_path = __DIR__ . '/api/fake_refuge.json';
$pensionnaires_partenaires = [];

if (file_exists($json_path)) {
    $json_data = file_get_contents($json_path);
    $pensionnaires_partenaires = json_decode($json_data, true) ?? [];
}

// Définition du préfixe de site pour les inclusions si nécessaire
$sitePrefix = '';

// On inclut ton header global (on remonte d'un dossier pour aller chercher includes/)
if (file_exists(__DIR__ . '/../includes/header.php')) {
    include_once __DIR__ . '/../includes/header.php';
} elseif (file_exists(__DIR__ . '/includes/header.php')) {
    include_once __DIR__ . '/includes/header.php';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refuges Partenaires - Le Repaire</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .partenaires-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            font-family: 'Montserrat', sans-serif;
        }
        .partenaires-title {
            color: #2B2B2B;
            border-bottom: 4px solid #85D6CD;
            padding-bottom: 10px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .partenaires-subtitle {
            color: #666;
            margin-bottom: 30px;
        }
        .grid-partenaires {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        .card-animal {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #eee;
            transition: transform 0.3s ease;
        }
        .card-animal:hover {
            transform: translateY(-5px);
        }
        .card-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-content {
            padding: 20px;
        }
        .animal-name {
            margin: 0 0 10px 0;
            color: #2B2B2B;
            font-size: 20px;
        }
        .animal-info {
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        .badge-refuge {
            display: inline-block;
            background: #e6f7f5;
            color: #207a70;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="partenaires-container">
        <h1 class="partenaires-title">🐾 Réseau des Refuges Partenaires</h1>
        <p class="partenaires-subtitle">Ces animaux sont logés chez nos partenaires mais synchronisés en temps réel sur notre plateforme.</p>
        
        <?php if (empty($pensionnaires_partenaires)): ?>
            <p>Aucun animal partenaire disponible pour le moment.</p>
        <?php else: ?>
            <div class="grid-partenaires">
                <?php foreach ($pensionnaires_partenaires as $animal): ?>
                    <div class="card-animal">
                        <img src="<?php echo htmlspecialchars($animal['image']); ?>" alt="<?php echo htmlspecialchars($animal['nom']); ?>" class="card-img">
                        <div class="card-content">
                            <h3 class="animal-name"><?php echo htmlspecialchars($animal['nom']); ?></h3>
                            <div class="animal-info">
                                <strong>Espèce :</strong> <?php echo htmlspecialchars($animal['espece']); ?><br>
                                <strong>Race :</strong> <?php echo htmlspecialchars($animal['race']); ?><br>
                                <strong>Âge :</strong> <?php echo htmlspecialchars($animal['age']); ?>
                            </div>
                            <span class="badge-refuge">📍 <?php echo htmlspecialchars($animal['refuge_provenance']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php 
    // On inclut ton footer global
    if (file_exists(__DIR__ . '/../includes/footer.php')) {
        include_once __DIR__ . '/../includes/footer.php'; 
    } elseif (file_exists(__DIR__ . '/includes/footer.php')) {
        include_once __DIR__ . '/includes/footer.php'; 
    }
    ?>
</body>
</html>