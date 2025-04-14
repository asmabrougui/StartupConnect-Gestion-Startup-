<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../Model/startup.php';

class StartupController {
    private $model;

    public function __construct() {
        $this->model = new StartupModel();
    }

    // Ajouter une startup
    public function addStartup($name, $description, $categoryId) {
        $this->model->addStartup($name, $description, $categoryId);
    }

    // Afficher toutes les startups
    public function getAllStartups() {
        try {
            $startups = $this->model->getAllStartups();
            error_log("Startups récupérées : " . print_r($startups, true));
            return $startups;
        } catch (Exception $e) {
            error_log("Erreur dans getAllStartups: " . $e->getMessage());
            return [];
        }
    }

    // Modifier une startup
    public function updateStartup($id, $name, $description, $categoryId) {
        $this->model->updateStartup($id, $name, $description, $categoryId);
    }

    // Supprimer une startup
    public function deleteStartup($id) {
        $this->model->deleteStartup($id);
    }

    // Récupérer une startup par ID
    public function getStartupById($id) {
        return $this->model->getStartupById($id);
    }

    // Handle form submissions
    public function handleRequest() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['add_startup'])) {
                    $name = $_POST['name'];
                    $description = $_POST['description'];
                    $categoryId = $_POST['category_id'];
                    
                    $this->addStartup($name, $description, $categoryId);
                    header("Location: ../view/Admin/dashboard-startup.php?success=1");
                    exit();
                }
            }
        } catch (Exception $e) {
            header("Location: ../view/Admin/dashboard-startup.php?error=" . urlencode($e->getMessage()));
            exit();
        }
    }
}

// Initialize controller and handle requests
$controller = new StartupController();
$controller->handleRequest();
?>