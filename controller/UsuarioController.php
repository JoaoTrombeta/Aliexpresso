<?php
    namespace Aliexpresso\Controller;

    use Aliexpresso\Model\UsuarioModel;
    use Aliexpresso\Model\PedidoModel;
    use Aliexpresso\Model\ItemPedidoModel;
    use Aliexpresso\Model\EnderecoModel;
    use Aliexpresso\Helper\Auth;

    class UsuarioController {

        private $userModel;
        private $pedidoModel;
        private $itemPedidoModel;
        private $enderecoModel;

        public function __construct() {
            $this->userModel = new UsuarioModel();
            $this->pedidoModel = new PedidoModel();
            $this->itemPedidoModel = new ItemPedidoModel();
            $this->enderecoModel = new EnderecoModel();
        }

        public function login() {
            require_once __DIR__ . '/../view/usuarios/Login.php';
        }

        public function register() {
            require_once __DIR__ . '/../view/usuarios/Register.php';
        }

        public function store() {
            $data = [
                'nome' => $_POST['nome'] ?? null,
                'email' => $_POST['email'] ?? null,
                'senha' => $_POST['senha'] ?? null
            ];

            $data['tipo'] = 'cliente';

            if ($this->userModel->create($data)) {
                header('Location: index.php?page=usuario&action=login&sucesso=1');
            } else {
                header('Location: index.php?page=usuario&action=register&erro=1');
            }
            exit();
        }

        public function authenticate() {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            // [ATUALIZADO] Usa a propriedade $this->userModel
            $usuario = $this->userModel->findByEmail($email);

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario'] = $usuario;
                
                // Lógica de Sincronização de Carrinho (do seu ficheiro)
                $userId = $_SESSION['usuario']['id_usuario'];
                $carrinhoDB = $this->pedidoModel->findCartByUserId($userId);
                
                $_SESSION['carrinho'] = ['produtos' => [], 'total' => 0]; 

                if ($carrinhoDB) {
                    $itensDB = $this->itemPedidoModel->findItemsByOrderId($carrinhoDB->id_pedido);
                    
                    foreach ($itensDB as $item) {
                        $_SESSION['carrinho']['produtos'][$item->id_produto] = [
                            'id' => $item->id_produto,
                            'quantidade' => $item->quantidade,
                            'preco' => $item->preco_unitario
                        ];
                    }
                    $_SESSION['carrinho']['total'] = $carrinhoDB->valor_total;
                }
                
                header('Location: index.php?page=home');
                exit();
            } else {
                header('Location: index.php?page=usuario&action=login&erro=1');
                exit();
            }
        }

        public function logout() {
            session_unset();
            session_destroy();
            header('Location: index.php');
            exit();
        }

        // --- NOVAS AÇÕES PARA GERIR MORADAS ---

        /**
         * [NOVO] Mostra a página de perfil e moradas do utilizador.
         */
        public function perfil() {
            if (!Auth::isLoggedIn()) {
                header('Location: index.php?page=usuario&action=login');
                exit();
            }

            $userId = Auth::user()['id_usuario'];
            
            // Busca os dados do utilizador (Ex: nome, email)
            $user = $this->userModel->getById($userId);
            
            // Busca a lista de moradas guardadas
            $enderecos = $this->enderecoModel->getByUserId($userId);
            
            // Verifica se há um formulário de edição pré-preenchido (para o VIACEP)
            $enderecoParaEditar = null;
            if (isset($_GET['edit_id'])) {
                $enderecoParaEditar = $this->enderecoModel->getById((int)$_GET['edit_id'], $userId);
            }

            // Carrega a view e passa os dados
            require_once __DIR__ . '/../view/usuarios/perfil.php';
        }

        /**
         * [NOVO] Salva uma morada (nova ou editada).
         */
        public function saveAddress() {
            if (!Auth::isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: index.php');
                exit();
            }

            $userId = Auth::user()['id_usuario'];
            $enderecoId = (int)($_POST['id_endereco'] ?? 0);
            
            $data = [
                'cep' => $_POST['cep'] ?? null,
                'logradouro' => $_POST['logradouro'] ?? null,
                'numero' => $_POST['numero'] ?? null,
                'complemento' => $_POST['complemento'] ?? null,
                'bairro' => $_POST['bairro'] ?? null,
                'cidade' => $_POST['cidade'] ?? null,
                'uf' => $_POST['uf'] ?? null
            ];

            if ($enderecoId > 0) {
                // Atualiza morada existente
                $this->enderecoModel->update($enderecoId, $userId, $data);
                $_SESSION['form_message'] = ['text' => 'Morada atualizada com sucesso!', 'type' => 'success'];
            } else {
                // Cria nova morada
                $this->enderecoModel->create($userId, $data);
                $_SESSION['form_message'] = ['text' => 'Nova morada adicionada!', 'type' => 'success'];
            }
            
            header('Location: index.php?page=usuario&action=perfil');
            exit();
        }

        /**
         * [NOVO] Remove uma morada.
         */
        public function deleteAddress() {
            if (!Auth::isLoggedIn()) {
                header('Location: index.php?page=usuario&action=login');
                exit();
            }

            $userId = Auth::user()['id_usuario'];
            $enderecoId = (int)($_GET['id'] ?? 0);

            if ($enderecoId > 0) {
                $this->enderecoModel->delete($enderecoId, $userId);
                $_SESSION['form_message'] = ['text' => 'Morada removida com sucesso.', 'type' => 'success'];
            }

            header('Location: index.php?page=usuario&action=perfil');
            exit();
        }

        /**
         * [NOVO] Define uma morada como principal.
         */
        public function setPrincipalAddress() {
            if (!Auth::isLoggedIn()) {
                header('Location: index.php?page=usuario&action=login');
                exit();
            }

            $userId = Auth::user()['id_usuario'];
            $enderecoId = (int)($_GET['id'] ?? 0);

            if ($enderecoId > 0) {
                $this->enderecoModel->setPrincipal($userId, $enderecoId);
                $_SESSION['form_message'] = ['text' => 'Morada principal definida!', 'type' => 'success'];
            }

            header('Location: index.php?page=usuario&action=perfil');
            exit();
        }

        /**
         * [NOVO] Processa o upload da imagem de perfil.
        */
        public function uploadProfileImage() {
            if (!Auth::isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: index.php');
                exit();
            }

            $userId = Auth::user()['id_usuario'];
            $caminho_imagem_db = null;

            // Verifica se um ficheiro foi enviado e se não há erros
            if (isset($_FILES['imagem_perfil']) && $_FILES['imagem_perfil']['error'] === UPLOAD_ERR_OK) {
                
                // Define o diretório de destino. Certifique-se que esta pasta existe!
                $target_dir = "assets/images/perfil/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true); // Tenta criar a pasta
                }

                // Cria um nome de ficheiro único para evitar sobreposições
                $fileExtension = pathinfo($_FILES["imagem_perfil"]["name"], PATHINFO_EXTENSION);
                $nome_unico = $userId . '_' . uniqid() . '.' . $fileExtension;
                $target_file = $target_dir . $nome_unico;

                // Move o ficheiro do local temporário para o destino final
                if (move_uploaded_file($_FILES["imagem_perfil"]["tmp_name"], $target_file)) {
                    $caminho_imagem_db = $target_file;
                }
            }

            if ($caminho_imagem_db) {
                // Se o upload foi bem-sucedido, salva no banco
                $this->userModel->updateProfileImage($userId, $caminho_imagem_db);
                
                // [IMPORTANTE] Atualiza a sessão imediatamente
                $_SESSION['usuario']['imagem_perfil'] = $caminho_imagem_db; 
                
                $_SESSION['form_message'] = ['text' => 'Imagem de perfil atualizada!', 'type' => 'success'];
            } else {
                $_SESSION['form_message'] = ['text' => 'Erro ao fazer o upload da imagem.', 'type' => 'error'];
            }
            
            header('Location: index.php?page=usuario&action=perfil');
            exit();
        }
    }