<?php
declare(strict_types=1);

// Force l'encodage UTF-8 au niveau du serveur pour supprimer les caractères bizarres
header('Content-Type: text/html; charset=utf-8');

$sitePrefix = ''; 
include_once __DIR__ . '/../includes/header.php';

// Connexion à la BDD
require_once __DIR__ . '/../config/database.php';
$pdo = getPDO();

// Récupération des chats
$stmt = $pdo->prepare('SELECT * FROM pensionnaires ORDER BY nom');
$stmt->execute();
$chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// On associe chaque chat à son image (fixe)
$images = [
    'Velours' => ['webp' => 'images/chat1.webp', 'jpg' => 'images/chat1.jpg', 'alt' => 'Velours, chat roux élégant'],
    'Biscuit' => ['webp' => 'images/chat2.webp', 'jpg' => 'images/chat2.jpg', 'alt' => 'Biscuit, petit chat gourmand'],
    'Moonlight' => ['webp' => 'images/chat3.webp', 'jpg' => 'images/chat3.jpg', 'alt' => 'Moonlight, chat mélancolique et poète'],
];
?>

<style>
    .grille-chats {
        display: flex;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap;
        align-items: stretch;
    }

    .carte-chat {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background: white;
        padding: 30px 20px 25px 20px;
        border-radius: 20px;
        box-shadow: 0px 10px 20px rgba(0,0,0,0.05);
        width: 320px;
    }

    .actions-chat {
        display: flex;
        flex-direction: column;
        gap: 12px;
        width: 100%;
        margin-top: auto;
    }

    .bouton-chat {
        display: block !important;
        width: 100% !important;
        text-align: center;
        box-sizing: border-box;
        margin: 0 !important;
        transition: all 0.3s ease;
    }

    .bouton-chat.secondaire {
        background-color: var(--vert-menthe) !important;
        color: var(--gris-fonce) !important;
    }

    .bouton-chat:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        opacity: 0.9;
    }
</style>

<main>
    <section class="page-section moustachus">
        <h1 class="page-title">Rencontrez l'Équipage</h1>
        <p class="sous-titre" style="margin-bottom: 40px; font-style: italic;">Les trois mousquetaires du Repaire, chacun avec sa personnalité attachante. Venez les rencontrer pour un moment de ronrons et de tendresse.</p>
        
        <div class="grille-chats">
            <?php foreach ($chats as $chat): 
                $nom = $chat['nom'];
                // Récupération de l'image associée
                $img = isset($images[$nom]) ? $images[$nom] : null;
                if (!$img) continue; // Si le chat n'a pas d'image associée, on passe
            ?>
                <article class="carte-chat">
                    <div>
                        <picture>
                            <source srcset="<?= $img['webp'] ?>" type="image/webp">
                            <img src="<?= $img['jpg'] ?>" 
                                 alt="<?= $img['alt'] ?>" 
                                 width="220" height="220" loading="lazy" 
                                 style="border-radius: 50%; object-fit: cover; margin: 0 auto 15px; display: block;">
                        </picture>
                        <div class="info-chat">
                            <h3><?= htmlspecialchars($nom) ?></h3>
                            <p class="chat-trait"><?= htmlspecialchars($chat['caractere'] ?? 'À découvrir') ?></p>
                            <p class="chat-histoire"><?= nl2br(htmlspecialchars($chat['description'])) ?></p>
                        </div>
                    </div>
                    <div class="actions-chat">
                        <a href="adoption.php?id=<?= $chat['id'] ?>" class="bouton-chat">Tomber sous le charme</a>
                        <a href="adoption.php?id=<?= $chat['id'] ?>" class="bouton-chat secondaire">Adopter <?= htmlspecialchars($nom) ?></a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>