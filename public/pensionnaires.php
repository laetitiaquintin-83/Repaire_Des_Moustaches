<?php
declare(strict_types=1);

// Connexion BDD & Header
require_once __DIR__ . '/../config/database.php';
$sitePrefix = ''; 
include_once '../includes/header.php'; 

// Récupération dynamique depuis la BDD MySQL
try {
    $pdo = getPDO();
    $stmt = $pdo->query("SELECT * FROM pensionnaires ORDER BY id ASC");
    $pensionnaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $pensionnaires = [];
}
?>

<style>
    /* Barre de filtres */
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

    /* 1. On s'assure que la grille répartit bien ses cartes */
    .grille-chats {
        display: flex;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap; /* Évite que ça déborde sur petit écran */
        align-items: stretch; /* Force toutes les cartes à avoir la même hauteur */
    }

    /* 2. On configure la carte pour qu'elle pousse les boutons tout en bas */
    .carte-chat {
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Pousse les blocs info en haut et boutons en bas */
        background: white; /* Donne un fond blanc propre pour faire ressortir le texte */
        padding: 30px 20px 25px 20px;
        border-radius: 20px;
        box-shadow: 0px 10px 20px rgba(0,0,0,0.05);
        width: 320px; /* Largeur fixe propre pour chaque colonne */
        transition: transform 0.2s ease, opacity 0.2s ease;
    }

    /* 3. On gère le bloc qui enveloppe les deux boutons */
    .actions-chat {
        display: flex;
        flex-direction: column; /* Aligne les boutons l'un sous l'autre */
        gap: 12px; /* Espace de 12px très propre entre les deux boutons */
        width: 100%;
        margin-top: auto; /* Force le bloc à se coller au bas de la carte */
    }

    /* 4. On redéfinit le bouton pour qu'il prenne toute la largeur sans casser le style d'origine */
    .bouton-chat {
        display: block !important; /* Force le bouton à se comporter en bloc */
        width: 100% !important;
        text-align: center;
        box-sizing: border-box;
        margin: 0 !important; /* Retire les marges parasites qui causaient des décalages */
        transition: all 0.3s ease;
    }

    /* Optionnel : Un bouton secondaire vert menthe pour éviter d'avoir deux gros pavés roses identiques */
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

        <!-- 🔍 BARRE DE FILTRES EN DIRECT -->
        <div class="barre-filtres-chats">
            <select id="filtre-age" onchange="filtrerChats()" class="input-filtre" style="cursor: pointer; font-weight: bold;">
                <option value="tous">🐾 Tous les âges</option>
                <option value="jeune">Jeunes (2 ans et moins)</option>
                <option value="adulte">Adultes (3 - 4 ans)</option>
                <option value="senior">Séniors (5 ans et +)</option>
            </select>

            <input type="text" id="filtre-recherche" onkeyup="filtrerChats()" placeholder="🔍 Rechercher un nom..." class="input-filtre">
        </div>

        <!-- 🐱 GRILLE DYNAMIQUE DE BDD -->
        <div class="grille-chats" id="grille-chats">
            <?php foreach ($pensionnaires as $chat): ?>
                <article class="carte-chat" 
                         data-age="<?= (int)$chat['age'] ?>" 
                         data-nom="<?= htmlspecialchars(strtolower($chat['nom'])) ?>">
                    <div>
                        <picture>
                            <img src="<?= htmlspecialchars($chat['photo_url']) ?>" 
                                 alt="<?= htmlspecialchars($chat['nom']) ?>" 
                                 width="220" 
                                 height="220" 
                                 loading="lazy" 
                                 onerror="this.src='images/chats/chat1.jpg'"
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

<!-- ⚡ JAVASCRIPT : Filtre instantané -->
<script>
function filtrerChats() {
    const filtreAge = document.getElementById('filtre-age').value;
    const recherche = document.getElementById('filtre-recherche').value.toLowerCase().trim();
    const cartes = document.querySelectorAll('.carte-chat');

    cartes.forEach(carte => {
        const age = parseInt(carte.getAttribute('data-age')) || 0;
        const nom = carte.getAttribute('data-nom') || '';

        let correspondAge = false;

        // Condition par Âge
        if (filtreAge === 'tous') {
            correspondAge = true;
        } else if (filtreAge === 'jeune' && age <= 2) {
            correspondAge = true;
        } else if (filtreAge === 'adulte' && (age === 3 || age === 4)) {
            correspondAge = true;
        } else if (filtreAge === 'senior' && age >= 5) {
            correspondAge = true;
        }

        // Condition par Recherche textuelle
        const correspondRecherche = nom.includes(recherche);

        // Affichage dynamique
        if (correspondAge && correspondRecherche) {
            carte.style.display = "flex";
        } else {
            carte.style.display = "none";
        }
    });
}
</script>

<?php include_once '../includes/footer.php'; ?>