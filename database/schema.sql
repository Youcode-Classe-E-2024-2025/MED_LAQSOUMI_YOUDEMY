-- Create database if not exists
CREATE DATABASE IF NOT EXISTS youdemy;
USE youdemy;

-- Users table
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'enseignant', 'etudiant') NOT NULL,
    validated BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tags table
CREATE TABLE IF NOT EXISTS tags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Courses table
CREATE TABLE IF NOT EXISTS cours (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    contenu TEXT,
    image VARCHAR(255),
    categorie_id INT NOT NULL,
    enseignant_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id),
    FOREIGN KEY (enseignant_id) REFERENCES utilisateurs(id)
);

-- Course tags relationship
CREATE TABLE IF NOT EXISTS cours_tags (
    cours_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (cours_id, tag_id),
    FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Course sections
CREATE TABLE IF NOT EXISTS sections (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cours_id INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    ordre INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE
);

-- Course lessons
CREATE TABLE IF NOT EXISTS lecons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    section_id INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT,
    duree INT NOT NULL, -- Duration in minutes
    ordre INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE
);

-- Course enrollments
CREATE TABLE IF NOT EXISTS inscriptions (
    etudiant_id INT NOT NULL,
    cours_id INT NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    progress INT DEFAULT 0,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_completion TIMESTAMP NULL,
    PRIMARY KEY (etudiant_id, cours_id),
    FOREIGN KEY (etudiant_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE
);

-- Insert data into utilisateurs table
INSERT INTO utilisateurs (nom, email, mot_de_passe, role, validated) VALUES
('Hassan', 'hassan@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'admin', TRUE),
('Khadija', 'khadija@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', TRUE),
('Soufiane', 'soufiane@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', FALSE),
('Oumaima', 'oumaima@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', TRUE),
('Reda', 'reda@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', FALSE),
('Fatima', 'fatima@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', TRUE),
('Mehdi', 'mehdi@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', TRUE),
('Salma', 'salma@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', FALSE),
('Youssef', 'youssef@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', TRUE),
('Samira', 'samira@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', FALSE),
('Abdellah', 'abdellah@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', TRUE),
('Zineb', 'zineb@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', TRUE),
('Amine', 'amine@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', FALSE),
('Meryem', 'meryem@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', TRUE),
('Karim', 'karim@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', TRUE),
('Nawal', 'nawal@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', FALSE),
('Yassine', 'yassine@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', TRUE),
('Houda', 'houda@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', FALSE),
('Hamid', 'hamid@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'etudiant', TRUE),
('Rachida', 'rachida@example.com', '$2y$10$jb2COpaZSdFhs4PXUcRSHOX7LvQrROHZ.Qe5zhNuAIXWiFOj4injG', 'enseignant', TRUE);

-- Insert data into categories table
INSERT INTO categories (nom, description) VALUES
('Technologie', 'Cours sur les technologies modernes'),
('Design', 'Apprendre le design graphique'),
('Marketing', 'Cours de marketing digital'),
('Langues', 'Apprendre de nouvelles langues'),
('Développement personnel', 'Améliorer vos compétences personnelles'),
('Business', 'Cours pour entrepreneurs'),
('Photographie', 'Apprendre la photographie'),
('Musique', 'Cours sur la musique et les instruments'),
('Cuisine', 'Apprendre les arts culinaires'),
('Fitness', 'Cours de fitness et bien-être'),
('Programmation', 'Apprendre à coder'),
('Data Science', 'Cours de science des données'),
('IA', 'Introduction à l''intelligence artificielle'),
('Éducation', 'Cours pour enseignants'),
('Mode', 'Cours sur la mode et le stylisme'),
('Santé', 'Cours sur la santé et le bien-être'),
('Finance', 'Cours de finance et comptabilité'),
('Histoire', 'Cours sur l''histoire'),
('Psychologie', 'Introduction à la psychologie'),
('Architecture', 'Cours d''architecture et design urbain');

-- Insert data into tags table
INSERT INTO tags (nom) VALUES
('HTML'), ('CSS'), ('JavaScript'), ('Python'), ('Java'),
('SEO'), ('Photoshop'), ('UX/UI'), ('Marketing'), ('Leadership'),
('Finance'), ('SQL'), ('Machine Learning'), ('AI'), ('Fitness'),
('Nutrition'), ('Cooking'), ('Photography'), ('Fashion'), ('Music');

-- Insert data into cours table
INSERT INTO cours (titre, description, contenu, image, categorie_id, enseignant_id) VALUES
('Cours HTML Débutant', 'Introduction au HTML pour les débutants', 'Contenu HTML...', 'https://placehold.co/300', 1, 2),
('CSS Flexbox et Grid', 'Apprendre le layout CSS moderne', 'Contenu CSS...', 'https://placehold.co/300', 1, 2),
('JavaScript pour les débutants', 'Introduction à JavaScript', 'Contenu JS...', 'https://placehold.co/300', 1, 2),
('Python Avancé', 'Techniques avancées en Python', 'Contenu Python...', 'https://placehold.co/300', 11, 6),
('Apprendre le Marketing', 'Introduction au marketing digital', 'Contenu Marketing...', 'https://placehold.co/300', 3, 6),
('Photographie de Portrait', 'Techniques de portrait en photo', 'Contenu Photo...', 'https://placehold.co/300', 7, 6),
('Cuisine Marocaine', 'Recettes traditionnelles marocaines', 'Contenu Cuisine...', 'https://placehold.co/300', 9, 11),
('Cours de Guitare', 'Apprendre la guitare acoustique', 'Contenu Musique...', 'https://placehold.co/300', 8, 11),
('Introduction à la Finance', 'Les bases de la finance', 'Contenu Finance...', 'https://placehold.co/300', 17, 11),
('Introduction à l''AI', 'Les bases de l''intelligence artificielle', 'Contenu AI...', 'https://placehold.co/300', 13, 2),
('Leadership et Management', 'Développer vos compétences en leadership', 'Contenu Leadership...', 'https://placehold.co/300', 5, 11),
('Fitness et Bien-être', 'Programme complet de fitness', 'Contenu Fitness...', 'https://placehold.co/300', 10, 11),
('Stylisme Moderne', 'Cours sur la mode moderne', 'Contenu Mode...', 'https://placehold.co/300', 15, 11),
('SQL Débutant', 'Apprendre les bases de SQL', 'Contenu SQL...', 'https://placehold.co/300', 1, 2),
('Science des Données', 'Introduction à la Data Science', 'Contenu Data...', 'https://placehold.co/300', 12, 11),
('Histoire Marocaine', 'Cours sur l''histoire du Maroc', 'Contenu Histoire...', 'https://placehold.co/300', 18, 11),
('Introduction à UX/UI', 'Les bases du design UX/UI', 'Contenu UX/UI...', 'https://placehold.co/300', 2, 2),
('Nutrition et Santé', 'Cours sur la nutrition', 'Contenu Nutrition...', 'https://placehold.co/300', 16, 11),
('Java Avancé', 'Techniques avancées en Java', 'Contenu Java...', 'https://placehold.co/300', 11, 2),
('Psychologie Moderne', 'Introduction à la psychologie moderne', 'Contenu Psycho...', 'https://placehold.co/300', 19, 2);