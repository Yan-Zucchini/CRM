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
        $_SESSION['user'] = $user['user'];
        header("Location: home.php"); // Redireciona para a página inicial
        exit;
    } else {
        // Login falhou
        $erro = "Usuário ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
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
            echo "<p style='color: red;'>$erro</p>";
        }
        ?>
    </div>
</body>
</html>
