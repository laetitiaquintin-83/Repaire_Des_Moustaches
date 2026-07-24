<?php
// On remonte au dossier racine pour inclure le header
include '../includes/header.php';
?>

<!-- Styles spécifiques propres à la section adhésion -->
<style>
    .adhesion-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 30px;
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        align-items: center;
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .adhesion-image {
        flex: 1;
        min-width: 280px;
    }

    .adhesion-image img {
        width: 100%;
        height: auto;
        border-radius: 12px;
        border: 4px solid #F7B2B7; /* Rose pastel */
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        display: block;
    }

    .adhesion-content {
        flex: 1;
        min-width: 280px;
    }

    .adhesion-content h1 {
        font-family: 'Pacifico', cursive;
        color: #802C38;
        font-size: 2.2rem;
        margin-top: 10px;
        margin-bottom: 15px;
    }

    .badge-tarif {
        display: inline-block;
        background-color: #F7B2B7;
        color: #802C38;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
        text-transform: uppercase;
    }

    .adhesion-content p {
        line-height: 1.6;
        color: #4a4a4a;
        margin-bottom: 15px;
    }

    .adhesion-content ul {
        list-style: none;
        padding: 0;
        margin-bottom: 25px;
    }

    .adhesion-content li {
        margin-bottom: 10px;
        font-weight: 500;
    }

    .btn-adherer {
        display: inline-block;
        background-color: #75B898; /* Vert pastel */
        color: white;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-adherer:hover {
        background-color: #5fa383;
        transform: translateY(-2px);
    }
</style>

<main class="adhesion-container">
    
    <!-- BLOC IMAGE -->
    <div class="adhesion-image">
        <img src="../images/club-moustaches.png" alt="Club des Moustaches">
    </div>

    <!-- BLOC TEXTE & OFFRE -->
    <div class="adhesion-content">
        <span class="badge-tarif">Carte de Membre • 5€ / an</span>
        <h1>Rejoins le Club des Moustaches !</h1>
        
        <p>
            En devenant membre, tu soutiens directement le café et nos petits protégés à quatre pattes. 
            Une simple contribution pour faire vivre le lieu et profiter d'avantages exclusifs !
        </p>

        <ul>
            <li>🐾 Ta carte de membre physique personnalisée</li>
            <li>☕ 10% de réduction sur toutes tes consommations</li>
            <li>⭐ Accès prioritaire aux événements et soirées spéciales</li>
        </ul>

        <!-- Redirection vers le panier cart.php -->
      <a href="cart.php?action=add&type=adhesion" class="btn-adherer">Prendre ma carte (5€)</a>
    </div>

</main>

<?php
// On remonte au dossier racine pour inclure le footer
include '../includes/footer.php';
?>