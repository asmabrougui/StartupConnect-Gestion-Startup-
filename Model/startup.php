<?php
require_once __DIR__ . '/../config.php'; // Inclure la configuration de la base de données

class Startup {
    private $db;

    public function __construct() {
        $this->db = Config::GetConnexion(); // Connexion à la base de données
    }

    // Lire toutes les startups avec leurs catégories
    public function getAllStartups() {
        try {
            $sql = "SELECT s.*, c.name as category_name 
                    FROM startup s 
                    LEFT JOIN categorie c ON s.category_id = c.id";
            $query = $this->db->query($sql);
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
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

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $categoryId = $_POST['category_id'];

    $startup = new Startup();
    $startup->addStartup($name, $description, $categoryId);

    echo "<p>Startup ajoutée avec succès !</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Startup</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Ajouter une Startup</h1>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Nom de la Startup</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Entrez le nom de la startup" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Entrez une description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Catégorie</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="1">Technologie</option>
                    <option value="2">Santé</option>
                    <option value="3">Éducation</option>
                    <option value="4">Finance</option>
                    <option value="5">E-commerce</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
</body>
</html>