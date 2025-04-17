<?php
require_once '../Model/AnalyticsModel.php';

class AnalyticsController {
    private $model;

    public function __construct() {
        $this->model = new AnalyticsModel();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            switch ($_GET['action'] ?? '') {
                case 'get_analytics':
                    $this->getAnalyticsData();
                    break;
                case 'record_view':
                    $this->recordView($_GET['startup_id'] ?? null);
                    break;
            }
        }
    }

    private function getAnalyticsData() {
        try {
            // Get all startups with their metrics
            $startups = $this->model->getTrendingStartups();
            
            // Calculate total views
            $totalViews = array_sum(array_column($startups, 'view_count'));
            
            // Calculate average rating
            $ratings = array_filter(array_column($startups, 'avg_rating'));
            $averageRating = count($ratings) > 0 ? array_sum($ratings) / count($ratings) : 0;
            
            $response = [
                'success' => true,
                'totalViews' => $totalViews,
                'averageRating' => round($averageRating, 1),
                'trendingCount' => count($startups),
                'topStartups' => array_slice($startups, 0, 5)
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function recordView($startupId) {
        if (!$startupId) {
            http_response_code(400);
            echo json_encode(['error' => 'Startup ID required']);
            return;
        }

        try {
            $userId = $_SESSION['user_id'] ?? null;
            $success = $this->model->recordStartupView($startupId, $userId);
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

$controller = new AnalyticsController();
$controller->handleRequest();
