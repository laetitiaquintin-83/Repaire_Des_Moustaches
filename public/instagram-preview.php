<?php
declare(strict_types=1);

// Debug local (à désactiver en production)

$sitePrefix = '../';
$siteProtocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 80) == 443 ? 'https' : 'http';
$siteHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
$siteUrl = $siteProtocol . '://' . $siteHost;

include_once '../includes/header.php';
?>

<main class="insta-mockup-page">
    <!-- Bandeau informatif pour le jury -->
    <div class="demo-banner">
        🚀 <strong>Mode Démonstration :</strong> Aperçu du futur compte officiel Instagram du Repaire des Moustaches.
    </div>

    <!-- Leurre Profil Instagram -->
    <div class="insta-container">
        <!-- En-tête Profil -->
        <header class="insta-header">
            <div class="insta-avatar">
                <img src="../assets/img/logo.png" alt="Le Repaire des Moustaches" onerror="this.src='https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=200'">
            </div>
            <div class="insta-info">
                <div class="insta-title-row">
                    <h2>repaire_des_moustaches</h2>
                    <button class="btn-follow" onclick="alert('Merci pour votre intérêt ! Le compte ouvrira prochainement.')">S'abonner</button>
                </div>
                <ul class="insta-stats">
                    <li><strong>6</strong> publications</li>
                    <li><strong>1.2k</strong> abonnés</li>
                    <li><strong>42</strong> abonnements</li>
                </ul>
                <div class="insta-bio">
                    <p><strong>Le Repaire des Moustaches 🐾</strong></p>
                    <p>☕ Café & Tiers-lieu solidaire pour chats & humains</p>
                    <p>📍 Toulon, France</p>
                    <p>👇 Réservez vos ateliers & commandes sur notre site :</p>
                    <a href="<?php echo htmlspecialchars($siteUrl, ENT_QUOTES, 'UTF-8'); ?>" class="insta-link"><?php echo htmlspecialchars($siteHost, ENT_QUOTES, 'UTF-8'); ?></a>
                </div>
            </div>
        </header>

        <!-- Grille de posts (Mockup) -->
        <div class="insta-grid">
            <div class="insta-post">
                <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=500" alt="Chat 1">
                <div class="insta-post-overlay">❤️ 142 • 💬 12</div>
            </div>
            <div class="insta-post">
                <img src="https://images.unsplash.com/photo-1573865526739-10659fec78a5?w=500" alt="Chat 2">
                <div class="insta-post-overlay">❤️ 98 • 💬 5</div>
            </div>
            <div class="insta-post">
                <img src="https://images.unsplash.com/photo-1533738363-b7f9aef128ce?w=500" alt="Chat 3">
                <div class="insta-post-overlay">❤️ 210 • 💬 34</div>
            </div>
            <div class="insta-post">
                <img src="https://images.unsplash.com/photo-1495360010541-f48722b34f7d?w=500" alt="Chat 4">
                <div class="insta-post-overlay">❤️ 175 • 💬 19</div>
            </div>
            <div class="insta-post">
                <img src="https://images.unsplash.com/photo-1561948955-570b270e7c36?w=500" alt="Chat 5">
                <div class="insta-post-overlay">❤️ 320 • 💬 45</div>
            </div>
            <div class="insta-post">
                <img src="https://images.unsplash.com/photo-1519052537078-e6302a4968d4?w=500" alt="Chat 6">
                <div class="insta-post-overlay">❤️ 89 • 💬 2</div>
            </div>
        </div>
    </div>
</main>

<style>
    .insta-mockup-page {
        padding: 30px 15px;
        max-width: 935px;
        margin: 0 auto;
    }

    .demo-banner {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
        padding: 12px 20px;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 30px;
        font-size: 0.95rem;
    }

    .insta-container {
        background: #ffffff;
        border: 1px solid #dbdbdb;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .insta-header {
        display: flex;
        gap: 40px;
        margin-bottom: 40px;
        align-items: center;
        background: #ffffff;
        border-radius: 8px;
    }

    .insta-avatar img {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        border: 3px solid #ff7b7b;
        padding: 3px;
        object-fit: cover;
        background: #ffffff;
    }

    .insta-info {
        flex: 1;
    }

    .insta-title-row {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .insta-title-row h2 {
        font-size: 1.4rem;
        font-weight: 400;
        margin: 0;
        color: #262626;
        font-family: system-ui, -apple-system, sans-serif;
    }

    .btn-follow {
        background-color: #ff7b7b;
        color: white;
        border: none;
        padding: 8px 18px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-follow:hover {
        background-color: #e06666;
    }

    .insta-stats {
        display: flex;
        gap: 25px;
        list-style: none;
        padding: 0;
        margin-bottom: 15px;
        color: #262626;
        font-family: system-ui, -apple-system, sans-serif;
    }

    .insta-bio p {
        margin: 3px 0;
        color: #262626;
        font-size: 0.95rem;
        font-family: system-ui, -apple-system, sans-serif;
    }

    .insta-link {
        color: #00376b;
        font-weight: bold;
        text-decoration: none;
    }

    .insta-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        border-top: 1px solid #dbdbdb;
        padding-top: 30px;
    }

    .insta-post {
        position: relative;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        border-radius: 8px;
        cursor: pointer;
    }

    .insta-post img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .insta-post-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .insta-post:hover img {
        transform: scale(1.05);
    }

    .insta-post:hover .insta-post-overlay {
        opacity: 1;
    }

    @media (max-width: 600px) {
        .insta-header { flex-direction: column; text-align: center; }
        .insta-title-row { justify-content: center; }
        .insta-stats { justify-content: center; }
    }
</style>

<?php include_once '../includes/footer.php'; ?>