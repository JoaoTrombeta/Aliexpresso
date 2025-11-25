<?php

namespace Aliexpresso\Model;

// Carrega o ficheiro da base de dados, como nos seus outros modelos
require_once __DIR__ . '/Database.php';

class EnderecoModel
{
    private $pdo;

    public function __construct()
    {
        // Obtém a instância da conexão
        $this->pdo = \Database::getInstance()->getConnection();
    }

    /**
     * Busca todos os endereços de um utilizador específico.
     */
    public function getByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM enderecos WHERE id_usuario = ? ORDER BY is_principal DESC, id_endereco ASC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Busca um endereço específico, garantindo que ele pertença ao utilizador.
     */
    public function getById(int $enderecoId, int $userId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM enderecos WHERE id_endereco = ? AND id_usuario = ?");
        $stmt->execute([$enderecoId, $userId]);
        $result = $stmt->fetch();
        return $result ? $result : null;
    }

    /**
     * Cria um novo endereço para o utilizador.
     */
    public function create(int $userId, array $data): bool
    {
        $sql = "INSERT INTO enderecos (id_usuario, cep, logradouro, numero, complemento, bairro, cidade, uf)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $userId,
            $data['cep'],
            $data['logradouro'],
            $data['numero'],
            $data['complemento'],
            $data['bairro'],
            $data['cidade'],
            $data['uf']
        ]);
    }

    /**
     * [NOVO] Remove o status de principal de um endereço específico.
     */
    public function removePrincipal(int $userId, int $enderecoId)
    {
        $sql = "UPDATE enderecos SET is_principal = 0 WHERE id_usuario = ? AND id_endereco = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $enderecoId]);
    }

    /**
     * Atualiza um endereço existente.
     */
    public function update(int $enderecoId, int $userId, array $data): bool
    {
        $sql = "UPDATE enderecos 
                SET cep = ?, logradouro = ?, numero = ?, complemento = ?, bairro = ?, cidade = ?, uf = ?
                WHERE id_endereco = ? AND id_usuario = ?";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['cep'],
            $data['logradouro'],
            $data['numero'],
            $data['complemento'],
            $data['bairro'],
            $data['cidade'],
            $data['uf'],
            $enderecoId,
            $userId
        ]);
    }

    /**
     * Remove um endereço da base de dados.
     */
    public function delete(int $enderecoId, int $userId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM enderecos WHERE id_endereco = ? AND id_usuario = ?");
        return $stmt->execute([$enderecoId, $userId]);
    }

    /**
     * Define uma morada como a principal, desmarcando todas as outras.
     */
    public function setPrincipal(int $userId, int $enderecoId)
    {
        try {
            // Inicia a transação
            $this->pdo->beginTransaction();

            // 1. Desmarca todas as outras moradas deste utilizador
            $stmt1 = $this->pdo->prepare("UPDATE enderecos SET is_principal = 0 WHERE id_usuario = ?");
            $stmt1->execute([$userId]);

            // 2. Marca a nova morada como principal
            $stmt2 = $this->pdo->prepare("UPDATE enderecos SET is_principal = 1 WHERE id_usuario = ? AND id_endereco = ?");
            $stmt2->execute([$userId, $enderecoId]);

            // Confirma as alterações
            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            // Se algo falhar, desfaz tudo
            $this->pdo->rollBack();
            error_log("Erro ao definir morada principal: " . $e->getMessage());
            return false;
        }
    }
}
?>