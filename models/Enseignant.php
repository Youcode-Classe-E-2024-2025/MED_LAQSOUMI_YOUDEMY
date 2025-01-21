<?php

require_once __DIR__ . '/Utilisateur.php';
require_once __DIR__ . '/Cours.php';
require_once __DIR__ . '/Tag.php';
require_once __DIR__ . '/Categorie.php';

class Enseignant extends Utilisateur {
    public function __construct($db, $id = null, $nom = null, $email = null, $motDePasse = null, $role = 'enseignant') {
        parent::__construct($db, $id, $nom, $email, $motDePasse, $role);
    }

    public function ajouterCours($titre, $description, $contenu, $tags, $categorie) {
        try {
            $this->db->beginTransaction();

            // Insert course
            $query = "INSERT INTO cours (titre, description, contenu, categorie_id, enseignant_id) 
                     VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$titre, $description, $contenu, $categorie->getId(), $this->id]);
            $coursId = $this->db->lastInsertId();

            // Add tags
            foreach ($tags as $tag) {
                $query = "INSERT INTO cours_tags (cours_id, tag_id) VALUES (?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$coursId, $tag->getId()]);
            }

            $this->db->commit();
            return new Cours($this->db, $coursId);
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log('Error adding course: ' . $e->getMessage());
            throw new Exception('Failed to add course');
        }
    }

    public function modifierCours($cours) {
        try {
            // Verify ownership
            if ($cours->getEnseignantId() !== $this->id) {
                throw new Exception('Unauthorized to modify this course');
            }

            $query = "UPDATE cours 
                     SET titre = ?, description = ?, contenu = ?, categorie_id = ? 
                     WHERE id = ? AND enseignant_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                $cours->getTitre(),
                $cours->getDescription(),
                $cours->getContenu(),
                $cours->getCategorie()->getId(),
                $cours->getId(),
                $this->id
            ]);
        } catch (PDOException $e) {
            error_log('Error modifying course: ' . $e->getMessage());
            throw new Exception('Failed to modify course');
        }
    }

    public function supprimerCours($cours) {
        try {
            // Verify ownership
            if ($cours->getEnseignantId() !== $this->id) {
                throw new Exception('Unauthorized to delete this course');
            }

            $this->db->beginTransaction();

            // Delete course tags
            $query = "DELETE FROM cours_tags WHERE cours_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$cours->getId()]);

            // Delete inscriptions
            $query = "DELETE FROM inscriptions WHERE cours_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$cours->getId()]);

            // Delete course
            $query = "DELETE FROM cours WHERE id = ? AND enseignant_id = ?";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$cours->getId(), $this->id]);

            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log('Error deleting course: ' . $e->getMessage());
            throw new Exception('Failed to delete course');
        }
    }

    public function consulterInscriptions($cours) {
        try {
            // Verify ownership
            if ($cours->getEnseignantId() !== $this->id) {
                throw new Exception('Unauthorized to view these inscriptions');
            }

            $query = "SELECT u.* 
                     FROM inscriptions i
                     JOIN utilisateurs u ON i.etudiant_id = u.id
                     WHERE i.cours_id = ?
                     ORDER BY i.date_inscription DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$cours->getId()]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error consulting inscriptions: ' . $e->getMessage());
            throw new Exception('Failed to retrieve inscriptions');
        }
    }
}