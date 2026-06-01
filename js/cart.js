/**
 * cart.js - Gestion dynamique du panier avec fetch() AJAX
 * Stockage en session PHP, mise à jour sans rechargement
 */

class CartManager {
    constructor() {
        this.cartCountElement = document.querySelector('.panier-count');
        this.cartLinkElement = document.querySelector('.panier-link');
        this.init();
    }

    init() {
        // Attacher les événements aux boutons "Ajouter au panier"
        document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleAddToCart(e));
        });

        // Mettre à jour le compteur au chargement
        this.updateCartCount();
    }

    /**
     * Ajouter un produit au panier via AJAX (fetch)
     */
    async handleAddToCart(event) {
        event.preventDefault();

        const form = event.target.closest('form');
        if (!form) return;

        const produitId = form.querySelector('input[name="produit_id"]').value;
        const quantite = form.querySelector('input[name="quantite"]')?.value || 1;
        const csrfToken = form.querySelector('input[name="csrf_token"]').value;

        // Feedback utilisateur pendant la requête
        const originalText = event.target.textContent;
        event.target.textContent = '⏳ Ajout...';
        event.target.disabled = true;

        try {
            const response = await fetch('./add-to-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest' // Indiquer que c'est AJAX
                },
                body: new URLSearchParams({
                    produit_id: produitId,
                    quantite: quantite,
                    csrf_token: csrfToken,
                    json: '1' // Demander du JSON en retour
                })
            });

            const data = await response.json();

            if (data.success) {
                // ✅ Succès : mettre à jour l'UI
                this.showNotification(data.message, 'success');
                this.updateCartCount(data.cart_count);
                
                // Animation de succès
                event.target.textContent = '✅ Ajouté !';
                setTimeout(() => {
                    event.target.textContent = originalText;
                    event.target.disabled = false;
                }, 2000);
            } else {
                // ❌ Erreur
                this.showNotification(data.message || 'Erreur lors de l\'ajout', 'error');
                event.target.textContent = originalText;
                event.target.disabled = false;
            }
        } catch (error) {
            console.error('Erreur fetch:', error);
            this.showNotification('Erreur réseau. Vérifiez votre connexion.', 'error');
            event.target.textContent = originalText;
            event.target.disabled = false;
        }
    }

    /**
     * Mettre à jour le compteur du panier dynamiquement
     */
    updateCartCount(count = null) {
        if (count !== null) {
            // Si on reçoit le count directement
            if (count > 0) {
                if (!this.cartCountElement) {
                    // Créer le badge s'il n'existe pas
                    const badge = document.createElement('span');
                    badge.className = 'panier-count';
                    badge.textContent = count;
                    this.cartLinkElement?.appendChild(badge);
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
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            font-weight: bold;
            z-index: 10000;
            animation: slideIn 0.3s ease-in-out;
            ${type === 'success' ? 'background: #85D6CD; color: white;' : 'background: #FE7B7E; color: white;'}
        `;

        document.body.appendChild(toast);

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

// CSS Animation pour le toast (à ajouter en style ou dans style.css)
const style = document.createElement('style');
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
