<?php
include '../config/db.php'; // Inclui a configuração do banco de dados


session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Inicializa variáveis de pesquisa
$search = $_GET['search'] ?? '';
$column = $_GET['column'] ?? 'id';

// Validação simples para evitar consultas em colunas inválidas
$valid_columns = ['id', 'cliente_nome', 'servico_nome', 'data_hora', 'status'];
if (!in_array($column, $valid_columns)) {
    $column = 'id';
}

// Busca as ordens de serviço no banco de dados com base na pesquisa
try {
    if ($search) {
        $query = $pdo->prepare("
            SELECT ordens_servico.*, clientes.nome AS cliente_nome, servicos.nome AS servico_nome
            FROM ordens_servico
            JOIN clientes ON ordens_servico.cliente_id = clientes.id
            JOIN servicos ON ordens_servico.servico_id = servicos.id
            WHERE $column LIKE :search
            ORDER BY ordens_servico.id ASC
        ");
        $query->execute(['search' => "%$search%"]);
    } else {
        $query = $pdo->query("
            SELECT ordens_servico.*, clientes.nome AS cliente_nome, servicos.nome AS servico_nome
            FROM ordens_servico
            JOIN clientes ON ordens_servico.cliente_id = clientes.id
            JOIN servicos ON ordens_servico.servico_id = servicos.id
            ORDER BY ordens_servico.id ASC
        ");
    }
    $ordens = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar ordens de serviço: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Ordens de Serviço</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>
    <div class="list-container">
        <h2>Ordens de Serviço</h2>

        <!-- Formulário de Pesquisa -->
        <form method="GET" action="" style="margin-bottom: 20px;">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Pesquisar...">
            <select name="column">
                <option value="id" <?= $column === 'id' ? 'selected' : '' ?>>ID</option>
                <option value="cliente_nome" <?= $column === 'cliente_nome' ? 'selected' : '' ?>>Cliente</option>
                <option value="servico_nome" <?= $column === 'servico_nome' ? 'selected' : '' ?>>Serviço</option>
                <option value="data_hora" <?= $column === 'data_hora' ? 'selected' : '' ?>>Data e Hora</option>
                <option value="status" <?= $column === 'status' ? 'selected' : '' ?>>Status</option>
            </select>
            <button type="submit">Pesquisar</button>
        </form>

        <!-- Tabela de Ordens de Serviço -->
        <?php if (count($ordens) > 0): ?>
          <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Serviço</th>
                    <th>Data e Hora</th>
                    <th>Status</th>
                    <th>Observações</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ordens as $ordem): ?>
                    <tr>
                        <td><?= htmlspecialchars($ordem['id']) ?></td>
                        <td><?= htmlspecialchars($ordem['cliente_nome']) ?></td>
                        <td><?= htmlspecialchars($ordem['servico_nome']) ?></td>
                        <td><?= htmlspecialchars($ordem['data_hora']) ?></td>
                        <td><?= htmlspecialchars($ordem['status']) ?></td>
                        <td><?= htmlspecialchars($ordem['observacoes'] ?? '') ?></td>
                        <td>
                            <a href="editar_ordem.php?id=<?= $ordem['id'] ?>">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
            <p>Nenhuma ordem de serviço encontrada.</p>
        <?php endif; ?>
    </div>
</body>
</html>
