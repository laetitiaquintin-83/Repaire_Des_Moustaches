# 🌿 Workflow Git - Le Repaire des Moustaches

## Structure de Branchement

### Branche `main` 🔴
- **Accès** : Code stable, testé et prêt pour production
- **Qui peut merger** : Revue obligatoire
- **Protection** : Pas de push direct, fusion via Pull Request uniquement

### Branches `feature/*` 🟢
Chaque fonctionnalité développée sur sa propre branche isolée :

```bash
git checkout -b feature/nom-du-feature
```

**Exemples** :
- `feature/ateliers-solidaires` → nouveau module ateliers
- `feature/auth-securisee` → refonte sécurité login
- `feature/cart-validation` → validation panier en session
- `feature/paiement-ssl` → intégration SSL checkout

### Branches `bugfix/*` 🔧
Corrections de bugs urgents :

```bash
git checkout -b bugfix/xss-contact
```

### Branches `hotfix/*` 🔥
Corrections critiques en production (depuis `main`) :

```bash
git checkout -b hotfix/faille-securite
```

---

## Workflow Développement

### 1️⃣ Créer une branche feature

```bash
# Assurez-vous d'être à jour
git checkout main
git pull origin main

# Créer la branche
git checkout -b feature/ma-fonctionnalite
```

### 2️⃣ Développer & Commiter

Faire des commits **atomiques** et **sémantiques** :

```bash
# Format: <type>(<scope>): <description>
git add .
git commit
# L'éditeur affichera le template avec les conventions
```

**Types de commits** :
- `feat:` Nouvelle fonctionnalité
- `fix:` Correction de bug
- `refactor:` Refactorisation (pas de changement fonctionnel)
- `perf:` Amélioration de performance
- `docs:` Documentation
- `test:` Tests
- `chore:` Build, dépendances

**Exemples valides** :
```
feat(cart): ajoute validation du panier en session
fix(auth): corrige faille XSS sur formulaire contact
refactor(admin): améliore structure dashboard
perf(db): optimise requête produits best-sellers
docs(setup): ajoute guide installation
test(checkout): ajoute tests unitaires paiement
```

### 3️⃣ Pousser vers GitHub

```bash
git push origin feature/ma-fonctionnalite
```

### 4️⃣ Créer une Pull Request (PR)

- Titre clair et atomique
- Description avec contexte du changement
- Référencer les issues : "Closes #42"
- Attendre la revue & les tests

### 5️⃣ Merger & Nettoyer

```bash
# Une fois approuvée
git checkout main
git pull origin main
git merge feature/ma-fonctionnalite
git push origin main

# Supprimer la branche
git branch -d feature/ma-fonctionnalite
git push origin --delete feature/ma-fonctionnalite
```

---

## 🔐 Sécurité Git

### ✅ Ce qui EST commité
- Code source `.php`, `.js`, `.css`
- Configuration `.gitignore` & `.gitmessage`
- Documentation et schémas
- Migrations de base de données (`.sql`)

### ❌ Ce qui N'EST PAS commité
- `config/database.php` → variables d'env locales
- `.env` files
- Dépendances (`vendor/`, `node_modules/`)
- Fichiers de log
- IDE settings (`.vscode/`, `.idea/`)

### Vérifier avant de pousser

```bash
# Voir ce qui sera pushé
git diff --cached

# Vérifier les fichiers sensibles
git ls-files --others --ignored --exclude-standard
```

---

## 📊 Exemple Complet : Ajouter un Module Ateliers

```bash
# 1. Créer branche
git checkout -b feature/ateliers-solidaires

# 2. Ajouter fichiers & tester localement
echo "<?php // Nouveau module ateliers" > ateliers_module.php

# 3. Commit atomique
git add ateliers_module.php
git commit
# Éditer le message :
# feat(ateliers): ajoute module ateliers solidaires
# 
# Permet aux utilisateurs de consulter et s'inscrire
# aux ateliers organisés par l'association.

# 4. Pousser
git push origin feature/ateliers-solidaires

# 5. Après approbation sur GitHub
git checkout main
git pull origin main
git merge feature/ateliers-solidaires
git push origin main
git branch -d feature/ateliers-solidaires
```

---

## 📝 Bonnes Pratiques

✅ **À faire**
- Un commit = une idée/une fonction/une correction
- Messages en français, clairs et descriptifs
- Tester localement avant de pusher
- Garder les branches courtes (< 1 semaine de dev)
- Mettre à jour `main` avant de merger une feature

❌ **À éviter**
- Mélanger plusieurs fonctionnalités dans un commit
- Messages vagues ("update", "fix stuff")
- Pousser directement sur `main` sans PR
- Commits avec des fichiers sensibles oubliés

---

## 🆘 Commandes Utiles

```bash
# Voir l'état
git status

# Voir l'historique des commits
git log --oneline -10
git log --graph --oneline --all

# Annuler un commit (pas encore pushé)
git reset --soft HEAD~1

# Amender le dernier commit
git commit --amend

# Récupérer les changements distants
git fetch origin

# Voir différences avant commit
git diff
```

---

## 🔄 Synchronisation Régulière

À faire **chaque jour** :
```bash
git fetch origin  # Voir les changements distants
git status        # Vérifier l'état
git pull origin main  # Mettre à jour main
```

---

**Dernier commit honnête** : `git log --oneline -1`
