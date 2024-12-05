<?php
require 'PHP/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["UserName"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
    $tipo = 2; 
    $estado = 'ativo';

    // Ajuste do SQL e tipos
    $stmt = $conn->prepare("INSERT INTO utilizadores (nome, palavra_passe, tipo_utilizador_id, estado) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $nome, $senha, $tipo, $estado);

    if ($stmt->execute()) {
        echo "<script>alert('Cadastro realizado com sucesso!');</script>";
        echo "<script>window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar: " . $conn->error . "');</script>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/styleRegister.css">
    <title>Cadastro</title>
</head>
<body>
    <div class="form-container">
        <h2>Registrar</h2>
        <form action="register.php" method="POST">
            <input type="text" name="UserName" placeholder="User Name" required>
            <input type="password" name="senha" placeholder="Password" required>
            <button type="submit">Registrar</button>
        </form>
        <div class="redirect-login">
            <p>Já possui uma conta? <a href="Login.php">Faça login aqui</a></p>
        </div>
    </div>
</body>
</html>
