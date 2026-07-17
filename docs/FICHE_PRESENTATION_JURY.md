# 🎓 Fiche de Présentation - Le Repaire des Moustaches

## 📋 Résumé du Projet

**Le Repaire des Moustaches** est un site web pour un tiers-lieu solidaire à Toulon qui mélange :
- 🎨 Une ambiance années 50 rétro
- 🐱 L'accueil de chats en besoin d'adoption
- 🛒 Une boutique en ligne pour financer le projet
- 💬 Un espace pour partager les histoires des animaux adoptés

---

## 🏗️ Architecture du Projet

### Structure des dossiers

```
📁 Repaire_Des_Moustaches/
├── 📄 index.php, concept.php, etc.    → Pages principales du site
├── 📁 public/                          → Boutique & panier (pages visiteurs)
│   ├── boutique.php                   → Afficher les produits
│   ├── cart.php                       → Panier d'achat
│   ├── checkout.php                   → Paiement de la commande
│   └── add-to-cart.php               → Ajouter un article (AJAX)
├── 📁 admin/                          → Pages réservées aux admins
│   ├── dashboard.php                  → Vue d'ensemble des stats
│   ├── produits.php                   → Gérer les produits
│   ├── commandes.php                  → Voir les commandes
│   └── utilisateurs.php               → Lister les clients
├── 📁 config/                         → Configuration
│   └── database.php                   → Connexion à la BDD
├── 📁 includes/                       → Réutilisable
│   ├── header.php                     → En-tête du site
│   └── footer.php                     → Pied de page
└── 📁 js/                             → JavaScript
    ├── cart.js                        → Gestion du panier
    └── form-validation.js             → Vérification des formulaires
```

---

## 💾 Base de Données

### Les tables principales

| Table | Rôle |
|-------|------|
| **utilisateurs** | Clients du site (nom, email, mot de passe hashé) |
| **produits** | Articles de la boutique (nom, prix, description) |
| **commandes** | Achats des clients (total, date, statut) |
| **lignes_commandes** | Détail de chaque commande (produit, quantité) |
| **belles_histoires** | Histoires des animaux adoptés |
| **ateliers** | Événements/ateliers proposés |
| **admin_users** | Identifiants des administrateurs |

---

## 🔐 Sécurité Implémentée

### 1️⃣ Protection contre les injections SQL
```php
// ❌ MAUVAIS (Dangereux)
$result = $pdo->query("SELECT * FROM produits WHERE id = " . $_GET['id']);

// ✅ BON (Protégé)
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$_GET['id']]);
```
**Pourquoi ?** Les prepared statements empêchent un attaquant d'injecter du code SQL.

### 2️⃣ Protection contre le XSS (affichage de code malveillant)
```php
// ✅ Tous les affichages utilisateurs sont échappés
echo htmlspecialchars($titre, ENT_QUOTES, 'UTF-8');
```
**Pourquoi ?** Cela transforme les caractères spéciaux en code HTML inoffensif.

### 3️⃣ Tokens CSRF (Protection contre les attaques forgées)
```php
// À chaque formulaire, on ajoute un token unique
<input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

// On vérifie le token avant de traiter le formulaire
if (!validateCSRFToken($_POST['csrf_token'])) {
    die('Erreur de sécurité');
}
```
**Pourquoi ?** Cela empêche un attaquant d'effectuer une action à votre place.

### 4️⃣ Mots de passe sécurisés
```php
// On ne stocke JAMAIS le mot de passe en clair
$hash = password_hash($password, PASSWORD_DEFAULT);

// On vérifie avec password_verify()
if (password_verify($password, $hash)) {
    // Connexion OK
}
```

### 5️⃣ Session fixation évitée
```php
// Après une connexion réussie, on régénère l'ID de session
session_regenerate_id(true);
```

### 6️⃣ Gestion des erreurs
```php
// Les erreurs PHP ne s'affichent PAS en production
ini_set('display_errors', '0');
ini_set('log_errors', '1');  // Les erreurs sont archivées dans un fichier
```

---

## 🎯 Fonctionnalités Principales

### 1. La Boutique
**Fichiers clés** : `public/boutique.php`, `public/add-to-cart.php`, `js/cart.js`

```
Visiteur clique "Ajouter au panier"
    ↓
JavaScript envoie une requête AJAX au serveur (sans recharger la page)
    ↓
add-to-cart.php traite la demande et sauvegarde en session PHP
    ↓
Le compteur du panier se met à jour automatiquement
    ↓
Visiteur va à checkout.php pour acheter
```

**Points clés** :
- Le panier est stocké en session PHP (disparaît si on ferme le navigateur)
- AJAX = requête rapide sans recharger la page
- Les requêtes sont protégées par un token CSRF

### 2. L'Admin
**Fichiers clés** : `admin/dashboard.php`, `admin/produits.php`, etc.

```
Admin se connecte via login.php
    ↓
Un token est régénéré (sécurité)
    ↓
Dashboard affiche les stats :
   - Produits en stock
   - Commandes en attente
   - Histoires à modérer
    ↓
Admin peut modifier/ajouter/supprimer depuis chaque page
```

**Sécurité** : 
- Vérification `if (!isset($_SESSION['admin_id']))` au début de chaque page admin
- Si pas connecté = redirection vers login.php

### 3. Le Formulaire de Réservation
**Fichier clé** : `formulaire.php`

```
Visiteur remplit le formulaire
    ↓
Validation JavaScript côté client (feedback rapide)
    ↓
Envoi au serveur
    ↓
Validation PHP côté serveur (la vraie sécurité)
    ↓
Les données sont stockées en BDD via prepared statement
```

**Double validation** = sécurisé + rapide pour l'utilisateur

---

## 🔧 Technos Utilisées

| Tech | Rôle | Pourquoi ? |
|------|------|-----------|
| **PHP** | Langage serveur | Génère le HTML dynamique, accède à la BDD |
| **MySQL** | Base de données | Stocke tous les données (produits, clients, etc.) |
| **HTML/CSS** | Interface | Affichage et design du site |
| **JavaScript** | Interactivité | Ajouter au panier sans recharger, validation des formulaires |
| **AJAX/Fetch** | Communication asynchrone | Le panier se met à jour sans recharger la page |
| **PDO** | Accès à la BDD | Protection contre les injections SQL |
| **Sessions PHP** | Mémorisation | Garder le panier pendant la visite |

---

## 🚀 Flux d'une Commande (Scénario Complet)

```
1. VISITEUR VISITE LA BOUTIQUE
   └─ boutique.php récupère les produits en BDD
   └─ Affiche 10 produits avec prix et images

2. VISITEUR AJOUTE UN PRODUIT
   └─ JavaScript envoie fetch() à add-to-cart.php
   └─ add-to-cart.php ajoute à $_SESSION['cart']
   └─ Le badge "panier" se met à jour : 1 article

3. VISITEUR VA AU PANIER
   └─ cart.php affiche le contenu de $_SESSION['cart']
   └─ Peut modifier quantités ou retirer des articles

4. VISITEUR CLIQUE "PASSER COMMANDE"
   └─ Redirection vers checkout.php

5. CHECKOUT (checkout.php)
   └─ Affiche un formulaire : nom, email, adresse
   └─ Validation CSRF + validation données
   └─ Crée une commande en BDD
   └─ Crée les lignes_commandes (détail de chaque article)
   └─ Vide la session du panier
   └─ Redirige vers confirmation.php

6. CONFIRMATION
   └─ Affiche le numéro de commande
   └─ Email envoyé au client (optionnel)
   └─ Admin voit la commande dans le dashboard
```

---

## 📊 Exemple : Ajouter un Produit au Panier

### Ce qui se passe sous le capot

**1. Utilisateur clique le bouton**
```html
<button class="btn-add-to-cart">Ajouter au panier</button>
```

**2. JavaScript capture le clic**
```javascript
// Depuis cart.js
const produitId = form.querySelector('input[name="produit_id"]').value;
const quantite = form.querySelector('input[name="quantite"]').value || 1;

const response = await fetch('./add-to-cart.php', {
    method: 'POST',
    body: new URLSearchParams({
        produit_id: produitId,
        quantite: quantite,
        csrf_token: csrfToken
    })
});
```

**3. Le serveur traite**
```php
// add-to-cart.php
$produit_id = (int)$_POST['produit_id'];  // Conversion en nombre (sûr)
$quantite = (int)$_POST['quantite'];

// Vérification du token CSRF
if (!validateCSRFToken($_POST['csrf_token'])) {
    die('Erreur de sécurité');
}

// Recherche le produit en BDD (prepared statement)
$stmt = $pdo->prepare('SELECT id, nom, prix FROM produits WHERE id = ?');
$stmt->execute([$produit_id]);
$produit = $stmt->fetch();

// Ajoute à la session
$_SESSION['cart'][$produit_id] = [
    'nom' => $produit['nom'],
    'prix' => $produit['prix'],
    'quantite' => $quantite
];

// Retourne du JSON
echo json_encode(['success' => true, 'message' => 'Article ajouté']);
```

**4. JavaScript met à jour l'interface**
```javascript
// Le badge du panier change de nombre
updateCartCount(data.cart_count);
// Message de succès s'affiche
showNotification(data.message, 'success');
```

---

## 🛡️ Choix de Sécurité Expliqués

### Pourquoi on utilise les Prepared Statements ?

```php
// ❌ AVANT (Code vulnérable)
$sql = "SELECT * FROM produits WHERE id = " . $_GET['id'];
// Si quelqu'un met en URL : ?id=1 OR 1=1  → Tous les produits s'affichent !

// ✅ APRÈS (Code sécurisé)
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$_GET['id']]);
// Le ? est remplacé de manière sûre, l'injection est impossible
```

### Pourquoi on hash les mots de passe ?

```php
// ❌ MAUVAIS
$motdepasse = $_POST['password'];
$stmt->execute(['mdp' => $motdepasse]);  // Stocké en clair = DANGEREUX

// ✅ BON
$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
$stmt->execute(['mdp' => $hash]);  // Stocké en hash = sûr
// Si la BDD est piratée, les attaquants ne voient que du charabia
```

### Pourquoi les tokens CSRF ?

```
Scénario sans CSRF token:
- Vous êtes connecté à votre banque
- Vous visitez un site malveillant
- Le site peut faire une requête à votre banque (transfert d'argent)
- Car le navigateur envoie automatiquement vos cookies

Avec CSRF token:
- Le site malveillant ne connaît pas votre token unique
- La requête est rejetée
```

---

## 🎓 Points à Retenir pour le Jury

### ✅ Ce qui a été bien fait

1. **Architecture propre** : Séparation publique/admin, includes réutilisables
2. **Sécurité complète** : Prepared statements, CSRF, XSS protection, password hashing
3. **Expérience utilisateur** : AJAX pour le panier (pas de reload), validation en temps réel
4. **Base de données normalisée** : Tables bien structurées avec clés étrangères
5. **Gestion d'erreurs** : Les erreurs ne s'affichent pas en production
6. **Code lisible** : Commentaires, noms de variables clairs, structure logique

### 📌 Difficultés Rencontrées

| Problème | Solution |
|----------|----------|
| Erreur PDOConnection inexistant | Remplacé par getPDO() |
| Compteur panier incohérent | Utiliser array_sum() au lieu de count() |
| Chemin AJAX cassé | Passer de /Repaire_Des_Moustaches/... à ./ |
| SQL query dynamique | Utiliser prepared statements partout |
| Session fixation | Ajouter session_regenerate_id(true) |

---

## 🎤 Comment Présenter au Jury

### Ouverture (30 secondes)
> "Le Repaire des Moustaches est un site e-commerce solidaire pour un tiers-lieu de Toulon. Le projet combine une boutique en ligne, un espace admin, et une gestion des utilisateurs avec une forte importance sur la sécurité."

### Architecture (1 minute)
> "Le projet est organisé en 3 parties : les pages publiques à la racine, la boutique dans /public/, et l'admin dans /admin/. La sécurité est au cœur avec des prepared statements pour éviter les injections SQL, des tokens CSRF pour les formulaires, et un hachage des mots de passe."

### Fonctionnalité clé : Le Panier (2 minutes)
> "Quand un utilisateur ajoute un article, JavaScript envoie une requête AJAX sans recharger. Le serveur vérifie le token CSRF, récupère le produit en BDD de manière sécurisée, et ajoute à la session. L'interface se met à jour automatiquement."

### Sécurité (1 minute)
> "J'ai implémenté 6 couches de sécurité : prepared statements contre l'injection SQL, htmlspecialchars() contre le XSS, tokens CSRF, hachage des mots de passe avec password_hash(), régénération d'ID de session après login, et gestion des erreurs pour ne rien afficher en production."

### Fermeture (30 secondes)
> "Le projet est fonctionnel, sécurisé, et scalable. On pourrait améliorer avec un système de paiement réel (Stripe), des notifications email, et un système de logs avancé."

---

## 📞 Questions Possibles du Jury

### Q: Pourquoi PHP et pas une autre technologie ?
> PHP est simple pour un projet vitrine/e-commerce, intégré partout, et performant pour ce cas d'usage.

### Q: Comment gères-tu les sessions ?
> Session PHP stocke les données côté serveur. Le panier est dans $_SESSION, accessible à chaque requête via le cookie de session.

### Q: Et si JavaScript est désactivé chez l'utilisateur ?
> Le panier ne fonctionne pas. C'est acceptable car tous les navigateurs modernes ont JS. Pour plus de compatibilité, on aurait pu faire un formulaire classique.

### Q: Comment le site scale si beaucoup d'utilisateurs arrivent ?
> Il faudrait : caching (Redis), base de données répliquée, serveur équilibré de charge, CDN pour les images.

### Q: Tu as utilisé une IA, c'est un problème ?
> Non, l'IA a aidé à générer du code, mais j'ai compris et validé chaque partie. Je peux expliquer tous les mécanismes de sécurité et d'architecture.

---

## 🎯 Résumé Technique en 1 Page

```
🏗️ ARCHITECTURE
- Frontend : HTML/CSS/JS (Vanilla, pas de framework)
- Backend : PHP (PDO pour la BDD)
- BDD : MySQL avec 8 tables normalisées

🔒 SÉCURITÉ (6 couches)
1. Prepared statements (SQL injection)
2. htmlspecialchars() (XSS)
3. Tokens CSRF (attaques forgées)
4. password_hash() (mots de passe)
5. session_regenerate_id() (session fixation)
6. Error handling (rien en production)

🛒 FONCTIONNALITÉS
- Boutique avec AJAX
- Panier persistant en session
- Checkout avec validation double
- Admin avec stats
- Formulaires avec CSRF
- Authentification sécurisée

📊 PERFORMANCE
- Pas de requête N+1
- Images optimisées
- Code minifié en production
- Logs pour débugging

💡 APPRENTISSAGES
- Prepared statements indispensables
- CSRF souvent oublié mais crucial
- Double validation (client + serveur)
- Gestion d'erreurs silencieuse en prod
```

---

## ✨ Conseil Final

**Le jury cherche à voir si tu comprends :**
1. Pourquoi les choix de sécurité
2. Comment les données circulent
3. L'interaction client-serveur
4. La gestion des erreurs

**N'essaie pas de tout expliquer.** Focus sur :
- L'architecture globale
- Une ou deux fonctionnalités en détail (le panier, la connexion admin)
- La sécurité
- Les défis rencontrés et comment tu les as résolus

Bonne chance ! 🚀
