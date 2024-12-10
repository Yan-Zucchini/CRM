<?php
session_start();
include '../config/db.php'; // Inclui a configuração do banco de dados

if (isset($_POST['login'])) {
    $username = $_POST['user'];
    $password = $_POST['password'];

    // Prepara a consulta para buscar o usuário
    $query = $pdo->prepare("SELECT * FROM usuarios WHERE user = :user");
    $query->execute(['user' => $username]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Login bem-sucedido
        $_SESSION['user_id'] = $user['id']; // Armazena o ID do usuário
        $_SESSION['user_name'] = $user['user']; // Nome do usuário, se necessário
        header("Location: home.php"); // Redireciona para a página inicial
        exit;
    } else {
        // Login falhou
        $erro = "Credenciais inválidas.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CRM</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #2c3e50; /* Cor do fundo igual à barra lateral do CRM */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #34495e; /* Fundo com tom mais claro */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
            color: white; /* Texto branco para contraste */
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #1abc9c; /* Verde principal do tema */
        }

        .login-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .login-container label {
            font-size: 14px;
            color: white;
            margin: 10px 0 5px;
            align-self: flex-start;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: #ecf0f1; /* Fundo cinza claro */
            color: #333; /* Texto escuro */
        }

        .login-container input:focus {
            outline: none;
            border: 2px solid #1abc9c; /* Destaque verde ao focar */
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #1abc9c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-container button:hover {
            background-color: #16a085;
        }

        .login-container p {
            margin-top: 15px;
            font-size: 14px;
            color: #e74c3c; /* Vermelho para erros */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <label for="user">Usuário:</label>
            <input type="text" name="user" id="user" required>
            <label for="password">Senha:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit" name="login">Entrar</button>
        </form>
        <?php
        // Exibir mensagem de erro, se houver
        if (isset($erro)) {
            echo "<p>$erro</p>";
        }
        ?>
    </div>
</body>
</html>
