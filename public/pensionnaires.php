<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();

$sql = 'SELECT id, nom, age, description, photo_url, statut
        FROM pensionnaires
        ORDER BY id DESC';

$pensionnaires = $pdo->query($sql)->fetchAll();

function statutLabel(string $statut): string
{
    return match ($statut) {
        'a_l_adoption' => 'A l adoption',
        'famille_accueil' => 'Famille d accueil',
        'adopte' => 'Adopte',
        default => ucfirst(str_replace('_', ' ', $statut)),
    };
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notre equipage - Le Repaire des Moustaches</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <main class="page-liste">
        <section class="liste-header">
            <h1>Notre equipage</h1>
            <p>Chats accueillis par le refuge partenaire et presentes au Repaire.</p>
        </section>

        <section class="grille-pensionnaires">
            <?php if ($pensionnaires === []): ?>
                <p>Aucun pensionnaire n est encore enregistre.</p>
            <?php else: ?>
                <?php foreach ($pensionnaires as $pensionnaire): ?>
                    <article class="carte-pensionnaire">
                        <?php if (!empty($pensionnaire['photo_url'])): ?>
                            <img src="../<?php echo htmlspecialchars($pensionnaire['photo_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="Photo de <?php echo htmlspecialchars($pensionnaire['nom'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php endif; ?>
                        <h2><?php echo htmlspecialchars($pensionnaire['nom'], ENT_QUOTES, 'UTF-8'); ?></h2>
                        <p class="statut-pensionnaire"><?php echo htmlspecialchars(statutLabel((string) $pensionnaire['statut']), ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php if ($pensionnaire['age'] !== null): ?>
                            <p><strong>Age :</strong> <?php echo (int) $pensionnaire['age']; ?> ans</p>
                        <?php endif; ?>
                        <?php if (!empty($pensionnaire['description'])): ?>
                            <p><?php echo htmlspecialchars($pensionnaire['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
