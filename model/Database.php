<?php
    class Database {
        private static $instance = null;
        private $conn;

        private function __construct() {
            try {
                // Correto: especificar a porta com ";port=3306"
                //$this->conn = new PDO('mysql:host=172.16.1.105;port=3306;dbname=aliexpresso', 'root', '');

                // DSN de conexão, agora com charset para suportar todos os caracteres
                $dsn = 'mysql:host=localhost;port=3306;dbname=aliexpresso;charset=utf8mb4';
                
                $this->conn = new PDO($dsn, 'root', '');
                
                // Define o modo de erro do PDO para exceções (como no seu código original)
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // [ACRESCENTADO] Define o modo de busca padrão para array associativo, para facilitar o uso dos dados.
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

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
