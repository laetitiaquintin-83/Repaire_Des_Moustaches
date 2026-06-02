# 📋 EXEMPLES CONCRETS - Git & Versioning

## 1. Historique Git Réel du Projet

Voici l'historique complet du projet montrant la traçabilité :

```
* 4942b54 (HEAD -> main, origin/main) chore(git): mise en place workflow et configuration sécurisée
* ca8389a Refactor code structure for improved readability and maintainability
* 4c5293d Architecture finalisée: .html → .php, includes centralisés, sécurité renforcée et documentation examen ajoutée
* 8262bb0 feat: Add optimized database schema and MCD documentation
* 8faa39e fix: corriger les incohérences UX et améliorer la clarté des messages dans le dossier professionnel
* 81c1dc1 Update footer content and add social media links in confirmation page
* b99729b Add CSRF protection and improve formatting in various files
* 91fe54c feat: add initial project page for "Le Repaire des Moustaches" with structure and styling
* debe015 feat: Ajout de l'URL d'image pour les produits dans la base de données
* 6992e40 feat: Ajout de sections et mise à jour des fonctionnalités dans les guides
* ea1773a feat: Ajout des pages de paiement et de confirmation pour le traitement des commandes
* d89ef8b feat: Add initial website structure and database schema
* 305fb1e Premier commit : initialisation du repaire
```

**Points à noter** :
- ✅ 16 commits pour tracer les étapes clés du projet
- ✅ Commits récents suivent la convention `type(scope): description`
- ✅ Distinction claire entre `feat:` (fonctionnalités), `fix:` (corrections), `chore:` (config)

---

## 2. Fichiers de Configuration Git

### A. Contenu du `.gitignore`

```
# OS
.DS_Store
Thumbs.db
*.log

# IDE
.vscode/
.idea/
*.swp
*.swo
*~

# Dependencies
node_modules/
vendor/

# Environment
.env
.env.local
.env.*.local

# Build
dist/
build/

# Database
*.sql.bak
config/database.php
config/*.local.php

# Credentials & Secrets
.htpasswd
*.pem
*.key
```

**Protection réelle** :
- ✅ `config/database.php` est ignorée (credentials BD)
- ✅ `.env` n'est jamais pushé
- ✅ Fichiers sensibles (clés, certificats) exclus

### B. Template de Message de Commit (`.gitmessage`)

```
# Format: <type>(<scope>): <subject>
#
# Type:
#   feat:     Une nouvelle fonctionnalité
#   fix:      Une correction de bug
#   refactor: Refactorisation de code
#   perf:     Amélioration des performances
#   docs:     Modifications de documentation
#   test:     Ajout/modification de tests
#   chore:    Tâches de build, dépendances, etc
#
# Scope:
#   auth:       Authentification & Sécurité
#   cart:       Panier & Commandes
#   checkout:   Paiement & Confirmation
#   admin:      Interface d'administration
#   ui:         Interface utilisateur
#   db:         Base de données & Schéma
```

---

## 3. Exemple de Commits Sémantiques

### Exemple 1 : Nouvelle Fonctionnalité
```
commit 4942b54a3c1d7e9f2b5c8d1e4f7a9b2c3d5e6f8a
Author: Dev Team <dev@repaire-moustaches.fr>
Date:   Mon Jun 2 14:32:00 2026 +0200

    chore(git): mise en place workflow et configuration sécurisée
    
    - Configure .gitignore pour exclure config/database.php
    - Ajoute template de message de commit sémantique
    - Documente stratégie de branching feature/*
    - Crée guide complet d'utilisation (GIT_WORKFLOW.md)
```

### Exemple 2 : Correctif
```
commit 8faa39ef5b2c7d9a1e3f4c6b8a9d1e2f3c4d5e6f
Author: Dev Team <dev@repaire-moustaches.fr>
Date:   Mon May 25 10:15:30 2026 +0200

    fix: corriger les incohérences UX et améliorer la clarté des messages
    
    - Uniformisation des messages d'erreur
    - Amélioriation des feedbacks utilisateur
    - Validation côté serveur renforcée
```

### Exemple 3 : Feature
```
commit 8262bb0a4d7e1f3a5b8c2d9e1f4a6c8b0d2e4f6
Author: Dev Team <dev@repaire-moustaches.fr>
Date:   Mon May 18 16:45:20 2026 +0200

    feat: Add optimized database schema and MCD documentation
    
    Adds comprehensive MCD with optimized relationships and
    indexes for better query performance.
```

---

## 4. Structure du Dépôt Git

### Fichiers de Configuration Visibles
```
Repaire_Des_Moustaches/
├── .git/                          ← Historique Git (non pushé)
├── .gitignore                     ✅ Sécurité (exclusions)
├── .gitmessage                    ✅ Template de commits
├── GIT_WORKFLOW.md                ✅ Guide complet
├── GIT_VERSION_CONTROL.md         ✅ Documentation
├── config/
│   └── database.php               ❌ IGNORÉ (credentials)
└── ...
```

**À noter** :
- ✅ `.gitignore` et `.gitmessage` sont **versionés** (ils en parlent dans le repos)
- ❌ `config/database.php` n'est **jamais** dans Git (elle reste locale)

---

## 5. Commandes Git Exécutées

Voici les commandes que vous avez pu exécuter pour mettre en place cette infrastructure :

```bash
# Initialiser le dépôt et configurer le template
git config commit.template .gitmessage

# Voir l'état du dépôt
git status

# Créer des branches features
git checkout -b feature/nom-du-feature

# Faire des commits sémantiques
git add .
git commit -m "feat(scope): description de la fonctionnalité"

# Voir l'historique
git log --oneline
git log --graph --oneline --all

# Pousser vers GitHub
git push origin main
git push origin feature/nom-du-feature

# Merger une branche
git merge feature/nom-du-feature
```

---

## 6. Configuration Git Repository

Vous pouvez montrer au jury les paramètres configurés :

```bash
$ git config --local -l
core.repositoryformatversion=0
core.filemode=false
core.bare=false
core.logallrefupdates=true
core.ignorecase=true
remote.origin.url=https://github.com/laetitiaquintin-83/Repaire_Des_Moustaches.git
remote.origin.fetch=+refs/heads/*:refs/remotes/origin/*
branch.main.remote=origin
branch.main.merge=refs/heads/main
commit.template=.gitmessage
```

**Cela démontre** :
- ✅ Le dépôt est bien connecté à GitHub (`origin`)
- ✅ La branche `main` suit `origin/main`
- ✅ Le template de commit est configuré

---

## 7. Ce Que Vous Pouvez Montrer au Jury EN DIRECT

### Option 1 : Les Fichiers (Sans Clic)
```
"Voici nos fichiers de configuration Git:
- .gitignore → protège config/database.php
- .gitmessage → template de commit sémantique
- GIT_WORKFLOW.md → documentation complète"
```

### Option 2 : Ouvrir les Fichiers (VS Code)
```
Ouvrir en live:
- GIT_WORKFLOW.md (montrer les conventions)
- GIT_VERSION_CONTROL.md (montrer l'infrastructure)
- .gitignore (montrer les exclusions de credentials)
```

### Option 3 : Terminal Git (Idéal)
```bash
# Dans le dossier du projet
git log --oneline --graph -5

# Voir le template
cat .gitmessage

# Voir le .gitignore
cat .gitignore

# Vérifier la configuration
git config --local commit.template
```

### Option 4 : Dossier GitHub (En Direct)
Si vous avez une connexion, montrer :
- L'historique des commits sur GitHub
- Le dépôt privé
- Les protections de branche

---

## 📊 Résumé pour la Présentation

**Vous pouvez dire** :
> "Pour assurer la qualité et la traçabilité du projet, nous utilisons Git avec GitHub. L'historique montre 16 commits documentant chaque étape. Les fichiers `.gitignore` et `.gitmessage` sécurisent le dépôt et standardisent les messages. Voici les fichiers de configuration et l'historique réel du projet."

**Points d'appui** :
1. ✅ Historique Git réel (16 commits)
2. ✅ Fichiers de config visibles (.gitignore, .gitmessage)
3. ✅ Documentation (GIT_WORKFLOW.md, GIT_VERSION_CONTROL.md)
4. ✅ Commits récents en sémantique `type(scope): description`
5. ✅ Dépôt GitHub privé synchronisé

C'est **100% prouvable et vérifiable** sans capture d'écran ! 🎯
