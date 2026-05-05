-- =====================================================
-- Base de donnees : repaire_des_moustaches
-- Script compatible MySQL 8+ / MariaDB (Laragon)
-- =====================================================

CREATE DATABASE IF NOT EXISTS repaire_des_moustaches
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE repaire_des_moustaches;

-- Nettoyage (ordre dependant des cles etrangeres)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS lignes_commandes;
DROP TABLE IF EXISTS commandes;
DROP TABLE IF EXISTS produits;
DROP TABLE IF EXISTS categories_produits;
DROP TABLE IF EXISTS belles_histoires;
DROP TABLE IF EXISTS reservations_ateliers;
DROP TABLE IF EXISTS ateliers;
DROP TABLE IF EXISTS adhesions;
DROP TABLE IF EXISTS pensionnaires;
DROP TABLE IF EXISTS refuges_partenaires;
DROP TABLE IF EXISTS admin_users;
DROP TABLE IF EXISTS utilisateurs;
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- UTILISATEURS
-- =====================================================
CREATE TABLE utilisateurs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  mot_de_passe VARCHAR(255) NOT NULL,
  date_inscription DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  mot_de_passe VARCHAR(255) NOT NULL,
  role ENUM('admin', 'moderateur') NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB;

-- =====================================================
-- REFUGES ET PENSIONNAIRES
-- =====================================================
CREATE TABLE refuges_partenaires (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150) NOT NULL,
  contact_email VARCHAR(190) NOT NULL,
  telephone VARCHAR(30)
) ENGINE=InnoDB;

CREATE TABLE pensionnaires (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(120) NOT NULL,
  age INT,
  description TEXT,
  photo_url VARCHAR(255),
  statut ENUM('a_l_adoption', 'famille_accueil', 'adopte') NOT NULL DEFAULT 'a_l_adoption',
  refuge_id INT NOT NULL,
  admin_id INT NOT NULL,
  CONSTRAINT fk_pensionnaires_refuge
    FOREIGN KEY (refuge_id) REFERENCES refuges_partenaires(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_pensionnaires_admin
    FOREIGN KEY (admin_id) REFERENCES admin_users(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- =====================================================
-- ADHESIONS
-- Regle metier : adhesion annuelle fixe a 5 EUR
-- =====================================================
CREATE TABLE adhesions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilisateur_id INT NOT NULL,
  date_debut DATE NOT NULL,
  date_fin DATE NOT NULL,
  montant DECIMAL(6,2) NOT NULL DEFAULT 5.00,
  statut ENUM('valide', 'expiree') NOT NULL DEFAULT 'valide',
  CONSTRAINT fk_adhesions_utilisateur
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT chk_adhesion_dates CHECK (date_fin >= date_debut),
  CONSTRAINT chk_adhesion_montant CHECK (montant = 5.00)
) ENGINE=InnoDB;

-- =====================================================
-- ATELIERS
-- =====================================================
CREATE TABLE ateliers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(150) NOT NULL,
  description TEXT,
  date_heure DATETIME NOT NULL,
  capacite_max INT NOT NULL,
  admin_id INT NOT NULL,
  CONSTRAINT fk_ateliers_admin
    FOREIGN KEY (admin_id) REFERENCES admin_users(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT chk_ateliers_capacite CHECK (capacite_max > 0)
) ENGINE=InnoDB;

CREATE TABLE reservations_ateliers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilisateur_id INT NOT NULL,
  atelier_id INT NOT NULL,
  role ENUM('participant', 'animateur') NOT NULL DEFAULT 'participant',
  montant_libre DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  date_reservation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reservations_utilisateur
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_reservations_atelier
    FOREIGN KEY (atelier_id) REFERENCES ateliers(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT uq_reservation_utilisateur_atelier UNIQUE (utilisateur_id, atelier_id),
  CONSTRAINT chk_montant_libre CHECK (montant_libre >= 0.00)
) ENGINE=InnoDB;

-- =====================================================
-- MUR DES BELLES HISTOIRES
-- =====================================================
CREATE TABLE belles_histoires (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilisateur_id INT NOT NULL,
  titre VARCHAR(150) NOT NULL,
  contenu TEXT NOT NULL,
  statut ENUM('en_attente', 'publiee', 'refusee') NOT NULL DEFAULT 'en_attente',
  admin_id INT NULL,
  date_publication DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_histoires_utilisateur
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_histoires_admin
    FOREIGN KEY (admin_id) REFERENCES admin_users(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================================================
-- PRODUITS ET COMMANDES (diner + goodies)
-- =====================================================
CREATE TABLE categories_produits (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE produits (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150) NOT NULL,
  description TEXT,
  prix DECIMAL(10,2) NOT NULL,
  categorie_id INT NOT NULL,
  image_url VARCHAR(255),
  CONSTRAINT fk_produits_categorie
    FOREIGN KEY (categorie_id) REFERENCES categories_produits(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT chk_produits_prix CHECK (prix >= 0.00)
) ENGINE=InnoDB;

CREATE TABLE commandes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilisateur_id INT NOT NULL,
  date_commande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  montant_total DECIMAL(10,2) NOT NULL,
  statut ENUM('en_attente', 'payee', 'annulee') NOT NULL DEFAULT 'en_attente',
  CONSTRAINT fk_commandes_utilisateur
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT chk_commandes_total CHECK (montant_total >= 0.00)
) ENGINE=InnoDB;

CREATE TABLE lignes_commandes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  commande_id INT NOT NULL,
  produit_id INT NOT NULL,
  quantite INT NOT NULL,
  prix_unitaire DECIMAL(10,2) NOT NULL,
  CONSTRAINT fk_lignes_commande
    FOREIGN KEY (commande_id) REFERENCES commandes(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_lignes_produit
    FOREIGN KEY (produit_id) REFERENCES produits(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT chk_lignes_quantite CHECK (quantite > 0),
  CONSTRAINT chk_lignes_prix CHECK (prix_unitaire >= 0.00)
) ENGINE=InnoDB;

-- =====================================================
-- Index utiles
-- =====================================================
CREATE INDEX idx_pensionnaires_statut ON pensionnaires(statut);
CREATE INDEX idx_ateliers_date_heure ON ateliers(date_heure);
CREATE INDEX idx_histoires_statut ON belles_histoires(statut);
CREATE INDEX idx_commandes_date ON commandes(date_commande);

-- =====================================================
-- Donnees minimales de depart
-- =====================================================
INSERT INTO categories_produits (nom) VALUES
('diner'),
('goodies');
