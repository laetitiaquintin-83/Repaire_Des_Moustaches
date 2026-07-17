<?php include_once 'includes/header.php'; ?>

<style>
    /* 1. On force les cartes à s'organiser de manière à pousser le contenu vers le bas */
    .carte-chat {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    /* 2. On définit la structure pour la boîte des boutons afin de créer un espace propre */
    .actions-chat {
        display: flex;
        flex-direction: column;
        gap: 12px; /* Crée un espace parfait entre vos deux boutons */
        width: 100%;
        max-width: 280px; /* Aligné sur la largeur max de vos textes */
        margin: 0 auto; /* Centre les boutons par rapport à la carte */
        padding-bottom: 20px; /* Ajoute une marge agréable avec le bas de la carte */
    }

    /* 3. On redéfinit proprement le comportement de vos boutons */
    .bouton-chat {
        display: block; /* S'assure que le bouton prend toute la largeur disponible */
        width: 100%;
        box-sizing: border-box;
        text-align: center;
        transition: all 0.3s ease;
    }

    /* 4. Effet de survol sur les boutons */
    .bouton-chat:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    /* 5. Style alternatif optionnel pour le second bouton (pour utiliser votre vert menthe si besoin) */
    .bouton-chat.secondaire {
        background-color: var(--vert-menthe);
        color: var(--gris-fonce);
    }
</style>

    <main>
        <section class="page-section moustachus">
            <h1 class="page-title">Rencontrez l'Équipage</h1>
            <p class="sous-titre">Les trois mousquetaires du Repaire, chacun avec sa personnalité attachante. Venez les rencontrer pour un moment de ronrons et de tendresse.</p>
            <div class="grille-chats">
                <article class="carte-chat">
                    <picture>
                        <source srcset="images/chat1.webp" type="image/webp">
                        <img src="images/chat1.jpg" alt="Velours, chat roux élégant" width="300" height="250" loading="lazy">
                    </picture>
                    <div class="info-chat">
                        <h3>Velours</h3>
                        <p class="chat-trait">Le luxe rétro incarné</p>
                        <p class="chat-histoire">Trouvé errant dans les ruelles , Velours s'est transformé en diva du Repaire. Ses ronrons mélodieux et son charme naturel en font l'ambassadeur princier de nos adoptions. Rêve d'une maison avec vue sur les toits de Toulon.</p>
                    </div>
                    <div class="actions-chat">
                        <a href="#" class="bouton-chat">Tomber sous le charme</a>
                        <a href="#" class="bouton-chat secondaire">Adopter Velours</a>
                    </div>
                </article>
                <article class="carte-chat">
                    <picture>
                        <source srcset="images/chat2.webp" type="image/webp">
                        <img src="images/chat2.jpg" alt="Biscuit, petit chat gourmand" width="300" height="250" loading="lazy">
                    </picture>
                    <div class="info-chat">
                        <h3>Biscuit</h3>
                        <p class="chat-trait">Le petit gourmand turbulent</p>
                        <p class="chat-histoire">Biscuit a grandi sous les tables du dîner et considère chaque assiette comme une invitation personnelle. Plein de vie, de câlins imprévisibles et d'aventures, il transforme chaque jour en jeu. Un compagnon parfait pour ceux qui adorent l'énergie féline.</p>
                    </div>
                    <div class="actions-chat">
                        <a href="#" class="bouton-chat">Jouer avec Biscuit</a>
                        <a href="#" class="bouton-chat secondaire">L'emmener chez toi</a>
                    </div>
                </article>
                <article class="carte-chat">
                    <picture>
                        <source srcset="images/chat3.webp" type="image/webp">
                        <img src="images/chat3.jpg" alt="Moonlight, chat mélancolique et poète" width="300" height="250" loading="lazy">
                    </picture>
                    <div class="info-chat">
                        <h3>Moonlight</h3>
                        <p class="chat-trait">Le poète des toits parisiens</p>
                        <p class="chat-histoire">Moonlight a connu la liberté sauvage avant d'arriver au Repaire. Ses yeux profonds racontent mille histoires. Doux et pensif, il cherche une âme sœur qui comprenne ses silences éloquents et aime les nuits étoilées depuis une fenêtre douillette.</p>
                    </div>
                    <div class="actions-chat">
                        <a href="#" class="bouton-chat">Découvrir son histoire</a>
                        <a href="#" class="bouton-chat secondaire">Lui donner un foyer</a>
                    </div>
                </article>
            </div>
        </section>
    </main>

<?php include_once 'includes/footer.php'; ?>