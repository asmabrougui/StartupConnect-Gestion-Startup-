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
                    LEFT JOIN categorie c ON s.category_id = c.id 
                    ORDER BY s.id DESC";
            $query = $this->db->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Add debug logging
            error_log("Query results: " . print_r($results, true));
            
            return $results;
        } catch (Exception $e) {
            error_log("Error in getAllStartups: " . $e->getMessage());
            error_log("SQL: " . $sql);
            return [];
        }
    }

    // Ajouter une startup avec vérification
    public function addStartup($name, $description, $categoryId) {
        try {
            error_log("Starting addStartup with parameters: " . print_r([
                'name' => $name,
                'description' => $description,
                'category_id' => $categoryId
            ], true));

            // Validate inputs
            if (empty($name) || empty($description) || empty($categoryId)) {
                error_log("Invalid input parameters");
                return false;
            }

            $sql = "INSERT INTO startup (name, description, category_id) 
                    VALUES (:name, :description, :category_id)";
            $query = $this->db->prepare($sql);
            
            error_log("Executing SQL: " . $sql);
            
            $params = [
                'name' => $name,
                'description' => $description,
                'category_id' => $categoryId
            ];
            
            $result = $query->execute($params);
            
            if (!$result) {
                error_log("Database error: " . print_r($query->errorInfo(), true));
                return false;
            }
            
            $newId = $this->db->lastInsertId();
            error_log("New startup added with ID: " . $newId);
            
            return true;
        } catch (Exception $e) {
            error_log("Exception in addStartup: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
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
        try {
            error_log("Starting updateStartup with parameters: " . print_r([
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'category_id' => $categoryId
            ], true));

            if (empty($id) || empty($name) || empty($description) || empty($categoryId)) {
                error_log("Invalid input parameters for update");
                return false;
            }

            $sql = "UPDATE startup SET name = :name, description = :description, 
                    category_id = :category_id WHERE id = :id";
            $query = $this->db->prepare($sql);
            
            error_log("Executing update SQL: " . $sql);
            
            $result = $query->execute([
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'category_id' => $categoryId
            ]);

            if (!$result) {
                error_log("Database error during update: " . print_r($query->errorInfo(), true));
                return false;
            }

            if ($query->rowCount() === 0) {
                error_log("No startup was updated with id: " . $id);
                return false;
            }

            error_log("Startup updated successfully");
            return true;
        } catch (Exception $e) {
            error_log("Exception in updateStartup: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    // Supprimer une startup
    public function deleteStartup($id) {
        $sql = "DELETE FROM startup WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute(['id' => $id]);
    }
}
?>