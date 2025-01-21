<?php

class Etudiant extends User {
    public function __construct($db, $id = null, $role = null, $name = null, $email = null, $password = null) {
        parent::__construct($db, $id, $role, $name, $email, $password);
    }

    public function consulterCours() {
        // Code pour consulter les cours de l'étudiant
        
    }

    public function inscrireCours() {
        // Code pour inscrire aux cours de l'étudiant
    }

    public function getMesCours() {
        // Code pour obtenir les cours inscrits par l'étudiant
    }
    
}