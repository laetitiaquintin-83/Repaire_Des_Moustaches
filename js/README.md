# 📦 JavaScript Vanilla - Le Repaire des Moustaches

Ce dossier contient les scripts JavaScript pour:
- Gestion dynamique du panier AJAX
- Validation des formulaires côté client

## 📄 Fichiers

### 1. **cart.js** (150 lignes)
Gestion AJAX du panier avec `fetch()`.

**Features**:
- ✅ Ajout/suppression d'articles sans rechargement
- ✅ Mise à jour du compteur dynamiquement
- ✅ Notifications toast (succès/erreur)
- ✅ Validation CSRF

**Utilisé dans**: `public/boutique.php`

**Comment ça marche**:
```javascript
// Événement click sur btn "Ajouter"
→ Récupère produit_id, csrf_token
→ Appelle fetch('public/add-to-cart.php', POST)
→ add-to-cart.php ajoute à $_SESSION['cart']
→ Retour JSON avec cart_count
→ JS met à jour le compteur et affiche notification
```

---

### 2. **form-validation.js** (200 lignes)
Validation JavaScript côté client en temps réel.

**Features**:
- ✅ Validation email (regex RFC)
- ✅ Longueur min/max (message, nom)
- ✅ Numéros de téléphone français
- ✅ Codes postaux
- ✅ Messages d'erreur en temps réel
- ✅ Feedback visuel (bordures rouge/verte)

**Utilisé dans**: 
- `formulaire.php` (atelier)
- `public/checkout.php` (commande)

**Comment ça marche**:
```javascript
// Au blur de chaque champ
→ Valider le champ (email, longueur, etc)
→ Afficher erreur si invalide (texte rouge + bordure)

// À la soumission
→ Valider TOUS les champs
→ Si erreur: empêcher envoi, afficher message général
→ Si OK: laisser PHP traiter
```

---

## 🔄 Flux d'Intégration

### Fichier `js/cart.js` dans `boutique.php`

```html
<footer>
    ...
</footer>

<!-- Charger après le HTML -->
<script src="../js/cart.js"></script>
</body>
```

**Au chargement de la page**:
1. `cart.js` s'initialise
2. Recherche tous les boutons `.btn-add-to-cart`
3. Attache les event listeners (click)
4. Met à jour le compteur initial

### Fichier `js/form-validation.js` dans `formulaire.php`

```html
<!-- Avant </body> -->
<script src="js/form-validation.js"></script>
</body>
```

**Au chargement de la page**:
1. `form-validation.js` s'initialise
2. Recherche les formulaires
3. Attache les validateurs (blur, change, submit)
4. Affiche erreurs en temps réel

---

## 🛡️ Sécurité

### Côté JavaScript (cart.js)
- ✅ Tokens CSRF inclus dans chaque fetch
- ✅ textContent (pas innerHTML) = pas XSS
- ✅ URLSearchParams = pas d'injection URL

### Côté PHP (add-to-cart.php)
- ✅ Validation CSRF token
- ✅ Vérification produit existe en BDD
- ✅ Prix réel depuis table `produits`
- ✅ Pas de confiance aux données client

---

## 🎯 Différences avec localStorage

**Cette implémentation utilise SESSION PHP, pas localStorage**:

| Aspect | localStorage | Session PHP |
|---|---|---|
| **Stockage** | Client (navigateur) | Serveur |
| **Sécurité prix** | ❌ Modifiable côté client | ✅ Vérifiée en BDD |
| **Persistence** | Entre sessions | Entre requêtes |
| **Vol XSS** | ⚠️ Possible | ✅ Protégé |

---

## 🧪 Test

### Test 1: Ajouter au panier sans rechargement
```
1. Aller sur /public/boutique.php
2. Cliquer "🛒 Ajouter" sur un produit
3. ✅ Toast notification s'affiche
4. ✅ Compteur se met à jour (🛒 Panier 3)
5. ✅ Page NE recharge PAS
```

### Test 2: Validation formulaire atelier
```
1. Aller sur /formulaire.php
2. Laisser champ email vide, blur
3. ✅ Message d'erreur rouge s'affiche
4. Entrer "test@" (email incomplet)
5. ✅ Message "Email invalide" s'affiche
6. Corriger, blur
7. ✅ Bordure devient verte
```

---

## 📊 Statistiques

| Aspect | Valeur |
|---|---|
| **Fichiers JS** | 2 |
| **Lignes totales** | ~350 |
| **Dépendances externes** | 0 (Vanilla) |
| **Validation côté client** | 6+ types |
| **Animations** | 2 (slideIn/slideOut) |

---

## 🎓 Points Clés

1. **Vanilla JS**: Aucun jQuery, React ou autre framework
2. **fetch() API**: Moderne (remplace XMLHttpRequest)
3. **Session PHP**: Plus sûr que localStorage
4. **Validation double**: Client + Serveur
5. **UX moderne**: Pas de rechargement page

---

**Bonne chance pour l'examen! 🍀**
