<?php
include '../config/db.php'; // Inclui a configuração do banco de dados


session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Obtém o ID da ordem de serviço a ser editada
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID da ordem de serviço não fornecido.");
}

// Busca os dados da ordem de serviço no banco
try {
    $query = $pdo->prepare("SELECT * FROM ordens_servico WHERE id = :id");
    $query->execute(['id' => $id]);
    $ordem = $query->fetch(PDO::FETCH_ASSOC);

    if (!$ordem) {
        die("Ordem de serviço não encontrada.");
    }
} catch (PDOException $e) {
    die("Erro ao buscar ordem de serviço: " . $e->getMessage());
}

// Atualiza a ordem ao salvar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $cliente_id = $_POST['cliente_id'];
    $servico_id = $_POST['servico_id'];
    $data_hora = $_POST['data_hora'];
    $status = $_POST['status'];
    $observacoes = $_POST['observacoes'] ?? null;

    try {
        $query = $pdo->prepare("UPDATE ordens_servico SET 
            cliente_id = :cliente_id,
            servico_id = :servico_id,
            data_hora = :data_hora,
            status = :status,
            observacoes = :observacoes
            WHERE id = :id
        ");

        $query->execute([
            'id' => $id,
            'cliente_id' => $cliente_id,
            'servico_id' => $servico_id,
            'data_hora' => $data_hora,
            'status' => $status,
            'observacoes' => $observacoes
        ]);

        $mensagem = "Ordem de serviço atualizada com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar ordem de serviço: " . $e->getMessage();
    }
}

// Deleta a ordem ao clicar em deletar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    try {
        $query = $pdo->prepare("DELETE FROM ordens_servico WHERE id = :id");
        $query->execute(['id' => $id]);
        header("Location: listar_ordens.php"); // Redireciona para a lista após deletar
        exit;
    } catch (PDOException $e) {
        die("Erro ao deletar ordem de serviço: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ordem de Serviço</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>
    <div class="form-container">
        <h2>Editar Ordem de Serviço</h2>
        <?php
        if (isset($mensagem)) {
            echo "<p>$mensagem</p>";
        }
        ?>
        <form action="" method="POST">
            <input type="hidden" name="action" value="update">

            <label for="cliente_id">Cliente:</label>
            <select name="cliente_id" id="cliente_id" required>
                <?php
                $clientes = $pdo->query("SELECT id, nome FROM clientes")->fetchAll();
                foreach ($clientes as $cliente) {
                    $selected = $cliente['id'] == $ordem['cliente_id'] ? 'selected' : '';
                    echo "<option value=\"{$cliente['id']}\" $selected>{$cliente['nome']}</option>";
                }
                ?>
            </select><br>

            <label for="servico_id">Serviço:</label>
            <select name="servico_id" id="servico_id" required>
                <?php
                $servicos = $pdo->query("SELECT id, nome FROM servicos")->fetchAll();
                foreach ($servicos as $servico) {
                    $selected = $servico['id'] == $ordem['servico_id'] ? 'selected' : '';
                    echo "<option value=\"{$servico['id']}\" $selected>{$servico['nome']}</option>";
                }
                ?>
            </select><br>

            <label for="data_hora">Data e Hora:</label>
            <input type="datetime-local" name="data_hora" id="data_hora" value="<?= date('Y-m-d\TH:i', strtotime($ordem['data_hora'])) ?>" required><br>

            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="pendente" <?= $ordem['status'] == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="em andamento" <?= $ordem['status'] == 'em andamento' ? 'selected' : '' ?>>Em andamento</option>
                <option value="concluído" <?= $ordem['status'] == 'concluído' ? 'selected' : '' ?>>Concluído</option>
            </select><br>

            <label for="observacoes">Observações:</label>
            <textarea name="observacoes" id="observacoes"><?= htmlspecialchars($ordem['observacoes'] ?? '') ?></textarea><br>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit">Salvar</button>
                <button type="submit" name="action" value="delete" style="background-color: red; color: white;">Deletar</button>
            </div>
        </form>
    </div>
</body>
</html>
