<?php
declare(strict_types=1);

$sitePrefix = '';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/header.php';
?>

<style>
/* --- STYLES ESCAPE GAME RÉTRO --- */
.escape-game-container {
    max-width: 950px;
    margin: 40px auto 60px auto;
    padding: 0 20px;
}

.escape-game-header {
    text-align: center;
    margin-bottom: 35px;
}

.escape-title {
    font-family: 'Pacifico', cursive;
    color: #FF7B7B;
    font-size: 2.8rem;
    font-weight: normal;
    margin-bottom: 10px;
    text-shadow: 2px 2px 0px #fff, 4px 4px 0px rgba(0, 0, 0, 0.05);
}

.escape-subtitle {
    font-family: 'Montserrat', sans-serif;
    color: #4A5568;
    font-size: 1.1rem;
    font-weight: 500;
}

/* Encart Teaser & Accroche */
.escape-teaser {
    background: #FFFFFF;
    border: 3px solid #82CECA;
    border-radius: 20px;
    padding: 25px 30px;
    text-align: center;
    margin-bottom: 30px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.04);
}

.escape-accroche {
    font-family: 'Pacifico', cursive;
    color: #802C38;
    font-size: 1.3rem;
    margin-bottom: 10px;
}

.escape-description {
    font-family: 'Montserrat', sans-serif;
    color: #2D3748;
    font-size: 1rem;
    line-height: 1.6;
}

/* Iframe Canva Rétro */
.escape-frame {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border: 4px solid #F7B2B7;
    margin-bottom: 35px;
    background: #000;
}

.escape-frame iframe {
    display: block;
    border: none;
}

/* Bloc Infos Pratiques */
.escape-infos {
    background: #FFF8E7;
    border-left: 6px solid #FCE185;
    border-radius: 12px;
    padding: 25px 30px;
    margin-bottom: 35px;
}

.escape-infos h3 {
    font-family: 'Montserrat', sans-serif;
    color: #802C38;
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.escape-infos ul {
    list-style: none;
    padding: 0;
    margin: 0;
    font-family: 'Montserrat', sans-serif;
    color: #4A5568;
}

.escape-infos li {
    padding: 8px 0;
    border-bottom: 1px dashed rgba(0,0,0,0.08);
    font-size: 0.95rem;
}

.escape-infos li:last-child {
    border-bottom: none;
}

.escape-infos strong {
    color: #2D3748;
}

.link-primary {
    color: #FF7B7B;
    font-weight: 700;
    text-decoration: underline;
}

.link-primary:hover {
    color: #802C38;
}

/* Boutons d'action */
.escape-actions {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.btn-escape, .btn-escape-primary {
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    font-size: 0.95rem;
    padding: 12px 26px;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-escape {
    background-color: #E2E8F0;
    color: #4A5568;
}

.btn-escape:hover {
    background-color: #CBD5E0;
    transform: translateY(-2px);
}

.btn-escape-primary {
    background-color: #FF7B7B;
    color: #FFFFFF;
    box-shadow: 0 4px 12px rgba(255, 123, 123, 0.3);
}

.btn-escape-primary:hover {
    background-color: #802C38;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(128, 44, 56, 0.3);
}
</style>

<main class="page-content">
    <div class="escape-game-container">
        
        <div class="escape-game-header">
            <h1 class="escape-title">🎵 Le Mystère du Jukebox</h1>
            <p class="escape-subtitle">Une enquête policière au cœur du Diner des Moustaches</p>
        </div>

        <div class="escape-game-content">
            
            <!-- Teaser -->
            <div class="escape-teaser">
                <p class="escape-accroche">« Certaine portes ne devraient pas s'ouvrir. Celle-ci, si. »</p>
                <p class="escape-description">
                    Un homme a été retrouvé sans vie près du Jukebox. Le seul témoin ? Un chat noir au regard perspicace... Il n'attend que vous.<br>
                    <em>Il y a des secrets qui ne demandent qu'à être déterrés. Auriez-vous l'étoffe d'un vrai détective ?</em>
                </p>
            </div>

            <!-- Intégration Canva -->
            <div class="escape-frame">
                <iframe 
                    src="https://www.canva.com/design/DAHOmTXwE-k/0SWRSDjhjKvQwLdudD0-sg/view?embed" 
                    width="100%" 
                    height="550" 
                    allowfullscreen>
                </iframe>
            </div>

            <!-- Infos pratiques -->
            <div class="escape-infos">
                <h3>🔍 Infos & Réservation</h3>
                <ul>
                    <li><strong>⏱️ Durée :</strong> 45 minutes d'immersion</li>
                    <li><strong>👥 Public :</strong> 2 à 6 joueurs (idéal ados et adultes)</li>
                    <li><strong>🎟️ Tarif :</strong> 5€ par personne (gratuit pour les membres du Club)</li>
                    <li><strong>📅 Sur réservation :</strong> Choisissez votre session en ligne dès maintenant.</li>
                </ul>
            </div>

            <!-- Boutons retour & réservation -->
            <div class="escape-actions">
                <a href="index.php" class="btn-escape">🏠 Retour à l'accueil</a>
                <a href="formulaire.php" class="btn-escape-primary">🔎 Réserver un créneau</a>
            </div>

        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>