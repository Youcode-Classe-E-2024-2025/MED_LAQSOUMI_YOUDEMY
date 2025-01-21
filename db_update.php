<?php

require_once __DIR__ . '/models/Model.php';

try {
    $db = Model::getConnection();
    
    // Check if the column exists
    $checkColumn = $db->query("SHOW COLUMNS FROM cours LIKE 'published'");
    if ($checkColumn->rowCount() == 0) {
        // Add the published column if it doesn't exist
        $db->exec("ALTER TABLE cours ADD COLUMN published TINYINT(1) DEFAULT 0 AFTER enseignant_id");
        echo "Successfully added 'published' column to cours table.";
    } else {
        echo "'published' column already exists in cours table.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
