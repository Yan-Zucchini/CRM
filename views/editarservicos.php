<?php
include '../config/db.php'; // Inclui a configuração do banco de dados

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Obtém o ID do serviço a ser editado
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID do serviço não fornecido.");
}

// Busca os dados do serviço no banco
try {
    $query = $pdo->prepare("SELECT * FROM servicos WHERE id = :id");
    $query->execute(['id' => $id]);
    $servico = $query->fetch(PDO::FETCH_ASSOC);

    if (!$servico) {
        die("Serviço não encontrado.");
    }
} catch (PDOException $e) {
    die("Erro ao buscar serviço: " . $e->getMessage());
}

// Atualiza o serviço ao salvar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = str_replace(['.', ','], ['', '.'], $_POST['preco']); // Remove pontos e converte vírgula para ponto

    try {
        $query = $pdo->prepare("UPDATE servicos SET 
            nome = :nome,
            descricao = :descricao,
            preco = :preco
            WHERE id = :id
        ");

        $query->execute([
            'id' => $id,
            'nome' => $nome,
            'descricao' => $descricao,
            'preco' => $preco
        ]);

        $mensagem = "Serviço atualizado com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar serviço: " . $e->getMessage();
    }
}

// Deleta o serviço ao clicar em deletar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    try {
        $query = $pdo->prepare("DELETE FROM servicos WHERE id = :id");
        $query->execute(['id' => $id]);
        header("Location: listar_servicos.php"); // Redireciona para a lista após deletar
        exit;
    } catch (PDOException $e) {
        die("Erro ao deletar serviço: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Serviço</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
    <style>
        /* Resetando margens e padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f5f7;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            color: #2c3e50; /* cor do título */
        }

        label {
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
            color: #34495e; /* cor do texto dos campos */
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        textarea {
            resize: vertical;
        }

        /* Ajustando layout para os campos de forma mais horizontal */
        .input-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .input-container > div {
            flex: 1;
            min-width: 200px;
        }

        /* Ajustando botões */
        button {
            width: 48%;
            padding: 12px;
            background-color: #16a085; /* verde para salvar */
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1abc9c;
        }

        button[type="submit"]:nth-child(2) {
            background-color: #e74c3c; /* vermelho para deletar */
        }

        button[type="submit"]:nth-child(2):hover {
            background-color: #c0392b;
        }

        button[type="button"] {
            background-color: #3498db; /* azul para voltar */
            width: 100%;
            margin-top: 20px;
        }

        button[type="button"]:hover {
            background-color: #2980b9;
        }

        p {
            text-align: center;
            font-size: 18px;
            color: #2ecc71; /* cor verde para a mensagem de sucesso */
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Editar Serviço</h2>
        <?php
        if (isset($mensagem)) {
            echo "<p>$mensagem</p>";
        }
        ?>
        <form action="" method="POST">
            <input type="hidden" name="action" value="update">
            
            <div class="input-container">
                <div>
                    <label for="nome">Nome do Serviço:</label>
                    <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($servico['nome']) ?>" required>
                </div>
            </div>

            <div class="input-container">
                <div>
                    <label for="descricao">Descrição:</label>
                    <textarea name="descricao" id="descricao" rows="4" required><?= htmlspecialchars($servico['descricao']) ?></textarea>
                </div>
            </div>

            <div class="input-container">
                <div>
                    <label for="preco">Preço (R$):</label>
                    <input type="text" name="preco" id="preco" value="<?= number_format($servico['preco'], 2, ',', '.') ?>" required>
                </div>
            </div>

            <div style="display: flex; gap: 20px; justify-content: space-between;">
                <button type="submit">Salvar</button>
                <button type="submit" name="action" value="delete">Deletar</button>
            </div>
        </form>
        
        <!-- Botão de Voltar -->
        <form action="listar_servicos.php" method="get">
            <button type="button" onclick="window.location.href='listar_servicos.php'">Voltar para a Lista</button>
        </form>
    </div>
</body>
</html>
