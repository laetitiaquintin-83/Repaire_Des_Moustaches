/**
 * form-validation.js - Validation JavaScript côté client
 * Formulaires: Inscription Ateliers, Contact, Checkout
 */

class FormValidator {
    constructor(formSelector) {
        this.form = document.querySelector(formSelector);
        if (!this.form) return;
        
        this.errors = {};
        this.init();
    }

    init() {
        // Valider en temps réel au blur
        this.form.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
            field.addEventListener('change', () => this.validateField(field));
        });

        // Empêcher la soumission si erreurs
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    /**
     * Valider un champ individuel
     */
    validateField(field) {
        const name = field.name;
        const value = field.value.trim();
        const type = field.type;
        let error = null;

        // Validation requise
        if (field.hasAttribute('required') && !value) {
            error = `${field.getAttribute('data-label') || field.placeholder || name} est requis`;
        }
        // Validation email
        else if (type === 'email' && value && !this.isValidEmail(value)) {
            error = 'Email invalide. Format: user@exemple.fr';
        }
        // Validation date
        else if (type === 'date' && value && !this.isValidDate(value)) {
            error = 'Format de date invalide (YYYY-MM-DD)';
        }
        // Validation longueur minimum
        else if (field.hasAttribute('minlength')) {
            const minlen = parseInt(field.getAttribute('minlength'));
            if (value.length > 0 && value.length < minlen) {
                error = `Minimum ${minlen} caractères requis`;
            }
        }
        // Validation custom par name
        error = error || this.customValidation(name, value, field);

        // Mettre à jour l'UI
        this.setFieldError(field, error);
        this.errors[name] = error;

        return !error;
    }

    /**
     * Validations personnalisées par champ
     */
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
            // Accepter formats: 0123456789, 01 23 45 67 89, +33123456789
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

    /**
     * Afficher l'erreur pour un champ
     */
    setFieldError(field, error) {
        // Supprimer l'erreur précédente si elle existe
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

    /**
     * Valider un email
     */
    isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    /**
     * Valider une date au format YYYY-MM-DD
     */
    isValidDate(dateString) {
        const date = new Date(dateString);
        return date instanceof Date && !isNaN(date);
    }

    /**
     * Gérer la soumission du formulaire
     */
    handleSubmit(event) {
        // Valider tous les champs
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

        // ✅ Formulaire valide, laisser la soumission se faire
        return true;
    }

    /**
     * Afficher une erreur générale du formulaire
     */
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

// Initialiser les validateurs au chargement
document.addEventListener('DOMContentLoaded', () => {
    // Formulaire des ateliers
    new FormValidator('form[action*="formulaire"]');
    
    // Formulaire de checkout
    new FormValidator('form[action*="checkout"]');
    
    // Formulaire de contact si présent
    new FormValidator('form[class*="contact"]');

    // Appliquer CSS pour les champs invalides
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
