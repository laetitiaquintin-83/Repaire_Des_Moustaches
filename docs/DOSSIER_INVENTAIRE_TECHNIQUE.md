# Dossier d'inventaire technique

Je rédige ce dossier à partir du code réellement présent dans mon projet. Je ne m'appuie pas sur une intention, mais sur ce qui est effectivement codé dans le dépôt.

## 1. Architecture et arborescence réelle

Je travaille sur une application PHP procédurale structurée autour de pages publiques, d'un espace d'administration et de quelques composants partagés.

### Arborescence utile

- `config/` : j'y centralise la connexion à la base de données et les helpers transverses de session et de CSRF.
- `includes/` : j'y place les éléments communs du site, comme l'en-tête, le pied de page et la bannière cookies.
- `public/` : j'y regroupe l'ensemble des pages applicatives accessibles au visiteur, ainsi que l'espace admin dans `public/admin/`.
- `database/` : j'y conserve les scripts SQL du projet, notamment le schéma et les données de démonstration.
- `public/css/` et `public/js/` : j'y stocke les feuilles de style et les scripts JavaScript réellement utilisés par le front.
- `public/api/` : j'y ai un fichier JSON local utilisé comme source de données statique pour la page partenaires.
- `scripts/` : j'y ai des scripts Node.js pour optimiser les images et minifier les assets.
- `docs/` : j'y ai les documents de cadrage, de sécurité et de soutenance.

### Ce que j'utilise réellement dans le code

- J'inclus le header partagé dans la plupart des pages publiques via `includes/header.php`.
- J'inclus le footer partagé via `includes/footer.php`.
- J'utilise `config/database.php` comme point central pour la BDD, la session et les fonctions CSRF.
- J'ai un espace d'administration fonctionnel sous `public/admin/` avec authentification, gestion des contenus et modération.
- J'ai un parcours e-commerce fonctionnel avec boutique, panier, checkout et confirmation.

## 2. Composants et fonctionnalités logiques

### Front-office réellement développé

Je peux présenter les fonctionnalités suivantes comme réellement codées :

- Accueil et pages de présentation du lieu, du concept et du projet.
- Page équipage / pensionnaires avec lecture dynamique depuis la base et filtrage JavaScript.
- Page pensionnaires avec affichage dynamique depuis la base et filtres côté client.
- Page ateliers avec lecture depuis la table `ateliers`.
- Boutique avec catalogue produit regroupé par catégories.
- Panier en session PHP avec ajout, mise à jour, suppression et vidage.
- Tunnel de commande avec création de commande et lignes de commande.
- Page confirmation de commande.
- Formulaire de contact avec validation serveur.
- Formulaire de demande / privatisation / animation avec insertion en base.
- Soumission d'histoires avec modération administrateur.
- Page belles histoires avec affichage des histoires publiées.
- Page partenaires avec lecture d'un JSON local.
- Page d'adhésion, qui alimente le panier via une ligne spécifique adhésion.

### Back-office réellement présent

Je peux aussi décrire ces fonctions d'administration :

- Connexion administrateur.
- Tableau de bord avec indicateurs de synthèse.
- Gestion des ateliers en CRUD.
- Gestion des produits en CRUD avec upload d'images.
- Gestion des pensionnaires en CRUD avec images et rattachement refuge.
- Gestion des commandes avec changement de statut.
- Modération des histoires avec publication ou rejet.
- Liste des utilisateurs avec agrégats métier.

## 3. Base de données et modèles

Dans le schéma SQL réellement présent, j'identifie les tables suivantes :

- `adhesions`
- `admin_users`
- `ateliers`
- `belles_histoires`
- `categories_produits`
- `commandes`
- `demandes`
- `lignes_commandes`
- `pensionnaires`
- `produits`
- `refuges_partenaires`
- `reservations_ateliers`
- `utilisateurs`

### Relations que j'identifie dans le code et le schéma

- `adhesions.utilisateur_id` vers `utilisateurs.id`
- `ateliers.admin_id` vers `admin_users.id`
- `belles_histoires.utilisateur_id` vers `utilisateurs.id`
- `belles_histoires.admin_id` vers `admin_users.id`
- `commandes.utilisateur_id` vers `utilisateurs.id`
- `lignes_commandes.commande_id` vers `commandes.id`
- `lignes_commandes.produit_id` vers `produits.id`
- `pensionnaires.refuge_id` vers `refuges_partenaires.id`
- `pensionnaires.admin_id` vers `admin_users.id`
- `produits.categorie_id` vers `categories_produits.id`
- `reservations_ateliers.utilisateur_id` vers `utilisateurs.id`
- `reservations_ateliers.atelier_id` vers `ateliers.id`

### Contraintes réellement visibles

- Email unique sur `admin_users` et `utilisateurs`.
- `CHECK` sur le montant d'adhésion à `5.00`.
- `CHECK` sur les dates d'adhésion.
- `CHECK` sur le prix des produits.
- `CHECK` sur la quantité et le prix unitaire des lignes de commande.
- `CHECK` sur le montant libre des réservations d'ateliers.
- `CHECK` sur la capacité maximale des ateliers.
- Clés étrangères avec actions `CASCADE`, `RESTRICT` ou `SET NULL` selon les cas.

## 4. Sécurité et bonnes pratiques implémentées

Je peux justifier les mécanismes suivants à partir du code :

- Connexion PDO centralisée avec mode exception et prépares désactivées en émulation.
- Journalisation des erreurs dans `logs/php-errors.log`.
- Désactivation de l'affichage des erreurs côté serveur.
- Tokens CSRF générés en session et vérifiés avant les actions sensibles.
- Authentification admin par `password_verify()`.
- Régénération d'identifiant de session après connexion admin.
- Échappement systématique des sorties avec `htmlspecialchars()`.
- Validation serveur des emails, longueurs, motifs autorisés et formats de données.
- Contrôle d'accès admin par vérification de `admin_id` et du rôle.
- Upload d'images avec whitelist d'extensions.
- Requêtes SQL préparées pour les actions sur les données.
- Règle 404 déclarée dans `.htaccess`.

### Extrait clé 1 : socle BDD et CSRF

```php
function getPDO(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $host, $dbname, $charset);

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);
    return $pdo;
}

function generateCSRFToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken(string $token): bool
{
    if (empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}
```

### Extrait clé 2 : création de commande

```php
$stmt = $pdo->prepare('
    INSERT INTO commandes (utilisateur_id, date_commande, montant_total, statut)
    VALUES (?, NOW(), ?, "en_attente")
');
$stmt->execute([$utilisateur_id, $total]);
$commande_id = (int)$pdo->lastInsertId();

foreach ($cart as $produit_id => $item) {
    $stmt = $pdo->prepare('
        INSERT INTO lignes_commandes (commande_id, produit_id, quantite, prix_unitaire)
        VALUES (?, ?, ?, ?)
    ');
    $stmt->execute([
        $commande_id,
        $produit_id,
        (int)$item['quantite'],
        (float)$item['prix']
    ]);
}
```

### Extrait clé 3 : contrôle d'accès admin

```php
if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}
```

## 5. Ce que je peux présenter dans mon dossier

Je peux mettre en avant trois blocs solides :

- le socle de connexion BDD et CSRF dans `config/database.php` ;
- le tunnel de commande dans `public/checkout.php` ;
- le contrôle d'accès admin et la modération dans `public/admin/moderer-histoires.php` ou le CRUD produits dans `public/admin/produits.php`.

## 6. Ce que je dois éviter d'affirmer

Je ne dois pas écrire que j'ai un MVC strict, parce que ce n'est pas le cas dans le code.

Je ne dois pas dire que le formulaire de contact enregistre réellement les messages en base, car le code valide surtout la saisie et affiche un message de succès.

Je ne dois pas dire que la page partenaires est alimentée par la base de données, car elle lit un fichier JSON local.

Je ne dois pas dire que l'adhésion est une table métier directe du front, car la page d'adhésion passe par le panier.

## 7. Résumé prêt pour ma soutenance

Je peux donc décrire mon projet comme une application PHP procédurale sécurisée, organisée autour de pages publiques, d'un espace administrateur protégé, d'un catalogue produit avec panier et commande, d'un module d'atelier, d'un système de témoignages modérés, et d'une base MySQL normalisée avec relations et contraintes.
