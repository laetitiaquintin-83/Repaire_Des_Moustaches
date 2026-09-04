# 🐱 Le Repaire des Moustaches

Projet web dynamique développé pour l'examen du titre professionnel **DWWM (Développeur Web et Web Mobile)** - Session 2026.

---

## 📋 Concept du Projet

**Le Repaire des Moustaches** est un tiers-lieu hybride et solidaire basé à Toulon combinant :

- ☕ **Café / Dîner Rétro** : Un espace restauration convivial au style rétro des années 50.
- 🐈 **Refuge & Adoption Solidaire** : Rencontre avec les pensionnaires félins en liberté pour favoriser l'adoption via des refuges partenaires.
- 🛠️ **Ateliers & Événements** : Création, bien-être, pâtisserie et animations (Escape Game) accessibles via le Club des Moustaches.
- 🛒 **Boutique & Adhésion** : Modèle économique coopératif (vente de goodies, consommations et adhésion annuelle à 5€).

---

## 🎨 Stack Technique & Architecture

### Frontend
- **HTML5 sémantique / PHP 8.2+** : Pages PHP modulaires et composants réutilisables.
- **CSS3 Responsive** : Architecture mobile-first, variables CSS (`:root`), effets de survol et transitions.
- **Typographies & Style** : Google Fonts (*Montserrat* pour la lisibilité, *Pacifico* pour la touche rétro).
- **Palette Visuelle** : 
  - Crème `#FFF8E7` | Menthe `#85D6CD` | Rose `#FE7B7E` | Dark `#2B2B2B`

### Backend & Sécurité
- **PHP 8.2+** (architecture native modulaire, principalement procédurale)
- **Base de données MySQL 8.0+** : 13 tables relationnelles normalisées avec clés étrangères (FK) et contraintes `CHECK`.
- **Connexion BDD** : Design Pattern Singleton PDO (`config/database.php`).
- **Paiement** : Stripe Checkout côté serveur (`public/checkout.php`), avec vérification de la session sur la page de succès (`public/success.php`). Aucun endpoint webhook n'est actuellement présent dans le dépôt.
- **Sécurité intégrée** :
  - **Injection SQL** : Requêtes préparées systématiques via PDO.
  - **Attaques XSS** : Échappement des sorties via `htmlspecialchars()`.
  - **CSRF** : Validation par jetons uniques (`hash_equals()`).
  - **Mots de passe** : Hachage fort avec `password_hash()` (bcrypt).

---

## 📁 Structure du Projet

```text
Repaire_Des_Moustaches/
├── composer.json             # Dépendance Stripe PHP
├── package.json              # Scripts d'optimisation et de minification
├── config/
│   ├── database.php          # Connexion PDO & helpers CSRF
│   └── stripe.php            # Lecture de la clé secrète Stripe
├── database/
│   ├── schema.sql             # Structure BDD (13 tables, contraintes, FK)
│   └── demo_data.sql          # Jeu de données de démonstration
│
├── includes/                 # Composants réutilisables (DRY)
│   ├── header.php            # Navigation principale & sessions
│   └── footer.php            # Pied de page & liens légaux
│
├── public/                   # Point d'entrée web et pages applicatives
│   ├── index.php              # Page d'accueil
│   ├── concept.php            # Les 3 piliers du lieu
│   ├── equipage.php           # Présentation de l'équipe et des mascottes
│   ├── ateliers.php           # Catalogue et réservation d'ateliers
│   ├── repaire.php            # Histoire du lieu & engagements
│   ├── projet.php             # Vision et trajectoire du tiers-lieu
│   ├── formulaire.php         # Formulaire de contact / réservation
│   ├── login.php              # Connexion à l'espace administration
│   ├── logout.php             # Déconnexion sécurisée
│   ├── cgv.php                # Conditions Générales de Vente
│   ├── adhesion.php           # Adhésion au Club des Moustaches (5€/an)
│   ├── belles-histoires.php  # Témoignages et histoires d'adoptions
│   ├── boutique.php          # Catalogue de la boutique solidaire
│   ├── cart.php              # Gestion du panier (session PHP)
│   ├── checkout.php          # Tunnel de commande sécurisé
│   ├── confirmation.php      # Confirmation de commande
│   ├── escape-game.php       # Présentation & réservation de l'Escape Game
│   ├── mentions-legales.php  # Informations légales
│   ├── partenaires.php       # Refuges & associations partenaires
│   ├── pensionnaires.php     # Galerie dynamique des chats à l'adoption
│   ├── soumettre-histoire.php # Formulaire d'envoi de témoignages
│   ├── add-to-cart.php        # Action d'ajout au panier et réponse JSON
│   ├── css/                   # Feuilles de style
│   └── js/                    # Scripts du panier et des formulaires
│
├── public/admin/              # Espace Administration (Back-Office)
│   ├── dashboard.php         # Tableau de bord principal (Statistiques & KPI)
│   ├── ateliers.php          # CRUD Gestion des ateliers
│   ├── commandes.php         # Suivi et gestion des commandes
│   ├── moderer-histoires.php # Validation/Modération des témoignages
│   ├── produits.php          # CRUD Catalogue produits
│   └── utilisateurs.php      # Gestion des membres et accès
│
├── public/images/             # Visuels, logos, illustrations & photos
└── README.md                 # Documentation du projet
```

Le traitement des images d'ateliers et de pensionnaires accepte les extensions JPG, JPEG, PNG et WebP. Les images JPG/PNG sont chargées avec GD puis converties en WebP ; les fichiers WebP sont déplacés vers le dossier d'images. La validation actuelle repose sur l'extension et le traitement GD, sans contrôle `finfo` dédié.

🗄️ Modèle de Données (MySQL - 13 Tables)
utilisateurs : Gestion des membres et clients.

admin_users : Comptes administrateurs sécurisés.

refuges_partenaires : Associations et refuges associés.

pensionnaires : Chats hébergés (statuts : disponible, réservé, adopté).

adhesions : Suivi des cotisations (5€/an avec contrainte CHECK).

ateliers & reservations_ateliers : Inscriptions et gestion du prix libre/animation.

belles_histoires : Témoignages soumis à modération (en attente, approuvée, refusée).

categories_produits, produits, commandes, lignes_commandes : Module e-commerce complet.

demandes : Formulaire de contact, privatisations et propositions d'ateliers.

🚀 Installation & Test en Local (Laragon / XAMPP)
Cloner / Placer le projet dans votre dossier web local (ex: C:\laragon\www\Repaire_Des_Moustaches\).

Lancer le serveur MySQL & Apache.

Configurer la base de données :

Accéder à phpMyAdmin (http://localhost/phpmyadmin).

Créer une base de données nommée repaire_des_moustaches.

Importer database/schema.sql (structure des tables).

Importer database/demo_data.sql (données de test).

Accéder à l'application : http://localhost/Repaire_Des_Moustaches/.

💡 Conformité Titre Pro DWWM
✅ Architecture modulaire : composants communs regroupés dans `includes/` (Header, Nav, Footer).

✅ Code Sémantique & Accessibilité : Balises HTML5, attributs alt renseignés, contraste validé.

✅ Mesures de sécurité : protections présentes contre les injections SQL, XSS et CSRF, avec gestion des sessions HTTP.

✅ Responsive Design : Testé et optimisé pour écrans mobiles, tablettes et ordinateurs.

✅ Soutenance documentée : code source, schéma SQL et interface responsive présents dans le projet.

Auteur : Projet réalisé dans le cadre du Titre Professionnel DWWM 2026.

Statut : ✅ Projet fonctionnel pour démonstration (Frontend ✅ | Backend ✅ | BDD ✅ | Sécurité à compléter/tester)