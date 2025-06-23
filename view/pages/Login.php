<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Aliexpresso</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f1ea; }
        .login-form { width: 320px; padding: 30px; border-radius: 8px; background-color: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .login-form h2 { text-align: center; color: #4a2c2a; }
        .login-form input { width: 100%; padding: 12px; margin-top: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .login-form button { width: 100%; padding: 12px; margin-top: 20px; border: none; background-color: #c7a17a; color: white; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .error-message { color: #d9534f; text-align: center; margin-top: 10px; }
        .login-link { text-align: center; margin-top: 15px; }
        .login-link a { color: #c7a17a; text-decoration: none; }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Login Aliexpresso</h2>
        <?php if (isset($_GET['sucesso'])): ?>
            <p style="color: green; text-align: center;">Cadastro realizado com sucesso!</p>
        <?php endif; ?>
        <form action="index.php?page=usuario&action=authenticate" method="post">
            <input type="email" name="email" placeholder="Seu e-mail" required>
            <input type="password" name="senha" placeholder="Sua senha" required>
            <button type="submit">Entrar</button>
        </form>
        <?php if (isset($_GET['erro'])): ?>
            <p class="error-message">E-mail ou senha inválidos.</p>
        <?php endif; ?>
        <div class="login-link">
            <p>Não possui uma conta? <a href="index.php?page=usuario&action=register">Cadastre-se</a></p>
        </div>
    </div>
</body>
</html>