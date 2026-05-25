-- ========================================
-- Base de données : `le_repaire_des_moustaches` (Version Optimisée)
-- ========================================

-- Table UTILISATEURS (fusionnée : clients + admins + bénévoles)
CREATE TABLE utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('client', 'admin', 'benevole') DEFAULT 'client',
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    derniere_connexion DATETIME,
    actif BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table REFUGES_PARTENAIRES
CREATE TABLE refuges_partenaires (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    adresse TEXT NOT NULL,
    contact VARCHAR(100),
    telephone VARCHAR(20),
    email VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table PENSIONNAIRES (chats en adoption)
CREATE TABLE pensionnaires (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    age INT,
    caractere TEXT,
    description TEXT,
    photo VARCHAR(255),
    statut ENUM('a_adopter', 'adopte', 'en_famille', 'decede') DEFAULT 'a_adopter',
    date_arrivee DATE NOT NULL,
    refuge_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (refuge_id) REFERENCES refuges_partenaires(id) ON DELETE CASCADE
);

-- Table ATELIERS
CREATE TABLE ateliers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(100) NOT NULL,
    description TEXT,
    date_atelier DATETIME NOT NULL,
    places_max INT NOT NULL,
    prix_libre_suggere DECIMAL(10,2),
    lieu VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table RESERVATIONS_ATELIERS
CREATE TABLE reservations_ateliers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    atelier_id INT NOT NULL,
    date_reservation DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('confirmee', 'annulee', 'presente', 'absente') DEFAULT 'confirmee',
    montant_paye DECIMAL(10,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (atelier_id) REFERENCES ateliers(id) ON DELETE CASCADE,
    UNIQUE KEY unique_reservation (utilisateur_id, atelier_id)
);

-- Table CATEGORIES_PRODUITS
CREATE TABLE categories_produits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table PRODUITS
CREATE TABLE produits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    categorie_id INT NOT NULL,
    actif BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories_produits(id) ON DELETE RESTRICT
);

-- Table COMMANDES
CREATE TABLE commandes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    statut ENUM('panier', 'validee', 'payee', 'expediee', 'livree', 'annulee') DEFAULT 'panier',
    numero_suivi VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table LIGNES_COMMANDES
CREATE TABLE lignes_commandes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    commande_id INT NOT NULL,
    produit_id INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE RESTRICT
);

-- Table ADHESIONS
CREATE TABLE adhesions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    type_adhesion VARCHAR(50) NOT NULL,
    statut ENUM('active', 'expiree', 'suspendue') DEFAULT 'active',
    date_adhesion DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_expiration DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table BELLES_HISTOIRES
CREATE TABLE belles_histoires (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    pensionnaire_id INT NOT NULL,
    titre VARCHAR(100) NOT NULL,
    contenu TEXT NOT NULL,
    photo VARCHAR(255),
    approuve BOOLEAN DEFAULT FALSE,
    date_publication DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (pensionnaire_id) REFERENCES pensionnaires(id) ON DELETE CASCADE
);

-- Table AUDIT_LOG (optionnel mais recommandé)
CREATE TABLE audit_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT,
    action VARCHAR(50) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    ancien_valeur JSON,
    nouveau_valeur JSON,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL
);

-- ========================================
-- INDEX pour optimisation
-- ========================================
CREATE INDEX idx_utilisateurs_email ON utilisateurs(email);
CREATE INDEX idx_utilisateurs_role ON utilisateurs(role);
CREATE INDEX idx_pensionnaires_refuge ON pensionnaires(refuge_id);
CREATE INDEX idx_pensionnaires_statut ON pensionnaires(statut);
CREATE INDEX idx_ateliers_date ON ateliers(date_atelier);
CREATE INDEX idx_reservations_utilisateur ON reservations_ateliers(utilisateur_id);
CREATE INDEX idx_reservations_atelier ON reservations_ateliers(atelier_id);
CREATE INDEX idx_produits_categorie ON produits(categorie_id);
CREATE INDEX idx_commandes_utilisateur ON commandes(utilisateur_id);
CREATE INDEX idx_commandes_statut ON commandes(statut);
CREATE INDEX idx_lignes_commandes_commande ON lignes_commandes(commande_id);
CREATE INDEX idx_lignes_commandes_produit ON lignes_commandes(produit_id);
CREATE INDEX idx_belles_histoires_utilisateur ON belles_histoires(utilisateur_id);
CREATE INDEX idx_belles_histoires_pensionnaire ON belles_histoires(pensionnaire_id);
