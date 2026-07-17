# 🔐 Audit de Sécurité - Le Repaire des Moustaches

**Date** : 26 mai 2026  
**Projet** : Titre Professionnel DWWM - Le Repaire des Moustaches

---

## 📋 Résumé Exécutif

Ce document détaille les **mesures de sécurité implémentées** dans le projet pour le jury d'examen. Le projet suit les **bonnes pratiques de sécurité web** en PHP/MySQL.

---

## 🛡️ Mesures de Sécurité Implémentées

### 1️⃣ **Protection contre les injections SQL**

**Menace** : Injection SQL - Modification de requêtes pour accéder aux données

**Solution implémentée** : **Prepared Statements avec PDO**

```php
// ❌ MAUVAIS - Vulnérable aux injections SQL
$sql = "SELECT * FROM utilisateurs WHERE email = '" . $_POST['email'] . "'";

// ✅ BON - Utilisation de prepared statements
$stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = ?');
$stmt->execute([$email]);
```

**Fichiers concernés** :
- `config/database.php` - PDO singleton avec prepared statements
- `public/checkout.php` - Insertion commandes & lignes
- `public/add-to-cart.php` - Requêtes panier
- `public/soumettre-histoire.php` - Insertion histoires
- `formulaire.php` - Insertion demandes
- `admin/*.php` - Toutes les opérations CRUD

**Preuve pour le jury** :
```php
// Exemple: admin/produits.php
$stmt = $pdo->prepare('INSERT INTO produits (nom, description, prix, categorie_id, image_url) 
                       VALUES (?, ?, ?, ?, ?)');
$stmt->execute([$nom, $description, $prix, $categorie_id, $image_url]);
```

---

### 2️⃣ **Protection contre XSS (Cross-Site Scripting)**

**Menace** : Injection de scripts malveillants dans les pages

**Solution implémentée** : **htmlspecialchars() sur tous les outputs**

```php
// ❌ MAUVAIS - Vulnérable à XSS
echo $user_input;

// ✅ BON - Échappement des caractères spéciaux
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');
```

**Utilisation** :
- `ENT_QUOTES` : Échappe les guillemets simples et doubles
- `'UTF-8'` : Encoding UTF-8 pour accents français

**Fichiers concernés** :
- `public/checkout.php` : Affichage des adresses utilisateurs
- `public/belles-histoires.php` : Affichage des histoires
- `formulaire.php` : Affichage des confirmations
- `admin/moderer-histoires.php` : Affichage des histoires en modération

**Preuve pour le jury** :
```php
// Exemple: formulaire.php
<small>Numéro de demande: #<?php echo htmlspecialchars($demand_id); ?></small>
```

---

### 3️⃣ **Protection contre CSRF (Cross-Site Request Forgery)**

**Menace** : Attaques de falsification de requête intersite (formulaires malveillants)

**Solution implémentée** : **Tokens CSRF uniques par session**

```php
// Générer un token CSRF unique
function generateCSRFToken(): string {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Valider le token à la soumission
function validateCSRFToken(string $token): bool {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}
```

**Caractéristiques** :
- ✅ Token généré à chaque requête GET (affichage form)
- ✅ Validation du token lors du POST avec `hash_equals()` (protection timing attack)
- ✅ 32 octets aléatoires pour token fort

**Fichiers protégés** :
- ✅ `formulaire.php` - Demandes de réservation
- ✅ `public/checkout.php` - Validation des commandes
- ✅ `public/add-to-cart.php` - Ajout au panier
- ✅ `public/soumettre-histoire.php` - Soumission d'histoires
- ✅ `admin/ateliers.php` - CRUD ateliers
- ✅ `admin/produits.php` - CRUD produits
- ✅ `admin/moderer-histoires.php` - Modération

**Preuve pour le jury** :
```php
// Formulaire HTML
<form method="POST">
    <input type="hidden" name="csrf_token" 
           value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
    <!-- champs du formulaire -->
</form>

// Validation PHP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Erreur de sécurité : token CSRF invalide';
    }
}
```

---

### 4️⃣ **Hachage des mots de passe**

**Menace** : Stockage en clair des mots de passe

**Solution implémentée** : **bcrypt via password_hash()**

```php
// ✅ BON - Hachage sécurisé
$mot_de_passe_hash = password_hash($password, PASSWORD_DEFAULT);

// Stockage en base
$stmt = $pdo->prepare('INSERT INTO utilisateurs (..., mot_de_passe) VALUES (..., ?)');
$stmt->execute([..., $mot_de_passe_hash]);

// Vérification à la connexion
if (password_verify($password_saisi, $hash_bdd)) {
    // OK - connexion valide
}
```

**Fichiers concernés** :
- `login.php` - Authentification
- `public/checkout.php` - Création utilisateur pour commande
- `admin/dashboard.php` - Gestion admin

---

### 5️⃣ **Validation des entrées côté serveur**

**Menace** : Données invalides ou malformées

**Solution implémentée** : **Vérification de type et format**

```php
// Validation email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Email invalide';
}

// Validation numérique
$produit_id = (int)$_POST['produit_id'];  // Cast entier

// Validation motif
if (!in_array($motif, ['participer', 'animer', 'prive'])) {
    $error = 'Motif invalide';
}

// Validation date
$date_parsed = DateTime::createFromFormat('Y-m-d', $date);
if (!$date_parsed || $date_parsed->format('Y-m-d') !== $date) {
    $error = 'Date invalide';
}
```

**Fichiers concernés** :
- `formulaire.php` - Validation des demandes
- `public/checkout.php` - Validation adresse livraison
- `admin/ateliers.php` - Validation date/capacité

---

### 6️⃣ **Authentification & Autorisation**

**Menace** : Accès non autorisé aux pages admin

**Solution implémentée** : **Vérification session sur pages protégées**

```php
// Protection des pages admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}
```

**Pages protégées** :
- ✅ `admin/dashboard.php`
- ✅ `admin/ateliers.php`
- ✅ `admin/produits.php`
- ✅ `admin/commandes.php`
- ✅ `admin/utilisateurs.php`
- ✅ `admin/moderer-histoires.php`

---

### 7️⃣ **Gestion des sessions**

**Mesures** :
- ✅ `session_start()` au début de chaque fichier PHP
- ✅ `declare(strict_types=1)` pour typage strict
- ✅ Destruction session à la déconnexion (`logout.php`)

---

## 📊 Matrice de Couverture Sécurité

| Vulnérabilité | Menace | Mitigation | Fichiers |
|---|---|---|---|
| **SQL Injection** | Accès/modification BD | Prepared statements | Tous PHP |
| **XSS** | Scripts malveillants | htmlspecialchars() | Output affichage |
| **CSRF** | Faux formulaires | Tokens uniques | POST forms |
| **Weak Passwords** | Accès non autorisé | bcrypt + hachage | login.php |
| **Unauthorized Access** | Pages admin | Session check | admin/*.php |
| **Invalid Data** | Erreurs métier | Validation type | Tous formulaires |
| **Timing Attacks** | Token bypass | hash_equals() | config/database.php |

---

## 🎯 Points Forts pour le Jury

1. **✅ Sécurité multicouche** - 6 protections implémentées
2. **✅ Bonnes pratiques PHP** - Prepared statements systématiques
3. **✅ Protection OWASP Top 10** - Injection SQL, XSS, CSRF couverts
4. **✅ Code maintenable** - Fonctions réutilisables dans config/database.php
5. **✅ Documentation** - Commentaires explicatifs dans le code

---

## 🔍 Checklist de Vérification pour L'Oral

**À montrer au jury** :

- [ ] Ouvrir `formulaire.php` → montrer token CSRF dans form
- [ ] Ouvrir `config/database.php` → montrer generateCSRFToken()
- [ ] Montrer un prepared statement : `$stmt->prepare('...')`
- [ ] Montrer htmlspecialchars() en action
- [ ] Montrer password_verify() dans login.php
- [ ] Montrer vérification session dans admin/

---

## 📖 Références OWASP

- **SQL Injection** : A03:2021 - Injection
- **XSS** : A07:2021 - Cross-Site Scripting
- **CSRF** : A01:2021 - Broken Access Control

---

**Audit réalisé le 26 mai 2026**  
**Conformité** : Niveau Titre Professionnel DWWM
