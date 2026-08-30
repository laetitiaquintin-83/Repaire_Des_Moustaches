<?php
declare(strict_types=1);

// Connexion BDD et header
require_once __DIR__ . '/../config/database.php';
$sitePrefix = ''; 
include_once '../includes/header.php'; 

// Chargement des pensionnaires
try {
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT * FROM pensionnaires ORDER BY id ASC');
    $stmt->execute();
    $pensionnaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $pensionnaires = [];
}
?>

<style>
    /* Filtres */
    .barre-filtres-chats {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 35px;
        flex-wrap: wrap;
    }
    
    .input-filtre {
        padding: 10px 16px;
        border-radius: 25px;
        border: 2px solid var(--vert-menthe, #2ecc71);
        font-size: 0.95rem;
        outline: none;
        background: white;
    }

    /* Grille */
    .grille-chats {
        display: flex;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap;
        align-items: stretch;
    }

    /* Carte */
    .carte-chat {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background: white;
        padding: 30px 20px 25px 20px;
        border-radius: 20px;
        box-shadow: 0px 10px 20px rgba(0,0,0,0.05);
        width: 320px;
        transition: transform 0.2s ease, opacity 0.2s ease;
    }

    /* Actions */
    .actions-chat {
        display: flex;
        flex-direction: column;
        gap: 12px;
        width: 100%;
        margin-top: auto;
    }

    /* Boutons */
    .bouton-chat {
        display: block !important;
        width: 100% !important;
        text-align: center;
        box-sizing: border-box;
        margin: 0 !important;
        transition: all 0.3s ease;
    }

    /* Bouton secondaire */
    .bouton-chat.secondaire {
        background-color: var(--vert-menthe, #2ecc71) !important;
        color: var(--gris-fonce, #333) !important;
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
        <p class="sous-titre" style="margin-bottom: 25px; font-style: italic;">
            Les pensionnaires du Repaire, chacun avec sa personnalité attachante. Venez les rencontrer pour un moment de ronrons et de tendresse.
        </p>

        <!-- Filtres -->
        <div class="barre-filtres-chats">
            <select id="filtre-age" onchange="filtrerChats()" class="input-filtre" style="cursor: pointer; font-weight: bold;">
                <option value="tous">🐾 Tous les âges</option>
                <option value="jeune">Jeunes (2 ans et moins)</option>
                <option value="adulte">Adultes (3 - 4 ans)</option>
                <option value="senior">Séniors (5 ans et +)</option>
            </select>

            <input type="text" id="filtre-recherche" onkeyup="filtrerChats()" placeholder="🔍 Rechercher un nom..." class="input-filtre">
        </div>

        <!-- Liste dynamique -->
        <div class="grille-chats" id="grille-chats">
            <?php foreach ($pensionnaires as $chat): ?>
                <article class="carte-chat" 
                         data-age="<?= (int)$chat['age'] ?>" 
                         data-nom="<?= htmlspecialchars(strtolower($chat['nom'])) ?>">
                    <div>
                        <picture>
                            <img src="<?= htmlspecialchars($chat['photo'] ?? 'images/chat1.webp') ?>" 
                                 alt="<?= htmlspecialchars($chat['nom']) ?>" 
                                 width="220" 
                                 height="220" 
                                 loading="lazy" 
                                 onerror="this.src='images/chat1.webp'"
                                 style="border-radius: 50%; object-fit: cover; margin: 0 auto 15px; display: block; width: 220px; height: 220px;">
                        </picture>

                        <div class="info-chat">
                            <h3><?= htmlspecialchars($chat['nom']) ?> <small style="font-size: 0.8em; opacity: 0.7;">(<?= (int)$chat['age'] ?> ans)</small></h3>
                            
                            <?php if (!empty($chat['caractere'])): ?>
                                <p class="chat-trait"><?= htmlspecialchars($chat['caractere']) ?></p>
                            <?php endif; ?>
                            
                            <p class="chat-histoire"><?= nl2br(htmlspecialchars($chat['description'] ?? '')) ?></p>
                        </div>
                    </div>

                    <div class="actions-chat">
                        <a href="adoption.php?chat_id=<?= $chat['id'] ?>" class="bouton-chat">Rencontrer <?= htmlspecialchars($chat['nom']) ?></a>
                        <a href="adoption.php?chat_id=<?= $chat['id'] ?>" class="bouton-chat secondaire">Adopter</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<!-- Filtre dynamique -->
<script>
function filtrerChats() {
    const filtreAge = document.getElementById('filtre-age').value;
    const recherche = document.getElementById('filtre-recherche').value.toLowerCase().trim();
    const cartes = document.querySelectorAll('.carte-chat');

    cartes.forEach(carte => {
        const age = parseInt(carte.getAttribute('data-age')) || 0;
        const nom = carte.getAttribute('data-nom') || '';

        let correspondAge = false;

        // Filtre par âge
        if (filtreAge === 'tous') {
            correspondAge = true;
        } else if (filtreAge === 'jeune' && age <= 2) {
            correspondAge = true;
        } else if (filtreAge === 'adulte' && (age === 3 || age === 4)) {
            correspondAge = true;
        } else if (filtreAge === 'senior' && age >= 5) {
            correspondAge = true;
        }

        // Filtre textuel
        const correspondRecherche = nom.includes(recherche);

        // Affichage
        if (correspondAge && correspondRecherche) {
            carte.style.display = "flex";
        } else {
            carte.style.display = "none";
        }
    });
}
</script>

<?php include_once '../includes/footer.php'; ?>