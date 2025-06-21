<?php
    namespace Aliexpresso\Model;

    require_once __DIR__ . './Database.php';

    class UsuarioModel 
    {
        private $pdo;

        public function __construct() {
            $this->pdo = \Database::getInstance()->getConnection();
        }

        public function findByEmail(string $email) {
            $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch();
        }
        
        public function create(array $data): bool {
            $senha_hash = password_hash($data['senha'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$data['nome'], $data['email'], $senha_hash, 'cliente']);
        }
    }
?>