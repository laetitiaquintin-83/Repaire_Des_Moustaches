<?php
declare(strict_types=1);

$sitePrefix = ''; 
include_once '../includes/header.php'; 
?>

<main class="legal-page">
    <div class="cgv-container">
        <header class="cgv-header">
            <span class="cgv-badge">⚖️ Informations légales</span>
            <h1 class="page-title">Mentions Légales</h1>
            <p class="cgv-intro">Conformément aux dispositions de la loi pour la confiance dans l'économie numérique, voici les informations juridiques relatives au site du Repaire des Moustaches.</p>
        </header>

        <div class="cgv-grid">
            <section class="cgv-card">
                <div class="cgv-icon">🏢</div>
                <div class="cgv-content">
                    <h2>Éditeur du site</h2>
                    <p><strong>Le Repaire des Moustaches</strong> – Association Loi 1901 (Tiers-lieu solidaire)</p>
                    <p>📍 <strong>Siège social :</strong> Toulon (83000), Var, France</p>
                    <p>✉️ <strong>Email :</strong> contact@repaire-des-moustaches.fr</p>
                    <p>👤 <strong>Responsable de la publication :</strong> Équipe Projet Le Repaire des Moustaches</p>
                </div>
            </section>

            <section class="cgv-card">
                <div class="cgv-icon">🖥️</div>
                <div class="cgv-content">
                    <h2>Hébergement & Démonstration</h2>
                    <p>Ce site est actuellement hébergé dans un environnement de développement local (Laragon) dans le cadre de la soutenance du diplôme <strong>Développeur Web et Web Mobile (DWWM 2026)</strong>.</p>
                    <p><em>En environnement de production, l'hébergement sera confié à un prestataire certifié (ex: O2Switch).</em></p>
                </div>
            </section>

            <section class="cgv-card">
                <div class="cgv-icon">🎨</div>
                <div class="cgv-content">
                    <h2>Propriété intellectuelle</h2>
                    <p>L'ensemble des éléments graphiques, logos, illustrations (notamment les créations originales et univers retro-vintage) et textes présents sur ce site sont la propriété exclusive du <strong>Repaire des Moustaches</strong>, sauf mention contraire explicite.</p>
                </div>
            </section>

            <section class="cgv-card">
                <div class="cgv-icon">🛡️</div>
                <div class="cgv-content">
                    <h2>Données personnelles (RGPD)</h2>
                    <p>Les informations recueillies via nos formulaires (inscription, contact, réservations) sont enregistrées exclusivement pour la gestion de nos services.</p>
                    <p>Conformément au RGPD, vous bénéficiez d'un droit d'accès, de rectification et de suppression de vos données en nous écrivant directement par email.</p>
                </div>
            </section>
        </div>

        <footer class="cgv-footer">
            <p>Projet présenté dans le cadre du titre professionnel DWWM • <strong>2026</strong></p>
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
        margin: 0 0 6px 0;
    }

    .cgv-content p:last-child {
        margin-bottom: 0;
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