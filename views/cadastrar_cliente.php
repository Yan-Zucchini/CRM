<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário e remove formatações
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = preg_replace('/\D/', '', $_POST['telefone']); // Remove tudo que não é número
    $cpf = preg_replace('/\D/', '', $_POST['cpf']); // Remove tudo que não é número
    $cep = preg_replace('/\D/', '', $_POST['cep']); // Remove tudo que não é número
    $rua = $_POST['rua'];
    $bairro = $_POST['bairro'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'] ?? null;
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    // Insere os dados no banco
    $query = $pdo->prepare("INSERT INTO clientes (nome, email, telefone, cpf, cep, rua, bairro, numero, complemento, cidade, estado)
                            VALUES (:nome, :email, :telefone, :cpf, :cep, :rua, :bairro, :numero, :complemento, :cidade, :estado)");
    try {
        $query->execute([
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'cpf' => $cpf,
            'cep' => $cep,
            'rua' => $rua,
            'bairro' => $bairro,
            'numero' => $numero,
            'complemento' => $complemento,
            'cidade' => $cidade,
            'estado' => $estado
        ]);
        $mensagem = "Cliente cadastrado com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao cadastrar cliente: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>
    <div class="form-container">
        <h2>Cadastrar Cliente</h2>
        <?php
        if (isset($mensagem)) {
            echo "<p>$mensagem</p>";
        }
        ?>
        <form action="" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" maxlength="15" required>

            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" maxlength="14" required>

            <label for="cep">CEP:</label>
            <input type="text" name="cep" id="cep" maxlength="9" required>

            <label for="rua">Rua:</label>
            <input type="text" name="rua" id="rua" required>

            <label for="bairro">Bairro:</label>
            <input type="text" name="bairro" id="bairro" required>

            <label for="numero">Número:</label>
            <input type="text" name="numero" id="numero" required>

            <label for="complemento">Complemento:</label>
            <input type="text" name="complemento" id="complemento">

            <label for="cidade">Cidade:</label>
            <input type="text" name="cidade" id="cidade" required>

            <label for="estado">Estado:</label>
            <input type="text" name="estado" id="estado" maxlength="2" required>

            <button type="submit">Cadastrar</button>
        </form>
    </div>
    <script>
        // Máscara para CPF
        document.getElementById('cpf').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é número
            e.target.value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4').slice(0, 14);
        });

        // Máscara para telefone
        document.getElementById('telefone').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é número
            e.target.value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3').slice(0, 15);
        });

        // Máscara para CEP
        document.getElementById('cep').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é número
            e.target.value = value.replace(/(\d{5})(\d{3})/, '$1-$2').slice(0, 9);
        });
    </script>
</body>
</html>
