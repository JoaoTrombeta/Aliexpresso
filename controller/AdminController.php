<?php
    namespace Aliexpresso\Controller;

    use Aliexpresso\Helper\Auth;
    use Aliexpresso\Model\UsuarioModel;

    class AdminController {

        private $userModel;

        public function __construct() {
            $this->userModel = new UsuarioModel();
            // Protege todo o controller de uma vez. Nenhuma ação aqui pode ser
            // acessada por quem não for admin.
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
        public function users() {
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
            header('Location: index.php?page=admin&action=users');
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
            header('Location: index.php?page=admin&action=users');
            exit();
        }
    }
?>