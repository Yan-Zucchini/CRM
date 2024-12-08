<?php
include '../config/db.php'; // Inclui a configuração do banco de dados


session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Inicializa a variável de mensagem
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário e remove formatação
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];

    // Remover pontos (milhares) e substituir a vírgula por ponto
    $preco = str_replace(['.', ','], ['', '.'], $_POST['preco']); // Converte para formato numérico correto (R$ 1.234,56 -> 1234.56)

    // Insere os dados no banco
    try {
        $query = $pdo->prepare("INSERT INTO servicos (nome, descricao, preco)
                                VALUES (:nome, :descricao, :preco)");
        $query->execute([
            'nome' => $nome,
            'descricao' => $descricao,
            'preco' => $preco
        ]);
        $mensagem = "Serviço cadastrado com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao cadastrar serviço: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Serviço</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>
    <div class="form-container">
        <h2>Cadastrar Serviço</h2>
        <?php
        if (isset($mensagem)) {
            echo "<p>$mensagem</p>";
        }
        ?>
        <form action="" method="POST">
            <label for="nome">Nome do Serviço:</label>
            <input type="text" name="nome" id="nome" required>

            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" rows="4" required></textarea>

            <label for="preco">Preço (R$):</label>
            <!-- Aqui formatamos o preço para exibição com vírgula -->
            <input type="text" name="preco" id="preco" maxlength="10" required>

            <button type="submit">Cadastrar</button>
        </form>
    </div>
    <script>
        // Máscara para preço
        document.getElementById('preco').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é número
            e.target.value = value.replace(/(\d)(\d{2})$/, '$1,$2').replace(/(?=(\d{3})+(\D))\B/g, '.'); // Formata o valor com ponto e vírgula
        });
    </script>
</body>
</html>
