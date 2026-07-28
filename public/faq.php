<?php
// On inclut le bon header dans /includes/
require_once __DIR__ . '/../includes/header.php';

$page_description = "Foire Aux Questions (FAQ) du Repaire des Moustaches à Toulon. Toutes les réponses sur l'ouverture, les chats, et les ateliers.";
?>

<style>
/* --- STYLES FAQ SUR-MESURE --- */

.faq-container {
    max-width: 900px;
    margin: 30px auto 60px auto;
    padding: 0 20px;
}

.faq-header {
    text-align: center;
    margin-bottom: 40px;
}

/* L'IMAGE : Plus grande et bien mise en valeur ! */
.faq-hero-img {
    max-width: 420px; /* Augmentée pour un vrai impact visuel */
    width: 100%;
    height: auto;
    margin-bottom: 25px;
    border-radius: 24px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.faq-header h1 {
    font-family: 'Pacifico', cursive;
    color: #ff7b7b; /* Rose/Corail exact du site */
    font-size: 2.8rem;
    font-weight: normal;
    margin-bottom: 12px;
}

.faq-header p {
    font-family: 'Montserrat', sans-serif;
    color: #555555;
    font-size: 1.1rem;
    font-weight: 500;
}

/* CARTE FAQ : Bordure Turquoise & Fond Blanc Pur */
.faq-list {
    background: #FFFFFF;
    border: 3px solid #82ceca; /* Turquoise signature du header */
    border-radius: 24px;
    padding: 35px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.04);
}

.faq-item {
    border-bottom: 1px solid #F2EFE9;
    padding: 20px 0;
}

.faq-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.faq-item:first-child {
    padding-top: 0;
}

/* QUESTIONS : Le vrai bordeaux du site (#802c38) */
.faq-question {
    font-family: 'Montserrat', sans-serif;
    color: #802c38; /* Couleur bordeaux exacte */
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    user-select: none;
    transition: color 0.2s ease;
}

.faq-question:hover {
    color: #ff7b7b; /* Switch au rose au survol */
}

/* Le signe + / - */
.faq-toggle-icon {
    font-size: 1.4rem;
    font-weight: bold;
    color: #ff7b7b; /* Rose corail */
    transition: transform 0.3s ease;
    line-height: 1;
}

/* RÉPONSES */
.faq-answer {
    font-family: 'Montserrat', sans-serif;
    color: #4A5568;
    line-height: 1.6;
    font-size: 0.98rem;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.35s ease-in-out, margin-top 0.3s ease;
    margin-top: 0;
}

.faq-item.active .faq-answer {
    max-height: 300px;
    margin-top: 14px;
}
</style>

<div class="faq-container">
    <div class="faq-header">
        <!-- Ton illustration vintage maintenant bien grande ! -->
        <img src="images/faq-cat.webp" alt="Une question ? Nos ronrons ont la réponse !" class="faq-hero-img">
        
        <h1>Nos Ronrons ont la Réponse !</h1>
        <p>Tout ce que vous avez toujours voulu savoir sur le Repaire sans oser le miauler.</p>
    </div>

    <div class="faq-list">
        <!-- Question 1 -->
        <div class="faq-item">
            <h2 class="faq-question">
                <span>Puis-je venir avec mon propre animal ?</span>
                <span class="faq-toggle-icon">+</span>
            </h2>
            <div class="faq-answer">
                <p>Pour la sécurité et la sérénité de nos chats résidents ainsi que du vôtre, les animaux extérieurs ne sont malheureusement pas admis dans le tiers-lieu. Nos petits moustachus sur place se feront un plaisir de combler votre besoin de câlins !</p>
            </div>
        </div>

        <!-- Question 2 -->
        <div class="faq-item">
            <h2 class="faq-question">
                <span>Les enfants sont-ils les bienvenus ?</span>
                <span class="faq-toggle-icon">+</span>
            </h2>
            <div class="faq-answer">
                <p>Absolument ! Les enfants sont accueillis avec grand plaisir sous la responsabilité d'un adulte. C'est l'endroit parfait pour leur apprendre à interagir doucement avec les animaux et respecter leur sommeil.</p>
            </div>
        </div>

        <!-- Question 3 -->
        <div class="faq-item">
            <h2 class="faq-question">
                <span>À quoi sert l'adhésion à 5 € par an ?</span>
                <span class="faq-toggle-icon">+</span>
            </h2>
            <div class="faq-answer">
                <p>L'adhésion au Club des Moustaches soutient directement la prise en charge des chats (soins, nourriture). Elle vous donne aussi droit à des avantages exclusifs : réductions sur les ateliers, accès aux soirées membres et préventes !</p>
            </div>
        </div>

        <!-- Question 4 -->
        <div class="faq-item">
            <h2 class="faq-question">
                <span>Où en est l'ouverture du lieu physique à Toulon ?</span>
                <span class="faq-toggle-icon">+</span>
            </h2>
            <div class="faq-answer">
                <p>Le Repaire est actuellement en phase de « nidation » ! Nous recherchons activement le local idéal à Toulon. En attendant, notre boutique en ligne et nos événements hors-les-murs sont bien actifs.</p>
            </div>
        </div>

        <!-- Question 5 -->
        <div class="faq-item">
            <h2 class="faq-question">
                <span>Peut-on adopter un chat du Repaire ?</span>
                <span class="faq-toggle-icon">+</span>
            </h2>
            <div class="faq-answer">
                <p>Oui ! Tous nos chats résidents sont issus de nos associations et refuges partenaires. Si vous avez un coup de cœur, nous vous mettrons en relation avec la structure référente pour suivre le parcours d'adoption responsable.</p>
            </div>
        </div>
    </div>
</div>

<script>
// Animation Accordéon
document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', () => {
        const item = question.parentElement;
        const icon = question.querySelector('.faq-toggle-icon');
        
        // Ferme les autres items
        document.querySelectorAll('.faq-item').forEach(otherItem => {
            if (otherItem !== item) {
                otherItem.classList.remove('active');
                const otherIcon = otherItem.querySelector('.faq-toggle-icon');
                if (otherIcon) otherIcon.textContent = '+';
            }
        });

        // Bascule l'état de l'élément actuel
        const isActive = item.classList.toggle('active');
        if (icon) {
            icon.textContent = isActive ? '−' : '+';
        }
    });
});
</script>

<?php 
if (file_exists(__DIR__ . '/../includes/footer.php')) {
    require_once __DIR__ . '/../includes/footer.php';
}
?>
</body>
</html>