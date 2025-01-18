<?php
require_once 'Utilisateur.php';
require_once 'Cours.php';
require_once 'Tag.php';
require_once 'Categorie.php';

class Enseignant extends Utilisateur {
    public function __construct($id, $nom, $email, $motDePasse) {
        parent::__construct($id, $nom, $email, $motDePasse, 'enseignant');
    }

    public function ajouterCours(string $titre, string $description, string $contenu, array $tags, Categorie $categorie): Cours {
        // Implementation to add a new course
        return new Cours(0, $titre, $description, $contenu, $categorie, $this);
    }

    public function modifierCours(Cours $cours): void {
        // Implementation to modify a course
    }

    public function supprimerCours(Cours $cours): void {
        // Implementation to delete a course
    }

    public function consulterInscriptions(Cours $cours): array {
        // Implementation to get course enrollments
        return [];
    }

    public function consulterStatistiques(): array {
        // Implementation to get teacher statistics
        return [];
    }
}

