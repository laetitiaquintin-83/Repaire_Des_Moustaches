<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$sitePrefix = '';
require_once __DIR__ . '/../config/database.php';

// Code PHP de traitement des dons (sécurisé)
$message_status = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_don = trim($_POST['type_don'] ?? '');
    $mot_doux = trim($_POST['mot_doux'] ?? '');
    
    if (!empty($type_don)) {
        // Enregistrement fictif ou en BDD du don
        $message_status = "🎉 Merci du fond du cœur pour votre Patte Suspendue ! Votre générosité fera un(e) heureux(se) au Repaire.";
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<style>
/* --- STYLES CHARTE EXACTE : LA PATTE SUSPENDUE --- */
.solidaire-container {
    max-width: 1000px;
    margin: 40px auto 70px auto;
    padding: 0 20px;
}

.solidaire-header {
    text-align: center;
    margin-bottom: 40px;
}

.solidaire-header h1 {
    font-family: 'Pacifico', cursive;
    color: #FF7B7B; /* Rose corail du site */
    font-size: 3.2rem;
    margin-bottom: 10px;
    font-weight: normal;
}

.solidaire-header p {
    font-family: 'Montserrat', sans-serif;
    color: #5A4A42;
    font-size: 1.15rem;
    max-width: 680px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Hero Section avec la photo */
.solidaire-hero {
    display: flex;
    gap: 40px;
    align-items: center;
    background: #FFFFFF;
    border: 3px solid #72C2BB; /* Vert menthe/turquoise de ton header */
    border-radius: 24px;
    padding: 30px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    margin-bottom: 50px;
    flex-wrap: wrap;
}

.solidaire-hero-img {
    flex: 1;
    min-width: 300px;
}

.solidaire-hero-img img {
    width: 100%;
    height: auto;
    border-radius: 18px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

.solidaire-hero-text {
    flex: 1.2;
    min-width: 300px;
    font-family: 'Montserrat', sans-serif;
}

.solidaire-hero-text h2 {
    color: #802C38; /* Bordeaux sombre pour la lisibilité des sous-titres */
    font-size: 1.6rem;
    margin-bottom: 15px;
    line-height: 1.3;
    font-weight: 700;
}

.solidaire-hero-text p {
    color: #4A5568;
    line-height: 1.7;
    font-size: 1rem;
    margin-bottom: 15px;
}

/* Compteur de solidarité */
.solidaire-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 50px;
    text-align: center;
}

.stat-card {
    background: #FFFFFF;
    border: 2px dashed #72C2BB;
    border-radius: 18px;
    padding: 20px;
    font-family: 'Montserrat', sans-serif;
}

.stat-number {
    font-size: 2.4rem;
    font-weight: 800;
    color: #FF7B7B;
    display: block;
}

.stat-label {
    color: #5A4A42;
    font-weight: 600;
    font-size: 0.95rem;
}

/* Cartes d'actions / Dons */
.dons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 50px;
}

.don-card {
    background: #FFFFFF;
    border: 2px solid #72C2BB; /* Bordure menthe/turquoise identique aux cartes équipage */
    border-radius: 20px;
    padding: 25px;
    text-align: center;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.don-card:hover {
    border-color: #FF7B7B;
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(255, 123, 123, 0.2);
}

.don-icon {
    font-size: 2.8rem;
    margin-bottom: 10px;
}

.don-title {
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    color: #802C38;
    font-size: 1.2rem;
    margin-bottom: 8px;
}

.don-price {
    font-family: 'Pacifico', cursive;
    color: #FF7B7B;
    font-size: 1.8rem;
    margin-bottom: 12px;
}

.don-desc {
    font-family: 'Montserrat', sans-serif;
    color: #718096;
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 20px;
}

.btn-don {
    background-color: #FF7B7B; /* Corail bouton Réserver */
    color: #FFFFFF;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    padding: 12px 20px;
    border-radius: 50px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 10px rgba(255, 123, 123, 0.3);
}

.btn-don:hover {
    background-color: #802C38;
    transform: scale(1.03);
}

/* Section Bénéficier */
.beneficier-box {
    background: #FFFFFF;
    border: 3px solid #72C2BB;
    border-radius: 20px;
    padding: 30px;
    font-family: 'Montserrat', sans-serif;
}

.beneficier-box h3 {
    color: #802C38;
    font-size: 1.3rem;
    margin-bottom: 10px;
}

.beneficier-box p {
    color: #4A5568;
    line-height: 1.6;
    margin: 0;
}
</style>

<div class="solidaire-container">
    
    <div class="solidaire-header">
        <h1>La Patte Suspendue 🐾</h1>
        <p>Un geste simple et anonyme : offrir une pause douceur à quelqu'un qui en a besoin.</p>
    </div>

    <!-- Hero avec la photo en WEBP -->
    <div class="solidaire-hero">
        <div class="solidaire-hero-img">
            <img src="images/patte-suspendue.webp" alt="Deux chats solidaires au Repaire des Moustaches">
        </div>
        <div class="solidaire-hero-text">
            <h2>Aujourd'hui j'ai un peu plus, demain j'aurai besoin.</h2>
            <p>Inspiré de la tradition italienne du <em>« Caffè Sospeso »</em>, la Patte Suspendue permet à chacun d'acheter une boisson, un goûter ou une adhésion supplémentaire lors de sa visite ou en ligne.</p>
            <p>Ces dons sont mis de côté pour permettre à toute personne traversant un moment difficile de venir se ressourcer, boire un café chaud et profiter de la présence apaisante de nos chats, sans aucune justification.</p>
        </div>
    </div>

    <!-- Compteur solidaire -->
    <div class="solidaire-stats">
        <div class="stat-card">
            <span class="stat-number">14</span>
            <span class="stat-label">☕ Cafés suspendus disponibles</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">6</span>
            <span class="stat-label">🍰 Goûters offerts en attente</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">3</span>
            <span class="stat-label">🎨 Ateliers créatifs réservés</span>
        </div>
    </div>

    <!-- Choisir un don -->
    <h2 style="text-align: center; font-family: 'Pacifico', cursive; color: #FF7B7B; margin-bottom: 30px; font-weight: normal; font-size: 2.4rem;">Offrir un coup de patte 💛</h2>

    <div class="dons-grid">
        <!-- Option 1 -->
        <div class="don-card">
            <div>
                <div class="don-icon">☕</div>
                <div class="don-title">Le Café Suspendu</div>
                <div class="don-price">2,50 €</div>
                <div class="don-desc">Offre un café, un thé chaud ou une infusion réconfortante à un(e) inconnu(e).</div>
            </div>
            <button class="btn-don">Offrir un café 🐾</button>
        </div>

        <!-- Option 2 -->
        <div class="don-card">
            <div>
                <div class="don-icon">🧁</div>
                <div class="don-title">Le Goûter Douceur</div>
                <div class="don-price">6,00 €</div>
                <div class="don-desc">Offre une boisson gourmande et une pâtisserie artisanale du Repaire.</div>
            </div>
            <button class="btn-don">Offrir un goûter 🐾</button>
        </div>

        <!-- Option 3 -->
        <div class="don-card">
            <div>
                <div class="don-icon">🎨</div>
                <div class="don-title">L'Atelier Solidaire</div>
                <div class="don-price">15,00 €</div>
                <div class="don-desc">Permet à une personne d'assister gratuitement à un atelier créatif ou de ronronthérapie.</div>
            </div>
            <button class="btn-don">Offrir un atelier 🐾</button>
        </div>
    </div>

    <!-- Comment en bénéficier -->
    <div class="beneficier-box">
        <h3>🤝 Vous avez besoin d'un moment de répit ?</h3>
        <p>Pas besoin de vous justifier ou de vous expliquer. Quand vous venez au Repaire, demandez simplement discrètement au comptoir : <strong>« Est-ce qu'il reste une Patte Suspendue aujourd'hui ? »</strong>. L'équipe vous accueillera avec grand plaisir et le sourire !</p>
    </div>

</div>

<?php 
if (file_exists(__DIR__ . '/includes/footer.php')) {
    require_once __DIR__ . '/includes/footer.php';
} else {
    require_once __DIR__ . '/../includes/footer.php';
}
?>