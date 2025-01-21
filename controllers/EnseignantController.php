<?php
require_once __DIR__ . '/../models/inscription.php';

class EnseignantController
{
    private $db;
    private $etudiant_id;
    private $cours_id;
    private $date_inscription;
    private $inscription;

    public function __construct($db)
    {
        $this->db = $db;
        $this->etudiant_id = null;
        $this->cours_id = null;
        $this->date_inscription = null;
    }

    public function inscrireCours($user_id, $cours_id) {
        $this->inscription->inscrireCours($user_id, $cours_id);
        header("Location: index.php?action=myCourses");
        exit;
    }
}
