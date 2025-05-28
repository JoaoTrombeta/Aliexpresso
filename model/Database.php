<?php
class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            // Correto: especificar a porta com ";port=3306"
            $this->conn = new PDO('mysql:host=172.16.1.105;port=3306;dbname=aliexpresso', 'root', '');
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
