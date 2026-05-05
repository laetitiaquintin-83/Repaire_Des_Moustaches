# 🔐 Système d'Administration - Le Repaire des Moustaches

## Accès Admin

**URL**: `http://localhost/Repaire_Des_Moustaches/login.php`

**Identifiants de démo**:
- Email: `admin@repaire.local`
- Mot de passe: `admin123`

## ⚠️ À faire avant la mise en production

1. **Changer le mot de passe admin** - Très important !
2. Ajouter HTTPS/SSL
3. Configurer les en-têtes de sécurité (CSRF tokens complets, etc.)
4. Sauvegarder la base de données régulièrement
5. Implémenter des logs d'audit
6. Mettre en place des rate limits pour la connexion

## Fonctionnalités Admin

### 📊 Dashboard
- Vue d'ensemble des statistiques
- Nombre d'histoires à modérer
- Nombre total de produits, commandes, ateliers, utilisateurs

### 📖 Belles Histoires - Modération
- **Tab "À modérer"** : Voir les histoires en attente
  - Cliquer "Publier" pour accepter
  - Cliquer "Rejeter" pour refuser
- **Tab "Publiées"** : Voir les histoires déjà publiées

### 🎨 Ateliers (En développement)
- Page stub créée, à compléter

### 🛍️ Produits (En développement)
- Page stub créée
- 10 produits déjà en base

### 📦 Commandes (En développement)
- Page stub créée
- Système de panier/paiement à implémenter

### 👥 Utilisateurs (En développement)
- Page stub créée

## Architecture Technique

### Fichiers Admin
```
admin/
├── dashboard.php           # Vue d'ensemble
├── moderer-histoires.php   # Modération des histoires
├── ateliers.php            # Gestion des ateliers (stub)
├── produits.php            # Gestion des produits (stub)
├── commandes.php           # Gestion des commandes (stub)
└── utilisateurs.php        # Gestion des utilisateurs (stub)

login.php                    # Page de connexion
logout.php                   # Déconnexion
```

### Sécurité
- ✓ Sessions PHP avec `session_start()`
- ✓ Vérification de `$_SESSION['admin_id']` sur chaque page
- ✓ Mots de passe hashés avec bcrypt (`password_hash()`)
- ✓ Vérification avec `password_verify()`
- ✓ Redirection automatique vers login.php si pas connecté

### Base de Données
- Table `admin_users` avec email/mot_de_passe
- Champs de modération dans `belles_histoires` :
  - `statut` (en_attente, publiee, refusee)
  - `admin_id` (qui a modéré)
  - `date_publication` (quand publiée)

## Prochaines Étapes

1. Implémenter la gestion complète des produits (CRUD)
2. Ajouter le système de panier/commandes
3. Créer les page de gestion des ateliers
4. Ajouter un système d'invitation pour les modérateurs
5. Implémenter les logs d'activité admin

## Support

Pour toute question, consultez la documentation du projet.
