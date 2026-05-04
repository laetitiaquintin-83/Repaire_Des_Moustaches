# Repaire des Moustaches - Dossier de projet

## Introduction

Ce document resume de facon claire et progressive le travail realise pour le projet "Le Repaire des Moustaches". Il peut servir de support ecrit pour l'examen et de fil conducteur pour l'oral.

## 1. Contexte du projet

Le projet "Le Repaire des Moustaches" est un tiers-lieu solidaire qui combine plusieurs usages :

- un diner retro a l'ambiance vintage
- un espace de rencontres avec des chats accueillis par un refuge partenaire
- des ateliers citoyens a inscription libre
- une boutique et une offre de restauration pour financer le lieu

L'objectif est de proposer un site web clair, attractif et coherent avec les valeurs du projet : partage, solidarite, adoption responsable et participation a la vie du lieu.

### 1.1 Objectifs du site

Le site doit permettre de :

- presenter le concept du lieu des l'accueil
- montrer les chats accueillis par le refuge partenaire
- expliquer le principe des ateliers a participation libre
- mettre en avant l'adhesion annuelle a 5 euros
- valoriser la restauration et les goodies qui financent le projet
- afficher des retours d'experience et des nouvelles des adoptants

## 2. Regles metier principales

Les regles suivantes ont ete definies avant la partie technique :

- l'adhesion annuelle est obligatoire pour participer aux ateliers
- le montant de l'adhesion est fixe a 5 euros par an
- les ateliers fonctionnent sur un principe de participation libre
- une personne peut choisir d'etre participant ou animateur d'atelier
- les adoptions ne sont pas gerees directement par le site : le site provoque le coup de foudre, puis le refuge partenaire gere la suite

### 2.1 Parcours utilisateur

Le projet distingue plusieurs parcours :

- un visiteur qui decouvre le lieu et ses services
- un participant qui reserve un atelier
- un animateur qui propose ou encadre un atelier
- un adoptant qui laisse un retour ou des nouvelles
- un administrateur qui gere les contenus et les donnees

## 3. Modelisation des donnees

Le MCD a permis de structurer la base autour des grandes entites du projet :

- utilisateurs
- admin_users
- refuges_partenaires
- pensionnaires
- adhesions
- ateliers
- reservations_ateliers
- belles_histoires
- categories_produits
- produits
- commandes
- lignes_commandes

Cette modelisation couvre les besoins fonctionnels du projet : gestion des chats, gestion des ateliers, gestion du contenu publie, et gestion des ventes du diner et des goodies.

### 3.1 Lecture rapide des tables

- utilisateurs : comptes du public
- admin_users : comptes administrateur et moderation
- refuges_partenaires : refuge qui gere l'adoption officielle
- pensionnaires : chats presentes sur le site
- adhesions : suivi de l'adhesion annuelle a 5 euros
- ateliers : agenda des ateliers
- reservations_ateliers : inscriptions, role participant ou animateur
- belles_histoires : mur des nouvelles et retours
- categories_produits : types de produits
- produits : carte du diner et boutique
- commandes : achat global
- lignes_commandes : detail de chaque commande

## 4. Creation de la base de donnees

### Etape 1 : creation de la base

La base s'appelle `repaire_des_moustaches`.

Commande utilisee :

```sql
CREATE DATABASE IF NOT EXISTS repaire_des_moustaches;
```

### Etape 2 : creation des tables

Le script SQL principal contient :

- les tables avec leurs cles primaires
- les relations entre tables avec des cles etrangeres
- les contraintes de validation
- les index utiles

### 4.1 Validation technique

Les verifications faites dans phpMyAdmin ont montre que :

- la base existe bien
- les tables sont presentes
- la relation entre les tables est correctement creee
- la table `adhesions` respecte la regle des 5 euros

### Etape 3 : regle de l'adhesion

La table `adhesions` impose :

- une duree annuelle
- un montant fixe de 5.00 EUR
- une verification sur les dates de debut et de fin

Exemple de controle attendu :

```sql
DESCRIBE repaire_des_moustaches.adhesions;
```

## 5. Donnees de demonstration

Un second fichier SQL a ete prepare pour tester la base avec des donnees concretes :

- un compte administrateur
- trois utilisateurs
- un refuge partenaire
- trois pensionnaires
- deux adhesions
- deux ateliers
- des reservations avec les roles participant et animateur
- deux belles histoires
- quatre produits
- une commande de demonstration avec ses lignes

Ce jeu de donnees permet de montrer le fonctionnement du projet pendant l'oral.

Il inclut volontairement plusieurs cas d'usage :

- un chat a l'adoption
- un chat en famille d'accueil
- un chat adopte
- une reservation en tant que participant
- une reservation en tant qu'animateur
- une histoire publiee
- une histoire en attente de moderation
- une commande avec plusieurs lignes

## 6. Verification dans phpMyAdmin

Les verifications realisees ont ete les suivantes :

1. creation de la base dans phpMyAdmin
2. execution du script `schema.sql`
3. controle de la presence des tables avec `SHOW TABLES`
4. verification de la structure de la table `adhesions`
5. test de la contrainte sur le montant de l'adhesion

Le resultat attendu est :

- toutes les tables sont bien presentes
- la base `repaire_des_moustaches` est correctement creee
- la regle des 5 euros pour l'adhesion est appliquee

### 6.1 Message d'erreur observe au depart

Pendant les tests, l'erreur `#1046 - No database selected` a ete observee lorsque la requete etait lancee sans base selectionnee. La correction consiste a selectionner la base `repaire_des_moustaches` ou a lancer la requete avec le nom complet de la base.

## 7. Fichiers du projet

- [index.html](index.html)
- [style.css](style.css)
- [schema.sql](schema.sql)
- [demo_data.sql](demo_data.sql)
- [README.md](README.md)

### 7.1 Ordre de lecture conseille

Pour presenter le projet, il est conseille de suivre cet ordre :

1. expliquer le concept du lieu
2. presenter les regles metier
3. montrer le MCD
4. expliquer le script SQL
5. montrer les donnees de demo
6. faire les tests dans phpMyAdmin
7. conclure sur les prochaines etapes

## 8. Synthese pour l'oral

Phrase courte possible a dire au jury :

"J'ai construit un tiers-lieu solidaire et retro autour d'un diner a chats, avec une base de donnees relationnelle qui gere les pensionnaires, les ateliers, les adhesions a 5 euros, les ventes de produits et la moderation des contenus."

Version plus naturelle a dire a l'oral :

"Mon projet s'appelle Le Repaire des Moustaches. C'est un lieu hybride entre diner retro, espace solidaire et refuge partenaire. J'ai d'abord defini les besoins, puis j'ai modelise les donnees avec un MCD avant de creer la base MySQL et de la tester avec des donnees de demonstration."

## 9. Suite du projet

Les prochaines etapes possibles sont :

- integrer le front-end complet des pages
- connecter le projet en PHP avec PDO
- ajouter le back-office administrateur
- mettre en place les formulaires et la validation cote serveur
- preparer les captures d'ecran pour le dossier final

## 10. Conclusion

Le projet est deja solide sur la partie base de donnees et structuration fonctionnelle. La suite logique consiste maintenant a relier cette base au front-end, puis a mettre en place les formulaires, la connexion utilisateur et l'espace administrateur.
