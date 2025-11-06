<?php
    namespace Aliexpresso\Model;

    require_once __DIR__ . '/Database.php';

    class UsuarioModel {
        private $pdo;

        public function __construct() {
            $this->pdo = \Database::getInstance()->getConnection();
        }

        public function findByEmail(string $email) {
            $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch();
        }
        
        public function getById(int $id) {
            $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        }

        public function getAll() {
            // [ALTERADO] A ordenação agora é pelo ID do usuário, do menor para o maior.
            $stmt = $this->pdo->query("SELECT * FROM usuarios ORDER BY id_usuario ASC");
            return $stmt->fetchAll();
        }

        public function create(array $data): bool {
            $senha_hash = password_hash($data['senha'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$data['nome'], $data['email'], $senha_hash, $data['tipo']]);
        }

        public function update(int $id, array $data): bool {
            if (!empty($data['senha'])) {
                // Se uma nova senha for fornecida, criptografa e atualiza.
                $senha_hash = password_hash($data['senha'], PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios SET nome = ?, email = ?, tipo = ?, senha = ? WHERE id_usuario = ?";
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([$data['nome'], $data['email'], $data['tipo'], $senha_hash, $id]);
            } else {
                // Se a senha estiver vazia, não a atualiza.
                $sql = "UPDATE usuarios SET nome = ?, email = ?, tipo = ? WHERE id_usuario = ?";
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([$data['nome'], $data['email'], $data['tipo'], $id]);
            }
        }

        public function delete(int $id): bool {
            $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            return $stmt->execute([$id]);
        }

        /**
         * [NOVO] Atualiza apenas o caminho da imagem de perfil do utilizador.
         */
        public function updateProfileImage(int $userId, string $imagePath): bool {
            $sql = "UPDATE usuarios SET imagem_perfil = ? WHERE id_usuario = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$imagePath, $userId]);
        }
    }
?>