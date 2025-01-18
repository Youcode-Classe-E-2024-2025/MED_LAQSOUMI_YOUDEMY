<?php
class Tag {
    private $id;
    private $nom;

    public function __construct($id, $nom) {
        $this->id = $id;
        $this->nom = $nom;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
}

