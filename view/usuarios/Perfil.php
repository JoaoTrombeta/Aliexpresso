<?php
// view/usuarios/Perfil.php

// Define o modo de visualização (Perfil, Novo Endereço ou Editar Endereço)
// Se tivermos um endereço para editar, o modo é 'edit'.
// Se tivermos o parametro 'new', o modo é 'new'.
// Caso contrário, é 'profile'.
$viewMode = 'profile';
if (isset($enderecoParaEditar)) {
    $viewMode = 'edit';
} elseif (isset($_GET['new_address'])) {
    $viewMode = 'new';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - Aliexpresso</title>
    
    <!-- Seus CSS globais -->
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/admin.css"> 
    
    <!-- NOVO CSS DO PERFIL -->
    <link rel="stylesheet" href="assets/css/perfil.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php \Aliexpresso\Controller\PageController::renderHeader(); ?>

    <!-- Feedback de Erro/Sucesso -->
    <?php if (isset($_SESSION['form_message'])): ?>
        <div style="max-width: 1200px; margin: 1rem auto; text-align: center; padding: 10px; border-radius: 8px; 
             background-color: <?= $_SESSION['form_message']['type'] == 'success' ? '#d4edda' : '#f8d7da' ?>; 
             color: <?= $_SESSION['form_message']['type'] == 'success' ? '#155724' : '#721c24' ?>;">
            <?= htmlspecialchars($_SESSION['form_message']['text']) ?>
        </div>
        <?php unset($_SESSION['form_message']); ?>
    <?php endif; ?>

    <main class="perfil-dashboard">
        
        <!-- ================================
             COLUNA ESQUERDA (LISTA)
             ================================ -->
        <section class="coluna-esquerda">
            <div class="lista-header">
                <h2><i class="fas fa-map-marker-alt"></i> Meus Endereços</h2>
                <!-- Botão que muda a URL para abrir o form vazio na direita -->
                <a href="index.php?page=usuario&action=perfil&new_address=1" class="btn-novo-endereco">
                    <i class="fas fa-plus"></i> Novo
                </a>
            </div>

            <div class="lista-content">
                <?php if (empty($enderecos)): ?>
                    <div style="text-align: center; color: #888; padding-top: 2rem;">
                        <i class="far fa-folder-open" style="font-size: 3rem; margin-bottom: 10px;"></i>
                        <p>Nenhum endereço cadastrado.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($enderecos as $endereco): ?>
                        <!-- Adiciona classe 'ativo-edicao' se este for o endereço sendo editado agora -->
                        <?php $isEditingThis = ($viewMode === 'edit' && isset($enderecoParaEditar) && $enderecoParaEditar['id_endereco'] == $endereco['id_endereco']); ?>
                        
                        <div class="endereco-card <?= $endereco['is_principal'] ? 'principal' : '' ?> <?= $isEditingThis ? 'ativo-edicao' : '' ?>">
                            
                            <?php if ($endereco['is_principal']): ?>
                                <span class="badge-principal">Principal</span>
                            <?php endif; ?>

                            <div class="endereco-info">
                                <p class="rua"><?= htmlspecialchars($endereco['logradouro']) ?>, <?= htmlspecialchars($endereco['numero']) ?></p>
                                <p><?= htmlspecialchars($endereco['bairro']) ?> - <?= htmlspecialchars($endereco['cidade']) ?>/<?= htmlspecialchars($endereco['uf']) ?></p>
                                <p style="font-size: 0.85rem; color: #888;">CEP: <?= htmlspecialchars($endereco['cep']) ?></p>
                            </div>

                            <div class="endereco-acoes">
                                <a href="index.php?page=usuario&action=perfil&subview=edit&id=<?= $endereco['id_endereco'] ?>" class="btn-acao-sm">
                                    <i class="fas fa-pencil-alt"></i> Editar
                                </a>
                                
                                <?php if ($endereco['is_principal']): ?>
                                    <a href="index.php?page=usuario&action=unsetPrincipalAddress&id=<?= $endereco['id_endereco'] ?>" class="btn-acao-sm btn-star-remove" title="Remover status de Principal">
                                        <i class="fas fa-star"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?page=usuario&action=setPrincipalAddress&id=<?= $endereco['id_endereco'] ?>" class="btn-acao-sm" title="Definir como Principal">
                                        <i class="far fa-star"></i>
                                    </a>
                                    <a href="index.php?page=usuario&action=deleteAddress&id=<?= $endereco['id_endereco'] ?>" class="btn-acao-sm" style="color: #c62828; border-color: #ffcdd2;" onclick="return confirm('Tem certeza que deseja remover este endereço?');" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- ================================
             COLUNA DIREITA (DINÂMICA)
             ================================ -->
        <section class="coluna-direita">
            
            <?php if ($viewMode === 'profile'): ?>
                <!-- MODO 1: PERFIL (Padrão) -->
                <div class="perfil-view">
                    <div class="avatar-container">
                        <?php 
                            $userImage = \Aliexpresso\Helper\Auth::user()['imagem_perfil'] ?? null;
                            if (!empty($userImage)): 
                        ?>
                            <img src="<?= htmlspecialchars($userImage) ?>" alt="Foto">
                        <?php else: 
                            $userName = \Aliexpresso\Helper\Auth::user()['nome'];
                            $userInitial = mb_strtoupper(mb_substr($userName, 0, 1));
                        ?>
                            <span><?= $userInitial ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="perfil-info">
                        <h2><?= htmlspecialchars($user['nome']) ?></h2>
                        <p><i class="far fa-envelope"></i> <?= htmlspecialchars($user['email']) ?></p>
                    </div>

                    <div style="margin-top: 2rem; width: 100%; padding: 0 2rem; box-sizing: border-box;">
                        <button id="btnToggleUpload" class="btn-acao-sm" style="width: 100%; justify-content: center; padding: 10px;">
                            <i class="fas fa-camera"></i> Alterar Foto de Perfil
                        </button>

                        <!-- Formulário de Upload Oculto -->
                        <form action="index.php?page=usuario&action=uploadProfileImage" method="post" enctype="multipart/form-data" id="formUploadPerfil" style="display: none; margin-top: 10px; background: #f9f9f9; padding: 10px; border-radius: 6px;">
                            <input type="file" name="imagem_perfil" accept="image/*" class="form-control">
                            <button type="submit" class="btn-submit" style="margin-top: 5px; background-color: #5D4037;">Enviar</button>
                        </form>
                    </div>
                </div>

            <?php else: ?>
                <!-- MODO 2: FORMULÁRIO (Novo ou Editar) -->
                <div class="form-view">
                    <div class="form-header">
                        <h3><?= $viewMode === 'edit' ? 'Editar Endereço' : 'Novo Endereço' ?></h3>
                        <!-- Botão X: Volta para o modo perfil -->
                        <a href="index.php?page=usuario&action=perfil" class="btn-close">&times;</a>
                    </div>

                    <form action="index.php?page=usuario&action=saveAddress" method="post" id="form-endereco">
                        <?php if ($viewMode === 'edit'): ?>
                            <input type="hidden" name="id_endereco" value="<?= $enderecoParaEditar['id_endereco'] ?>">
                        <?php endif; ?>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1;">
                                <label>CEP</label>
                                <input type="text" id="cep" name="cep" class="form-control" value="<?= htmlspecialchars($enderecoParaEditar['cep'] ?? '') ?>" placeholder="00000-000" maxlength="9" required>
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label>Estado (UF)</label>
                                <input type="text" id="uf" name="uf" class="form-control" value="<?= htmlspecialchars($enderecoParaEditar['uf'] ?? '') ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Rua / Logradouro</label>
                            <input type="text" id="logradouro" name="logradouro" class="form-control" value="<?= htmlspecialchars($enderecoParaEditar['logradouro'] ?? '') ?>" readonly>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1;">
                                <label>Número</label>
                                <input type="text" id="numero" name="numero" class="form-control" value="<?= htmlspecialchars($enderecoParaEditar['numero'] ?? '') ?>" required>
                            </div>
                            <div class="form-group" style="flex: 2;">
                                <label>Bairro</label>
                                <input type="text" id="bairro" name="bairro" class="form-control" value="<?= htmlspecialchars($enderecoParaEditar['bairro'] ?? '') ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Cidade</label>
                            <input type="text" id="cidade" name="cidade" class="form-control" value="<?= htmlspecialchars($enderecoParaEditar['cidade'] ?? '') ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Complemento (Opcional)</label>
                            <input type="text" id="complemento" name="complemento" class="form-control" value="<?= htmlspecialchars($enderecoParaEditar['complemento'] ?? '') ?>" placeholder="Ex: Apto 101">
                        </div>

                        <button type="submit" class="btn-submit">Salvar Endereço</button>
                    </form>
                </div>
            <?php endif; ?>

        </section>
    </main>

    <?php \Aliexpresso\Controller\PageController::renderFooter(); ?>

    <!-- Scripts -->
    <script>
        // Toggle do Upload
        const btnToggle = document.getElementById('btnToggleUpload');
        const formUpload = document.getElementById('formUploadPerfil');
        if(btnToggle && formUpload) {
            btnToggle.addEventListener('click', () => {
                formUpload.style.display = formUpload.style.display === 'none' ? 'block' : 'none';
            });
        }

        // VIACEP Logic (Para preencher o endereço automaticamente)
        document.addEventListener('DOMContentLoaded', function() {
            const cepInput = document.getElementById('cep');
            if (cepInput) {
                // Máscara simples
                cepInput.addEventListener('input', (e) => {
                    let v = e.target.value.replace(/\D/g, '');
                    v = v.replace(/^(\d{5})(\d)/, '$1-$2');
                    e.target.value = v;
                });
                
                const buscarCep = () => {
                    const cep = cepInput.value.replace(/\D/g, '');
                    if (cep.length === 8) {
                        document.getElementById('logradouro').placeholder = "A carregar...";
                        document.body.style.cursor = "wait";
                        
                        fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(r => r.json())
                        .then(data => {
                            document.body.style.cursor = "default";
                            if(!data.erro) {
                                document.getElementById('logradouro').value = data.logradouro;
                                document.getElementById('bairro').value = data.bairro;
                                document.getElementById('cidade').value = data.localidade;
                                document.getElementById('uf').value = data.uf;
                                document.getElementById('numero').focus();
                            } else {
                                alert('CEP não encontrado.');
                                document.getElementById('logradouro').value = "";
                            }
                        })
                        .catch(() => {
                            document.body.style.cursor = "default";
                            alert('Erro na busca do CEP.');
                        });
                    }
                };

                cepInput.addEventListener('blur', buscarCep);
                // Se já tiver valor (edição), busca para garantir
                if (cepInput.value.length >= 8) { buscarCep(); }
            }
        });
    </script>
</body>
</html>