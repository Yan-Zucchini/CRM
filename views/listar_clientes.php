<?php
include '../config/db.php'; // Inclui a configuração do banco de dados

session_start();

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
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>
    <div class="list-container">
        <h2>Clientes Cadastrados</h2>

        <!-- Formulário de Pesquisa -->
        <form method="GET" action="" style="margin-bottom: 20px;">
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
                    <th>Ações</th> <!-- Nova coluna -->
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
            <p>Nenhum cliente encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
