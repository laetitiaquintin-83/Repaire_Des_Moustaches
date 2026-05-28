-- =====================================================
-- Donnees de demo : repaire_des_moustaches
-- A executer apres schema.sql
-- =====================================================

USE repaire_des_moustaches;
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

START TRANSACTION;

-- -----------------------------------------------------
-- 1) Categories (idempotent)
-- -----------------------------------------------------
INSERT INTO categories_produits (nom)
VALUES ('diner')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

INSERT INTO categories_produits (nom)
VALUES ('diner_retro')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

INSERT INTO categories_produits (nom)
VALUES ('cat_lovers')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

INSERT INTO categories_produits (nom)
VALUES ('solidaire')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

-- -----------------------------------------------------
-- 2) Admin (email unique)
-- -----------------------------------------------------
INSERT INTO admin_users (email, mot_de_passe, role)
VALUES ('admin@repaire.local', '$2y$10$demo_hash_a_remplacer', 'admin')
ON DUPLICATE KEY UPDATE role = VALUES(role);

SET @admin_id = (
  SELECT id FROM admin_users WHERE email = 'admin@repaire.local' LIMIT 1
);

-- -----------------------------------------------------
-- 3) Utilisateurs (emails uniques)
-- -----------------------------------------------------
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe)
VALUES ('Martin', 'Lea', 'lea.martin@example.com', '$2y$10$demo_hash_1')
ON DUPLICATE KEY UPDATE nom = VALUES(nom), prenom = VALUES(prenom);

INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe)
VALUES ('Bernard', 'Hugo', 'hugo.bernard@example.com', '$2y$10$demo_hash_2')
ON DUPLICATE KEY UPDATE nom = VALUES(nom), prenom = VALUES(prenom);

INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe)
VALUES ('Petit', 'Nina', 'nina.petit@example.com', '$2y$10$demo_hash_3')
ON DUPLICATE KEY UPDATE nom = VALUES(nom), prenom = VALUES(prenom);

SET @u1 = (SELECT id FROM utilisateurs WHERE email = 'lea.martin@example.com' LIMIT 1);
SET @u2 = (SELECT id FROM utilisateurs WHERE email = 'hugo.bernard@example.com' LIMIT 1);
SET @u3 = (SELECT id FROM utilisateurs WHERE email = 'nina.petit@example.com' LIMIT 1);

-- -----------------------------------------------------
-- 4) Refuge partenaire
-- -----------------------------------------------------
INSERT INTO refuges_partenaires (nom, contact_email, telephone)
SELECT 'Refuge Les Pattes Libres', 'contact@pattes-libres.fr', '0494000001'
WHERE NOT EXISTS (
  SELECT 1 FROM refuges_partenaires WHERE nom = 'Refuge Les Pattes Libres'
);

SET @refuge_id = (
  SELECT id FROM refuges_partenaires WHERE nom = 'Refuge Les Pattes Libres' LIMIT 1
);

-- -----------------------------------------------------
-- 5) Pensionnaires
-- -----------------------------------------------------
INSERT INTO pensionnaires (nom, age, description, photo_url, statut, refuge_id, admin_id)
SELECT 'Capitaine Crochet', 3,
       'Male joueur et tres sociable. Adore les siestes en hauteur.',
       'images/chats/capitaine-crochet.jpg',
       'a_l_adoption', @refuge_id, @admin_id
WHERE NOT EXISTS (
  SELECT 1 FROM pensionnaires WHERE nom = 'Capitaine Crochet'
);

INSERT INTO pensionnaires (nom, age, description, photo_url, statut, refuge_id, admin_id)
SELECT 'Moka', 2,
       'Femelle douce, un peu timide au debut puis tres caline.',
       'images/chats/moka.jpg',
       'famille_accueil', @refuge_id, @admin_id
WHERE NOT EXISTS (
  SELECT 1 FROM pensionnaires WHERE nom = 'Moka'
);

INSERT INTO pensionnaires (nom, age, description, photo_url, statut, refuge_id, admin_id)
SELECT 'Pixel', 5,
       'Calme et gourmand, parfait pour une famille avec enfants.',
       'images/chats/pixel.jpg',
       'adopte', @refuge_id, @admin_id
WHERE NOT EXISTS (
  SELECT 1 FROM pensionnaires WHERE nom = 'Pixel'
);

-- -----------------------------------------------------
-- 6) Adhesions (regle metier 5.00 EUR)
-- -----------------------------------------------------
INSERT INTO adhesions (utilisateur_id, date_debut, date_fin, montant, statut)
SELECT @u1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 5.00, 'valide'
WHERE NOT EXISTS (
  SELECT 1 FROM adhesions
  WHERE utilisateur_id = @u1 AND statut = 'valide' AND date_fin >= CURDATE()
);

INSERT INTO adhesions (utilisateur_id, date_debut, date_fin, montant, statut)
SELECT @u2, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 5.00, 'valide'
WHERE NOT EXISTS (
  SELECT 1 FROM adhesions
  WHERE utilisateur_id = @u2 AND statut = 'valide' AND date_fin >= CURDATE()
);

-- -----------------------------------------------------
-- 7) Ateliers
-- -----------------------------------------------------
INSERT INTO ateliers (titre, description, image, date_heure, capacite_max, admin_id)
SELECT 'Création de Jouets Écolos',
       'Récupérez, transformez, créez ! Fabriquez des jouets stimulants pour vos futurs compagnons ou pour nos pensionnaires, à partir de matériaux recyclés. Pendant que vos mains s\'activent, Velours, Biscuit et Moonlight vous observent avec leur curiosité légendaire. Créativité garantie, fou rire inclus.',
       'images/atelier5.jpg',
       DATE_ADD(NOW(), INTERVAL 7 DAY),
       12,
       @admin_id
WHERE NOT EXISTS (
  SELECT 1 FROM ateliers WHERE titre = 'Création de Jouets Écolos'
);

INSERT INTO ateliers (titre, description, image, date_heure, capacite_max, admin_id)
SELECT 'Bien-être & Ronronthérapie',
       'Votre semaine vous a épuisé ? Les ronrons du Repaire sont votre remède. Soin, manucure, détente sous les caresses félines de nos moustachus... Laissez le stress s\'envoler dans les vapeurs apaisantes de ce rituel de self-care. Moonlight est expert en thérapie par câlins.',
       'images/atelier2.jpg',
       DATE_ADD(NOW(), INTERVAL 10 DAY),
       8,
       @admin_id
WHERE NOT EXISTS (
  SELECT 1 FROM ateliers WHERE titre = 'Bien-être & Ronronthérapie'
);

INSERT INTO ateliers (titre, description, image, date_heure, capacite_max, admin_id)
SELECT 'Café Administratif',
       'L\'administratif vous terrifie ? Pas de panique ! Autour d\'un bon café rétro et sous les encouragements silencieux de Biscuit, nos bénévoles démystifient les papiers. Pas de jugement, juste de la solidarité. Le formulaire n\'a jamais été aussi inoffensif.',
       'images/atelier4.jpg',
       DATE_ADD(NOW(), INTERVAL 5 DAY),
       15,
       @admin_id
WHERE NOT EXISTS (
  SELECT 1 FROM ateliers WHERE titre = 'Café Administratif'
);

INSERT INTO ateliers (titre, description, image, date_heure, capacite_max, admin_id)
SELECT 'Les Pâtisseries du Diner',
       'Plongez dans l\'univers gourmand des années 50 ! Nos animatrices vous enseignent les secrets des cupcakes mythiques, des brownies décadents et des cookies qui collent au palais. Vous repartirez avec vos créations (si la tentation de les dévorer sur place ne vous gagne pas).',
       'images/atelier1.jpg',
       DATE_ADD(NOW(), INTERVAL 12 DAY),
       10,
       @admin_id
WHERE NOT EXISTS (
  SELECT 1 FROM ateliers WHERE titre = 'Les Pâtisseries du Diner'
);

SET @atelier_1 = (SELECT id FROM ateliers WHERE titre = 'Création de Jouets Écolos' LIMIT 1);
SET @atelier_2 = (SELECT id FROM ateliers WHERE titre = 'Bien-être & Ronronthérapie' LIMIT 1);

-- -----------------------------------------------------
-- 8) Reservations ateliers (participant + animateur)
-- -----------------------------------------------------
INSERT INTO reservations_ateliers (utilisateur_id, atelier_id, role, montant_libre)
SELECT @u1, @atelier_1, 'participant', 8.00
WHERE NOT EXISTS (
  SELECT 1 FROM reservations_ateliers WHERE utilisateur_id = @u1 AND atelier_id = @atelier_1
);

INSERT INTO reservations_ateliers (utilisateur_id, atelier_id, role, montant_libre)
SELECT @u2, @atelier_1, 'animateur', 0.00
WHERE NOT EXISTS (
  SELECT 1 FROM reservations_ateliers WHERE utilisateur_id = @u2 AND atelier_id = @atelier_1
);

INSERT INTO reservations_ateliers (utilisateur_id, atelier_id, role, montant_libre)
SELECT @u3, @atelier_2, 'participant', 5.00
WHERE NOT EXISTS (
  SELECT 1 FROM reservations_ateliers WHERE utilisateur_id = @u3 AND atelier_id = @atelier_2
);

-- -----------------------------------------------------
-- 9) Belles histoires
-- -----------------------------------------------------
INSERT INTO belles_histoires (utilisateur_id, titre, contenu, statut, admin_id)
SELECT @u1,
       'Des nouvelles de Pixel',
       'Pixel est arrive a la maison depuis 2 mois. Il joue et ronronne tous les soirs.',
       'publiee',
       @admin_id
WHERE NOT EXISTS (
  SELECT 1 FROM belles_histoires WHERE titre = 'Des nouvelles de Pixel'
);

INSERT INTO belles_histoires (utilisateur_id, titre, contenu, statut, admin_id)
SELECT @u3,
       'Premier atelier au top',
       'Super ambiance au Repaire, merci pour l accueil et les conseils.',
       'en_attente',
       NULL
WHERE NOT EXISTS (
  SELECT 1 FROM belles_histoires WHERE titre = 'Premier atelier au top'
);

-- -----------------------------------------------------
-- 10) Produits (diner + goodies)
-- -----------------------------------------------------
SET @cat_diner = (SELECT id FROM categories_produits WHERE nom = 'diner' LIMIT 1);
SET @cat_diner_retro = (SELECT id FROM categories_produits WHERE nom = 'diner_retro' LIMIT 1);
SET @cat_cat_lovers = (SELECT id FROM categories_produits WHERE nom = 'cat_lovers' LIMIT 1);
SET @cat_solidaire = (SELECT id FROM categories_produits WHERE nom = 'solidaire' LIMIT 1);

-- Produits Diner
INSERT INTO produits (nom, description, prix, categorie_id, image_url)
SELECT 'Milkshake Fraise', 'Milkshake maison creme et fraises', 6.50, @cat_diner, 'images/produits/milkshake-fraise.jpg'
WHERE NOT EXISTS (SELECT 1 FROM produits WHERE nom = 'Milkshake Fraise');

INSERT INTO produits (nom, description, prix, categorie_id, image_url)
SELECT 'Burger Veggie Moustache', 'Burger vegetarien sauce maison', 12.90, @cat_diner, 'images/produits/burger-veggie.jpg'
WHERE NOT EXISTS (SELECT 1 FROM produits WHERE nom = 'Burger Veggie Moustache');

-- Goodies Diner Retro
INSERT INTO produits (nom, description, prix, categorie_id, image_url)
SELECT 'Mug Diner', 'Mug en céramique épaisse style 50s américain, logo floqué', 12.99, @cat_diner_retro, 'images/produits/mug-diner.jpg'
WHERE NOT EXISTS (SELECT 1 FROM produits WHERE nom = 'Mug Diner');

INSERT INTO produits (nom, description, prix, categorie_id, image_url)
SELECT 'Tablier Vintage', 'Tablier de cuisine retro pastel avec logo du Repaire', 19.99, @cat_diner_retro, 'images/produits/tablier-vintage.jpg'
WHERE NOT EXISTS (SELECT 1 FROM produits WHERE nom = 'Tablier Vintage');

INSERT INTO produits (nom, description, prix, categorie_id, image_url)
SELECT 'Pins Emailles', 'Set de 3 pins émaillés (milkshake, burger, chat moustache)', 8.99, @cat_diner_retro, 'images/produits/pins-emailles.jpg'
WHERE NOT EXISTS (SELECT 1 FROM produits WHERE nom = 'Pins Emailles');

-- Goodies Cat Lovers
INSERT INTO produits (nom, description, prix, categorie_id, image_url)
SELECT 'Tote Bag Solidaire', 'Sac en toile recyclée avec punchline "Mon cœur appartient à un moustachu"', 13.99, @cat_cat_lovers, 'images/produits/tote-bag.jpg'
WHERE NOT EXISTS (SELECT 1 FROM produits WHERE nom = 'Tote Bag Solidaire');

INSERT INTO produits (nom, description, prix, categorie_id, image_url)
SELECT 'Jouets Catnip Deluxe', 'Set de 3 jouets (frites, donut, hot-dog) remplis d''herbe à chat', 9.99, @cat_cat_lovers, 'images/produits/jouets-catnip.jpg'
WHERE NOT EXISTS (SELECT 1 FROM produits WHERE nom = 'Jouets Catnip Deluxe');

INSERT INTO produits (nom, description, prix, categorie_id, image_url)
SELECT 'Planches de Stickers Retro', 'Pack de 24 autocollants vintage style 50s et chats', 5.99, @cat_cat_lovers, 'images/produits/stickers-retro.jpg'
WHERE NOT EXISTS (SELECT 1 FROM produits WHERE nom = 'Planches de Stickers Retro');

-- Goodies Solidaires
INSERT INTO produits (nom, description, prix, categorie_id, image_url)
SELECT 'Cartes Postales Polaroid', 'Set de 6 cartes photos rétro de nos pensionnaires. 1 carte = 1 repas financé', 9.99, @cat_solidaire, 'images/produits/cartes-postales.jpg'
WHERE NOT EXISTS (SELECT 1 FROM produits WHERE nom = 'Cartes Postales Polaroid');

INSERT INTO produits (nom, description, prix, categorie_id, image_url)
SELECT 'Badge Solidaire', 'Badge 56mm "Soutien officiel du Repaire des Moustaches"', 3.99, @cat_solidaire, 'images/produits/badge-solidaire.jpg'
WHERE NOT EXISTS (SELECT 1 FROM produits WHERE nom = 'Badge Solidaire');

-- -----------------------------------------------------
-- 11) Commande de demo
-- -----------------------------------------------------
INSERT INTO commandes (utilisateur_id, montant_total, statut)
SELECT @u1, 19.89, 'payee'
WHERE NOT EXISTS (
  SELECT 1 FROM commandes WHERE utilisateur_id = @u1 AND montant_total = 19.89
);

SET @commande_demo = (
  SELECT id
  FROM commandes
  WHERE utilisateur_id = @u1 AND montant_total = 19.89
  ORDER BY id DESC
  LIMIT 1
);

SET @prod_milkshake = (SELECT id FROM produits WHERE nom = 'Milkshake Fraise' LIMIT 1);
SET @prod_mug = (SELECT id FROM produits WHERE nom = 'Mug Diner' LIMIT 1);

INSERT INTO lignes_commandes (commande_id, produit_id, quantite, prix_unitaire)
SELECT @commande_demo, @prod_milkshake, 1, 6.50
WHERE NOT EXISTS (
  SELECT 1 FROM lignes_commandes
  WHERE commande_id = @commande_demo AND produit_id = @prod_milkshake
);

INSERT INTO lignes_commandes (commande_id, produit_id, quantite, prix_unitaire)
SELECT @commande_demo, @prod_mug, 1, 12.99
WHERE NOT EXISTS (
  SELECT 1 FROM lignes_commandes
  WHERE commande_id = @commande_demo AND produit_id = @prod_mug
);

COMMIT;

-- -----------------------------------------------------
-- Verification rapide
-- -----------------------------------------------------
SELECT 'utilisateurs' AS table_name, COUNT(*) AS total FROM utilisateurs
UNION ALL
SELECT 'pensionnaires', COUNT(*) FROM pensionnaires
UNION ALL
SELECT 'ateliers', COUNT(*) FROM ateliers
UNION ALL
SELECT 'reservations_ateliers', COUNT(*) FROM reservations_ateliers
UNION ALL
SELECT 'produits', COUNT(*) FROM produits
UNION ALL
SELECT 'commandes', COUNT(*) FROM commandes;
