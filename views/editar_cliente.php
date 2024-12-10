<?php
include '../config/db.php'; // Inclui a configuração do banco de dados

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Obtém o ID do cliente a ser editado
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID do cliente não fornecido.");
}

// Busca os dados do cliente no banco
try {
    $query = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
    $query->execute(['id' => $id]);
    $cliente = $query->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        die("Cliente não encontrado.");
    }
} catch (PDOException $e) {
    die("Erro ao buscar cliente: " . $e->getMessage());
}

// Atualiza o cliente ao salvar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = preg_replace('/\D/', '', $_POST['telefone']);
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);
    $cep = preg_replace('/\D/', '', $_POST['cep']);
    $rua = $_POST['rua'];
    $bairro = $_POST['bairro'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'] ?? null;
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    try {
        $query = $pdo->prepare("UPDATE clientes SET 
            nome = :nome,
            email = :email,
            telefone = :telefone,
            cpf = :cpf,
            cep = :cep,
            rua = :rua,
            bairro = :bairro,
            numero = :numero,
            complemento = :complemento,
            cidade = :cidade,
            estado = :estado
            WHERE id = :id
        ");

        $query->execute([
            'id' => $id,
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'cpf' => $cpf,
            'cep' => $cep,
            'rua' => $rua,
            'bairro' => $bairro,
            'numero' => $numero,
            'complemento' => $complemento,
            'cidade' => $cidade,
            'estado' => $estado
        ]);

        $mensagem = "Cliente atualizado com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar cliente: " . $e->getMessage();
    }
}

// Deleta o cliente ao clicar em deletar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    try {
        $query = $pdo->prepare("DELETE FROM clientes WHERE id = :id");
        $query->execute(['id' => $id]);
        header("Location: listar_clientes.php"); // Redireciona para a lista após deletar
        exit;
    } catch (PDOException $e) {
        die("Erro ao deletar cliente: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
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

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
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
        <h2>Editar Cliente</h2>
        <?php
        if (isset($mensagem)) {
            echo "<p>$mensagem</p>";
        }
        ?>
        <form action="" method="POST">
            <input type="hidden" name="action" value="update">
            
            <div class="input-container">
                <div>
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($cliente['nome']) ?>" required>
                </div>

                <div>
                    <label for="email">E-mail:</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>
                </div>
            </div>

            <div class="input-container">
                <div>
                    <label for="telefone">Telefone:</label>
                    <input type="text" name="telefone" id="telefone" maxlength="15" value="<?= htmlspecialchars($cliente['telefone']) ?>" required>
                </div>

                <div>
                    <label for="cpf">CPF:</label>
                    <input type="text" name="cpf" id="cpf" maxlength="14" value="<?= htmlspecialchars($cliente['cpf']) ?>" required>
                </div>
            </div>

            <div class="input-container">
                <div>
                    <label for="cep">CEP:</label>
                    <input type="text" name="cep" id="cep" maxlength="9" value="<?= htmlspecialchars($cliente['cep']) ?>" required onblur="buscarEndereco()">
                </div>

                <div>
                    <label for="rua">Rua:</label>
                    <input type="text" name="rua" id="rua" value="<?= htmlspecialchars($cliente['rua']) ?>" required>
                </div>
            </div>

            <div class="input-container">
                <div>
                    <label for="bairro">Bairro:</label>
                    <input type="text" name="bairro" id="bairro" value="<?= htmlspecialchars($cliente['bairro']) ?>" required>
                </div>

                <div>
                    <label for="numero">Número:</label>
                    <input type="text" name="numero" id="numero" value="<?= htmlspecialchars($cliente['numero']) ?>" required>
                </div>
            </div>

            <div class="input-container">
                <div>
                    <label for="complemento">Complemento:</label>
                    <input type="text" name="complemento" id="complemento" value="<?= htmlspecialchars($cliente['complemento'] ?? '') ?>">
                </div>

                <div>
                    <label for="cidade">Cidade:</label>
                    <input type="text" name="cidade" id="cidade" value="<?= htmlspecialchars($cliente['cidade']) ?>" required>
                </div>
            </div>

            <div class="input-container">
                <div>
                    <label for="estado">Estado:</label>
                    <input type="text" name="estado" id="estado" maxlength="2" value="<?= htmlspecialchars($cliente['estado']) ?>" required>
                </div>
            </div>

            <div style="display: flex; gap: 20px; justify-content: space-between;">
                <button type="submit">Salvar</button>
                <button type="submit" name="action" value="delete">Deletar</button>
            </div>
        </form>
        
        <!-- Botão de Voltar -->
        <form action="listar_clientes.php" method="get">
            <button type="button" onclick="window.location.href='listar_clientes.php'">Voltar para a Lista</button>
        </form>
    </div>

    <script>
        function buscarEndereco() {
            var cep = document.getElementById('cep').value.replace(/\D/g, '');
            if (cep.length === 8) {
                var url = 'https://viacep.com.br/ws/' + cep + '/json/';
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.logradouro) {
                            document.getElementById('rua').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('estado').value = data.uf;
                        } else {
                            alert('CEP não encontrado.');
                        }
                    })
                    .catch(error => alert('Erro ao buscar endereço.'));
            }
        }
    </script>
</body>
</html>
