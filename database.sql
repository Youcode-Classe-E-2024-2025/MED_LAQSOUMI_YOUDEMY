CREATE DATABASE youdemy;
USE youdemy;

-- Créer la table catégories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Créer la table utilisateurs
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('etudiant', 'enseignant', 'administrateur') NOT NULL,
    status ENUM('pending', 'active', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Créer la table cours
CREATE TABLE cours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    contenu TEXT,
    image VARCHAR(255) NOT NULL,
    categorie_id INT,
    enseignant_id INT,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (enseignant_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Créer la table inscriptions
CREATE TABLE inscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT,
    cours_id INT,
    status ENUM('active', 'completed', 'dropped') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (etudiant_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (etudiant_id, cours_id)
);

-- Créer la table tags
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Créer la table d'association cours_tags
CREATE TABLE cours_tags (
    cours_id INT,
    tag_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (cours_id, tag_id),
    FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Insérer 30 données dans la table catégories
INSERT INTO categories (nom) VALUES
('Programmation'), ('Design'), ('Marketing'), ('Langues'), ('Photographie'),
('Développement Web'), ('Data Science'), ('Business'), ('E-commerce'), ('Cloud Computing'),
('AI et Machine Learning'), ('Sécurité Informatique'), ('Mathématiques'), ('Physique'), ('Chimie'),
('Électronique'), ('Art'), ('Musique'), ('Danse'), ('Cuisine'),
('Jardinage'), ('Écriture'), ('Mode'), ('Fitness'), ('Santé'),
('Psychologie'), ('Histoire'), ('Voyage'), ('Finance'), ('Éducation');

-- Insérer 30 données dans la table utilisateurs avec status
INSERT INTO utilisateurs (nom, email, mot_de_passe, role, status) VALUES
('Ahmed', 'ahmed@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', 'active'),
('Fatima', 'fatima@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', 'pending'),
('Hassan', 'hassan@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'administrateur', 'active'),
('Rania', 'rania@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', 'active'),
('Karim', 'karim@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', 'active'),
('Youssef', 'youssef@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', 'active'),
('Amina', 'amina@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', 'pending'),
('Nour', 'nour@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'administrateur', 'active'),
('Soukaina', 'soukaina@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', 'active'),
('Samir', 'samir@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', 'pending'),
('Omar', 'omar@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', 'active'),
('Khadija', 'khadija@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', 'active'),
('Mohamed', 'mohamed@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', 'active'),
('Salma', 'salma@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', 'active'),
('Hamza', 'hamza@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', 'active'),
('Sara', 'sara@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', 'pending'),
('Zakaria', 'zakaria@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', 'active'),
('Manal', 'manal@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', 'active'),
('Yassine', 'yassine@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', 'active'),
('Imane', 'imane@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', 'pending'),
('Anas', 'anas@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', 'active'),
('Aya', 'aya@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', 'active'),
('Simo', 'simo@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', 'active'),
('Nawal', 'nawal@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', 'active'),
('Taha', 'taha@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', 'active'),
('Amal', 'amal@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', 'active'),
('Laila', 'laila@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', 'active'),
('Fouad', 'fouad@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', 'active'),
('Chakib', 'chakib@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', 'active'),
('Hind', 'hind@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', 'active');

-- Insérer les données avec des liens d’images fictifs
INSERT INTO cours (titre, description, contenu, image, categorie_id, enseignant_id) VALUES
('Cours de HTML', 'q', 'Contenu sur HTML', 'https://placehold.co/300', 1, 2),
('CSS pour Débutants', 'Les bases de CSS', 'Contenu CSS', 'https://placehold.co/300', 1, 5),
('JavaScript Avancé', 'JS pour experts', 'Contenu JS', 'https://placehold.co/300', 1, 15),
('Apprendre Photoshop', 'Techniques Photoshop', 'Contenu Photoshop', 'https://placehold.co/300', 2, 8),
('Marketing Réseaux Sociaux', 'SM marketing', 'Contenu SM', 'https://placehold.co/300', 3, 13),
('Introduction à Python', 'Python pour débutants', 'Contenu Python', 'https://placehold.co/300', 1, 7),
('SQL et Bases de Données', 'Maîtrise SQL', 'Contenu SQL', 'https://placehold.co/300', 7, 10),
('Développement Vue.js', 'Vue.js avancé', 'Contenu Vue.js', 'https://placehold.co/300', 6, 14),
('Introduction au PHP', 'Les bases du PHP', 'Contenu PHP', 'https://placehold.co/300', 6, 12),
('Laravel et Backend', 'Laravel complet', 'Contenu Laravel', 'https://placehold.co/300', 6, 11),
('Photographie Numérique', 'Capturer de belles photos', 'Contenu Photo', 'https://placehold.co/300', 5, 4),
('Finance Personnelle', 'Gérez vos finances', 'Contenu Finance', 'https://placehold.co/300', 29, 9),
('Leadership et Management', 'Devenez un leader', 'Contenu Leadership', 'https://placehold.co/300', 8, 6),
('Créativité en Design', 'Boostez votre créativité', 'Contenu Créativité', 'https://placehold.co/300', 2, 3),
('Fitness pour Tous', 'Améliorez votre santé', 'Contenu Fitness', 'https://placehold.co/300', 24, 18),
('Musique et Composition', 'Créez vos morceaux', 'Contenu Musique', 'https://placehold.co/300', 18, 16),
('Art et Expression', 'Art visuel', 'Contenu Art', 'https://placehold.co/300', 17, 20),
('Découvrez la Chimie', 'Bases de la chimie', 'Contenu Chimie', 'https://placehold.co/300', 15, 21),
('Mathématiques Avancées', 'Concepts avancés', 'Contenu Math', 'https://placehold.co/300', 13, 22),
('Apprendre les Langues', 'Les bases de l’anglais', 'Contenu Langues', 'https://placehold.co/300', 4, 19),
('Découvrez l’Histoire', 'Les grandes époques', 'Contenu Histoire', 'https://placehold.co/300', 27, 8),
('Voyages autour du Monde', 'Explorer de nouveaux horizons', 'Contenu Voyage', 'https://placehold.co/300', 28, 17),
('Psychologie Positive', 'Améliorez votre bien-être', 'Contenu Psychologie', 'https://placehold.co/300', 26, 23),
('Jardinage Facile', 'Prenez soin de votre jardin', 'Contenu Jardinage', 'https://placehold.co/300', 20, 25),
('Fitness Avancé', 'Techniques pour experts', 'Contenu Fitness avancé', 'https://placehold.co/300', 24, 5),
('Mode et Tendance', 'Créez votre style', 'Contenu Mode', 'https://placehold.co/300', 23, 2),
('Cuisine du Monde', 'Recettes internationales', 'Contenu Cuisine', 'https://placehold.co/300', 19, 3),
('Danse Moderne', 'Apprenez à danser', 'Contenu Danse', 'https://placehold.co/300', 19, 24),
('Sécurité Informatique', 'Protégez vos données', 'Contenu Sécurité', 'https://placehold.co/300', 12, 15),
('Sport et Activité', 'Pratiquez vos sports', 'Contenu Sport', 'https://placehold.co/300', 21, 7),
('Éducation Numérique', 'Apprenez à écrire', 'Contenu Éducation', 'https://placehold.co/300', 17, 20);

-- Insérer 30 données dans la table tags
INSERT INTO tags (nom) VALUES
('HTML'), ('CSS'), ('JavaScript'), ('Photoshop'), ('Social Media'),
('SEO'), ('Graphisme'), ('UI/UX'), ('Web Design'), ('Marketing'),
('AI'), ('Python'), ('SQL'), ('Data Analysis'), ('Vue.js'),
('React'), ('Laravel'), ('PHP'), ('Java'), ('C++'),
('Finance'), ('Leadership'), ('Productivité'), ('Créativité'), ('Cuisine'),
('Santé'), ('Voyage'), ('Sport'), ('Écriture'), ('Musique');

-- Insérer 30 données dans la table cours_tags
INSERT INTO cours_tags (cours_id, tag_id) VALUES
(1, 1), (1, 2), (2, 2), (2, 3), (3, 3),
(4, 4), (5, 5), (6, 12), (7, 13), (8, 15),
(9, 18), (10, 17), (11, 4), (12, 21), (13, 22),
(14, 23), (15, 26), (16, 29), (17, 7), (18, 25),
(19, 27), (20, 10), (21, 6), (22, 28), (23, 20),
(24, 19), (25, 16), (26, 14), (27, 9), (28, 8);

-- Insérer 30 données dans la table inscriptions
INSERT INTO inscriptions (etudiant_id, cours_id, status) VALUES
(1, 1, 'active'), (2, 2, 'active'), (3, 3, 'active'), (4, 4, 'active'), (5, 5, 'active'),
(6, 6, 'active'), (7, 7, 'active'), (8, 8, 'active'), (9, 9, 'active'), (10, 10, 'active'),
(11, 11, 'active'), (12, 12, 'active'), (13, 13, 'active'), (14, 14, 'active'), (15, 15, 'active'),
(16, 16, 'active'), (17, 17, 'active'), (18, 18, 'active'), (19, 19, 'active'), (20, 20, 'active'),
(21, 21, 'active'), (22, 22, 'active'), (23, 23, 'active'), (24, 24, 'active'), (25, 25, 'active'),
(26, 26, 'active'), (27, 27, 'active'), (28, 28, 'active'), (29, 29, 'active'), (30, 30, 'active');

-- Add status column to utilisateurs table
ALTER TABLE utilisateurs ADD COLUMN status ENUM('pending', 'active', 'suspended') DEFAULT 'active';

-- Set default status for existing users
UPDATE utilisateurs SET status = 'active' WHERE status IS NULL;

UPDATE utilisateurs SET status = 'pending' WHERE role = 'enseignant' AND id IN (2, 7, 10, 16);

ALTER TABLE cours
ADD COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' AFTER contenu;
