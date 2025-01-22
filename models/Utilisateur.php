<?php
require_once __DIR__ . '/../config/database.php';

class Utilisateur {
    protected $id;
    protected $nom;
    protected $email;
    protected $motDePasse;
    protected $role;
    protected $db;

    public function __construct($db = null) {
        if ($db instanceof DatabaseConnection) {
            $this->db = $db->getConnection();
        } elseif ($db instanceof PDO) {
            $this->db = $db;
        } else {
            $this->db = DatabaseConnection::getInstance()->getConnection();
        }
    }

    public function connecter($email, $motDePasse) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($motDePasse, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['nom'];
            return $user;
        }
        return false;
    }

    public function deconnecter() {
        session_destroy();
    }

    public function getRole() {
        return $this->role;
    }

    public function sEnregistrer($nom, $email, $motDePasse, $role) {
        $hashedPassword = password_hash($motDePasse, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $email, $hashedPassword, $role]);
        return $this->db->lastInsertId();
    }

    public function calculerStatistiquesUtilisateur($utilisateur) {
        $stats = [];
        if ($this->role === 'etudiant') {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM inscriptions WHERE etudiant_id = ?");
            $stmt->execute([$utilisateur]);
            $stats['total_cours'] = $stmt->fetchColumn();
        } elseif ($this->role === 'enseignant') {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM cours WHERE enseignant_id = ?");
            $stmt->execute([$utilisateur]);
            $stats['total_cours_crees'] = $stmt->fetchColumn();

            $stmt = $this->db->prepare("SELECT COUNT(DISTINCT i.etudiant_id) FROM inscriptions i JOIN cours c ON i.cours_id = c.id WHERE c.enseignant_id = ?");
            $stmt->execute([$utilisateur]);
            $stats['total_etudiants'] = $stmt->fetchColumn();
        }
        return $stats;
    }

    public function calculerStatistiquesGlobales() {
        $stats = [];
        $stats['total_utilisateurs'] = $this->db->query("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();
        $stats['total_cours'] = $this->db->query("SELECT COUNT(*) FROM cours")->fetchColumn();
        $stats['total_inscriptions'] = $this->db->query("SELECT COUNT(*) FROM inscriptions")->fetchColumn();
        return $stats;
    }
}
