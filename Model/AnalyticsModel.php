<?php
require_once __DIR__ . '/../config.php';

class AnalyticsModel {
    private $db;

    public function __construct() {
        $this->db = Config::GetConnexion();
    }

    public function getStartupMetrics($startupId) {
        try {
            $sql = "SELECT 
                        COUNT(DISTINCT sr.id) as total_ratings,
                        AVG(sr.rating) as average_rating,
                        COUNT(DISTINCT i.id) as total_investments,
                        SUM(i.amount) as total_investment_amount,
                        (SELECT COUNT(*) FROM startup_views WHERE startup_id = :startup_id) as view_count
                    FROM startup s
                    LEFT JOIN startup_ratings sr ON s.id = sr.startup_id
                    LEFT JOIN investments i ON s.id = i.startup_id
                    WHERE s.id = :startup_id
                    GROUP BY s.id";
            
            $query = $this->db->prepare($sql);
            $query->execute(['startup_id' => $startupId]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getStartupMetrics: " . $e->getMessage());
            return null;
        }
    }

    public function getTrendingStartups($limit = 5) {
        try {
            $sql = "SELECT 
                        s.*, 
                        c.name as category_name,
                        COUNT(DISTINCT sr.id) as rating_count,
                        AVG(sr.rating) as avg_rating,
                        COUNT(DISTINCT sv.id) as view_count
                    FROM startup s
                    LEFT JOIN categorie c ON s.category_id = c.id
                    LEFT JOIN startup_ratings sr ON s.id = sr.startup_id
                    LEFT JOIN startup_views sv ON s.id = sv.startup_id
                    GROUP BY s.id
                    ORDER BY view_count DESC, avg_rating DESC
                    LIMIT :limit";
            
            $query = $this->db->prepare($sql);
            $query->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query->execute();
            
            $startups = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Calculate total views
            $sql = "SELECT COUNT(*) as total_views FROM startup_views";
            $query = $this->db->query($sql);
            $totalViews = $query->fetch(PDO::FETCH_ASSOC)['total_views'] ?? 0;
            
            return [
                'startups' => $startups,
                'totalViews' => $totalViews,
                'success' => true
            ];
        } catch (Exception $e) {
            error_log("Error in getTrendingStartups: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function recordStartupView($startupId, $userId = null) {
        try {
            $sql = "INSERT INTO startup_views (startup_id, user_id, view_date) 
                    VALUES (:startup_id, :user_id, NOW())";
            $query = $this->db->prepare($sql);
            return $query->execute([
                'startup_id' => $startupId,
                'user_id' => $userId
            ]);
        } catch (Exception $e) {
            error_log("Error in recordStartupView: " . $e->getMessage());
            return false;
        }
    }
}
?>
