/** Gestion du panier. */

class CartManager {
    constructor() {
        this.cartCountElement = document.querySelector('.panier-count');
        this.cartLinkElement = document.querySelector('.panier-link');
        this.init();
    }

    init() {
        // Écoute des formulaires
        document.querySelectorAll('.form-add-to-cart').forEach(form => {
            form.addEventListener('submit', (e) => this.handleAddToCart(e));
        });

        // Mettre à jour le compteur au chargement
        this.updateCartCount();
    }

    /** Ajoute un produit au panier. */
    async handleAddToCart(event) {
        event.preventDefault();

        const form = event.target;
        const submitBtn = form.querySelector('.bouton-ajouter-panier') || form.querySelector('button[type="submit"]');
        
        // Texte initial du bouton
        const originalText = submitBtn ? submitBtn.textContent : '';
        if (submitBtn) {
            submitBtn.textContent = '⏳ Ajout...';
            submitBtn.disabled = true;
        }

        try {
            const formData = new FormData(form);
            // Demande de réponse JSON
            formData.append('json', '1');

            const response = await fetch('./add-to-cart.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            // Vérification du format
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                // Fallback sur la soumission standard
                throw new Error('Réponse non-JSON reçue, fallback vers la soumission normale.');
            }

            const data = await response.json();

            if (data.success) {
                // Mise à jour de l'interface
                this.showNotification(data.message || 'Produit ajouté !', 'success');
                this.updateCartCount(data.cart_count);
                
                // Retour visuel
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
                // Erreur serveur
                this.showNotification(data.message || 'Erreur lors de l\'ajout', 'error');
                if (submitBtn) {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }
            }
        } catch (error) {
            console.warn('Erreur AJAX, fallback vers la soumission classique:', error);
            // Soumission standard en cas d'erreur
            event.target.submit();
        }
    }

    /** Met à jour le compteur du panier. */
    updateCartCount(count = null) {
        if (count !== null) {
            if (count > 0) {
                if (!this.cartCountElement) {
                    // Création du badge
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
                // Suppression du badge vide
                this.cartCountElement.remove();
                this.cartCountElement = null;
            }
        }
    }

    /** Affiche une notification. */
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

        // Suppression automatique
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-in-out';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
}

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', () => {
    new CartManager();
});

// Styles des notifications
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