<?php
include '../config/db.php'; // Inclui a configuração do banco de dados

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
            <label for="nome">Nome do Serviço:</label>
            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($servico['nome']) ?>" required>

            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" rows="4" required><?= htmlspecialchars($servico['descricao']) ?></textarea>

            <label for="preco">Preço (R$):</label>
            <input type="text" name="preco" id="preco" maxlength="10" value="<?= number_format($servico['preco'], 2, ',', '.') ?>" required>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit">Salvar</button>
                <button type="submit" name="action" value="delete" style="background-color: red; color: white;">Deletar</button>
            </div>
        </form>
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
