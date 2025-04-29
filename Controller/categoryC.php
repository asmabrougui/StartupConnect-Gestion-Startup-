<?php
require_once __DIR__ . '/../Model/category.php';

class CategoryController {
    private $model;

    public function __construct() {
        $this->model = new CategoryModel();
    }

    public function getAllCategories() {
        return $this->model->getAllCategories();
    }

    public function handleRequest() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
                if ($_GET['action'] === 'getAll') {
                    $categories = $this->getAllCategories();
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'data' => $categories]);
                    exit;
                }
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $response = ['success' => false, 'message' => ''];

                if (isset($_POST['action'])) {
                    switch ($_POST['action']) {
                        case 'add':
                            if (empty($_POST['name'])) {
                                throw new Exception("Le nom de la catégorie est requis");
                            }
                            if ($this->model->addCategory($_POST['name'])) {
                                $response = ['success' => true, 'message' => 'Catégorie ajoutée avec succès'];
                            }
                            break;

                        case 'update':
                            if (empty($_POST['id']) || empty($_POST['name'])) {
                                throw new Exception("ID et nom sont requis");
                            }
                            if ($this->model->updateCategory($_POST['id'], $_POST['name'])) {
                                $response = ['success' => true, 'message' => 'Catégorie mise à jour avec succès'];
                            }
                            break;

                        case 'delete':
                            if (empty($_POST['id'])) {
                                throw new Exception("ID est requis");
                            }
                            if ($this->model->deleteCategory($_POST['id'])) {
                                $response = ['success' => true, 'message' => 'Catégorie supprimée avec succès'];
                            }
                            break;
                    }
                }

                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }
}

// Initialize controller and handle requests
$controller = new CategoryController();
$controller->handleRequest();
?>
