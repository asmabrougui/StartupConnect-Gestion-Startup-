<?php
require_once __DIR__ . '/../Model/startup.php';

class StartupController {
    private $startupModel;

    public function __construct() {
        $this->startupModel = new Startup();
    }

    // Ajouter une startup
    public function addStartup($name, $description, $categoryId) {
        $this->startupModel->addStartup($name, $description, $categoryId);
    }

    // Afficher toutes les startups
    public function getAllStartups() {
        $startups = $this->startupModel->getAllStartups();
        // Débogage
        if (empty($startups)) {
            error_log("Aucune startup trouvée");
        } else {
            error_log("Nombre de startups trouvées : " . count($startups));
        }
        return $startups;
    }

    // Modifier une startup
    public function updateStartup($id, $name, $description, $categoryId) {
        $this->startupModel->updateStartup($id, $name, $description, $categoryId);
    }

    // Supprimer une startup
    public function deleteStartup($id) {
        $this->startupModel->deleteStartup($id);
    }

    // Récupérer une startup par ID
    public function getStartupById($id) {
        return $this->startupModel->getStartupById($id);
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