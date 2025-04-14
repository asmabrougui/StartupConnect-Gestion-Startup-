<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../config.php';
require_once '../../Controller/startupC.php';

$controller = new StartupController();
$startups = $controller->getAllStartups();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Startup - Gestion des Startups</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Gestion des startups" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        /* Custom styles for the gallery */
        .card img {
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .card:hover img {
            transform: scale(1.1);
        }

        .search-bar {
            margin-bottom: 20px;
        }

        .filter-btn {
            margin-left: 10px;
        }

        .sidebar {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .sidebar h4 {
            font-weight: bold;
            margin-bottom: 15px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .sidebar ul li a:hover {
            color: #06A3DA;
        }

        .modal-dialog {
            max-width: 600px;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid bg-dark px-5 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>123 Rue Tunis, Tunisie, TN</small>
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>+216 29 999 999</small>
                    <small class="text-light"><i class="fa fa-envelope-open me-2"></i>startupconnect@gmail.com</small>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;"></div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0" style="background-color: #06A3DA;">
            <a href="index.html" class="navbar-brand p-0">
                <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>Startup Connect</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.html" class="nav-item nav-link">Acceuil</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu m-0">
                            <a href="dashboard.html" class="dropdown-item">Dashboard</a>
                            <a href="#" class="dropdown-item">Gestion utilisateurs</a>
                            <a href="#" class="dropdown-item">Gestion profiles</a>
                            <a href="gestion-startup.php" class="dropdown-item">Gestion Startup</a>
                            <a href="#" class="dropdown-item">Gestion evénements</a>
                            <a href="gestionInvestissement.html" class="dropdown-item">Gestion des investissements</a>
                            <a href="#" class="dropdown-item">Gestion documents</a>
                        </div>
                    </div>
                    <a href="login.html" class="nav-item nav-link">Connexion</a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Main Content -->
    <div class="container py-5" style="margin-top: 100px !important;">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar">
                    <h4>Catégories</h4>
                    <ul>
                        <li><a href="#" data-category="1">Technologie</a></li>
                        <li><a href="#" data-category="2">Santé</a></li>
                        <li><a href="#" data-category="3">Éducation</a></li>
                        <li><a href="#" data-category="4">Finance</a></li>
                        <li><a href="#" data-category="5">E-commerce</a></li>
                        <li><a href="#" data-category="0">Toutes les catégories</a></li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="row">
                    <div class="col-12">
                        <h1>Liste des Startups</h1>
                    </div>
                </div>

                <!-- Search Bar and Filter -->
                <div class="row search-bar mt-3">
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="searchInput" placeholder="Rechercher une startup...">
                    </div>
                    <div class="col-md-3 text-end">
                        <button class="btn btn-secondary filter-btn">Filtrer</button>
                    </div>
                </div>

                <!-- Gallery of Startups -->
                <div class="row mt-4" id="startupGallery">
                    <?php if(empty($startups)): ?>
                        <div class="col-12 text-center">
                            <p>Aucune startup trouvée.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($startups as $startup): ?>
                            <div class="col-md-4 mb-4 startup-card" data-category="<?php echo $startup['category_id']; ?>">
                                <div class="card">
                                    <img src="img/startup-placeholder.jpg" class="card-img-top" alt="<?php echo htmlspecialchars($startup['name']); ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($startup['name']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars(substr($startup['description'], 0, 100)) . '...'; ?></p>
                                        <p class="badge bg-info"><?php echo htmlspecialchars($startup['category_name']); ?></p>
                                        <div class="d-flex justify-content-between mt-3">
                                            <a href="#" class="btn btn-primary btn-sm view-startup" data-id="<?php echo $startup['id']; ?>">Voir plus</a>
                                            <div>
                                                <a href="#" class="btn btn-warning btn-sm edit-startup" data-id="<?php echo $startup['id']; ?>"><i class="fas fa-edit"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm delete-startup" data-id="<?php echo $startup['id']; ?>"><i class="fas fa-trash"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- View Startup Modal -->
    <div class="modal fade" id="viewStartupModal" tabindex="-1" aria-labelledby="viewStartupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewStartupModalLabel">Détails de la Startup</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewStartupContent">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <script>
        $(document).ready(function() {
            // Search functionality
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".startup-card").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Category filter
            $(".sidebar ul li a").click(function(e) {
                e.preventDefault();
                var categoryId = $(this).data("category");
                
                if (categoryId === 0) {
                    // Show all startups
                    $(".startup-card").show();
                } else {
                    // Filter by category
                    $(".startup-card").hide();
                    $(".startup-card[data-category='" + categoryId + "']").show();
                }
            });

            // View startup details
            $(".view-startup").click(function(e) {
                e.preventDefault();
                var startupId = $(this).data("id");
                
                // Here you would typically load the startup details via AJAX
                // For now, we'll just show a placeholder
                $("#viewStartupContent").html("<p>Chargement des détails pour la startup ID: " + startupId + "...</p>");
                $("#viewStartupModal").modal("show");
            });

            // Edit startup - implement if needed
            $(".edit-startup").click(function(e) {
                e.preventDefault();
                var startupId = $(this).data("id");
                alert("Modifier la startup ID: " + startupId);
                // Implement edit functionality
            });

            // Delete startup - implement if needed
            $(".delete-startup").click(function(e) {
                e.preventDefault();
                var startupId = $(this).data("id");
                if (confirm("Êtes-vous sûr de vouloir supprimer cette startup ?")) {
                    // Implement delete functionality
                    // You could redirect to a delete.php page or use AJAX
                    window.location.href = "delete-startup.php?id=" + startupId;
                }
            });
        });
    </script>
</body>

</html>