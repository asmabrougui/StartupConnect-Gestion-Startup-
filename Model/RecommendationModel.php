<?php
require_once __DIR__ . '/../config.php';

class RecommendationModel {
    private $db;

    public function __construct() {
        $this->db = Config::GetConnexion();
    }

    public function getSimilarStartups($startupId, $limit = 5) {
        try {
            // Get category and tags of the current startup
            $sql = "SELECT s.category_id, GROUP_CONCAT(t.name) as tags
                    FROM startup s
                    LEFT JOIN startup_tags st ON s.id = st.startup_id
                    LEFT JOIN tags t ON st.tag_id = t.id
                    WHERE s.id = :startup_id
                    GROUP BY s.id";
            
            $query = $this->db->prepare($sql);
            $query->execute(['startup_id' => $startupId]);
            $currentStartup = $query->fetch(PDO::FETCH_ASSOC);

            if (!$currentStartup) return [];

            // Find similar startups based on category and tags
            $sql = "SELECT 
                        s.*,
                        COUNT(DISTINCT t.id) as matching_tags,
                        AVG(sr.rating) as avg_rating
                    FROM startup s
                    LEFT JOIN startup_tags st ON s.id = st.startup_id
                    LEFT JOIN tags t ON st.tag_id = t.id
                    LEFT JOIN startup_ratings sr ON s.id = sr.startup_id
                    WHERE s.id != :startup_id
                    AND (s.category_id = :category_id 
                        OR t.name IN (SELECT name FROM tags 
                                    JOIN startup_tags ON tags.id = startup_tags.tag_id 
                                    WHERE startup_tags.startup_id = :startup_id))
                    GROUP BY s.id
                    ORDER BY matching_tags DESC, avg_rating DESC
                    LIMIT :limit";

            $query = $this->db->prepare($sql);
            $query->bindValue(':startup_id', $startupId, PDO::PARAM_INT);
            $query->bindValue(':category_id', $currentStartup['category_id'], PDO::PARAM_INT);
            $query->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query->execute();
            
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getSimilarStartups: " . $e->getMessage());
            return [];
        }
    }

    public function getPersonalizedRecommendations($userId, $limit = 5) {
        try {
            $sql = "SELECT s.*, 
                        AVG(sr.rating) as avg_rating,
                        COUNT(DISTINCT sr2.id) as user_interaction_count
                    FROM startup s
                    LEFT JOIN startup_ratings sr ON s.id = sr.startup_id
                    LEFT JOIN startup_ratings sr2 ON s.id = sr2.startup_id 
                        AND sr2.user_id = :user_id
                    LEFT JOIN startup_views sv ON s.id = sv.startup_id 
                        AND sv.user_id = :user_id
                    WHERE s.id NOT IN (
                        SELECT startup_id FROM startup_ratings 
                        WHERE user_id = :user_id
                    )
                    GROUP BY s.id
                    ORDER BY avg_rating DESC, user_interaction_count DESC
                    LIMIT :limit";

            $query = $this->db->prepare($sql);
            $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $query->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query->execute();
            
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getPersonalizedRecommendations: " . $e->getMessage());
            return [];
        }
    }
}
?>
