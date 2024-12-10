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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f5f7;
            color: #333;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        nav {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
        }

        nav h1 {
            font-size: 24px;
            margin-bottom: 30px;
            font-weight: bold;
        }

        nav ul {
            list-style: none;
            width: 100%;
        }

        nav ul li {
            width: 100%;
        }

        nav ul li a {
            display: block;
            width: 100%;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #34495e;
        }

        nav ul li a.active {
            background-color: #1abc9c;
        }

        .content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .header {
            background-color: white;
            padding: 15px 20px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 28px;
        }

        .card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            overflow-x: auto; /* Adiciona a rolagem horizontal */
            max-width: 100%; /* Garante que não ultrapasse o container pai */
        }

        .card h2 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 16px;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #34495e;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .search-container {
            margin-bottom: 20px;
        }

        .search-container input,
        .search-container select,
        .search-container button {
            padding: 10px;
            margin-right: 10px;
            font-size: 16px;
        }

        .search-container button {
            background-color: #1abc9c;
            color: white;
            border: none;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #16a085;
        }

        a {
            color: #1abc9c;
            font-weight: bold;
            text-decoration: none;
        }

        a:hover {
            color: #16a085;
        }

        .no-results {
            font-size: 18px;
            color: #e74c3c;
            margin-top: 20px;
    </style>
</head>
<body>
    <nav>
        <h1><a href="home.php" style="color: white; text-decoration: none;">CRM System</a></h1>
        <ul>
            <li><a href="cadastrar_cliente.php">Cadastrar Cliente</a></li>
            <li><a href="cadastrar_servicos.php">Cadastrar Serviços</a></li>
            <li><a href="criar_ordem.php">Criar Ordem</a></li>
            <li><a href="../core/logout.php">Sair</a></li>
        </ul>
    </nav>

    <div class="content">
        <div class="header">
            <h1>Ordens de Serviço</h1>
        </div>

        <div class="card">
            <div class="search-container">
                <!-- Formulário de Pesquisa -->
                <form method="GET" action="">
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
            </div>

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
    </div>
</body>
</html>
