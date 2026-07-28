<?php
declare(strict_types=1);

$sitePrefix = ''; 
include_once '../includes/header.php'; 
?>

<main class="legal-page">
    <div class="cgv-container">
        <header class="cgv-header">
            <span class="cgv-badge">📜 Document officiel</span>
            <h1 class="page-title">Conditions Générales de Vente</h1>
            <p class="cgv-intro">Bienvenue dans le cadre légal du Repaire des Moustaches. Ces règles garantissent des échanges clairs et sereins entre vous et notre association.</p>
        </header>

        <div class="cgv-grid">
            <section class="cgv-card">
                <div class="cgv-icon">🎯</div>
                <div class="cgv-content">
                    <h2>1. Objet</h2>
                    <p>Les présentes Conditions Générales de Vente régissent l'ensemble des ventes de produits, d'adhésions et de réservations d'ateliers effectuées sur le site internet du <strong>Repaire des Moustaches</strong>.</p>
                </div>
            </section>

            <section class="cgv-card">
                <div class="cgv-icon">🏷️</div>
                <div class="cgv-content">
                    <h2>2. Prix</h2>
                    <p>Les prix affichés sur notre plateforme sont indiqués en euros toutes taxes comprises (TTC). L'association se réserve le droit de modifier ses prix à tout moment, les produits étant facturés sur la base des tarifs en vigueur lors de la validation.</p>
                </div>
            </section>

            <section class="cgv-card">
                <div class="cgv-icon">🛒</div>
                <div class="cgv-content">
                    <h2>3. Commandes & Réservations</h2>
                    <p>Les commandes s'effectuent en ligne via le panier. Toute commande ou réservation validée vaut acceptation pleine et entière des présentes CGV sans réserve.</p>
                </div>
            </section>

            <section class="cgv-card">
                <div class="cgv-icon">💳</div>
                <div class="cgv-content">
                    <h2>4. Paiement</h2>
                    <p>Les paiements sont 100% sécurisés. <em>(Note de démonstration : Dans le cadre de ce projet d'examen, les transactions financières sont simulées via des environnements de test).</em></p>
                </div>
            </section>

            <section class="cgv-card">
                <div class="cgv-icon">📦</div>
                <div class="cgv-content">
                    <h2>5. Livraison & Retrait</h2>
                    <p>Les goodies et produits de la boutique sont disponibles en retrait sur place ("Click & Collect" au Repaire) ou expédiés selon les options sélectionnées lors de la commande.</p>
                </div>
            </section>

            <section class="cgv-card">
                <div class="cgv-icon">↩️</div>
                <div class="cgv-content">
                    <h2>6. Droit de rétractation</h2>
                    <p>Conformément à la réglementation, vous disposez d'un délai légal de 14 jours à compter de la réception de vos achats physiques pour exercer votre droit de rétractation.</p>
                </div>
            </section>

            <section class="cgv-card">
                <div class="cgv-icon">🔒</div>
                <div class="cgv-content">
                    <h2>7. Protection des données</h2>
                    <p>Vos données personnelles sont uniquement collectées pour le traitement de vos commandes et ateliers. Elles ne seront jamais revendues, conformément au RGPD.</p>
                </div>
            </section>
        </div>

        <footer class="cgv-footer">
            <p>Dernière mise à jour : <strong>Juillet 2026</strong></p>
        </footer>
    </div>
</main>

<style>
    .legal-page {
        padding: 40px 20px;
        max-width: 900px;
        margin: 0 auto;
    }

    .cgv-container {
        background-color: #ffffff;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .cgv-header {
        text-align: center;
        margin-bottom: 40px;
        border-bottom: 2px dashed rgba(133, 214, 205, 0.4);
        padding-bottom: 30px;
    }

    .cgv-badge {
        display: inline-block;
        background-color: var(--vert-menthe);
        color: var(--gris-fonce);
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .cgv-header .page-title {
        font-family: 'Pacifico', cursive;
        color: var(--rose-corail);
        font-size: 2.2rem;
        margin-bottom: 15px;
    }

    .cgv-intro {
        color: #666;
        font-size: 1rem;
        line-height: 1.6;
        max-width: 650px;
        margin: 0 auto;
    }

    .cgv-grid {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .cgv-card {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        background-color: #fafafa;
        padding: 20px 25px;
        border-radius: 12px;
        border-left: 4px solid var(--vert-menthe);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .cgv-card:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-left-color: var(--rose-corail);
    }

    .cgv-icon {
        font-size: 1.8rem;
        background: #ffffff;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        line-height: 1;
    }

    .cgv-content h2 {
        font-size: 1.15rem;
        color: var(--gris-fonce);
        margin-bottom: 8px;
        font-weight: bold;
    }

    .cgv-content p {
        color: #555;
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 0;
    }

    .cgv-footer {
        margin-top: 35px;
        text-align: center;
        font-size: 0.9rem;
        color: #888;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }
</style>

<?php include_once '../includes/footer.php'; ?>