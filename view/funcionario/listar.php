<?php include 'view/layout/header.php'; ?>

<h2>Funcionários</h2>

<a href="?controller=funcionario&action=novo">Cadastrar novo funcionário</a>

<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($funcionarios as $f): ?>
        <tr>
            <td><?php echo $f['nome']; ?></td>
            <td><?php echo $f['email']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'view/layout/footer.php'; ?>