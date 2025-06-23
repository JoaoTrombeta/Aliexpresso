<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Novo Produto - Aliexpresso</title>
    <style> /* Estilos básicos para o formulário */
        body { font-family: sans-serif; padding: 20px; }
        form { width: 300px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        select, input, button { width: 100%; padding: 10px; margin-top: 10px; box-sizing: border-box; }
    </style>
</head>
<body>
    <form action="index.php?page=produtos&action=store" method="post">
        <h2>Criar Novo Produto</h2>
        
        <label for="tipo">Tipo de Produto:</label>
        <select id="tipo" name="tipo" required>
            <option value="graos">Café em Grãos</option>
            <option value="capsula">Cápsula de Café</option>
        </select>
        
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" required>
        
        <button type="submit">Criar Produto</button>
    </form>
</body>
</html>