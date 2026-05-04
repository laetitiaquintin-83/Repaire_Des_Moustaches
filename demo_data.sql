-- =====================================================
-- Donnees de demo : repaire_des_moustaches
-- A executer apres schema.sql
-- =====================================================

USE repaire_des_moustaches;

START TRANSACTION;

-- -----------------------------------------------------
-- 1) Categories (idempotent)
-- -----------------------------------------------------
INSERT INTO categories_produits (nom)
VALUES ('diner')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

INSERT INTO categories_produits (nom)
VALUES ('goodies')
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
INSERT INTO ateliers (titre, description, date_heure, capacite_max, admin_id)
SELECT 'Creation de jouets pour chats',
       'Atelier recup: fabriquer des jouets avec tissus et carton.',
       DATE_ADD(NOW(), INTERVAL 7 DAY),
       12,
       @admin_id
WHERE NOT EXISTS (
  SELECT 1 FROM ateliers WHERE titre = 'Creation de jouets pour chats'
);

INSERT INTO ateliers (titre, description, date_heure, capacite_max, admin_id)
SELECT 'Repair cafe solidaire',
       'Reparer petit electromenager avec des benevoles.',
       DATE_ADD(NOW(), INTERVAL 14 DAY),
       10,
       @admin_id
WHERE NOT EXISTS (
  SELECT 1 FROM ateliers WHERE titre = 'Repair cafe solidaire'
);

SET @atelier_1 = (SELECT id FROM ateliers WHERE titre = 'Creation de jouets pour chats' LIMIT 1);
SET @atelier_2 = (SELECT id FROM ateliers WHERE titre = 'Repair cafe solidaire' LIMIT 1);

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
SET @cat_goodies = (SELECT id FROM categories_produits WHERE nom = 'goodies' LIMIT 1);

INSERT INTO produits (nom, description, prix, categorie_id)
SELECT 'Milkshake Fraise', 'Milkshake maison creme et fraises', 6.50, @cat_diner
WHERE NOT EXISTS (SELECT 1 FROM produits WHERE nom = 'Milkshake Fraise');

INSERT INTO produits (nom, description, prix, categorie_id)
SELECT 'Burger Veggie Moustache', 'Burger vegetarien sauce maison', 12.90, @cat_diner
WHERE NOT EXISTS (SELECT 1 FROM produits WHERE nom = 'Burger Veggie Moustache');

INSERT INTO produits (nom, description, prix, categorie_id)
SELECT 'Mug Le Repaire', 'Mug ceramique edition solidaire', 9.90, @cat_goodies
WHERE NOT EXISTS (SELECT 1 FROM produits WHERE nom = 'Mug Le Repaire');

INSERT INTO produits (nom, description, prix, categorie_id)
SELECT 'Tote Bag Moustaches', 'Sac coton bio imprime', 11.00, @cat_goodies
WHERE NOT EXISTS (SELECT 1 FROM produits WHERE nom = 'Tote Bag Moustaches');

-- -----------------------------------------------------
-- 11) Commande de demo
-- -----------------------------------------------------
INSERT INTO commandes (utilisateur_id, montant_total, statut)
SELECT @u1, 19.40, 'payee'
WHERE NOT EXISTS (
  SELECT 1 FROM commandes WHERE utilisateur_id = @u1 AND montant_total = 19.40
);

SET @commande_demo = (
  SELECT id
  FROM commandes
  WHERE utilisateur_id = @u1 AND montant_total = 19.40
  ORDER BY id DESC
  LIMIT 1
);

SET @prod_milkshake = (SELECT id FROM produits WHERE nom = 'Milkshake Fraise' LIMIT 1);
SET @prod_mug = (SELECT id FROM produits WHERE nom = 'Mug Le Repaire' LIMIT 1);

INSERT INTO lignes_commandes (commande_id, produit_id, quantite, prix_unitaire)
SELECT @commande_demo, @prod_milkshake, 1, 6.50
WHERE NOT EXISTS (
  SELECT 1 FROM lignes_commandes
  WHERE commande_id = @commande_demo AND produit_id = @prod_milkshake
);

INSERT INTO lignes_commandes (commande_id, produit_id, quantite, prix_unitaire)
SELECT @commande_demo, @prod_mug, 1, 9.90
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
