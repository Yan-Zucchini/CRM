<?php
include '../config/db.php'; // Inclui a configuração do banco de dados

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Inicializa variáveis de pesquisa
$search = $_GET['search'] ?? '';
$column = $_GET['column'] ?? 'nome';

// Validação simples para evitar consultas em colunas inválidas
$valid_columns = ['id', 'nome', 'email', 'telefone', 'cpf', 'cep', 'cidade', 'estado'];
if (!in_array($column, $valid_columns)) {
    $column = 'nome';
}

// Busca os clientes no banco de dados com base na pesquisa
try {
    if ($search) {
        $query = $pdo->prepare("SELECT * FROM clientes WHERE $column LIKE :search ORDER BY id ASC");
        $query->execute(['search' => "%$search%"]);
    } else {
        $query = $pdo->query("SELECT * FROM clientes ORDER BY id ASC");
    }
    $clientes = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar clientes: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
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

        .list-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            overflow-x: auto; /* Adiciona a rolagem horizontal */
            max-width: 100%; /* Garante que não ultrapasse o container pai */
        }

        form {
            display: flex;
            margin-bottom: 20px;
        }

        input[type="text"], select, button {
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
            margin-right: 10px;
        }

        button {
            background-color: #1abc9c;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #16a085;
        }

        /* Tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
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
        }
    </style>
</head>
<body>
    <nav>
    <h1><a href="home.php" style="color: white; text-decoration: none;">CRM </a></h1>
        <ul>
            <li><a href="cadastrar_cliente.php">Cadastrar Cliente</a></li>
            <li><a href="cadastrar_servicos.php">Cadastrar Serviços</a></li>
            <li><a href="criar_ordem.php">Criar Ordem</a></li>
            <li><a href="../core/logout.php">Sair</a></li>
        </ul>
    </nav>
    <div class="content">
        <div class="header">
            <h1>Lista de Clientes</h1>
        </div>

        <div class="list-container">
            <!-- Formulário de Pesquisa -->
            <form method="GET" action="">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Pesquisar...">
                <select name="column">
                    <option value="nome" <?= $column === 'nome' ? 'selected' : '' ?>>Nome</option>
                    <option value="email" <?= $column === 'email' ? 'selected' : '' ?>>E-mail</option>
                    <option value="telefone" <?= $column === 'telefone' ? 'selected' : '' ?>>Telefone</option>
                    <option value="cpf" <?= $column === 'cpf' ? 'selected' : '' ?>>CPF</option>
                    <option value="cep" <?= $column === 'cep' ? 'selected' : '' ?>>CEP</option>
                    <option value="cidade" <?= $column === 'cidade' ? 'selected' : '' ?>>Cidade</option>
                    <option value="estado" <?= $column === 'estado' ? 'selected' : '' ?>>Estado</option>
                </select>
                <button type="submit">Pesquisar</button>
            </form>

            <!-- Tabela de Clientes -->
            <?php if (count($clientes) > 0): ?>
              <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>CPF</th>
                        <th>CEP</th>
                        <th>Rua</th>
                        <th>Bairro</th>
                        <th>Número</th>
                        <th>Complemento</th>
                        <th>Cidade</th>
                        <th>Estado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?= htmlspecialchars($cliente['id']) ?></td>
                            <td><?= htmlspecialchars($cliente['nome']) ?></td>
                            <td><?= htmlspecialchars($cliente['email']) ?></td>
                            <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                            <td><?= htmlspecialchars($cliente['cpf']) ?></td>
                            <td><?= htmlspecialchars($cliente['cep']) ?></td>
                            <td><?= htmlspecialchars($cliente['rua']) ?></td>
                            <td><?= htmlspecialchars($cliente['bairro']) ?></td>
                            <td><?= htmlspecialchars($cliente['numero']) ?></td>
                            <td><?= htmlspecialchars($cliente['complemento'] ?? '') ?></td>
                            <td><?= htmlspecialchars($cliente['cidade']) ?></td>
                            <td><?= htmlspecialchars($cliente['estado']) ?></td>
                            <td>
                                <a href="editar_cliente.php?id=<?= $cliente['id'] ?>">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
             </table>

            <?php else: ?>
                <p class="no-results">Nenhum cliente encontrado.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
