# 🎓 Notes Examen - JavaScript & Panier

## 📋 Réponses Jury (à mémoriser)

### Q1: "Tu utilises JavaScript?"

**Réponse**: *(30 secondes)*
> J'ai implémenté deux modules JavaScript **Vanilla** (aucune dépendance).
> 
> **1. cart.js** (150 lignes):
> - Gestion AJAX du panier avec `fetch()`
> - Quand l'utilisateur clique "Ajouter", ça s'ajoute en **session PHP sans rechargement**
> - Notification toast + compteur mis à jour dynamiquement
> 
> **2. form-validation.js** (200 lignes):
> - Validation en temps réel (email, longueur, format)
> - Feedback immédiat avant envoi au serveur
> - Validation PHP côté serveur aussi (double validation)

---

### Q2: "Pourquoi session PHP et pas localStorage?"

**Réponse**: *(20 secondes)*
> localStorage c'est **dangereux** car:
> - ❌ Client peut modifier les prix (outils développeur)
> - ❌ Risque XSS (vol de données)
> 
> Session PHP c'est **sécurisé** car:
> - ✅ Données sur le serveur
> - ✅ Prix vérifiés en BDD (vraie valeur)
> - ✅ Pas d'accès au client

---

### Q3: "Comment tu gères la sécurité?"

**Réponse**: *(20 secondes)*
> Triptyque de sécurité:
> 
> 1. **CSRF tokens** - Tous les formulaires + fetch
> 2. **Validation double** - Client (JS) + Serveur (PHP)
> 3. **XSS protection** - `.textContent` (pas `.innerHTML`)

---

## 🚀 Démo Live (Script 5 min)

### Étape 1: Panier AJAX (1 min)
```
1. Ouvrir /public/boutique.php
2. Cliquer "🛒 Ajouter" sur un produit
   → Toast notification ✅
   → Compteur update (ex: 🛒 Panier 1)
   → PAGE NE RECHARGE PAS
3. Ouvrir DevTools (F12) → Network
4. Refaire un ajout → Voir requête fetch POST
5. Réponse JSON: { success: true, cart_count: 2 }
```

### Étape 2: Validation (1 min)
```
1. Aller sur /formulaire.php
2. Laisser champ email vide, click blur
   → Message rouge: "Email est requis"
3. Taper "test@" (incomplet)
   → Message: "Email invalide"
4. Taper "test@gmail.com"
   → Bordure verte ✅
```

### Étape 3: Checkout (1 min)
```
1. Aller sur /public/checkout.php
2. Remplir le formulaire
3. Observer validation en temps réel
4. Soumettre
5. Voir commande créée en BDD (admin)
```

### Étape 4: BDD (1 min)
```
1. phpMyAdmin → DB repaire
2. Ouvrir table "commandes"
3. Montrer les commandes créées
4. Montrer les "lignes_commandes" (articles du panier)
```

### Étape 5: Code (1 min)
```
1. Ouvrir js/cart.js dans l'éditeur
2. Montrer ligne ~23: fetch() avec CSRF token
3. Ouvrir js/form-validation.js
4. Montrer la validation email (regex)
```

---

## ✅ Checklist Avant Examen

```
AVANT LA DÉMO:
□ Tous les JS fichiers existent: js/cart.js, js/form-validation.js
□ Boutique charge cart.js: <script src="../js/cart.js"></script>
□ Formulaire charge form-validation.js
□ Checkout charge form-validation.js
□ La DB est remplie (produits, catégories, ateliers)
□ Au moins 1 produit dans la DB

PENDANT LA DÉMO:
□ Test ajout au panier (pas de rechargement)
□ Test compteur se met à jour
□ Test validation formulaire atelier
□ Test validation checkout
□ Montrer les requêtes dans DevTools Network

POINTS À NE PAS OUBLIER:
□ Dire "Vanilla JavaScript" (pas jQuery/React)
□ Montrer fetch() vs POST classique
□ Expliquer session PHP > localStorage
□ Montrer validation double (client + serveur)
```

---

## 💡 Points Clés (à dire avec assurance)

1. **"AJAX sans rechargement"** - Meilleure UX
2. **"Session PHP côté serveur"** - Sécurité
3. **"Validation double"** - Client (rapide) + Serveur (sûr)
4. **"Vanilla JS"** - Je comprends le JavaScript pur
5. **"fetch() API"** - JavaScript moderne
6. **"CSRF tokens"** - Protection contre attaques

---

## 📱 Ce que le Jury Adorera Voir

✅ **En direct dans le navigateur**:
- Ajouter au panier → Toast notification → Compteur update
- Aucun rechargement page
- Les données persistent (refresh page → panier toujours là)

✅ **Dans le code (DevTools F12)**:
- Requête fetch POST vers add-to-cart.php
- Réponse JSON avec cart_count
- Pas de erreurs dans la console

✅ **Dans la BDD**:
- Tables commandes, lignes_commandes remplies
- Dates créées automatiquement

---

## 🎯 En Une Phrase

> "J'utilise fetch() AJAX pour ajouter au panier sans rechargement, je stocke les données en session PHP (sécurisé), et je valide côté client (UX) + côté serveur (sécurité)."

---

**Bonne chance! 🍀**
