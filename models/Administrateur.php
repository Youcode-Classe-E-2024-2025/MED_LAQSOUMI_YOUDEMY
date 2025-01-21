<?php

require_once __DIR__ . '/Utilisateur.php';
require_once __DIR__ . '/Cours.php';
require_once __DIR__ . '/Tag.php';
require_once __DIR__ . '/Categorie.php';
require_once __DIR__ . '/Statistiques.php';

class Administrateur extends Utilisateur {
    public function __construct($db, $id = null, $nom = null, $email = null, $motDePasse = null, $role = 'administrateur') {
        parent::__construct($db, $id, $nom, $email, $motDePasse, $role);
    }

    public function validerCompteEnseignant($enseignant) {
        try {
            if (!($enseignant instanceof Enseignant)) {
                throw new Exception('Invalid teacher account');
            }

            $query = "UPDATE utilisateurs SET status = 'active' WHERE id = ? AND role = 'enseignant'";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$enseignant->getId()]);
        } catch (PDOException $e) {
            error_log('Error validating teacher account: ' . $e->getMessage());
            throw new Exception('Failed to validate teacher account');
        }
    }

    public function gererUtilisateurs($utilisateur) {
        try {
            // Can update user status, role, or delete user
            $query = "UPDATE utilisateurs 
                     SET status = ?, role = ? 
                     WHERE id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                $utilisateur->getStatus(),
                $utilisateur->getRole(),
                $utilisateur->getId()
            ]);
        } catch (PDOException $e) {
            error_log('Error managing user: ' . $e->getMessage());
            throw new Exception('Failed to manage user');
        }
    }

    public function gererContenus($cours) {
        try {
            // Can update course status or delete course
            $query = "UPDATE cours 
                     SET status = ?, titre = ?, description = ?, contenu = ? 
                     WHERE id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                $cours->getStatus(),
                $cours->getTitre(),
                $cours->getDescription(),
                $cours->getContenu(),
                $cours->getId()
            ]);
        } catch (PDOException $e) {
            error_log('Error managing content: ' . $e->getMessage());
            throw new Exception('Failed to manage content');
        }
    }

    public function insererTags($tags) {
        try {
            $this->db->beginTransaction();
            $insertedTags = [];

            foreach ($tags as $tag) {
                // Check if tag already exists
                $query = "SELECT id FROM tags WHERE nom = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$tag->getNom()]);
                $existingTag = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$existingTag) {
                    // Insert new tag
                    $query = "INSERT INTO tags (nom) VALUES (?)";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([$tag->getNom()]);
                    $tag->setId($this->db->lastInsertId());
                } else {
                    $tag->setId($existingTag['id']);
                }
                $insertedTags[] = $tag;
            }

            $this->db->commit();
            return $insertedTags;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log('Error inserting tags: ' . $e->getMessage());
            throw new Exception('Failed to insert tags');
        }
    }

    public function consulterStatistiques($statistiques) {
        try {
            $stats = [];
            
            // Get course statistics
            $stats = array_merge($stats, $statistiques->calculerStatistiquesCours(null));
            
            // Get user statistics
            $stats = array_merge($stats, $statistiques->calculerStatistiquesUtilisateur(null));
            
            // Get global statistics
            $stats = array_merge($stats, $statistiques->calculerStatistiquesGlobales());
            
            return $stats;
        } catch (PDOException $e) {
            error_log('Error consulting statistics: ' . $e->getMessage());
            throw new Exception('Failed to retrieve statistics');
        }
    }
}