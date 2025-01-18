<?php
require_once 'Categorie.php';
require_once 'Enseignant.php';
require_once 'Tag.php';

class Cours {
    private $id;
    private $titre;
    private $description;
    private $contenu;
    private $categorie;
    private $enseignant;
    private $tags = [];

    public function __construct($id, $titre, $description, $contenu, Categorie $categorie, Enseignant $enseignant) {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->contenu = $contenu;
        $this->categorie = $categorie;
        $this->enseignant = $enseignant;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getTitre(): string { return $this->titre; }
    public function getDescription(): string { return $this->description; }
    public function getContenu(): string { return $this->contenu; }
    public function getCategorie(): Categorie { return $this->categorie; }
    public function getEnseignant(): Enseignant { return $this->enseignant; }
    public function getTags(): array { return $this->tags; }

    // Setters
    public function setTitre(string $titre): void { $this->titre = $titre; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setContenu(string $contenu): void { $this->contenu = $contenu; }
    public function setCategorie(Categorie $categorie): void { $this->categorie = $categorie; }
    public function setEnseignant(Enseignant $enseignant): void { $this->enseignant = $enseignant; }
    public function setTags(array $tags): void { $this->tags = $tags; }

    // Other methods
    public function getAllCours(): array { return []; }
    public function getUnCours(int $id): Cours { return new Cours(0, '', '', '', new Categorie(0, ''), new Enseignant(0, '', '', '')); }
    public function ajouterCours(string $titre, string $description, string $contenu, array $tags, Categorie $categorie, Enseignant $enseignant): void { }
    public function modifierCours(Cours $cours): void { }
    public function supprimerCours(Cours $cours): void { }
    public function consulterInscriptions(Cours $cours): array { return []; }
    public function consulterStatistiques(): array { return []; }
}

