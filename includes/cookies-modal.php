<style>
/* --- BANNIÈRE COOKIES RÉTRO --- */
#cookie-banner {
    position: fixed;
    bottom: 20px;
    left: 20px;
    right: 20px;
    max-width: 500px;
    margin: 0 auto;
    background: #FFFFFF;
    border: 3px solid #82CECA;
    border-radius: 20px;
    padding: 20px 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    z-index: 9999;
    font-family: 'Montserrat', sans-serif;
    display: none; /* Masqué par défaut */
    animation: slideUp 0.4s ease-out forwards;
}

@keyframes slideUp {
    from { transform: translateY(100px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

#cookie-banner p {
    font-size: 0.9rem;
    color: #2D3748;
    line-height: 1.5;
    margin: 0 0 15px 0;
}

#cookie-banner p strong {
    color: #802C38;
}

.cookie-buttons {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn-cookie {
    font-family: 'Montserrat', sans-serif;
    font-size: 0.85rem;
    font-weight: 700;
    padding: 8px 18px;
    border-radius: 50px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-accept {
    background-color: #FF7B7B;
    color: #FFFFFF;
}

.btn-accept:hover {
    background-color: #802C38;
}

.btn-refuse {
    background-color: #E2E8F0;
    color: #4A5568;
}

.btn-refuse:hover {
    background-color: #CBD5E0;
}
</style>

<div id="cookie-banner">
    <p>
        🍪 <strong>Un cookie pour le chat ?</strong><br>
        Nous utilisons des cookies essentiels pour assurer le bon fonctionnement du site et garder votre session active.
    </p>
    <div class="cookie-buttons">
        <button id="cookie-refuse" class="btn-cookie btn-refuse">Refuser</button>
        <button id="cookie-accept" class="btn-cookie btn-accept">Accepter 🐾</button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const banner = document.getElementById("cookie-banner");
    const btnAccept = document.getElementById("cookie-accept");
    const btnRefuse = document.getElementById("cookie-refuse");

    // Vérifie si le choix a déjà été fait
    if (!localStorage.getItem("cookieConsent")) {
        banner.style.display = "block";
    }

    btnAccept.addEventListener("click", function () {
        localStorage.setItem("cookieConsent", "accepted");
        banner.style.display = "none";
    });

    btnRefuse.addEventListener("click", function () {
        localStorage.setItem("cookieConsent", "refused");
        banner.style.display = "none";
    });
});
</script>