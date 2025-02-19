<?php
// models/Database.php

class Database {
    private $host = "localhost";  // Coloque o seu host
    private $db_name = "aliexpresso";    // Nome do banco de dados
    private $username = "root";   // Usuário do banco de dados
    private $password = "";       // Senha do banco de dados
    private $conn;

    // Conectar ao banco de dados
    public function conectar() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Erro de conexão: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
