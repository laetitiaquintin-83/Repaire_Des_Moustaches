<?php
declare(strict_types=1);

// Debug local (à désactiver en production)

$sitePrefix = '../';
$siteProtocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 80) == 443 ? 'https' : 'http';
$siteHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
$siteUrl = $siteProtocol . '://' . $siteHost;

include_once '../includes/header.php';
?>

<main class="fb-mockup-page">
    <!-- Bandeau informatif pour le jury -->
    <div class="demo-banner">
        🚀 <strong>Mode Démonstration :</strong> Aperçu de la future page Facebook officielle du Repaire des Moustaches.
    </div>

    <!-- Leurre Profil Facebook -->
    <div class="fb-container">
        <!-- Photo de couverture & Profil -->
        <div class="fb-cover">
            <!-- Nouvelle image Unsplash bien cadrée pour une bannière -->
            <img src="https://images.unsplash.com/photo-1513360371669-4adf3dd7dff8?w=1200&q=80" alt="Couverture Repaire des Moustaches" class="cover-img">
            <div class="fb-avatar-container">
                <img src="../assets/img/logo.png" alt="Le Repaire des Moustaches" class="fb-avatar" onerror="this.src='https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=200'">
            </div>
        </div>

        <!-- En-tête Infos Facebook -->
        <div class="fb-profile-header">
            <div class="fb-profile-info">
                <h1>Le Repaire des Moustaches</h1>
                <p class="fb-likes"><strong>1.8k</strong> J'aime • <strong>2.1k</strong> abonnés</p>
            </div>
            <div class="fb-actions">
                <button class="btn-fb-like" onclick="alert('Merci pour votre soutien ! La page ouvrira prochainement.')">
                    👍 J'aime
                </button>
                <a href="index.php" class="btn-fb-message">🌐 Visiter le site</a>
            </div>
        </div>

        <!-- Navigation interne style FB -->
        <div class="fb-nav-tabs">
            <span class="active">Publications</span>
            <span>À propos</span>
            <span>Avis</span>
            <span>Photos</span>
        </div>

        <!-- Corps de la page (2 colonnes façon FB Desktop) -->
        <div class="fb-content-grid">
            <!-- Colonne Gauche : À propos -->
            <div class="fb-sidebar">
                <div class="fb-card">
                    <h3>À propos</h3>
                    <ul class="fb-about-list">
                        <li>🐾 <strong>Tiers-lieu & Café solidaire</strong></li>
                        <li>📍 Toulon, France</li>
                        <li>🌐 <a href="<?php echo htmlspecialchars($siteUrl, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($siteHost, ENT_QUOTES, 'UTF-8'); ?></a></li>
                        <li>⏰ Ouvert du mardi au dimanche</li>
                    </ul>
                </div>
            </div>

            <!-- Colonne Droite : Fil d'actualités -->
            <div class="fb-feed">
                <!-- Post 1 -->
                <div class="fb-card fb-post">
                    <div class="fb-post-header">
                        <img src="../assets/img/logo.png" alt="Logo" class="fb-post-avatar" onerror="this.src='https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=100'">
                        <div>
                            <h4>Le Repaire des Moustaches</h4>
                            <span class="fb-post-time">Hier à 14:30 • 🌍</span>
                        </div>
                    </div>
                    <div class="fb-post-body">
                        <p>Bienvenue sur la page officielle du Repaire des Moustaches ! 🐾☕<br>
                        Venez déguster une boisson chaude tout en entourant nos pensionnaires à quatre pattes. Ateliers, petite restauration et adoptions vous attendent à Toulon !</p>
                    </div>
                    <div class="fb-post-media">
                        <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=800" alt="Chat au Repaire">
                    </div>
                    <div class="fb-post-footer">
                        <span>❤️ 👍 48</span>
                        <span>12 commentaires • 5 partages</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .fb-mockup-page {
        padding: 30px 15px;
        max-width: 950px;
        margin: 0 auto;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .demo-banner {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
        padding: 12px 20px;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 25px;
        font-size: 0.95rem;
    }

    .fb-container {
        background: #ffffff;
        border: 1px solid #e4e6eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    /* Couverture & Avatar */
    .fb-cover {
        position: relative;
        height: 280px;
        background: #ced0d4;
    }

    .cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Positionne la photo idéalement sur les yeux / la tête du chat */
        object-position: center 20%;
    }

    .fb-avatar-container {
        position: absolute;
        bottom: -30px;
        left: 30px;
        z-index: 2;
    }

    .fb-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 4px solid #ffffff;
        object-fit: cover;
        background: #ffffff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    /* En-tête profil */
    .fb-profile-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        padding: 15px 30px 15px 190px;
        border-bottom: 1px solid #e4e6eb;
        flex-wrap: wrap;
        gap: 15px;
        background: #ffffff;
    }

    .fb-profile-info h1 {
        margin: 0 0 5px 0;
        font-size: 1.6rem;
        color: #050505;
    }

    .fb-likes {
        margin: 0;
        color: #65676b;
        font-size: 0.9rem;
    }

    .fb-actions {
        display: flex;
        gap: 10px;
    }

    .btn-fb-like {
        background-color: #1877f2;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
    }

    .btn-fb-like:hover {
        background-color: #166fe5;
    }

    .btn-fb-message {
        background-color: #e4e6eb;
        color: #050505;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 0.9rem;
    }

    /* Tabs */
    .fb-nav-tabs {
        display: flex;
        gap: 20px;
        padding: 0 30px;
        border-bottom: 1px solid #e4e6eb;
        background: #ffffff;
    }

    .fb-nav-tabs span {
        padding: 12px 0;
        color: #65676b;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
    }

    .fb-nav-tabs span.active {
        color: #1877f2;
        border-bottom: 3px solid #1877f2;
    }

    /* Grille de contenu */
    .fb-content-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 20px;
        padding: 20px;
        background: #f0f2f5;
    }

    .fb-card {
        background: #ffffff;
        border-radius: 8px;
        padding: 15px;
        border: 1px solid #e4e6eb;
    }

    .fb-card h3 {
        margin-top: 0;
        font-size: 1.1rem;
        color: #050505;
    }

    .fb-about-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .fb-about-list li {
        margin-bottom: 10px;
        color: #050505;
        font-size: 0.9rem;
    }

    .fb-about-list a {
        color: #1877f2;
        text-decoration: none;
    }

    /* Posts */
    .fb-post-header {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 12px;
    }

    .fb-post-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .fb-post-header h4 {
        margin: 0;
        font-size: 0.95rem;
        color: #050505;
    }

    .fb-post-time {
        font-size: 0.8rem;
        color: #65676b;
    }

    .fb-post-body {
        margin-bottom: 12px;
        font-size: 0.95rem;
        color: #050505;
        line-height: 1.4;
    }

    .fb-post-media img {
        width: 100%;
        border-radius: 6px;
        max-height: 350px;
        object-fit: cover;
    }

    .fb-post-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #e4e6eb;
        color: #65676b;
        font-size: 0.85rem;
    }

    @media (max-width: 768px) {
        .fb-content-grid { grid-template-columns: 1fr; }
        .fb-profile-header { padding: 80px 20px 20px 20px; text-align: center; justify-content: center; }
        .fb-avatar-container { left: 50%; transform: translateX(-50%); }
    }
</style>

<?php include_once '../includes/footer.php'; ?>