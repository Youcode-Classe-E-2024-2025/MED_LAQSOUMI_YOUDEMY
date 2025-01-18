<?php
abstract class Utilisateur {
    protected $id;
    protected $nom;
    protected $email;
    protected $motDePasse;
    protected $role;

    public function __construct($id, $nom, $email, $motDePasse, $role) {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->motDePasse = $motDePasse;
        $this->role = $role;
    }

    public function connecter(): bool {
        // Implementation of login logic
        return true;
    }

    public function deconnecter(): void {
        // Implementation of logout logic
    }

    public function getRole(): string {
        return $this->role;
    }

    // Getters and setters
    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getEmail(): string { return $this->email; }
    public function getMotDePasse(): string { return $this->motDePasse; }
}

