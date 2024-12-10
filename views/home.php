<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard CRM</title>
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
            margin-bottom: 20px;
        }

        .card h2 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 16px;
            line-height: 1.5;
        }

        .button-container {
            margin-top: 20px;
        }

        .btn {
            background-color: #1abc9c;
            color: white;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin: 10px 0;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #16a085;
        }
    </style>
</head>
<body>
    <nav>
        <h1>CRM System</h1>
        <ul>
            <li><a href="cadastrar_cliente.php">Cadastrar Cliente</a></li>
            <li><a href="cadastrar_servicos.php">Cadastrar Serviços</a></li>
            <li><a href="criar_ordem.php">Criar Ordem</a></li>
            <li><a href="../core/logout.php">Sair</a></li>
        </ul>
    </nav>
    <div class="content">
        <div class="header">
            <h1>Bem-vindo ao CRM</h1>
        </div>
        <div class="card">
            <h2>Informações</h2>
            <p>Selecione uma das opções no menu ao lado para acessar as funcionalidades do sistema.</p>
        </div>

        <div class="button-container">
            <a href="listar_clientes.php" class="btn">Listar Clientes</a>
            <a href="listar_servicos.php" class="btn">Listar Serviços</a>
            <a href="listar_ordens.php" class="btn">Listar Ordens</a>
        </div>
    </div>
</body>
</html>
