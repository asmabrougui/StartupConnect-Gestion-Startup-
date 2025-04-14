<?php
class Config {
    private static $pdo = null;

    public static function GetConnexion() {
        if (!isset(self::$pdo)) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=localhost;dbname=startup_db', // Vérifier le nom de la base
                    'root',
                    '',
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch(Exception $e) {
                die('Erreur: '.$e->getMessage());
            }
        }
        return self::$pdo;
    }
}
?>