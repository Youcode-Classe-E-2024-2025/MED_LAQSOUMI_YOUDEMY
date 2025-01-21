<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Etudiant.php';
require_once __DIR__ . '/../models/Course.php';
class Inscription {
    private $db;
    private $etudiant_id;
    private $cours_id;
    private $date_inscription;

    public function __construct($db, $etudiant_id, $cours_id, $date_inscription) {
        $this->db = $db;
        $this->etudiant_id = $etudiant_id;
        $this->cours_id = $cours_id;
        $this->date_inscription = $date_inscription;
    }

    
}