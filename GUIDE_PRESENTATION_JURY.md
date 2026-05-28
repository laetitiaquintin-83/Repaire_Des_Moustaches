# 🎬 GUIDE DE PRÉSENTATION - Examen Jury

**Projet** : Le Repaire des Moustaches  
**Candidat** : [TON NOM]  
**Date d'examen** : 26 mai 2026  
**Durée totale** : ~15-20 minutes (5 min présentation + 10 min démo + 5 min questions)

---

## 🎯 PITCH D'OUVERTURE (2-3 min)

**À dire au jury** (mémorise bien!) :

---

> ### "Le Repaire des Moustaches - Un tiers-lieu solidaire pour les chats et les humains"
>
> Bonjour, je présente mon projet de titre professionnel DWWM.
>
> **Le concept** : C'est un tiers-lieu hybride basé à Toulon qui combine trois usages :
> - Un dîner rétro style années 50 (modèle économique)
> - Un refuge partenaire avec des chats en adoption
> - Des ateliers citoyens à participation libre
>
> **L'objectif du site** : Permettre aux visiteurs de découvrir le lieu, consulter les chats, s'inscrire aux ateliers, et soutenir le projet via la boutique.
>
> **Mon approche technique** :
> 1. **Modélisation** : Créé un MCD avec 12 tables normalisées
> 2. **Frontend** : HTML5/CSS3 responsive + variables CSS
> 3. **Backend** : PHP/PDO sécurisé + MySQL relationnelle
> 4. **Sécurité** : Tokens CSRF, protection XSS, prepared statements
>
> **Points forts du projet** :
> - ✅ Architecture claire : données ≠ présentation
> - ✅ Sécurité multicouche (6 protections OWASP)
> - ✅ Admin panel fonctionnel (CRUD complet)
> - ✅ Flux utilisateur testés (panier, formulaire, modération)
>
> Je vais vous faire une petite démo.

---

## 🎮 SCRIPT DE DÉMO (10 min)

### **Segment 1 : Navigation & Frontend** (2 min)

```
Ouvrir : http://localhost/Repaire_Des_Moustaches/

"Voici la page d'accueil du site. On y trouve :
 - Un hero avec le titre principal (rétro, années 50)
 - Cinq cartes de navigation : Concept, Équipage, Ateliers, Repaire, Douceurs
 - Un design cohérent en crème, menthe et rose corail"

Cliquer sur "Les Ateliers"
"Ici on affiche les 4 ateliers depuis la base de données.
 Chaque atelier a une description, une image, une capacité max.
 C'est du PHP dynamique - pas de HTML statique!"

Cliquer sur "Belles Histoires"
"Voici les histoires de chats adoptés. Elles sont modérées avant publication.
 Les utilisateurs peuvent soumettre des histoires et c'est l'admin qui valide."
```

### **Segment 2 : Formulaire de Réservation** (2 min)

```
Cliquer sur bouton "Réserver" (haut droit, rouge)
→ http://localhost/Repaire_Des_Moustaches/formulaire.php

"Ici les utilisateurs peuvent faire 3 choses :
 1. Réserver pour un atelier
 2. Proposer et animer un atelier
 3. Privatiser pour un événement

Remplir le formulaire :
- Nom : Test User
- Email : test@example.com
- Motif : Réserver ma place
- Message : Je voudrais participer à l'atelier bien-être

Cliquer 'Envoyer'"

Message de confirmation s'affiche ✅
"Voyez, la demande est enregistrée avec un numéro unique.
 Elle est stockée en base de données, table DEMANDES.
 L'admin peut la consulter plus tard."
```

### **Segment 3 : Boutique & Panier** (2 min)

```
Aller en Boutique → Ajouter produit au panier
→ Cliquer "Panier"

"Voilà le panier fonctionnel.
 - On peut modifier les quantités
 - Voir le total
 - Passer la commande"

Cliquer "Passer la commande"
→ http://localhost/Repaire_Des_Moustaches/public/checkout.php

Remplir avec des infos :
- Prénom : Test
- Nom : User
- Email : test@example.com
- Adresse : 123 Rue du Repaire
- Code postal : 83000
- Ville : Toulon

Cliquer "Valider ma commande"

Confirmation ✅
"Une commande est créée en base de données.
 Elle a un ID unique, la date, le montant, un statut.
 L'admin peut gérer ces commandes."
```

### **Segment 4 : Admin Panel** (3 min)

```
Aller en bas du site → cliquer "Admin"
→ http://localhost/Repaire_Des_Moustaches/login.php

Login :
- Email : admin@repaire.local
- Mot de passe : admin123

Cliquer Dashboard
"Voici le dashboard administrateur avec les stats :
 - Nombre d'utilisateurs
 - Nombre de commandes
 - Nombre d'histoires à modérer
 - etc..."

Aller en "Ateliers"
"Ici l'admin peut :
 - Créer un nouvel atelier (titre, description, date, capacité)
 - Modifier un atelier existant
 - Supprimer un atelier

Créer un atelier :
- Titre : Test Atelier
- Description : Atelier de test
- Date/heure : 2026-05-30 14:00
- Capacité : 15
- Cliquer 'Créer'"

Message ✅
"L'atelier est créé. Si on va voir les ateliers publics, le nouvel atelier apparaît."

Aller en "Belles Histoires - À modérer"
"Voici les histoires en attente de modération.
 L'admin peut les publier ou les rejeter.
 C'est un système simple mais efficace pour la qualité."
```

---

## ❓ QUESTIONS PROBABLES DU JURY

### **Q1 : Pourquoi tu as choisi PHP/MySQL et pas Node.js/MongoDB?**

**Réponse** :
> C'était un requis du titre professionnel DWWM. De plus, PHP/MySQL est parfait pour un projet traditionnel web. C'est :
> - Facile à déployer (mutualisé)
> - Idéal pour un CRUD classique
> - Bien supporté sur les serveurs web
>
> Pour un temps réel ou du REST API massif, Node.js aurait été mieux.

---

### **Q2 : Comment tu as sécurisé le site?**

**Réponse** :
> J'ai implémenté 6 mesures de sécurité (voir document SECURITE_AUDIT.md):
> 
> 1. **SQL Injection** → Prepared statements avec PDO
> 2. **XSS** → htmlspecialchars() sur tous les outputs
> 3. **CSRF** → Tokens uniques par session, vérifiés avec hash_equals()
> 4. **Weak passwords** → bcrypt avec password_hash()
> 5. **Unauthorized access** → Vérification session sur admin
> 6. **Invalid data** → Validation côté serveur (types, formats)
>
> C'est conforme aux bonnes pratiques OWASP.

---

### **Q3 : Quelle est la cardinalité entre UTILISATEURS et COMMANDES?**

**Réponse** :
> 1 → N (un-à-plusieurs)
>
> Un utilisateur peut passer plusieurs commandes. La clé étrangère `utilisateur_id` est dans la table COMMANDES.

---

### **Q4 : Comment fonctionne le formulaire de réservation?**

**Réponse** :
> 1. Utilisateur remplit le formulaire (nom, email, motif, message)
> 2. Un token CSRF est envoyé (protection contre attaques falsifiées)
> 3. PHP valide les données (email format, motif enum valide)
> 4. Si OK, INSERT en table DEMANDES avec statut "nouvelle"
> 5. Un message de confirmation s'affiche avec numéro de demande
> 6. L'admin peut consulter les demandes plus tard

---

### **Q5 : Quelle est la différence entre ateliers.html et ateliers.php?**

**Réponse** :
> - **ateliers.html** : Version statique (avant refacto)
> - **ateliers.php** : Version dynamique, récupère les ateliers de la BD
>
> J'ai unifié la navigation pour toujours pointer vers ateliers.php. Ça utilise une boucle foreach pour afficher tous les ateliers.
>
> Le code du jury devra utiliser ONLY les versions PHP.

---

### **Q6 : Comment tu gères les sessions?**

**Réponse** :
> Chaque fichier PHP commence par `session_start()`.
>
> - **Utilisateurs publics** : Session pour le panier (`$_SESSION['cart']`)
> - **Admins** : Session avec `$_SESSION['admin_id']`
> - À chaque page admin, je vérife `if (!isset($_SESSION['admin_id']))` → redirection login
> - À la déconnexion, `session_destroy()`

---

### **Q7 : Qu'est-ce qu'un prepared statement et pourquoi c'est important?**

**Réponse** :
> C'est une requête SQL où les données sont séparées du code.
>
> ```php
> $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = ?');
> $stmt->execute([$email]);  // Email est paramètre, pas interpolé
> ```
>
> **Pourquoi c'est important** :
> - Empêche les injections SQL
> - Un hacker ne peut pas échapper les guillemets pour modifier la requête
> - Standard industrie (OWASP recommande)

---

### **Q8 : Y a-t-il des améliorations futures?**

**Réponse** :
> Oui :
> - AJAX pour ajouter au panier sans recharge
> - Système de paiement réel (Stripe)
> - Email de confirmation automatique
> - Filtrage des ateliers par date/catégorie
> - Dashboard analytics pour l'admin
> - Export commandes en PDF

---

## 📋 CHECKLIST AVANT L'EXAMEN

**Avant de rencontrer le jury** :

- [ ] Vérifier que Laragon/Apache tourne → `http://localhost/` OK
- [ ] Vérifier que phpMyAdmin fonctionne → table DEMANDES existe
- [ ] Tester le formulaire une fois → confirmation s'affiche
- [ ] Tester le panier → création de commande en BD
- [ ] Tester admin login → pas de erreur
- [ ] Vérifier pas d'erreurs PHP dans les logs
- [ ] Charger le document SECURITE_AUDIT.md sur ton ordi
- [ ] Imprimer ce guide (ou l'avoir en PDF)
- [ ] Mémoriser le pitch d'ouverture
- [ ] Vérifier ta présentation est bien structurée

---

## 🎤 DERNIERS CONSEILS

1. **Parle calmement** - Ne te précipite pas
2. **Montre le code** - Ouvre les fichiers PHP, fais voir les prepared statements
3. **Sois honnête** - Si tu ne sais pas, dis-le plutôt que d'inventer
4. **Valorise le contexte** - "C'est un projet métier réaliste pour une association"
5. **Finale avec confiance** - "Je suis fier de ce que j'ai construit"

---

**Bonne chance pour ton examen! 🍀🐱**
