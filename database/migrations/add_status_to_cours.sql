ALTER TABLE cours
ADD COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' AFTER contenu;
