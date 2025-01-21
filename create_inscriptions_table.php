<?php

require_once __DIR__ . '/models/Model.php';

try {
    $db = Model::getConnection();
    
    // Check if inscriptions table exists
    $tableExists = $db->query("SHOW TABLES LIKE 'inscriptions'")->rowCount() > 0;
    
    if (!$tableExists) {
        // Create inscriptions table
        $sql = "CREATE TABLE inscriptions (
            id INT PRIMARY KEY AUTO_INCREMENT,
            cours_id INT NOT NULL,
            etudiant_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            completed TINYINT(1) DEFAULT 0,
            FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE,
            FOREIGN KEY (etudiant_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
            UNIQUE KEY unique_enrollment (cours_id, etudiant_id)
        )";
        $db->exec($sql);
        echo "Successfully created inscriptions table.\n";
    } else {
        // Add columns if they don't exist
        $columns = $db->query("SHOW COLUMNS FROM inscriptions")->fetchAll(PDO::FETCH_COLUMN);
        
        if (!in_array('created_at', $columns)) {
            $db->exec("ALTER TABLE inscriptions ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
            echo "Added created_at column.\n";
        }
        
        if (!in_array('completed', $columns)) {
            $db->exec("ALTER TABLE inscriptions ADD COLUMN completed TINYINT(1) DEFAULT 0");
            echo "Added completed column.\n";
        }
    }
    
    echo "Database structure is now up to date.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
