<?php

class Enseignant extends User {
    public function __construct($db, $id = null, $role = null, $name = null, $email = null, $password = null) {
        parent::__construct($db, $id, $role, $name, $email, $password);
    }

    public function ajouterCours() {
        // Code pour consulter les cours de l'étudiant
        
    }

    public function modifierCours() {
        // Code pour inscrire aux cours de l'étudiant
    }

    public function supprimerCours() {
        // Code pour obtenir les cours inscrits par l'étudiant
    }

    public function consulterInscriptions() {
        // Code pour obtenir les cours inscrits par l'étudiant
    }
    
}