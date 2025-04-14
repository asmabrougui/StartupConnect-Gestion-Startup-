<?php
require_once __DIR__ . '/../config.php';

class StartupModel {
    private $db;

    public function __construct() {
        $this->db = Config::GetConnexion();
    }

    // Lire toutes les startups avec leurs catégories
    public function getAllStartups() {
        try {
            $sql = "SELECT s.*, c.name as category_name 
                    FROM startup s 
                    LEFT JOIN category c ON s.category_id = c.id";
            $query = $this->db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            error_log("Error in getAllStartups: " . $e->getMessage());
            return [];
        }
    }

    // Ajouter une startup avec vérification
    public function addStartup($name, $description, $categoryId) {
        try {
            $sql = "INSERT INTO startup (name, description, category_id) 
                    VALUES (:name, :description, :category_id)";
            $query = $this->db->prepare($sql);
            return $query->execute([
                'name' => $name,
                'description' => $description,
                'category_id' => $categoryId
            ]);
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // Récupérer une startup par ID
    public function getStartupById($id) {
        $sql = "SELECT * FROM startup WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute(['id' => $id]);
        return $query->fetch();
    }

    // Mettre à jour une startup
    public function updateStartup($id, $name, $description, $categoryId) {
        $sql = "UPDATE startup SET name = :name, description = :description, category_id = :category_id WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'category_id' => $categoryId
        ]);
    }

    // Supprimer une startup
    public function deleteStartup($id) {
        $sql = "DELETE FROM startup WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute(['id' => $id]);
    }
}
?>