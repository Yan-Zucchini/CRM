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
    <link rel="stylesheet" href="../assets/css/estilo.css">
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
    </div>
</body>
</html>
