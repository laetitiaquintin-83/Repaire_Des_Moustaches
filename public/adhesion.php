<?php
// On remonte au dossier racine pour inclure le header
include '../includes/header.php';
?>

<!-- Styles spécifiques propres à la section adhésion -->
<style>
    .adhesion-wrapper {
        max-width: 1000px;
        margin: 40px auto 80px auto;
        padding: 0 20px;
        font-family: 'Montserrat', sans-serif;
    }

    /* Carte Principale aux couleurs du site */
    .adhesion-card {
        background: #FFFFFF;
        border-radius: 24px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        border: 2px solid #82CECA; /* Turquoise du header */
        overflow: hidden;
    }

    /* HERO IMAGE XXL (Entière, sans aucun rognage) */
    .adhesion-hero-img {
        width: 100%;
        background-color: #FFF8E7; /* Fond vanille/crème */
    }

    .adhesion-hero-img img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.5s ease;
    }

    .adhesion-card:hover .adhesion-hero-img img {
        transform: scale(1.01);
    }

    /* Contenu textuel & offre */
    .adhesion-body {
        padding: 40px;
        text-align: center;
        background-color: #FFFFFF;
    }

    .badge-tarif-vip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #FFF0F3;
        color: #FF7B7B;
        border: 1.5px dashed #F7B2B7;
        padding: 8px 18px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 15px;
    }

    .adhesion-body h1 {
        font-family: 'Pacifico', cursive;
        color: #FF7B7B; /* Même rose corail que "Rencontrez l'Équipage" */
        font-size: 2.8rem;
        margin: 0 0 15px 0;
        font-weight: normal;
    }

    .adhesion-body .subtitle {
        color: #5A4A42;
        font-size: 1.1rem;
        line-height: 1.6;
        max-width: 650px;
        margin: 0 auto 35px auto;
    }

    /* Grille d'Avantages VIP - BORDURES TURQUOISE IDENTIQUES À LA PAGE ÉQUIPAGE */
    .advantages-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
        text-align: left;
    }

    .adv-item {
        background: #FFFFFF;
        border: 2px solid #82CECA; /* Turquoise identique aux cartes chats */
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: flex-start;
        gap: 15px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .adv-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(130, 206, 202, 0.25);
    }

    .adv-icon {
        font-size: 2rem;
        line-height: 1;
    }

    .adv-text h3 {
        margin: 0 0 5px 0;
        color: #FF7B7B; /* Titres en Rose Corail */
        font-size: 1.05rem;
        font-weight: 700;
    }

    .adv-text p {
        margin: 0;
        color: #5A4A42;
        font-size: 0.88rem;
        line-height: 1.4;
    }

    /* Zone d'action et bouton */
    .action-zone {
        background: #FFF8E7; /* Fond crème identique au fond du site */
        border-radius: 18px;
        padding: 30px;
        border: 2px dashed #82CECA;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    .btn-adherer-xxl {
        display: inline-block;
        background-color: #75B898; /* Vert d'eau du badge adhésion */
        color: #FFFFFF !important;
        font-size: 1.25rem;
        font-weight: 700;
        padding: 16px 40px;
        border-radius: 50px;
        text-decoration: none;
        box-shadow: 0 8px 20px rgba(117, 184, 152, 0.3);
        transition: all 0.3s ease;
    }

    .btn-adherer-xxl:hover {
        background-color: #FF7B7B; /* Survol au rose corail du site */
        color: #FFFFFF !important;
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(255, 123, 123, 0.35);
    }

    .rassurance-list {
        display: flex;
        gap: 20px;
        color: #6a5356;
        font-size: 0.85rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .rassurance-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }
</style>

<div class="adhesion-wrapper">
    
    <div class="adhesion-card">
        
        <!-- BLOC IMAGE ENTIÈRE -->
        <div class="adhesion-hero-img">
            <img src="images/club-moustaches.png" alt="Le Club des Moustaches - Adhésion Annuelle">
        </div>

        <!-- CONTENU D'ADHÉSION -->
        <div class="adhesion-body">
            
            <span class="badge-tarif-vip">✨ Offre VIP • 5€ / an</span>
            
            <h1>Rejoins le Club des Moustaches !</h1>
            
            <p class="subtitle">
                En devenant membre officiel, tu offres un précieux coup de patte à nos chats secourus tout en débloquant des privilèges exclusifs tout au long de l'année au Repaire.
            </p>

            <!-- AVANTAGES EN GRILLE -->
            <div class="advantages-grid">
                
                <div class="adv-item">
                    <div class="adv-icon">💳</div>
                    <div class="adv-text">
                        <h3>Ta Carte Officielle</h3>
                        <p>Ta carte de membre physique personnalisée offerte lors de ta prochaine visite.</p>
                    </div>
                </div>

                <div class="adv-item">
                    <div class="adv-icon">☕</div>
                    <div class="adv-text">
                        <h3>10% de Réduction</h3>
                        <p>Valable sur toutes tes boissons, pâtisseries et douceurs au salon de thé.</p>
                    </div>
                </div>

                <div class="adv-item">
                    <div class="adv-icon">⭐</div>
                    <div class="adv-text">
                        <h3>Accès Prioritaire</h3>
                        <p>Inscriptions coupe-file réservées pour nos soirées et ateliers créatifs.</p>
                    </div>
                </div>

            </div>

            <!-- BOUTON & REASSURANCE -->
            <div class="action-zone">
                <a href="cart.php?action=add&type=adhesion" class="btn-adherer-xxl">
                    🐾 Devenir Membre pour 5€
                </a>

                <div class="rassurance-list">
                    <span class="rassurance-item">🔒 Paiement sécurisé</span>
                    <span class="rassurance-item">•</span>
                    <span class="rassurance-item">💛 Valable 1 an complet</span>
                    <span class="rassurance-item">•</span>
                    <span class="rassurance-item">🐱 100% solidaire</span>
                </div>
            </div>

        </div>

    </div>

</div>

<?php
// On remonte au dossier racine pour inclure le footer
include '../includes/footer.php';
?>