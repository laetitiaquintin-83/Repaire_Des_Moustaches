<?php
declare(strict_types=1);

// Force l'encodage UTF-8 au niveau du serveur pour supprimer les caractères bizarres
header('Content-Type: text/html; charset=utf-8');

$sitePrefix = ''; 
include_once __DIR__ . '/../includes/header.php'; 
?>

<style>
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
            <article class="carte-chat">
                <div>
                    <picture>
                        <source srcset="images/chat1.webp" type="image/webp">
                        <img src="images/chat1.jpg" alt="Velours, chat roux élégant" width="220" height="220" loading="lazy" style="border-radius: 50%; object-fit: cover; margin: 0 auto 15px; display: block;">
                    </picture>
                    <div class="info-chat">
                        <h3>Velours</h3>
                        <p class="chat-trait">Le luxe rétro incarné</p>
                        <p class="chat-histoire">Trouvé errant dans les ruelles du 11e, Velours s'est transformé en diva du Repaire. Ses ronrons mélodieux et son charme naturel en font l'ambassadeur princier de nos adoptions. Rêve d'une maison avec vue sur les toits de Paris.</p>
                    </div>
                </div>
                <div class="actions-chat">
                    <a href="adoption.php" class="bouton-chat">Tomber sous le charme</a>
                    <a href="adoption.php" class="bouton-chat secondaire">Adopter Velours</a>
                </div>
            </article>

            <article class="carte-chat">
                <div>
                    <picture>
                        <source srcset="images/chat2.webp" type="image/webp">
                        <img src="images/chat2.jpg" alt="Biscuit, petit chat gourmand" width="220" height="220" loading="lazy" style="border-radius: 50%; object-fit: cover; margin: 0 auto 15px; display: block;">
                    </picture>
                    <div class="info-chat">
                        <h3>Biscuit</h3>
                        <p class="chat-trait">Le petit gourmand turbulent</p>
                        <p class="chat-histoire">Biscuit a grandi sous les tables du dîner et considère chaque assiette comme une invitation personnelle. Plein de vie, de câlins imprévisibles et d'aventures, il transforme chaque jour en jeu. Un compagnon parfait pour ceux qui adorent l'énergie féline.</p>
                    </div>
                </div>
                <div class="actions-chat">
                    <a href="adoption.php" class="bouton-chat">Jouer avec Biscuit</a>
                    <a href="adoption.php" class="bouton-chat secondaire">L'emmener chez toi</a>
                </div>
            </article>

            <article class="carte-chat">
                <div>
                    <picture>
                        <source srcset="images/chat3.webp" type="image/webp">
                        <img src="images/chat3.jpg" alt="Moonlight, chat mélancolique et poète" width="220" height="220" loading="lazy" style="border-radius: 50%; object-fit: cover; margin: 0 auto 15px; display: block;">
                    </picture>
                    <div class="info-chat">
                        <h3>Moonlight</h3>
                        <p class="chat-trait">Le poète des toits parisiens</p>
                        <p class="chat-histoire">Moonlight a connu la liberté sauvage avant d'arriver au Repaire. Ses yeux profonds racontent mille histoires. Doux et pensif, il cherche une âme sœur qui comprenne ses silences éloquents et aime les nuits étoilées depuis une fenêtre douillette.</p>
                    </div>
                </div>
                <div class="actions-chat">
                    <a href="belles-histoires.php" class="bouton-chat">Découvrir son histoire</a>
                    <a href="adoption.php" class="bouton-chat secondaire">Lui donner un foyer</a>
                </div>
            </article>
        </div>
    </section>
</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
