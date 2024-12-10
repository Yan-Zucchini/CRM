<?php
include '../config/db.php'; // Inclua a configuração do banco de dados

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $cep = $_POST['cep'];
    $rua = $_POST['rua'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $numero = $_POST['numero'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $complemento = $_POST['complemento'] ?? null;

    // Prepara a consulta SQL para inserir os dados na tabela clientes
    $query = $pdo->prepare("INSERT INTO clientes (cep, rua, bairro, cidade, estado, numero, nome, email, telefone, cpf, complemento)
                            VALUES (:cep, :rua, :bairro, :cidade, :estado, :numero, :nome, :email, :telefone, :cpf, :complemento)");

    try {
        // Executa a consulta com os dados do formulário
        $query->execute([
            'cep' => $cep,
            'rua' => $rua,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'estado' => $estado,
            'numero' => $numero,
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'cpf' => $cpf,
            'complemento' => $complemento
        ]);
        
        // Mensagem de sucesso
        $mensagem = "Cliente cadastrado com sucesso!";
    } catch (PDOException $e) {
        // Caso ocorra erro na execução da consulta
        $mensagem = "Erro ao cadastrar cliente: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
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

        .form-container input {
            width: 100%;
            padding: 8px;
            margin-bottom: 14px;
            border: none;
            border-radius: 5px;
            background-color: #ecf0f1;
            color: #333;
            font-size: 14px;
        }

        .form-container input:focus {
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

        /* Layout de duas colunas para os campos */
        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-row .form-group input {
            width: 100%;
        }

        .form-container input {
            margin-bottom: 12px;
        }

        /* Responsividade: em telas menores, os campos devem ocupar 100% da largura */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }

            .form-row .form-group {
                flex: none;
                width: 100%;
            }
        }

    </style>
</head>
<body>
    <div class="form-container">
        <h2>Cadastrar Cliente</h2>
        
        <?php
        if (isset($mensagem)) {
            echo "<p>$mensagem</p>";
        }
        ?>
        
        <form action="" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="cep">CEP:</label>
                    <input type="text" name="cep" id="cep" maxlength="9" required>
                </div>
                <div class="form-group">
                    <label for="rua">Rua:</label>
                    <input type="text" name="rua" id="rua" readonly required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="bairro">Bairro:</label>
                    <input type="text" name="bairro" id="bairro" readonly required>
                </div>
                <div class="form-group">
                    <label for="cidade">Cidade:</label>
                    <input type="text" name="cidade" id="cidade" readonly required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <input type="text" name="estado" id="estado" maxlength="2" readonly required>
                </div>
                <div class="form-group">
                    <label for="numero">Número:</label>
                    <input type="text" name="numero" id="numero" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" id="nome" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" name="email" id="email" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="text" name="telefone" id="telefone" maxlength="15" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF:</label>
                    <input type="text" name="cpf" id="cpf" maxlength="14" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="complemento">Complemento:</label>
                    <input type="text" name="complemento" id="complemento">
                </div>
            </div>

            <button type="submit">Cadastrar</button>
        </form>

        <!-- Botão de Voltar -->
        <div style="margin-top: 20px; text-align: center;">
            <a href="home.php">
                <button type="button" style="background-color: #3498db; padding: 10px 20px; border-radius: 5px; color: white; font-size: 16px; border: none; cursor: pointer;">
                    Voltar para o Menu
                </button>
            </a>
        </div>
    </div>

    <script>
        async function buscarCep(cep) {
            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                if (!response.ok) {
                    throw new Error('Erro ao buscar CEP.');
                }
                const data = await response.json();
                if (data.erro) {
                    alert('CEP não encontrado.');
                } else {
                    document.getElementById('rua').value = data.logradouro || '';
                    document.getElementById('bairro').value = data.bairro || '';
                    document.getElementById('cidade').value = data.localidade || '';
                    document.getElementById('estado').value = data.uf || '';
                }
            } catch (error) {
                alert('Erro ao buscar informações do CEP.');
            }
        }

        document.getElementById('cep').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value.replace(/(\d{5})(\d{3})/, '$1-$2').slice(0, 9);

            if (value.length === 8) {
                buscarCep(value);
            }
        });

        document.getElementById('cpf').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4').slice(0, 14);
        });

        document.getElementById('telefone').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3').slice(0, 15);
        });
    </script>
</body>
</html>
