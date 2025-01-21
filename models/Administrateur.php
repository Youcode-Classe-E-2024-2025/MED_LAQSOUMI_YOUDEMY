<?php

class Administrateur extends User {
    public function __construct($db, $id = null, $role = null, $name = null, $email = null, $password = null) {
        parent::__construct($db, $id, $role, $name, $email, $password);
    }

    public function validerCompteEnseignant() {
        // Code pour valider le compte de l'enseignant
        
        
    }

    public function gererUtilisateurs() {
        // Code pour inscrire aux cours de l'étudiant
    }

    public function gererContenus() {
        // Code pour obtenir les cours inscrits par l'étudiant
    }

    public function insererTag() {
        // Code pour obtenir les cours inscrits par l'étudiant
    }

    public function consulterStatistiques() {
        // Code pour obtenir les cours inscrits par l'étudiant
    }
    
}