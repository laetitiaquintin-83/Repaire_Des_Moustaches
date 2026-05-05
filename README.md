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

- **PHP 7.2+** avec PDO (sécurité SQL injection)
- **MySQL 5.7+** (12 tables normalisées, FK, CHECK constraints)
- **Config** : `config/database.php` (singleton PDO pattern)
- **Démo** : `schema.sql` + `demo_data.sql` prêts à l'import

### Structure des fichiers

```
Repaire_Des_Moustaches/
├── index.html              # Accueil (hero + 5 cartes navigation)
├── concept.html            # 3 piliers du projet + footer
├── equipage.html           # Galerie 3 chats + adoption + footer
├── ateliers.html           # 4 ateliers (images + descriptions + CTAs) + footer
├── repaire.html            # Histoire + Engagements + footer
├── douceurs.html           # Galerie 4 visuels gourmands + footer
├── espace.html             # Redirection vers repaire.html
├── style.css               # Styles centralisés (responsive, hover effects)
├── schema.sql              # 12 CREATE TABLE avec constraints
├── demo_data.sql           # Données test (3 chats, 2 adhésions, ateliers, etc)
├── config/
│   └── database.php        # PDO singleton connection
├── public/
│   └── pensionnaires.php   # Proof-of-concept (dynamic cat list)
├── images/                 # 30+ assets (logos, photos, icônes PNG)
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
✅ **Sécurité base** - PDO + CHECK constraints (adhésion 5€ fixe)  
✅ **Délivrables examen** - MCD, SQL complet, PHP backend scaffold, HTML/CSS production-ready  
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

## 🗄️ Base de Données (12 Tables)

Schéma normalisé avec foreign keys et constraints :

- **utilisateurs** - Comptes membres/visiteurs
- **admin_users** - Admin dashboard (future)
- **refuges_partenaires** - Refuges d'adoption partenaires
- **pensionnaires** - Chats en refuge (status: libre/reserve/adopte)
- **adhesions** - Membership annuelle (5€ CHECK constraint)
- **ateliers** - Ateliers proposés
- **reservations_ateliers** - Inscriptions (rôles: participant/animator, prix-libre)
- **belles_histoires** - Testimonials adoption
- **categories_produits** - Menu + boutique categories
- **produits** - Items vente (plats, goodies)
- **commandes** - Order headers
- **lignes_commandes** - Order line items

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

- ✅ **Hiérarchie sémantique** OK (H1, H2, semantic tags)
- ✅ **Accents français** corrigés partout
- ✅ **Footer** présent sur toutes les pages
- ✅ **Responsive** à partir de 1100px breakpoint
- ✅ **MCD** (diagram included in project docs)
- ✅ **SQL** 12 tables, normalized, FK + constraints
- ✅ **PDO** utilisé pour la sécurité (injection SQL impossible)
- ✅ **Membership** contraint à 5€ (CHECK constraint)
- ✅ **Images** bien organisées avec alt tags
- ⚠️ **Admin backend** : scaffold en place, CRUD à implémenter
- ⚠️ **Authentification** : non implémentée (future)

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

**Version** : 1.0 (2026-05-04)  
**Status** : ✅ Frontend & Database finalisés | ⏳ Admin backend en cours
