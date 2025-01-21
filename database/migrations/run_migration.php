<?php
require_once __DIR__ . '/../../models/Model.php';

try {
    $db = Model::getConnection();
    
    // Add active and validated columns
    $sql = "ALTER TABLE utilisateurs 
            ADD COLUMN IF NOT EXISTS active TINYINT(1) NOT NULL DEFAULT 1,
            ADD COLUMN IF NOT EXISTS validated TINYINT(1) NOT NULL DEFAULT 1";
    
    $db->exec($sql);
    echo "Migration completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Error running migration: " . $e->getMessage() . "\n";
}
