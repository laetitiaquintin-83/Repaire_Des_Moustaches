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
- **HTML5 sémantique / PHP 8.2+** : Séparation vue / logique, templates réutilisables.
- **CSS3 Responsive** : Architecture mobile-first, variables CSS (`:root`), effets de survol et transitions.
- **Typographies & Style** : Google Fonts (*Montserrat* pour la lisibilité, *Pacifico* pour la touche rétro).
- **Palette Visuelle** : 
  - Crème `#FFF8E7` | Menthe `#85D6CD` | Rose `#FE7B7E` | Dark `#2B2B2B`

### Backend & Sécurité
- **PHP 8.2+** (Programmation orientée objet / Procédurale sécurisée)
- **Base de données MySQL 8.0+** : 13 tables relationnelles normalisées avec clés étrangères (FK) et contraintes `CHECK`.
- **Connexion BDD** : Design Pattern Singleton PDO (`config/database.php`).
- **Sécurité intégrée** :
  - **Injection SQL** : Requêtes préparées systématiques via PDO.
  - **Attaques XSS** : Échappement des sorties via `htmlspecialchars()`.
  - **CSRF** : Validation par jetons uniques (`hash_equals()`).
  - **Mots de passe** : Hachage fort avec `password_hash()` (bcrypt).

---

## 📁 Structure du Projet

```text
Repaire_Des_Moustaches/
├── index.php                 # Page d'accueil (Hero, présentation, accès rapide)
├── concept.php               # Les 3 piliers du lieu
├── equipage.php              # Présentation de l'équipe et des mascottes
├── ateliers.php              # Catalogue et réservation d'ateliers
├── repaire.php               # Histoire du lieu & engagements
├── projet.php                # Vision et trajectoire du tiers-lieu
├── formulaire.php            # Formulaire de contact / réservation
├── login.php                 # Connexion à l'espace administration
├── logout.php                # Déconnexion sécurisée
├── cgv.php                   # Conditions Générales de Vente
├── style.css                 # Feuille de style globale responsive
├── schema.sql                # Structure BDD (13 tables, contraintes, FK)
├── demo_data.sql             # Jeu de données de démonstration
│
├── includes/                 # Composants réutilisables (DRY)
│   ├── header.php            # Navigation principale & sessions
│   └── footer.php            # Pied de page & liens légaux
│
├── config/
│   └── database.php          # Singleton PDO & helpers de sécurité (CSRF)
│
├── public/                   # Espace public / Modules interactifs
│   ├── adhésion.php          # Adhésion au Club des Moustaches (5€/an)
│   ├── belles-histoires.php  # Témoignages et histoires d'adoptions
│   ├── boutique.php          # Catalogue de la boutique solidaire
│   ├── cart.php              # Gestion du panier (session PHP)
│   ├── checkout.php          # Tunnel de commande sécurisé
│   ├── confirmation.php      # Confirmation de commande
│   ├── escape-game.php       # Présentation & réservation de l'Escape Game
│   ├── mentions-legales.php  # Informations légales
│   ├── partenaires.php       # Refuges & associations partenaires
│   ├── pensionnaires.php     # Galerie dynamique des chats à l'adoption
│   ├── reparateur.php        # Service / Atelier de réparation solidaire
│   └── soumettre-histoire.php# Formulaire d'envoi de témoignages
│
├── admin/                    # Espace Administration (Back-Office)
│   ├── dashboard.php         # Tableau de bord principal (Statistiques & KPI)
│   ├── ateliers.php          # CRUD Gestion des ateliers
│   ├── commandes.php         # Suivi et gestion des commandes
│   ├── moderer-histoires.php # Validation/Modération des témoignages
│   ├── produits.php          # CRUD Catalogue produits
│   └── utilisateurs.php      # Gestion des membres et accès
│
├── images/                   # Visuels, logos, illustrations & photos
└── README.md                 # Documentation du projet
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

Importer schema.sql (structure des tables).

Importer demo_data.sql (données de test).

Accéder à l'application : http://localhost/Repaire_Des_Moustaches/.

💡 Conformité Titre Pro DWWM
✅ Architecture DRY : Aucun code dupliqué grâce aux includes/ (Header, Nav, Footer).

✅ Code Sémantique & Accessibilité : Balises HTML5, attributs alt renseignés, contraste validé.

✅ Sécurité conforme OWASP : Shield contre les failles SQLi, XSS, CSRF, et gestion propre des sessions HTTP.

✅ Responsive Design : Testé et optimisé pour écrans mobiles, tablettes et ordinateurs.

✅ Soutenance prête : code source, schéma SQL et interface responsive documentés dans le projet.

Auteur : Projet réalisé dans le cadre du Titre Professionnel DWWM 2026.

Statut : ✅ Prêt pour soutenance (Frontend ✅ | Backend ✅ | BDD ✅ | Sécurité ✅)