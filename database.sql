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

-- Créer la table tags
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Créer la table cours_tags
CREATE TABLE cours_tags (
    cours_id INT,
    tag_id INT,
    PRIMARY KEY (cours_id, tag_id),
    FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Créer la table inscriptions
CREATE TABLE inscriptions (
    etudiant_id INT,
    cours_id INT,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (etudiant_id, cours_id),
    FOREIGN KEY (etudiant_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insérer 30 données dans la table catégories
INSERT INTO categories (nom) VALUES
('Programmation'), ('Design'), ('Marketing'), ('Langues'), ('Photographie'),
('Développement Web'), ('Data Science'), ('Business'), ('E-commerce'), ('Cloud Computing'),
('AI et Machine Learning'), ('Sécurité Informatique'), ('Mathématiques'), ('Physique'), ('Chimie'),
('Électronique'), ('Art'), ('Musique'), ('Danse'), ('Cuisine'),
('Jardinage'), ('Écriture'), ('Mode'), ('Fitness'), ('Santé'),
('Psychologie'), ('Histoire'), ('Voyage'), ('Finance'), ('Éducation');

-- Insérer 30 données dans la table utilisateurs
INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES
('Ahmed', 'ahmed@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant'),
('Fatima', 'fatima@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant'),
('Hassan', 'hassan@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'administrateur'),
('Rania', 'rania@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant'),
('Karim', 'karim@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant'),
('Youssef', 'youssef@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant'),
('Amina', 'amina@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant'),
('Nour', 'nour@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'administrateur'),
('Soukaina', 'soukaina@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant'),
('Samir', 'samir@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant'),
('Omar', 'omar@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant'),
('Khadija', 'khadija@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant'),
('Mohamed', 'mohamed@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant'),
('Salma', 'salma@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant'),
('Hamza', 'hamza@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant'),
('Sara', 'sara@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant'),
('Zakaria', 'zakaria@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant'),
('Manal', 'manal@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant'),
('Yassine', 'yassine@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant'),
('Imane', 'imane@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant'),
('Anas', 'anas@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant'),
('Aya', 'aya@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant'),
('Simo', 'simo@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant'),
('Nawal', 'nawal@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant'),
('Taha', 'taha@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant'),
('Amal', 'amal@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant'),
('Laila', 'laila@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant'),
('Fouad', 'fouad@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant'),
('Chakib', 'chakib@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant'),
('Hind', 'hind@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant');


-- Insérer les données avec des liens d’images fictifs
INSERT INTO cours (titre, description, contenu, image, categorie_id, enseignant_id) VALUES
('Cours de HTML', 'Introduction à HTML', 'Contenu sur HTML', 'https://via.placeholder.com/300/FF5733/FFFFFF?text=HTML', 1, 2),
('CSS pour Débutants', 'Les bases de CSS', 'Contenu CSS', 'https://via.placeholder.com/300/33C3FF/FFFFFF?text=CSS', 1, 5),
('JavaScript Avancé', 'JS pour experts', 'Contenu JS', 'https://via.placeholder.com/300/FFE933/000000?text=JavaScript', 1, 15),
('Apprendre Photoshop', 'Techniques Photoshop', 'Contenu Photoshop', 'https://via.placeholder.com/300/9C27B0/FFFFFF?text=Photoshop', 2, 8),
('Marketing Réseaux Sociaux', 'SM marketing', 'Contenu SM', 'https://via.placeholder.com/300/4CAF50/FFFFFF?text=Marketing', 3, 13),
('Introduction à Python', 'Python pour débutants', 'Contenu Python', 'https://via.placeholder.com/300/FFC107/000000?text=Python', 1, 7),
('SQL et Bases de Données', 'Maîtrise SQL', 'Contenu SQL', 'https://via.placeholder.com/300/607D8B/FFFFFF?text=SQL', 7, 10),
('Développement Vue.js', 'Vue.js avancé', 'Contenu Vue.js', 'https://via.placeholder.com/300/42A5F5/FFFFFF?text=Vue.js', 6, 14),
('Introduction au PHP', 'Les bases du PHP', 'Contenu PHP', 'https://via.placeholder.com/300/673AB7/FFFFFF?text=PHP', 6, 12),
('Laravel et Backend', 'Laravel complet', 'Contenu Laravel', 'https://via.placeholder.com/300/FF9800/FFFFFF?text=Laravel', 6, 11),
('Photographie Numérique', 'Capturer de belles photos', 'Contenu Photo', 'https://via.placeholder.com/300/E91E63/FFFFFF?text=Photo', 5, 4),
('Finance Personnelle', 'Gérez vos finances', 'Contenu Finance', 'https://via.placeholder.com/300/009688/FFFFFF?text=Finance', 29, 9),
('Leadership et Management', 'Devenez un leader', 'Contenu Leadership', 'https://via.placeholder.com/300/3F51B5/FFFFFF?text=Leadership', 8, 6),
('Créativité en Design', 'Boostez votre créativité', 'Contenu Créativité', 'https://via.placeholder.com/300/FF5722/FFFFFF?text=Design', 2, 3),
('Fitness pour Tous', 'Améliorez votre santé', 'Contenu Fitness', 'https://via.placeholder.com/300/4CAF50/FFFFFF?text=Fitness', 24, 18),
('Musique et Composition', 'Créez vos morceaux', 'Contenu Musique', 'https://via.placeholder.com/300/9C27B0/FFFFFF?text=Musique', 18, 16),
('Art et Expression', 'Art visuel', 'Contenu Art', 'https://via.placeholder.com/300/F44336/FFFFFF?text=Art', 17, 20),
('Découvrez la Chimie', 'Bases de la chimie', 'Contenu Chimie', 'https://via.placeholder.com/300/00BCD4/FFFFFF?text=Chimie', 15, 21),
('Mathématiques Avancées', 'Concepts avancés', 'Contenu Math', 'https://via.placeholder.com/300/8BC34A/FFFFFF?text=Math', 13, 22),
('Apprendre les Langues', 'Les bases de l’anglais', 'Contenu Langues', 'https://via.placeholder.com/300/FFEB3B/000000?text=Langues', 4, 19),
('Découvrez l’Histoire', 'Les grandes époques', 'Contenu Histoire', 'https://via.placeholder.com/300/9E9E9E/FFFFFF?text=Histoire', 27, 8),
('Voyages autour du Monde', 'Explorer de nouveaux horizons', 'Contenu Voyage', 'https://via.placeholder.com/300/795548/FFFFFF?text=Voyages', 28, 17),
('Psychologie Positive', 'Améliorez votre bien-être', 'Contenu Psychologie', 'https://via.placeholder.com/300/673AB7/FFFFFF?text=Psychologie', 26, 23),
('Jardinage Facile', 'Prenez soin de votre jardin', 'Contenu Jardinage', 'https://via.placeholder.com/300/4CAF50/FFFFFF?text=Jardinage', 20, 25),
('Fitness Avancé', 'Techniques pour experts', 'Contenu Fitness avancé', 'https://via.placeholder.com/300/FF9800/FFFFFF?text=Fitness', 24, 5),
('Mode et Tendance', 'Créez votre style', 'Contenu Mode', 'https://via.placeholder.com/300/E91E63/FFFFFF?text=Mode', 23, 2),
('Cuisine du Monde', 'Recettes internationales', 'Contenu Cuisine', 'https://via.placeholder.com/300/FF5722/FFFFFF?text=Cuisine', 19, 3),
('Danse Moderne', 'Apprenez à danser', 'Contenu Danse', 'https://via.placeholder.com/300/009688/FFFFFF?text=Danse', 19, 24),
('Sécurité Informatique', 'Protégez vos données', 'Contenu Sécurité', 'https://via.placeholder.com/300/607D8B/FFFFFF?text=Sécurité', 12, 15),
('Sport et Activité', 'Pratiquez vos sports', 'Contenu Sport', 'https://via.placeholder.com/300/795548/FFFFFF?text=Sport', 21, 7),
('Éducation Numérique', 'Apprenez à écrire', 'Contenu Éducation', 'https://via.placeholder.com/300/00BCD4/FFFFFF?text=Éducation', 17, 20);


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
INSERT INTO inscriptions (etudiant_id, cours_id) VALUES
(1, 1), (2, 2), (3, 3), (4, 4), (5, 5),
(6, 6), (7, 7), (8, 8), (9, 9), (10, 10),
(11, 11), (12, 12), (13, 13), (14, 14), (15, 15),
(16, 16), (17, 17), (18, 18), (19, 19), (20, 20),
(21, 21), (22, 22), (23, 23), (24, 24), (25, 25),
(26, 26), (27, 27), (28, 28), (29, 29), (30, 30);