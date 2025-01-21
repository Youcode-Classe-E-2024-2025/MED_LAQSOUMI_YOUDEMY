<?php

require_once __DIR__ . '/models/Model.php';

try {
    $db = Model::getConnection();
    
    // Check if created_at column exists
    $checkCreatedAt = $db->query("SHOW COLUMNS FROM inscriptions LIKE 'created_at'");
    if ($checkCreatedAt->rowCount() == 0) {
        $db->exec("ALTER TABLE inscriptions ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        echo "Successfully added 'created_at' column to inscriptions table.\n";
    }

    // Check if completed column exists
    $checkCompleted = $db->query("SHOW COLUMNS FROM inscriptions LIKE 'completed'");
    if ($checkCompleted->rowCount() == 0) {
        $db->exec("ALTER TABLE inscriptions ADD COLUMN completed TINYINT(1) DEFAULT 0");
        echo "Successfully added 'completed' column to inscriptions table.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
