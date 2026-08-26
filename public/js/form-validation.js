/** Validation des formulaires. */

class FormValidator {
    constructor(formSelector) {
        this.form = document.querySelector(formSelector);
        if (!this.form) return;
        
        this.errors = {};
        this.init();
    }

    init() {
        // Validation au changement de champ
        this.form.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
            field.addEventListener('change', () => this.validateField(field));
        });

        // Validation à la soumission
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    /** Valide un champ. */
    validateField(field) {
        const name = field.name;
        const value = field.value.trim();
        const type = field.type;
        let error = null;

        // Champ obligatoire
        if (field.hasAttribute('required') && !value) {
            error = `${field.getAttribute('data-label') || field.placeholder || name} est requis`;
        }
        // Adresse e-mail
        else if (type === 'email' && value && !this.isValidEmail(value)) {
            error = 'Email invalide. Format: user@exemple.fr';
        }
        // Date
        else if (type === 'date' && value && !this.isValidDate(value)) {
            error = 'Format de date invalide (YYYY-MM-DD)';
        }
        // Longueur minimale
        else if (field.hasAttribute('minlength')) {
            const minlen = parseInt(field.getAttribute('minlength'));
            if (value.length > 0 && value.length < minlen) {
                error = `Minimum ${minlen} caractères requis`;
            }
        }
        // Règles spécifiques
        error = error || this.customValidation(name, value, field);

        // Mise à jour de l'interface
        this.setFieldError(field, error);
        this.errors[name] = error;

        return !error;
    }

    /** Applique les règles spécifiques. */
    customValidation(name, value, field) {
        if (name === 'nom' && value) {
            if (value.length < 3) return 'Le nom doit avoir au moins 3 caractères';
            if (!/^[a-zA-Zàâäéèêëïîôöùûüçœæ\s'-]+$/.test(value)) {
                return 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes';
            }
        }

        if (name === 'message' && value) {
            if (value.length < 10) return 'Le message doit avoir au moins 10 caractères';
            if (value.length > 1000) return 'Le message ne peut pas dépasser 1000 caractères';
        }

        if (name === 'motif' && !value) {
            return 'Veuillez choisir une option';
        }

        if (name === 'telephone' && value) {
            // Numéro français
            if (!/^(\+33|0)[1-9](?:[0-9]{8})$|^\+33[1-9](?:[0-9]{8})$|^0[1-9](?:[ ]?[0-9]{2}){4}$/.test(value.replace(/\s/g, ''))) {
                return 'Numéro de téléphone invalide';
            }
        }

        if (name === 'codepostal' && value) {
            if (!/^\d{5}$/.test(value.replace(/\s/g, ''))) {
                return 'Code postal invalide (5 chiffres)';
            }
        }

        return null;
    }

    /** Affiche l'erreur d'un champ. */
    setFieldError(field, error) {
        // Suppression de l'erreur précédente
        const existingError = field.parentElement?.querySelector('.field-error');
        if (existingError) existingError.remove();

        if (error) {
            field.classList.add('is-invalid');
            const errorEl = document.createElement('small');
            errorEl.className = 'field-error';
            errorEl.textContent = error;
            errorEl.style.cssText = 'display: block; color: #FE7B7E; font-weight: bold; margin-top: 5px;';
            field.parentElement?.appendChild(errorEl);
        } else {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        }
    }

    /** Valide une adresse e-mail. */
    isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    /** Valide une date. */
    isValidDate(dateString) {
        const date = new Date(dateString);
        return date instanceof Date && !isNaN(date);
    }

    /** Gère la soumission du formulaire. */
    handleSubmit(event) {
        // Validation complète
        let isValid = true;
        this.form.querySelectorAll('input, textarea, select').forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        if (!isValid) {
            event.preventDefault();
            this.showFormError('❌ Veuillez corriger les erreurs ci-dessus');
            return false;
        }

        // Soumission autorisée
        return true;
    }

    /** Affiche une erreur générale. */
    showFormError(message) {
        let errorContainer = this.form.querySelector('.form-error-container');
        if (!errorContainer) {
            errorContainer = document.createElement('div');
            errorContainer.className = 'form-error-container';
            this.form.insertBefore(errorContainer, this.form.firstChild);
        }

        errorContainer.innerHTML = `
            <div style="background: #FE7B7E; color: white; padding: 15px; border-radius: 8px; font-weight: bold; margin-bottom: 20px;">
                ${message}
            </div>
        `;
    }
}

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', () => {
    // Formulaire atelier
    new FormValidator('form[action*="formulaire"]');
    
    // Formulaire commande
    new FormValidator('form[action*="checkout"]');
    
    // Formulaire contact
    new FormValidator('form[class*="contact"]');

    // Styles de validation
    const style = document.createElement('style');
    style.textContent = `
        input.is-invalid,
        textarea.is-invalid,
        select.is-invalid {
            border-color: #FE7B7E !important;
            background-color: #FFF5F5 !important;
        }

        input.is-valid,
        textarea.is-valid,
        select.is-valid {
            border-color: #85D6CD !important;
        }

        .field-error {
            display: block;
            color: #FE7B7E;
            font-size: 0.85rem;
            margin-top: 5px;
        }
    `;
    document.head.appendChild(style);
});
