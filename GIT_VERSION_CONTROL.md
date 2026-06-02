# 📦 Version Control & Git Strategy

## État Actuel ✅

Le projet **"Le Repaire des Moustaches"** utilise **Git** pour la gestion de version, avec synchronisation sur un dépôt **GitHub privé**.

### Infrastructure de Base
- ✅ Dépôt Git initialisé et connecté à GitHub
- ✅ Branche `main` stable avec code de production
- ✅ Fichier `.gitignore` configuré pour exclure les fichiers sensibles
- ✅ Template de message de commit sémantique en place

### Sécurité
- ✅ `config/database.php` protégé du versioning
- ✅ Variables d'environnement (.env) non pushées sur GitHub
- ✅ Dépendances (`vendor/`) exclues

---

## Améliorations Implémentées 🚀

### 1. Commits Sémantiques
À partir de **maintenant**, tous les commits suivent la convention :
```
type(scope): description

feat:     Nouvelle fonctionnalité
fix:      Correction de bug
refactor: Refactorisation
perf:     Performance
docs:     Documentation
test:     Tests
chore:    Build, dépendances
```

→ Voir [GIT_WORKFLOW.md](GIT_WORKFLOW.md) pour les détails

### 2. Stratégie de Branchement
- `main` : Code stable uniquement
- `feature/*` : Développement de fonctionnalités isolées
- `bugfix/*` : Corrections de bugs
- `hotfix/*` : Corrections critiques en production

### 3. .gitignore Sécurisé
Exclusion obligatoire de :
- `config/database.php` (credentials base de données)
- `.env` (variables d'environnement)
- `vendor/` (dépendances)
- Fichiers IDE et OS

---

## Historique Git 📜

L'historique précédent contient des commits généralistes. **À partir de maintenant**, tous les nouveaux commits utilisent le format sémantique pour :
- 📖 Meilleure lisibilité de l'évolution
- 🔍 Faciliter l'identification des changements
- 📋 Documenter automatiquement les releases

### Commits Récents
```bash
$ git log --oneline -5
ca8389a (HEAD -> main) Refactor code structure
4c5293d Architecture finalisée
8262bb0 feat: Add optimized database schema
8faa39e fix: corriger les incohérences UX
```

---

## Utilisation Quotidienne 🔄

```bash
# Avant de commencer
git pull origin main

# Créer une branche pour la fonctionnalité
git checkout -b feature/nom-du-feature

# Développer avec commits atomiques
git add .
git commit  # Les templates de message s'affichent

# Pousser vers GitHub
git push origin feature/nom-du-feature

# Après review & approbation
git checkout main
git merge feature/nom-du-feature
git push origin main
```

→ Guide complet dans [GIT_WORKFLOW.md](GIT_WORKFLOW.md)

---

## Documentation Complète 📚

- **[GIT_WORKFLOW.md](GIT_WORKFLOW.md)** : Workflow détaillé, exemples et bonnes pratiques
- **[.gitmessage](.gitmessage)** : Template de message de commit (auto-chargé)
- **[.gitignore](.gitignore)** : Fichiers exclus du versioning

---

## Vérification de Sécurité ✔️

**Avant chaque push, vérifier** :
```bash
# Fichiers qui vont être poussés
git diff --cached

# Fichiers sensibles oubliés ?
git ls-files --others --ignored --exclude-standard
```

---

**Dernière mise à jour** : Juin 2026
**Responsable** : Équipe Développement
