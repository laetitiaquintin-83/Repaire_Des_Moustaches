<?php
session_start();
require_once __DIR__ . '/../includes/header.php';
?>

<main class="page-content">
    <div class="container escape-game-container">
        <div class="escape-game-header">
            <h1 class="escape-title">🕵️ Le Mystère du Jukebox</h1>
            <p class="escape-subtitle">Une enquête policière au cœur du Diner</p>
        </div>

        <div class="escape-game-content">
            <!-- Teaser -->
            <div class="escape-teaser">
                <p class="escape-accroche">"Certaines portes ne devraient pas s'ouvrir. Celle-ci, si."</p>
                <p class="escape-description">
                    Un homme est mort au Diner. Le seul témoin : un chat noir. Il t'attend.<br>
                    <em>Il y a des secrets qui ne demandent qu'à être déterrés.</em>
                </p>
            </div>

            <!-- Intégration Canva -->
            <div class="escape-frame">
                <iframe 
                    src="https://www.canva.com/design/DAHOmTXwE-k/0SWRSDjhjKvQwLdudD0-sg/view?embed" 
                    width="100%" 
                    height="600" 
                    style="border: 2px solid #85D6CD; border-radius: 12px;"
                    allowfullscreen>
                </iframe>
            </div>

            <!-- Infos pratiques -->
            <div class="escape-infos">
                <h3>🧩 Infos pratiques</h3>
                <ul>
                    <li><strong>Durée :</strong> 45 minutes</li>
                    <li><strong>Public :</strong> 2 à 6 joueurs (ados et adultes)</li>
                    <li><strong>Tarif :</strong> 5€ par personne (gratuit pour les adhérents)</li>
                    <li><strong>Réservation :</strong> <a href="../formulaire.php" class="link-primary">Réserver un créneau</a></li>
                </ul>
            </div>

            <!-- Bouton retour -->
            <div class="escape-actions">
                <a href="../index.php" class="btn-escape">🏠 Retour à l'accueil</a>
                <a href="../formulaire.php" class="btn-escape-primary">📅 Réserver l'atelier</a>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>