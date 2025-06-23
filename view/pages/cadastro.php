<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Aliexpresso</title>
    <!-- Usando o mesmo estilo do login para consistência -->
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f1ea; padding: 20px; }
        .form-container { width: 360px; padding: 30px; border-radius: 8px; background-color: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .form-container h2 { text-align: center; color: #4a2c2a; }
        .form-container input { width: 100%; padding: 12px; margin-top: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-container button { width: 100%; padding: 12px; margin-top: 20px; border: none; background-color: #c7a17a; color: white; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .error-message, .success-message { text-align: center; margin-top: 10px; padding: 10px; border-radius: 4px; }
        .error-message { color: #d9534f; background-color: #f2dede;}
        .login-link { text-align: center; margin-top: 15px; }
        .login-link a { color: #c7a17a; text-decoration: none; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Crie sua Conta</h2>
        <form action="index.php?page=usuario&action=store" method="post">
            <input type="text" name="nome" placeholder="Seu nome completo" required>
            <input type="email" name="email" placeholder="Seu melhor e-mail" required>
            <input type="password" name="senha" placeholder="Crie uma senha forte" required>
            <button type="submit">Cadastrar</button>
        </form>
        <div class="login-link">
            <p>Já tem uma conta? <a href="index.php?page=usuario&action=login">Faça Login</a></p>
        </div>
    </div>
</body>
</html>