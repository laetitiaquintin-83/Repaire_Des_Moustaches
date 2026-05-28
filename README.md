# 🐱 Le Repaire des Moustaches

Projet web pour le titre professionnel **DWWM (Développeur Web et Web Mobile)** - Examen Titre Pro 2026.

## 📋 Concept

**Le Repaire des Moustaches** est un tiers-lieu hybride combinant :

- 🍔 **Dîner rétro** (style années 50 américain)
- 🐈 **Refuge de chats** (adoption solidaire)
- 🛠️ **Ateliers solidaires** (création, bien-être, administratif, pâtisserie)
- 🍰 **Restaurant & boutique** (modèle économique coopératif)

Basé à Toulon, c'est un lieu libre d'accès où chacun peut rencontrer les pensionnaires en liberté, se former via les ateliers (adhésion 5€/an), et soutenir le projet par la consommation.

---

## 🎨 Architecture du Projet

### Frontend

- **HTML5** sémantique sur 6 pages + redirection espace.html
- **CSS3** responsive avec variables de couleurs et hover effects
- **Google Fonts** : Montserrat (body) + Pacifico (titres rétro)
- **Palette** : Crème #FFF8E7 | Menthe #85D6CD | Rose #FE7B7E | Gris #2B2B2B

### Backend (Infrastructure)

- **PHP 8.2+** avec PDO (sécurité SQL injection)
- **MySQL 8.0+** (13 tables normalisées, FK, CHECK constraints)
- **Config** : `config/database.php` (singleton PDO pattern)
- **Démo** : `schema.sql` + `demo_data.sql` prêts à l'import

### Structure des fichiers

```
Repaire_Des_Moustaches/
├── index.php               # Accueil (hero + 5 cartes + 🔐 bouton admin)
├── concept.php             # 3 piliers du projet
├── equipage.php            # Galerie 3 chats + adoption
├── ateliers.php            # Ateliers dynamiques depuis BDD
├── repaire.php             # Histoire + Engagements
├── douceurs.php            # Galerie gourmande
├── projet.php              # Vision & trajectoire
├── formulaire.php          # Réservation + CSRF protection
├── login.php               # Authentification admin
├── logout.php              # Déconnexion
├── style.css               # Styles (responsive, 🔐 cadenas)
├── schema.sql              # 13 CREATE TABLE + DEMANDES
├── demo_data.sql           # Données test
├── includes/
│   ├── header.php          # Header + nav (include_once en début)
│   └── footer.php          # Footer (include_once en fin)
├── config/
│   └── database.php        # PDO singleton + CSRF tokens
├── public/
│   ├── pensionnaires.php   # Galerie chats dynamique
│   ├── belles-histoires.php# Testimonials adoption
│   ├── boutique.php        # Catalogue produits
│   ├── cart.php            # Panier session
│   ├── checkout.php        # Commande finale + CSRF protection
│   ├── confirmation.php    # Confirmation commande
│   └── soumettre-histoire.php # Form testimonial
├── admin/
│   ├── dashboard.php       # Tableau de bord admin
│   ├── ateliers.php        # CRUD ateliers
│   ├── produits.php        # CRUD produits
│   ├── commandes.php       # Gestion commandes
│   ├── utilisateurs.php    # Gestion utilisateurs
│   └── moderer-histoires.php # Modération belles histoires
├── images/                 # 30+ assets (logos, photos, icônes)
├── SECURITE_AUDIT.md       # Documentation sécurité (6 niveaux)
├── GUIDE_PRESENTATION_JURY.md # Script présentation + Q&A
├── BOUTON_ADMIN_CADENAS.md # Implémentation 🔐 cadenas
├── MCD.md                  # Diagramme entité-association
├── .gitignore              # Ignore OS, IDE, build files
└── README.md               # Ce fichier
```

---

## 🎯 Points Forts du Projet

✅ **Responsive design** - Mobile-first avec breakpoint 1100px  
✅ **Accessibilité** - Alt tags, sémantique HTML, lang="fr"  
✅ **Cohérence visuelle** - Variables CSS, composants réutilisables, footer récurrent  
✅ **Accents français** - Toutes les pages avec accents corrects (é, è, ê, à)  
✅ **SEO-friendly** - Titres hiérarchisés (H1), descriptions claires, meta viewport  
✅ **Navigation fluide** - Menu principal identique partout + footer avec liens  
✅ **Sécurité renforcée** - PDO + CSRF tokens (hash_equals) + htmlspecialchars() sur tous les outputs  
✅ **Formulaire de réservation** - formulaire.php avec validation server-side et insertion BDD  
✅ **Délivrables examen** - MCD, SQL complet, PHP backend fonctionnel, HTML/CSS production-ready  
✅ **Hover effects** - Boutons avec transition + shadow, liens sociaux colorés  
✅ **Zéro erreurs** - HTML/CSS valides, pas d'erreurs console

---

## 🚀 Utilisation Locale (Laragon)

1. **Placer le dossier** dans `C:\laragon\www\Repaire_Des_Moustaches\`
2. **Ouvrir navigateur** : `http://localhost/Repaire_Des_Moustaches/`
3. **Base de données** :
   - Ouvrir phpMyAdmin (http://localhost/phpmyadmin)
   - Créer une nouvelle BDD `repaire_des_moustaches`
   - Importer `schema.sql` (structure)
   - Importer `demo_data.sql` (test data)
4. **Config BD** : Éditer `config/database.php` si besoin d'autres credentials

---

## 📱 Pages Principales

| Page              | Contenu                                                                                               |
| ----------------- | ----------------------------------------------------------------------------------------------------- |
| **index.html**    | Landing page - Hero section + 5 cartes de navigation (Concept, Équipage, Ateliers, Repaire, Douceurs) |
| **concept.html**  | Explique 3 piliers (Dîner & Goodies, Ateliers Solidaires, Coup de Foudre adoption)                    |
| **equipage.html** | Galerie 3 chats + "Son histoire" + "Le rencontrer" buttons                                            |
| **ateliers.html** | 4 ateliers (images + titles + descriptions + CTAs variés)                                             |
| **repaire.html**  | Histoire + Engagements + Image ambiance                                                               |
| **douceurs.html** | Galerie gourmande (4 visuels produits/pâtisseries)                                                    |

---

## 🗄️ Base de Données (13 Tables)

Schéma normalisé avec foreign keys et constraints :

- **utilisateurs** - Comptes membres/visiteurs (email, password bcrypt)
- **admin_users** - Admin dashboard (login, password bcrypt)
- **refuges_partenaires** - Refuges d'adoption partenaires
- **pensionnaires** - Chats en refuge (status: libre/reserve/adopte)
- **adhesions** - Membership annuelle (5€ CHECK constraint)
- **ateliers** - Ateliers proposés
- **reservations_ateliers** - Inscriptions (rôles: participant/animator, prix-libre)
- **belles_histoires** - Testimonials adoption (modération: attente/approuvee/refusee)
- **categories_produits** - Menu + boutique categories
- **produits** - Items vente (plats, goodies)
- **commandes** - Order headers
- **lignes_commandes** - Order line items
- **demandes** - Réservations/animations/privatisations (motif: participer/animer/prive, statut: nouvelle/traitee/refusee)

---

## 🎨 Customization CSS

Toutes les couleurs sont des **variables CSS** :

```css
:root {
  --creme: #fff8e7;
  --vert-menthe: #85d6cd;
  --rose-corail: #fe7b7e;
  --gris-fonce: #2b2b2b;
}
```

Pour changer le thème : modifier les 4 values en `:root`.

---

## 📝 Notes pour l'Examen DWWM

### ✅ Délivrables Complétés

- ✅ **Hiérarchie sémantique** - H1, H2, semantic tags (header, nav, main, footer)
- ✅ **Accents français** - Corrects partout (é, è, ê, à, ç)
- ✅ **Footer** - Présent sur toutes les pages
- ✅ **Responsive** - Mobile-first avec breakpoint 1100px
- ✅ **MCD** - Diagramme inclus (MCD.md)
- ✅ **SQL** - 13 tables, normalisées, FK + CHECK constraints
- ✅ **PDO** - Prepared statements (? ou :param) - Injection SQL impossible
- ✅ **Sécurité CSRF** - Tokens uniques par session (hash_equals validation)
- ✅ **Sécurité XSS** - htmlspecialchars() sur tous les outputs
- ✅ **Sécurité Auth** - bcrypt password_hash (PASSWORD_DEFAULT)
- ✅ **Validation server-side** - Email, enum, date, type checking
- ✅ **Formulaires** - formulaire.php avec INSERT INTO demandes
- ✅ **CRUD Admin** - Ateliers, produits, utilisateurs, histoires, commandes
- ✅ **Images** - Alt tags, bien organisées, responsive
- ✅ **Documentation** - SECURITE_AUDIT.md, GUIDE_PRESENTATION_JURY.md, BOUTON_ADMIN_CADENAS.md
- ✅ **UI Polish** - 🔐 Bouton admin cadenas sur accueil

---

## 🔄 Workflow Git

```bash
git init
git add .
git commit -m "Initial commit: DWWM project structure"
git remote add origin <your-repo>
git push -u origin main
```

---

## 👨‍💻 Auteur & Contexte

**Projet d'examen** pour le titre **DWWM 2026**.  
Concept inspiré du "Repaire des Moustaches" réel (tiers-lieu Toulon).

Livrable pour démonstration à l'examen :

- Site web production-ready (Frontend ✅)
- Architecture base de données (SQL ✅)
- Fondation backend PHP (Scaffold ✅)
- Délivrables d'examen (MCD, SQL, HTML/CSS/JS)

---

## 📞 Support

Questions sur le projet ? Vérifier :

1. Accents français (tous les .html)
2. Footer sur toutes les pages
3. Responsive design (resize browser)
4. Images présentes dans `images/` folder
5. Database importée correctement

---

---

## 📚 Documentation Complète

| Fichier | Contenu |
| --- | --- |
| **SECURITE_AUDIT.md** | Audit complet 6 niveaux (SQL injection, XSS, CSRF, Auth, Password, Input validation) |
| **GUIDE_PRESENTATION_JURY.md** | Script présentation + demo 10 min + 8 questions jury + conseils |
| **POINTS_FRICTION_JURY.md** | 4 points critiques du jury + solutions + pépites à valoriser |
| **BOUTON_ADMIN_CADENAS.md** | Implémentation 🔐 cadenas + variantes CSS |
| **MCD.md** | Diagramme entité-association (13 tables) |
| **schema.sql** | Structure BDD complète avec constraints |
| **demo_data.sql** | Données test pour démonstration |

---

## ✅ Architecture FINALISÉE pour l'Examen

**Tous les points du jury résolus:**

1. ✅ **Zéro mélange .html/.php** - Tous les fichiers principaux en .php uniquement
2. ✅ **Pas de duplication** - Header/footer mutua lisés via includes/
3. ✅ **Structure cohérente** - Modules clairement séparés (/admin, /public, racine)
4. ✅ **Versions à jour** - PHP 8.2+, MySQL 8.0+
5. ✅ **Pas de redirection mystère** - espace.html supprimé
6. ✅ **Sessions partout** - Même pages d'accueil en .php avec session_start

---

**Version** : 1.2 (2026-05-26)  
**Status** : ✅ **EXAM-READY NIKEL** - Frontend ✅ | Backend ✅ | Database ✅ | Sécurité ✅ | Architecture ✅ | Documentation ✅
