<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../Model/startup.php';

class StartupController {
    private $model;

    public function __construct() {
        $this->model = new StartupModel();
    }

    // Ajouter une startups
    public function addStartup($name, $description, $categoryId) {
        $this->model->addStartup($name, $description, $categoryId);
    }

    // Afficher toutes les startups
    public function getAllStartups() {
        try {
            // Add debug logging
            error_log("StartupController: Attempting to get all startups");
            
            if (!$this->model) {
                error_log("Error: Model not initialized");
                return [];
            }

            $startups = $this->model->getAllStartups();
            
            // Debug: Log the number of startups retrieved
            error_log("Number of startups retrieved: " . count($startups));
            
            if (empty($startups)) {
                error_log("No startups found in database");
            }
            
            return $startups;
        } catch (Exception $e) {
            error_log("Controller error in getAllStartups: " . $e->getMessage());
            return [];
        }
    }

    // Modifier une startup
    public function updateStartup($id, $name, $description, $categoryId) {
        try {
            if (!$id) {
                throw new Exception("ID de startup non valide");
            }

            $success = $this->model->updateStartup($id, $name, $description, $categoryId);
            if (!$success) {
                throw new Exception("Échec de la mise à jour de la startup");
            }

            return true;
        } catch (Exception $e) {
            error_log("Error in updateStartup: " . $e->getMessage());
            throw $e;
        }
    }

    // Supprimer une startup
    public function deleteStartup($id) {
        try {
            if (!$id) {
                throw new Exception("ID de startup non valide");
            }
            
            $startup = $this->getStartupById($id);
            if (!$startup) {
                throw new Exception("Startup non trouvée");
            }
            
            $this->model->deleteStartup($id);
            return true;
        } catch (Exception $e) {
            error_log("Error deleting startup: " . $e->getMessage());
            throw $e;
        }
    }

    // Récupérer une startup par ID
    public function getStartupById($id) {
        return $this->model->getStartupById($id);
    }

    // Handle form submissions
    public function handleRequest() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                error_log("POST request received: " . print_r($_POST, true));

                // Handle delete operation
                if (isset($_POST['delete_startup']) && isset($_POST['startup_id'])) {
                    error_log("Delete startup request received for ID: " . $_POST['startup_id']);
                    if ($this->deleteStartup($_POST['startup_id'])) {
                        $_SESSION['success_message'] = "Startup supprimée avec succès!";
                    } else {
                        throw new Exception("Erreur lors de la suppression de la startup.");
                    }
                    exit();
                }

                // Handle add operation
                if (isset($_POST['add_startup'])) {
                    error_log("Add startup request received");
                    
                    if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category_id'])) {
                        throw new Exception("Tous les champs sont requis");
                    }

                    $result = $this->model->addStartup(
                        trim($_POST['name']),
                        trim($_POST['description']),
                        $_POST['category_id']
                    );

                    if ($result) {
                        error_log("Startup added successfully");
                        echo "success"; // Send response back to AJAX call
                        exit();
                    } else {
                        throw new Exception("Erreur lors de l'ajout de la startup");
                    }
                }

                // Handle update operation
                if (isset($_POST['update_startup'])) {
                    error_log("Update startup request received");
                    
                    if (empty($_POST['startup_id']) || empty($_POST['name']) || 
                        empty($_POST['description']) || empty($_POST['category_id'])) {
                        throw new Exception("Tous les champs sont requis");
                    }

                    $result = $this->updateStartup(
                        $_POST['startup_id'],
                        trim($_POST['name']),
                        trim($_POST['description']),
                        $_POST['category_id']
                    );

                    if ($result) {
                        error_log("Startup updated successfully");
                        echo "success";
                        exit();
                    } else {
                        throw new Exception("Erreur lors de la modification de la startup");
                    }
                }
            }
        } catch (Exception $e) {
            error_log("Error in handleRequest: " . $e->getMessage());
            http_response_code(500);
            echo $e->getMessage();
            exit();
        }
    }
}

// Initialize controller and handle requests
$controller = new StartupController();
$controller->handleRequest();
?>