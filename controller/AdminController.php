<?php
    namespace Aliexpresso\Controller;

    use Aliexpresso\Helper\Auth;
    use Aliexpresso\Model\UsuarioModel;
    use Aliexpresso\Model\ProdutoModel;
    use Aliexpresso\Model\ProdutoFactory;
    use Aliexpresso\Model\CupomModel;
    use Aliexpresso\Model\PedidoModel;

    class AdminController 
    {
        private $userModel;
        private $productModel;
        private $cupomModel;
        private $pedidoModel;

        public function __construct() {
            $this->userModel = new UsuarioModel();
            $this->productModel = new ProdutoModel();
            $this->cupomModel = new CupomModel();
            $this->pedidoModel = new PedidoModel();

            if (!Auth::isAdmin()) {
                http_response_code(403);
                die('<h1>Acesso Negado</h1><p>Você não tem permissão para acessar esta área.</p>');
            }
        }

        /**
         * Mostra o painel principal do admin.
         */
        public function index() {
            require_once __DIR__ . '/../view/admin/dashboard.php';
        }

        /**
         * Mostra a página de CRUD de usuários.
         */
        public function usuarios() {
            $users = $this->userModel->getAll();
            $userToEdit = null;

            if (isset($_GET['edit_id'])) {
                $userToEdit = $this->userModel->getById((int)$_GET['edit_id']);
            }

            // Passa as variáveis $users e $userToEdit para a view
            require_once __DIR__ . '/../view/admin/user_crud.php';
        }

        /**
         * Salva um usuário (novo ou editado).
         */
        public function saveUser() {
            if ($_POST) {
                $id = (int)($_POST['id_usuario'] ?? 0);
                $data = [
                    'nome' => $_POST['nome'],
                    'email' => $_POST['email'],
                    'tipo' => $_POST['tipo'],
                    'senha' => $_POST['senha'] // A senha só é usada se não estiver vazia
                ];

                if ($id > 0) {
                    // Editando
                    $this->userModel->update($id, $data);
                } else {
                    // Criando
                    $this->userModel->create($data);
                }
            }
            header('Location: index.php?page=admin&action=usuarios');
            exit();
        }

        /**
         * Deleta um usuário.
         */
        public function deleteUser() {
            $id = (int)($_GET['id'] ?? 0);
            if ($id > 0) {
                // Adicionar uma verificação para não deixar o admin se auto-deletar
                if ($id === Auth::user()['id_usuario']) {
                    // Pode adicionar uma mensagem de erro na URL se quiser
                    header('Location: index.php?page=admin&action=users&error=self_delete');
                    exit();
                }
                $this->userModel->delete($id);
            }
            header('Location: index.php?page=admin&action=usuarios');
            exit();
        }

        public function produtos() {
            $products = $this->productModel->getAll();
            $productToEdit = null;

            // [ATUALIZADO] Pega os tipos de produto da fábrica
            $productTypes = ProdutoFactory::getAvailableTypes();

            if (isset($_GET['edit_id'])) {
                $productToEdit = $this->productModel->getById((int)$_GET['edit_id']);
            }
            
            // Passa todas as variáveis necessárias para a view
            require_once __DIR__ . '/../view/admin/produto_crud.php';
        }

        /**
         * [NOVO] Salva um produto (novo ou editado).
         */
        public function saveProduct() {
            if ($_POST) {
                $id = (int)($_POST['id_produto'] ?? 0);
                $caminho_imagem_db = $_POST['imagem_atual'] ?? '';

                // Lógica de upload de imagem
                if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                    $target_dir = "assets/images/produtos/";
                    if (!is_dir($target_dir)) {
                        mkdir($target_dir, 0755, true);
                    }
                    $nome_unico = uniqid() . '-' . basename($_FILES["imagem"]["name"]);
                    $target_file = $target_dir . $nome_unico;

                    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)) {
                        $caminho_imagem_db = $target_file;
                    }
                }
                
                // Prepara os dados comuns do formulário
                $data = [
                    'nome' => $_POST['nome'],
                    'descricao' => $_POST['descricao'],
                    'preco' => (float)$_POST['preco'],
                    'quantidade_estoque' => (int)$_POST['quantidade_estoque'],
                    'categoria' => $_POST['categoria'],
                    'imagem' => $caminho_imagem_db
                ];

                if ($id > 0) {
                    // [MODO EDIÇÃO]: Pega o status do formulário
                    $data['status'] = $_POST['status'];
                    $this->productModel->update($id, $data);
                } else {
                    // [MODO CRIAÇÃO]: Define o status automaticamente
                    $data['status'] = 'a venda';
                    $this->productModel->create($data);
                }
            }
            header('Location: index.php?page=admin&action=produtos');
            exit();
        }

        /**
         * [NOVO] Deleta um produto.
         */
        public function deleteProduct() {
            $id = (int)($_GET['id'] ?? 0);
            if ($id > 0) {
                $this->productModel->delete($id);
            }
            header('Location: index.php?page=admin&action=produtos');
            exit();
        }

        public function cupons() {
            $coupons = $this->cupomModel->getAll();
            $couponToEdit = null;

            if (isset($_GET['edit_id'])) {
                $couponToEdit = $this->cupomModel->getById((int)$_GET['edit_id']);
            }
            
            require_once __DIR__ . '/../view/admin/cupom_crud.php';
        }

        /**
         * [NOVO] Salva um cupom (novo ou editado).
         */
        public function saveCoupon() {
            if ($_POST) {
                $id = (int)($_POST['id_cupom'] ?? 0);
                
                $data = [
                    'codigo' => $_POST['codigo'],
                    'descricao' => $_POST['descricao'],
                    'valor_desconto' => (float)$_POST['valor_desconto'],
                    'tipo' => $_POST['tipo'],
                    'data_validade' => $_POST['data_validade']
                ];

                if ($id > 0) {
                    // MODO EDIÇÃO: Pega o status do formulário.
                    $data['status'] = $_POST['status'];
                    $this->cupomModel->update($id, $data);
                } else {
                    // MODO CRIAÇÃO: Define o status como 'ativo' automaticamente.
                    $data['status'] = 'ativo';
                    $this->cupomModel->create($data);
                }
            }
            header('Location: index.php?page=admin&action=cupons');
            exit();
        }

        /**
         * [NOVO] Deleta um cupom.
         */
        public function deleteCoupon() {
            $id = (int)($_GET['id'] ?? 0);
            if ($id > 0) {
                $this->cupomModel->delete($id);
            }
            header('Location: index.php?page=admin&action=cupons');
            exit();
        }

        public function vendas() {
        // 1. Busca as estatísticas básicas (faturamento e total de vendas)
        $salesStats = $this->pedidoModel->getSalesStats();
        
        // 2. Calcula o Ticket Médio (evita divisão por zero)
        $ticketMedio = 0;
        if (!empty($salesStats['total_vendas']) && $salesStats['total_vendas'] > 0) {
            $ticketMedio = $salesStats['faturamento_total'] / $salesStats['total_vendas'];
        }

        // 3. Monta o array de estatísticas para a view
        $stats = [
            'faturamento_total' => $salesStats['faturamento_total'] ?? 0,
            'total_vendas'      => $salesStats['total_vendas'] ?? 0,
            'ticket_medio'      => $ticketMedio
        ];

        // 4. Busca os pedidos recentes para a tabela
        $recentOrders = $this->pedidoModel->getRecentOrders();

        // 5. [Futuro] Aqui você buscaria os dados para o gráfico de produtos mais vendidos
        // $bestSellers = $this->itemPedidoModel->getBestSellers();

        // 6. Carrega a view do dashboard, passando as variáveis $stats e $recentOrders
        require_once __DIR__ . '/../view/admin/dashboard_vendas.php';
    }

    }
?>