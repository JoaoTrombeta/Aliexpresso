<?php

require_once '/Database.php'; // Garante que o arquivo de conexão seja incluído

class CarrinhoModel {
    private $db;

    /**
     * Construtor da classe, obtém a conexão com o banco de dados.
     */
    public function __construct() {
        // Obtém a instância da conexão PDO
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Adiciona um produto ao carrinho de um usuário ou atualiza sua quantidade se já existir.
     *
     * @param int $id_usuario O ID do usuário.
     * @param int $id_produto O ID do produto a ser adicionado.
     * @param int $quantidade A quantidade do produto.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function adicionar($id_usuario, $id_produto, $quantidade) {
        if ($quantidade <= 0) {
            return false; // Não permite adicionar quantidade zero ou negativa
        }

        // 1. Encontra ou cria o pedido "no_carrinho" para o usuário
        $id_pedido = $this->_getPedidoId($id_usuario, true);
        if (!$id_pedido) {
            return false;
        }

        // 2. Verifica se o item já existe no carrinho
        $sql = "SELECT id_item, quantidade FROM itens_pedido WHERE id_pedido = :id_pedido AND id_produto = :id_produto";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_pedido' => $id_pedido, ':id_produto' => $id_produto]);
        $item_existente = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->db->beginTransaction();
        try {
            if ($item_existente) {
                // 3a. Se existe, atualiza a quantidade
                $nova_quantidade = $item_existente['quantidade'] + $quantidade;
                $sql_update = "UPDATE itens_pedido SET quantidade = :quantidade WHERE id_item = :id_item";
                $stmt_update = $this->db->prepare($sql_update);
                $stmt_update->execute([
                    ':quantidade' => $nova_quantidade,
                    ':id_item' => $item_existente['id_item']
                ]);
            } else {
                // 3b. Se não existe, busca o preço do produto e insere
                $sql_preco = "SELECT preco FROM produtos WHERE id_produto = :id_produto";
                $stmt_preco = $this->db->prepare($sql_preco);
                $stmt_preco->execute([':id_produto' => $id_produto]);
                $produto = $stmt_preco->fetch(PDO::FETCH_ASSOC);
                
                if (!$produto) {
                    throw new Exception("Produto não encontrado.");
                }

                $sql_insert = "INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario) VALUES (:id_pedido, :id_produto, :quantidade, :preco_unitario)";
                $stmt_insert = $this->db->prepare($sql_insert);
                $stmt_insert->execute([
                    ':id_pedido' => $id_pedido,
                    ':id_produto' => $id_produto,
                    ':quantidade' => $quantidade,
                    ':preco_unitario' => $produto['preco']
                ]);
            }
            
            // 4. Atualiza o valor total do pedido
            $this->_atualizarValorTotal($id_pedido);
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            // Em um ambiente de produção, você poderia logar o erro: error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Edita a quantidade de um produto específico no carrinho.
     *
     * @param int $id_usuario O ID do usuário.
     * @param int $id_produto O ID do produto a ser editado.
     * @param int $nova_quantidade A nova quantidade do produto.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function editar($id_usuario, $id_produto, $nova_quantidade) {
        if ($nova_quantidade <= 0) {
            // Se a quantidade for zero ou menos, remove o item
            return $this->remover($id_usuario, $id_produto);
        }

        $id_pedido = $this->_getPedidoId($id_usuario);
        if (!$id_pedido) {
            return false; // Usuário não tem carrinho ativo
        }
        
        $sql = "UPDATE itens_pedido SET quantidade = :quantidade WHERE id_pedido = :id_pedido AND id_produto = :id_produto";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':quantidade' => $nova_quantidade,
            ':id_pedido' => $id_pedido,
            ':id_produto' => $id_produto
        ]);
        
        // Atualiza o valor total do pedido
        $this->_atualizarValorTotal($id_pedido);

        return $stmt->rowCount() > 0;
    }

    /**
     * Remove um produto do carrinho de um usuário.
     *
     * @param int $id_usuario O ID do usuário.
     * @param int $id_produto O ID do produto a ser removido.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function remover($id_usuario, $id_produto) {
        $id_pedido = $this->_getPedidoId($id_usuario);
        if (!$id_pedido) {
            return false; // Usuário não tem carrinho ativo
        }

        $sql = "DELETE FROM itens_pedido WHERE id_pedido = :id_pedido AND id_produto = :id_produto";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_pedido' => $id_pedido, ':id_produto' => $id_produto]);

        $sucesso = $stmt->rowCount() > 0;

        if ($sucesso) {
             // Atualiza o valor total do pedido
            $this->_atualizarValorTotal($id_pedido);
        }

        return $sucesso;
    }

    /**
     * Obtém todos os itens e o valor total do carrinho de um usuário.
     *
     * @param int $id_usuario O ID do usuário.
     * @return array Retorna um array com os itens e o total do carrinho, ou um array vazio se o carrinho não existir.
     */
    public function getCarrinho($id_usuario) {
        $id_pedido = $this->_getPedidoId($id_usuario);
        if (!$id_pedido) {
            return ['itens' => [], 'total' => 0.00];
        }

        $sql = "SELECT p.id_produto, p.nome, p.imagem, i.quantidade, i.preco_unitario, (i.quantidade * i.preco_unitario) as subtotal
                FROM itens_pedido i
                JOIN produtos p ON i.id_produto = p.id_produto
                WHERE i.id_pedido = :id_pedido";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_pedido' => $id_pedido]);
        $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql_total = "SELECT valor_total FROM pedidos WHERE id_pedido = :id_pedido";
        $stmt_total = $this->db->prepare($sql_total);
        $stmt_total->execute([':id_pedido' => $id_pedido]);
        $total = $stmt_total->fetchColumn();

        return ['itens' => $itens, 'total' => $total];
    }
    
    /**
     * [PRIVADO] Obtém o ID do pedido com status 'no_carrinho' para um usuário.
     * Pode criar um novo se não existir.
     */
    private function _getPedidoId($id_usuario, $criar_se_nao_existir = false) {
        $sql = "SELECT id_pedido FROM pedidos WHERE id_usuario = :id_usuario AND status = 'no_carrinho'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario]);
        $id_pedido = $stmt->fetchColumn();

        if (!$id_pedido && $criar_se_nao_existir) {
            $sql_create = "INSERT INTO pedidos (id_usuario, status) VALUES (:id_usuario, 'no_carrinho')";
            $stmt_create = $this->db->prepare($sql_create);
            $stmt_create->execute([':id_usuario' => $id_usuario]);
            $id_pedido = $this->db->lastInsertId();
        }

        return $id_pedido;
    }

    /**
     * [PRIVADO] Recalcula e atualiza o valor total de um pedido.
     */
    private function _atualizarValorTotal($id_pedido) {
        $sql_sum = "SELECT SUM(quantidade * preco_unitario) FROM itens_pedido WHERE id_pedido = :id_pedido";
        $stmt_sum = $this->db->prepare($sql_sum);
        $stmt_sum->execute([':id_pedido' => $id_pedido]);
        $total = $stmt_sum->fetchColumn();
        
        // Garante que o total seja no mínimo 0.00
        $total = $total ?: 0.00;

        $sql_update = "UPDATE pedidos SET valor_total = :total WHERE id_pedido = :id_pedido";
        $stmt_update = $this->db->prepare($sql_update);
        $stmt_update->execute([':total' => $total, ':id_pedido' => $id_pedido]);
    }
}