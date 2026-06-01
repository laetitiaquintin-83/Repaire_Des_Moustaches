# 📱 Implémentation Complète - Panier & Dynamisme JavaScript (Vanilla)

**Date**: 29 mai 2026  
**Status**: ✅ COMPLET  
**Framework**: Vanilla JavaScript (aucune dépendance externe)

---

## 🎯 Objectifs Réalisés

### ✅ 1. Panier avec Session PHP (PAS localStorage)

**Choix d'architecture**: Session PHP côté serveur, persistent entre les requêtes

```php
// add-to-cart.php (ligne 63-79)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$produit_id])) {
    $_SESSION['cart'][$produit_id]['quantite'] += $quantite;
} else {
    $_SESSION['cart'][$produit_id] = [
        'id' => $produit_id,
        'nom' => $produit['nom'],
        'prix' => $produit['prix'],
        'quantite' => $quantite
    ];
}
```

**Avantage**: 
- ✅ Données persistent même après fermeture du navigateur
- ✅ Sécurisé (pas d'accès direct au client)
- ✅ Validation côté serveur (prix réel vérifiés en BDD)
- ✅ Pas de risque de manipulation du panier

---

### ✅ 2. Ajout/Suppression AJAX via fetch() (SANS rechargement)

#### **Fichier: `js/cart.js`** (Nouveau - 150 lignes)

La classe `CartManager` gère:

**Ajout au panier**:
```javascript
async handleAddToCart(event) {
    event.preventDefault();
    
    const response = await fetch('/Repaire_Des_Moustaches/public/add-to-cart.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({
            produit_id: produitId,
            quantite: quantite,
            csrf_token: csrfToken,
            json: '1'  // ← Demander une réponse JSON
        })
    });
    
    const data = await response.json();
    if (data.success) {
        this.showNotification(data.message, 'success');
        this.updateCartCount(data.cart_count);
    }
}
```

**Flux complet**:
1. ❌ Pas de rechargement page
2. ✅ Notification toast (vert succès / rouge erreur)
3. ✅ Compteur mis à jour dynamiquement
4. ✅ Button feedback: "⏳ Ajout..." → "✅ Ajouté !" → "🛒 Ajouter"

---

### ✅ 3. Compteur du Panier Mis à Jour Automatiquement

**Dans `boutique.php`**:

- **Affichage initial** (avec PHP):
```php
<?php if ($cart_count > 0): ?>
    <span class="panier-count"><?php echo $cart_count; ?></span>
<?php endif; ?>
```

- **Mise à jour en temps réel** (avec JS):
```javascript
updateCartCount(count = null) {
    if (count > 0) {
        if (!this.cartCountElement) {
            const badge = document.createElement('span');
            badge.className = 'panier-count';
            badge.textContent = count;
            this.cartLinkElement?.appendChild(badge);
        } else {
            this.cartCountElement.textContent = count;
        }
    }
}
```

**Résultat**: Le badge rouge `🛒 Panier <span>3</span>` se met à jour sans rechargement

---

### ✅ 4. Validation Formulaires Côté Client (JavaScript)

#### **Fichier: `js/form-validation.js`** (Nouveau - 200 lignes)

La classe `FormValidator` valide:

**Validations implémentées**:

| Champ | Validation | Message |
|---|---|---|
| **nom** | Min 3 caractères + lettres uniquement | "Le nom doit avoir au moins 3 caractères" |
| **email** | Regex RFC standard | "Email invalide. Format: user@exemple.fr" |
| **message** | Min 10 / Max 1000 caractères | "Le message doit avoir au moins 10 caractères" |
| **date** | Format YYYY-MM-DD | "Format de date invalide" |
| **motif (select)** | Obligatoire | "Veuillez choisir une option" |
| **telephone** | Format français (optionnel) | "Numéro de téléphone invalide" |
| **codepostal** | 5 chiffres | "Code postal invalide" |

**Validation en temps réel**:
```javascript
field.addEventListener('blur', () => this.validateField(field));
field.addEventListener('change', () => this.validateField(field));
```

**Feedback visuel**:
- 🔴 Champ invalide: Bordure rouge + message d'erreur sous le champ
- 🟢 Champ valide: Bordure verte

**UI des erreurs**:
```html
<input type="email" class="is-invalid"> <!-- Fond #FFF5F5, bordure rouge -->
<small class="field-error">Email invalide. Format: user@exemple.fr</small>
```

---

## 📁 Fichiers Créés/Modifiés

### Nouveaux fichiers:

| Chemin | Lignes | Description |
|---|---|---|
| `js/cart.js` | 150 | Gestion AJAX du panier avec fetch() |
| `js/form-validation.js` | 200 | Validation JavaScript côté client |

### Fichiers modifiés:

| Chemin | Changement |
|---|---|
| `public/boutique.php` | • Classe `btn-add-to-cart` sur le bouton Ajouter<br>• Type `button` au lieu de `submit`<br>• Chargement de `js/cart.js` avant `</body>` |
| `formulaire.php` | Chargement de `js/form-validation.js` avant `</body>` |
| `public/checkout.php` | Chargement de `js/form-validation.js` avant `</body>` |
| `public/add-to-cart.php` | ✅ Déjà compatible (retourne JSON quand `json=1`) |

---

## 🧪 Comment ça Fonctionne

### Scénario 1: Ajouter un produit au panier (sans rechargement)

**Utilisateur clique sur "🛒 Ajouter" dans la boutique**:

```
1. Event listener détecte click sur btn-add-to-cart
   ↓
2. JS extrait: produit_id, csrf_token du formulaire
   ↓
3. fetch('/Repaire_Des_Moustaches/public/add-to-cart.php', POST, json: 1)
   ↓
4. add-to-cart.php ajoute à $_SESSION['cart']
   ↓
5. Retour JSON: { success: true, cart_count: 3, ... }
   ↓
6. JS met à jour:
   - Toast notification ✅
   - Compteur badge: 🛒 Panier 3
   - Button feedback: "✅ Ajouté !"
   ↓
7. ZERO rechargement page ✅
```

### Scénario 2: Soumettre le formulaire d'atelier

**Utilisateur remplit et envoie le formulaire**:

```
1. Au blur de chaque champ:
   - Validation email, longueur, format...
   - Affichage message d'erreur en temps réel
   ↓
2. À la soumission du formulaire:
   - Valider TOUS les champs
   - Si erreur: empêcher submission, afficher "❌ Veuillez corriger..."
   - Si OK: laisser PHP traiter normalement
   ↓
3. Feedback utilisateur instant (avant envoi au serveur)
```

---

## 🔒 Sécurité

### Tokens CSRF

✅ Tous les formulaires ont un token CSRF valué:

```javascript
// cart.js - ligne 23
const csrfToken = form.querySelector('input[name="csrf_token"]').value;

body: new URLSearchParams({
    csrf_token: csrfToken,  // ← Envoyé avec fetch
})
```

### Validation Doubles

1. **Client** (JS): Feedback rapide à l'utilisateur
2. **Serveur** (PHP): Validation réelle (ne pas faire confiance au client)

```php
// add-to-cart.php - ligne 14
if (!validateCSRFToken($csrf_check)) {
    $response['message'] = 'Erreur de sécurité : token CSRF invalide';
    exit;
}
```

### Injection XSS

✅ Tous les outputs JSON échappés:

```javascript
// cart.js - showNotification()
toast.textContent = message;  // ← .textContent, pas .innerHTML (safer)
```

---

## 📊 Différences Avant/Après

### Avant

| Aspect | Avant | Après |
|---|---|---|
| **Ajout au panier** | POST → Rechargement page | AJAX fetch → Aucun rechargement |
| **Compteur** | Statique (PHP) | Dynamique (JS) |
| **Validation formulaire** | HTML5 basique (`required`) | JS complète (email, min/max, regex) |
| **Feedback utilisateur** | Message après rechargement | Toast notification immédiate |
| **Fichiers JS** | ❌ 0 fichier | ✅ 2 fichiers (cart.js + form-validation.js) |

---

## 🎓 Discours pour le Jury

### Q: "Tu utilises JavaScript?"

**Réponse**:
> J'ai implémenté deux modules JavaScript Vanilla:
>
> 1. **cart.js** (150 lignes): Gestion AJAX du panier avec fetch(). Quand l'utilisateur clique "Ajouter au panier", l'article s'ajoute en session PHP sans rechargement. Je affiche une notification toast et je mets à jour le compteur dynamiquement.
>
> 2. **form-validation.js** (200 lignes): Validation côté client en temps réel. L'utilisateur voit les erreurs avant de soumettre (email invalide, message trop court, etc.). Mais j'ai aussi validation côté serveur en PHP.
>
> J'ai volontairement choisi Vanilla JS (pas de framework) pour montrer que je comprends le JavaScript pur: fetch API, DOM manipulation, Event listeners.

### Q: "Pourquoi session PHP et pas localStorage?"

**Réponse**:
> localStorage stocke les données localement dans le navigateur. C'est un problème de sécurité car:
> - Les prix peuvent être modifiés côté client
> - Les articles fictifs peuvent être ajoutés au panier
>
> Avec session PHP, les données sont sur le serveur. Je fais une vérification réelle en BDD: vérifier que le produit existe, récupérer le prix réel depuis la table `produits`, not du client.

---

## ✅ Checklist Pré-Examen

- [x] Session PHP pour le panier
- [x] AJAX fetch() sans rechargement
- [x] Compteur dynamique
- [x] Validation client (formulaires)
- [x] Validation serveur (PHP)
- [x] CSRF tokens
- [x] Messages de feedback utilisateur
- [x] Pas de rechargement page (UX moderne)
- [x] Vanilla JS (pas de dépendances externes)
- [x] Responsive CSS animations

---

## 🚀 Démo Live (5-10 minutes)

1. **Boutique** → Cliquer "Ajouter au panier" → Voir notification + compteur update sans rechargement
2. **Formulaire atelier** → Laisser champ email vide → Voir erreur en rouge immédiate au blur
3. **Panier** → Vérifier que les données persistent (session PHP)
4. **Admin** → Vérifier que les commandes sont créées (validation serveur)

---

## 📝 Notes Supplémentaires

### localStorage vs Session PHP

**Pourquoi session PHP:**
```php
✅ Données sécurisées (serveur)
✅ Validation prix en BDD
✅ Persistent entre navigateurs (si cookies)
✅ Session ID côté serveur = no session hijacking

❌ localStorage c'est client-side:
  ❌ Données modifiables (outils développeur)
  ❌ Risque XSS (vol de données)
  ❌ Pas de validation réelle
```

### Animations

Les notifications toast ont des animations CSS:
- Entrée: `slideIn` (translateX 400px → 0px)
- Sortie: `slideOut` (translateX 0px → 400px)
- Durée: 300ms ease-in-out
- Auto-dismiss: 3 secondes

---

## 🎯 Points Clés à Mémoriser

1. **AJAX = fetch() sans rechargement** ✅
2. **Session PHP > localStorage** (sécurité) ✅
3. **Validation client (UX) + serveur (sécurité)** ✅
4. **CSRF tokens partout** ✅
5. **Vanilla JS = aucune dépendance externe** ✅
6. **Compteur dynamique** ✅

---

**Bonne chance pour l'examen! 🍀**
