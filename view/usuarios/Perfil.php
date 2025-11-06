<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - Aliexpresso</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/admin.css"> <!-- Reutiliza estilos de formulário e tabelas do admin -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Estilos específicos para a página de perfil */
        body { 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
            margin: 0; 
            background-color: var(--cor-fundo, #F4F1EA); 
        }
        main.perfil-container { 
            flex-grow: 1;
            max-width: 900px;
            margin: 2rem auto;
            padding: 0; /* O padding será interno dos cards */
        }
        main h2 {
            text-align: center;
            color: var(--cor-primaria, #5D4037);
            margin-bottom: 2rem;
        }
        .form-group input[readonly] {
            background-color: #f0f0f0;
            cursor: not-allowed;
            color: #777;
        }
        /* Estilos para as mensagens de feedback */
        .form-message {
            padding: 1rem;
            margin: 0 2rem 1rem 2rem;
            border-radius: 8px;
            text-align: center;
            font-size: 0.95rem;
        }
        .form-message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        /* Reutiliza o estilo do admin.css */
        .form-container, .table-container {
            margin: 2rem;
        }
        /* Estilos para os cartões de morada */
        .endereco-card {
            border: 1px solid var(--admin-border-color, #e0e0e0);
            border-radius: 8px;
            padding: 1.5rem;
            position: relative;
        }
        .endereco-card.principal {
            border-color: var(--admin-accent-edit, #a67c52);
            border-width: 2px;
            background-color: #faf8f5;
        }
        .endereco-card .badge-principal {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background-color: var(--admin-accent-edit, #a67c52);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .endereco-card p {
            margin: 0.25rem 0;
            color: #333;
        }
        .endereco-card p.morada-completa {
            font-weight: 500;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .endereco-card .morada-acoes {
            margin-top: 1rem;
            display: flex;
            gap: 0.5rem;
        }
        .morada-acoes .btn-principal {
            background-color: #28a745;
            color: white;
        }
        .perfil-imagem-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .avatar-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: var(--cor-primaria, #5D4037);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: bold;
            overflow: hidden; /* Garante que a imagem não saia do círculo */
        }
        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        #imagePreview { /* Estilo da pré-visualização do upload */
            max-width: 120px;
            max-height: 120px;
            border-radius: 50%;
            border: 2px dashed #ccc;
            padding: 5px;
            display: block;
        }
        #imagePreview.hidden {
            display: none;
        }
    </style>
</head>
<body>
    <?php \Aliexpresso\Controller\PageController::renderHeader(); ?>

    <main class="perfil-container">
        <h2>Minha Conta</h2>

        <!-- Mensagens de Feedback -->
        <?php if (isset($_SESSION['form_message'])): ?>
            <div class="form-message <?= $_SESSION['form_message']['type'] ?>">
                <?= htmlspecialchars($_SESSION['form_message']['text']) ?>
            </div>
            <?php unset($_SESSION['form_message']); ?>
        <?php endif; ?>

        <!-- [NOVO] Secção de Imagem de Perfil -->
        <div class="form-container">
            <h3>Imagem de Perfil</h3>
            <form action="index.php?page=usuario&action=uploadProfileImage" method="post" enctype="multipart/form-data">
                <div class="perfil-imagem-container">
                    <!-- Mostra a imagem atual ou a inicial -->
                    <div class="avatar-preview" title="Imagem de Perfil Atual">
                        <?php 
                            $userImage = \Aliexpresso\Helper\Auth::user()['imagem_perfil'] ?? null;
                            if (!empty($userImage)): 
                        ?>
                            <img src="<?= htmlspecialchars($userImage) ?>" alt="Imagem de Perfil">
                        <?php else: 
                            $userName = \Aliexpresso\Helper\Auth::user()['nome'];
                            $userInitial = mb_strtoupper(mb_substr($userName, 0, 1));
                        ?>
                            <span><?= $userInitial ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pré-visualização do novo upload -->
                    <img id="imagePreview" src="#" alt="Nova imagem" class="hidden"/>
                </div>

                <div class="form-group">
                    <label for="imagem_perfil_input">Carregar nova imagem:</label>
                    <input type="file" id="imagem_perfil_input" name="imagem_perfil" accept="image/png, image/jpeg, image/webp" class="form-control" required>
                    <small>A imagem será redimensionada e cortada para caber num círculo.</small>
                </div>
                <button type="submit" class="btn-primary">Salvar Imagem</button>
            </form>
        </div>

        <!-- Secção 1: Lista de Moradas Guardadas -->
        <div class="table-container">
            <h2>Minhas Moradas</h2>
            <?php if (empty($enderecos)): ?>
                <p>Nenhuma morada guardada. Adicione uma abaixo.</p>
            <?php else: ?>
                <?php foreach ($enderecos as $endereco): ?>
                    <div class="endereco-card <?= $endereco['is_principal'] ? 'principal' : '' ?>">
                        <?php if ($endereco['is_principal']): ?>
                            <span class="badge-principal">Principal</span>
                        <?php endif; ?>

                        <p class="morada-completa"><?= htmlspecialchars($endereco['logradouro']) ?>, <?= htmlspecialchars($endereco['numero']) ?></p>
                        <p><?= htmlspecialchars($endereco['complemento']) ? htmlspecialchars($endereco['complemento']) . ' - ' : '' ?><?= htmlspecialchars($endereco['bairro']) ?></p>
                        <p><?= htmlspecialchars($endereco['cidade']) ?> - <?= htmlspecialchars($endereco['uf']) ?></p>
                        <p>CEP: <?= htmlspecialchars($endereco['cep']) ?></p>
                        
                        <div class="morada-acoes">
                            <!-- Botão de Editar -->
                            <a href="index.php?page=usuario&action=perfil&edit_id=<?= $endereco['id_endereco'] ?>" class="btn-edit">Editar</a>
                            
                            <!-- Botão de Remover -->
                            <a href="index.php?page=usuario&action=deleteAddress&id=<?= $endereco['id_endereco'] ?>" class="btn-delete" onclick="return confirm('Tem a certeza que quer remover esta morada?');">Remover</a>
                            
                            <!-- Botão Definir como Principal (só aparece se não for a principal) -->
                            <?php if (!$endereco['is_principal']): ?>
                                <a href="index.php?page=usuario&action=setPrincipalAddress&id=<?= $endereco['id_endereco'] ?>" class="btn-edit btn-principal">Definir como Principal</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Secção 2: Formulário de Adicionar/Editar Morada -->
        <div class="form-container">
            <h2><?= isset($enderecoParaEditar) ? 'Editar Morada' : 'Adicionar Nova Morada' ?></h2>

            <form action="index.php?page=usuario&action=saveAddress" method="post" id="form-endereco">
                <!-- Se estiver a editar, envia o ID -->
                <?php if (isset($enderecoParaEditar)): ?>
                    <input type="hidden" name="id_endereco" value="<?= $enderecoParaEditar['id_endereco'] ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="cep">CEP:</label>
                    <input type="text" id="cep" name="cep" value="<?= htmlspecialchars($enderecoParaEditar['cep'] ?? '') ?>" maxlength="9" placeholder="Digite seu CEP (apenas números)" required>
                    <small>Ao preencher o CEP, a morada será preenchida automaticamente.</small>
                </div>
                <div class="form-group">
                    <label for="logradouro">Rua (Logradouro):</label>
                    <input type="text" id="logradouro" name="logradouro" value="<?= htmlspecialchars($enderecoParaEditar['logradouro'] ?? '') ?>" readonly required>
                </div>
                <div class="form-group">
                    <label for="bairro">Bairro:</label>
                    <input type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($enderecoParaEditar['bairro'] ?? '') ?>" readonly required>
                </div>
                <div class="form-group">
                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($enderecoParaEditar['cidade'] ?? '') ?>" readonly required>
                </div>
                <div class="form-group">
                    <label for="uf">Estado (UF):</label>
                    <input type="text" id="uf" name="uf" value="<?= htmlspecialchars($enderecoParaEditar['uf'] ?? '') ?>" readonly required>
                </div>
                <div class="form-group">
                    <label for="numero">Número:</label>
                    <input type="text" id="numero" name="numero" value="<?= htmlspecialchars($enderecoParaEditar['numero'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="complemento">Complemento (Opcional):</label>
                    <input type="text" id="complemento" name="complemento" value="<?= htmlspecialchars($enderecoParaEditar['complemento'] ?? '') ?>" placeholder="Ex: Apto 101, Bloco B">
                </div>
                
                <button type="submit" class="btn-primary">Salvar Morada</button>
                <?php if (isset($enderecoParaEditar)): ?>
                    <a href="index.php?page=usuario&action=perfil" class="btn-secondary">Cancelar Edição</a>
                <?php endif; ?>
            </form>
        </div>
    </main>

    <?php \Aliexpresso\Controller\PageController::renderFooter(); ?>

    <!-- JavaScript do VIACEP -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cepInput = document.getElementById('cep');
            if (cepInput) {
                // [NOVO] Adiciona um 'input' event listener para formatar o CEP (XXXXX-XXX)
                cepInput.addEventListener('input', formatarCEP);
                
                // O 'blur' (perda de foco) é o que ativa a busca na API
                cepInput.addEventListener('blur', preencherMorada);
            }
            
            // [NOVO] Se estiver a editar, aciona a busca do VIACEP assim que a página carrega
            // para preencher os campos readonly caso o JS não tenha corrido.
            if (cepInput.value.length >= 8) {
                preencherMorada();
            }

            function formatarCEP(e) {
                let valor = e.target.value.replace(/\D/g, ''); // Remove tudo exceto números
                valor = valor.replace(/^(\d{5})(\d)/, '$1-$2'); // Adiciona o hífen
                e.target.value = valor;
            }

            function preencherMorada() {
                const cep = cepInput.value.replace(/\D/g, ''); // Remove o hífen e outros caracteres
                
                if (cep.length !== 8) {
                    return;
                }

                setCamposStatus(true, 'A carregar...');

                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => {
                        if (!response.ok) { throw new Error('Falha na rede'); }
                        return response.json();
                    })
                    .then(data => {
                        // Se o utilizador já tiver preenchido o número (no modo de edição),
                        // guarda o valor para o repor.
                        const numeroAtual = document.getElementById('numero').value;
                        
                        setCamposStatus(false); // Limpa o "A carregar..."

                        if (data.erro) {
                            alert('CEP não encontrado. Por favor, verifique o número.');
                            limparFormulario(true); // Limpa o formulário, mas mantém o CEP digitado
                        } else {
                            // Preenche os campos
                            document.getElementById('logradouro').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('uf').value = data.uf;
                            
                            // Repõe o número se ele já existia
                            if(numeroAtual) {
                                document.getElementById('numero').value = numeroAtual;
                            } else {
                                // Foca no campo de número para o utilizador preencher
                                document.getElementById('numero').focus();
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar CEP:', error);
                        setCamposStatus(false);
                        limparFormulario(true);
                        alert('Não foi possível buscar o CEP. Tente novamente.');
                    });
            }

            function setCamposStatus(readonly, placeholderText = '') {
                // Não queremos bloquear o campo de número
                document.getElementById('logradouro').readOnly = readonly;
                document.getElementById('bairro').readOnly = readonly;
                document.getElementById('cidade').readOnly = readonly;
                document.getElementById('uf').readOnly = readonly;

                document.getElementById('logradouro').value = placeholderText;
                document.getElementById('bairro').value = placeholderText;
                document.getElementById('cidade').value = placeholderText;
                document.getElementById('uf').value = placeholderText;
            }

            function limparFormulario(manterCEP = false) {
                if (!manterCEP) {
                    document.getElementById('cep').value = '';
                }
                setCamposStatus(false, ''); // Limpa os campos readonly
                document.getElementById('numero').value = '';
                document.getElementById('complemento').value = '';
            }
        });
    </script>
    <script src="assets/js/image-preview.js"></script>
    <script>
        // Ativa a pré-visualização para o novo formulário
        setupImagePreview('imagem_perfil_input', 'imagePreview');
    </script>
</body>
</html>