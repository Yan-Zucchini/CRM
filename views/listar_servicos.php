<?php
include '../config/db.php'; // Inclui a configuração do banco de dados

// Inicializa variáveis de pesquisa
$search = $_GET['search'] ?? '';
$column = $_GET['column'] ?? 'nome';

// Validação simples para evitar consultas em colunas inválidas
$valid_columns = ['id', 'nome', 'descricao', 'preco'];
if (!in_array($column, $valid_columns)) {
    $column = 'nome';
}

// Busca os serviços no banco de dados com base na pesquisa
try {
    if ($search) {
        $query = $pdo->prepare("SELECT * FROM servicos WHERE $column LIKE :search ORDER BY id ASC");
        $query->execute(['search' => "%$search%"]);
    } else {
        $query = $pdo->query("SELECT * FROM servicos ORDER BY id ASC");
    }
    $servicos = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar serviços: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Serviços</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>
    <div class="list-container">
        <h2>Serviços Cadastrados</h2>

        <!-- Formulário de Pesquisa -->
        <form method="GET" action="" style="margin-bottom: 20px;">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Pesquisar...">
            <select name="column">
                <option value="nome" <?= $column === 'nome' ? 'selected' : '' ?>>Nome</option>
                <option value="descricao" <?= $column === 'descricao' ? 'selected' : '' ?>>Descrição</option>
                <option value="preco" <?= $column === 'preco' ? 'selected' : '' ?>>Preço</option>
            </select>
            <button type="submit">Pesquisar</button>
        </form>

        <!-- Tabela de Serviços -->
        <?php if (count($servicos) > 0): ?>
          <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Ações</th> <!-- Nova coluna -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($servicos as $servico): ?>
                    <tr>
                        <td><?= htmlspecialchars($servico['id']) ?></td>
                        <td><?= htmlspecialchars($servico['nome']) ?></td>
                        <td><?= htmlspecialchars($servico['descricao']) ?></td>
                        <td>R$ <?= number_format($servico['preco'], 2, ',', '.') ?></td>
                        <td>
                            <a href="editarservicos.php?id=<?= $servico['id'] ?>">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
         </table>

        <?php else: ?>
            <p>Nenhum serviço encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
            