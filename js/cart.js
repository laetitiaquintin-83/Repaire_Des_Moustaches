/**
 * cart.js - Gestion dynamique du panier avec fetch() AJAX
 * Stockage en session PHP, mise à jour sans rechargement
 * ⚠️ Fallback : si JS désactivé, le formulaire s'envoie normalement (redirection vers cart.php)
 */

class CartManager {
    constructor() {
        this.cartCountElement = document.querySelector('.panier-count');
        this.cartLinkElement = document.querySelector('.panier-link');
        this.init();
    }

    init() {
        // ✅ Attacher l'événement sur le submit du formulaire (pas sur le click du bouton)
        document.querySelectorAll('.form-add-to-cart').forEach(form => {
            form.addEventListener('submit', (e) => this.handleAddToCart(e));
        });

        // Mettre à jour le compteur au chargement
        this.updateCartCount();
    }

    /**
     * Ajouter un produit au panier via AJAX (fetch)
     * En cas d'erreur, le formulaire est soumis normalement (fallback)
     */
    async handleAddToCart(event) {
        event.preventDefault(); // On empêche la soumission par défaut

        const form = event.target;
        const submitBtn = form.querySelector('.bouton-ajouter-panier') || form.querySelector('button[type="submit"]');
        
        // Sauvegarder le texte original pour le feedback
        const originalText = submitBtn ? submitBtn.textContent : '';
        if (submitBtn) {
            submitBtn.textContent = '⏳ Ajout...';
            submitBtn.disabled = true;
        }

        try {
            const formData = new FormData(form);
            // Ajouter un flag pour indiquer qu'on veut du JSON (optionnel)
            formData.append('json', '1');

            const response = await fetch('./add-to-cart.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Indiquer que c'est AJAX
                },
                body: formData
            });

            // Vérifier si la réponse est du JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                // Si la réponse n'est pas JSON (ex: redirection), on fait un fallback
                throw new Error('Réponse non-JSON reçue, fallback vers la soumission normale.');
            }

            const data = await response.json();

            if (data.success) {
                // ✅ Succès : mettre à jour l'UI
                this.showNotification(data.message || 'Produit ajouté !', 'success');
                this.updateCartCount(data.cart_count);
                
                // Feedback visuel sur le bouton
                if (submitBtn) {
                    submitBtn.textContent = '✅ Ajouté !';
                    submitBtn.style.background = '#6bc3b8';
                    setTimeout(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                        submitBtn.style.background = '';
                    }, 2000);
                }
            } else {
                // ❌ Erreur retournée par le serveur
                this.showNotification(data.message || 'Erreur lors de l\'ajout', 'error');
                if (submitBtn) {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }
            }
        } catch (error) {
            console.warn('Erreur AJAX, fallback vers la soumission classique:', error);
            // 🔄 FALLBACK : soumettre le formulaire normalement
            // On retire event.preventDefault() en le remplaçant par un submit pur
            event.target.submit();
        }
    }

    /**
     * Mettre à jour le compteur du panier dynamiquement
     */
    updateCartCount(count = null) {
        if (count !== null) {
            if (count > 0) {
                if (!this.cartCountElement) {
                    // Créer le badge s'il n'existe pas
                    const badge = document.createElement('span');
                    badge.className = 'panier-count';
                    badge.textContent = count;
                    if (this.cartLinkElement) {
                        this.cartLinkElement.appendChild(badge);
                    }
                    this.cartCountElement = badge;
                } else {
                    this.cartCountElement.textContent = count;
                }
            } else if (this.cartCountElement) {
                // Supprimer le badge si 0 articles
                this.cartCountElement.remove();
                this.cartCountElement = null;
            }
        }
    }

    /**
     * Afficher une notification toast
     */
    showNotification(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            font-weight: bold;
            z-index: 10000;
            animation: slideIn 0.3s ease-in-out;
            background: ${type === 'success' ? '#85D6CD' : '#FE7B7E'};
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            max-width: 350px;
        `;

        document.body.appendChild(toast);

        // Supprimer automatiquement après 3 secondes
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-in-out';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
}

// Initialiser le gestionnaire de panier quand le DOM est prêt
document.addEventListener('DOMContentLoaded', () => {
    new CartManager();
});

// Ajouter les animations CSS pour les toasts (une seule fois)
(function addToastStyles() {
    if (document.getElementById('cart-toast-styles')) return;
    const style = document.createElement('style');
    style.id = 'cart-toast-styles';
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
})();