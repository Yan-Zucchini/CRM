<?php
include '../config/db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $cliente_id = $_POST['cliente_id'];
    $servico_id = $_POST['servico_id'];
    $data_hora = $_POST['data_hora'];
    $status = $_POST['status'];
    $observacoes = $_POST['observacoes'] ?? null;

    // Insere os dados no banco
    $query = $pdo->prepare("INSERT INTO ordens_servico (cliente_id, servico_id, data_hora, status, observacoes)
                            VALUES (:cliente_id, :servico_id, :data_hora, :status, :observacoes)");
    try {
        $query->execute([
            'cliente_id' => $cliente_id,
            'servico_id' => $servico_id,
            'data_hora' => $data_hora,
            'status' => $status,
            'observacoes' => $observacoes
        ]);
        $mensagem = "Ordem de serviço cadastrada com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao cadastrar ordem de serviço: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Ordem de Serviço</title>
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

        .form-container select, .form-container input, .form-container textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 14px;
            border: none;
            border-radius: 5px;
            background-color: #ecf0f1;
            color: #333;
            font-size: 14px;
        }

        .form-container select:focus, .form-container input:focus, .form-container textarea:focus {
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
        <h2>Criar Ordem de Serviço</h2>
        <?php
        if (isset($mensagem)) {
            echo "<p>$mensagem</p>";
        }
        ?>
        <form action="" method="POST">
            <label for="cliente_id">Cliente:</label>
            <select name="cliente_id" id="cliente_id" required>
                <?php
                $clientes = $pdo->query("SELECT id, nome FROM clientes")->fetchAll();
                foreach ($clientes as $cliente) {
                    echo "<option value=\"{$cliente['id']}\">{$cliente['nome']}</option>";
                }
                ?>
            </select><br>

            <label for="servico_id">Serviço:</label>
            <select name="servico_id" id="servico_id" required>
                <?php
                $servicos = $pdo->query("SELECT id, nome FROM servicos")->fetchAll();
                foreach ($servicos as $servico) {
                    echo "<option value=\"{$servico['id']}\">{$servico['nome']}</option>";
                }
                ?>
            </select><br>

            <label for="data_hora">Data e Hora:</label>
            <input type="datetime-local" name="data_hora" id="data_hora" required><br>

            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="pendente">Pendente</option>
                <option value="em andamento">Em andamento</option>
                <option value="concluído">Concluído</option>
            </select><br>

            <label for="observacoes">Observações:</label>
            <textarea name="observacoes" id="observacoes"></textarea><br>

            <button type="submit">Cadastrar</button>
        </form>

        <div class="back-button">
            <a href="home.php">
                <button type="button">Voltar para a Menu</button>
            </a>
        </div>
    </div>
</body>
</html>
