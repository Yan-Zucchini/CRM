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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #2c3e50;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
            padding: 10px;
        }

        .form-container {
            background-color: #34495e;
            padding: 20px;
            border-radius: 8px;
            width: 100%;
            max-width: 800px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #1abc9c;
            text-align: center;
            font-size: 20px;
        }

        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: white;
        }

        .form-container input, .form-container textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 14px;
            border: none;
            border-radius: 5px;
            background-color: #ecf0f1;
            color: #333;
            font-size: 14px;
        }

        .form-container input:focus, .form-container textarea:focus {
            outline: none;
            border: 2px solid #1abc9c;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #1abc9c;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #16a085;
        }

        .form-container p {
            font-size: 12px;
            text-align: center;
            color: #e74c3c;
            margin-top: 10px;
        }

        /* Botão de voltar */
        .back-button {
            margin-top: 20px;
            text-align: center;
        }

        .back-button button {
            background-color: #3498db;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .back-button button:hover {
            background-color: #2980b9;
        }
    </style>
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
            <input type="text" name="preco" id="preco" maxlength="10" required>

            <button type="submit">Cadastrar</button>
        </form>

        <div class="back-button">
            <a href="home.php">
                <button type="button">Voltar para o Menu</button>
            </a>
        </div>
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
