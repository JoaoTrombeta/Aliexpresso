<?php
require_once 'banco.php';

class Produto {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conectar();
    }

    // Buscar todos os produtos
    public function obterTodosProdutos() {
        $query = "SELECT * FROM produtos";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Buscar produto por ID
    public function obterProdutoPorId($id) {
        $query = "SELECT * FROM produtos WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
?>
