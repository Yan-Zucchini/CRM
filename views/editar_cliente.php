<?php
include '../config/db.php'; // Inclui a configuração do banco de dados

// Obtém o ID do cliente a ser editado
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID do cliente não fornecido.");
}

// Busca os dados do cliente no banco
try {
    $query = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
    $query->execute(['id' => $id]);
    $cliente = $query->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        die("Cliente não encontrado.");
    }
} catch (PDOException $e) {
    die("Erro ao buscar cliente: " . $e->getMessage());
}

// Atualiza o cliente ao salvar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = preg_replace('/\D/', '', $_POST['telefone']);
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);
    $cep = preg_replace('/\D/', '', $_POST['cep']);
    $rua = $_POST['rua'];
    $bairro = $_POST['bairro'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'] ?? null;
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    try {
        $query = $pdo->prepare("UPDATE clientes SET 
            nome = :nome,
            email = :email,
            telefone = :telefone,
            cpf = :cpf,
            cep = :cep,
            rua = :rua,
            bairro = :bairro,
            numero = :numero,
            complemento = :complemento,
            cidade = :cidade,
            estado = :estado
            WHERE id = :id
        ");

        $query->execute([
            'id' => $id,
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

        $mensagem = "Cliente atualizado com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar cliente: " . $e->getMessage();
    }
}

// Deleta o cliente ao clicar em deletar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    try {
        $query = $pdo->prepare("DELETE FROM clientes WHERE id = :id");
        $query->execute(['id' => $id]);
        header("Location: listar_clientes.php"); // Redireciona para a lista após deletar
        exit;
    } catch (PDOException $e) {
        die("Erro ao deletar cliente: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>
    <div class="form-container">
        <h2>Editar Cliente</h2>
        <?php
        if (isset($mensagem)) {
            echo "<p>$mensagem</p>";
        }
        ?>
        <form action="" method="POST">
            <input type="hidden" name="action" value="update">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($cliente['nome']) ?>" required>

            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" maxlength="15" value="<?= htmlspecialchars($cliente['telefone']) ?>" required>

            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" maxlength="14" value="<?= htmlspecialchars($cliente['cpf']) ?>" required>

            <label for="cep">CEP:</label>
            <input type="text" name="cep" id="cep" maxlength="9" value="<?= htmlspecialchars($cliente['cep']) ?>" required>

            <label for="rua">Rua:</label>
            <input type="text" name="rua" id="rua" value="<?= htmlspecialchars($cliente['rua']) ?>" required>

            <label for="bairro">Bairro:</label>
            <input type="text" name="bairro" id="bairro" value="<?= htmlspecialchars($cliente['bairro']) ?>" required>

            <label for="numero">Número:</label>
            <input type="text" name="numero" id="numero" value="<?= htmlspecialchars($cliente['numero']) ?>" required>

            <label for="complemento">Complemento:</label>
            <input type="text" name="complemento" id="complemento" value="<?= htmlspecialchars($cliente['complemento'] ?? '') ?>">

            <label for="cidade">Cidade:</label>
            <input type="text" name="cidade" id="cidade" value="<?= htmlspecialchars($cliente['cidade']) ?>" required>

            <label for="estado">Estado:</label>
            <input type="text" name="estado" id="estado" maxlength="2" value="<?= htmlspecialchars($cliente['estado']) ?>" required>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit">Salvar</button>
                <button type="submit" name="action" value="delete" style="background-color: red; color: white;">Deletar</button>
            </div>
        </form>
    </div>
</body>
</html>
