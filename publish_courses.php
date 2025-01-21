<?php

require_once __DIR__ . '/models/Model.php';

try {
    $db = Model::getConnection();
    
    // Update all existing courses to be published
    $db->exec("UPDATE cours SET published = 1");
    echo "Successfully published all existing courses.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
