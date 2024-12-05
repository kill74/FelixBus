<?php
require 'PHP/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
    $tipo = 2; 
    $estado = 'ativo';

    $stmt = $conn->prepare("INSERT INTO utilizadores (nome, palavra_passe, tipo_utilizador_id, estado) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $nome, $senha, $tipo, $estado);

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
    <link rel="stylesheet" href="style/stylePerfil.css">
    <title>Cadastro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e90ff, #00bfff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }
        .form-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        button {
            width: 100%;<?php
session_start(); 

//falta coisas aqui

?>
            padding: 10px;
            margin: 10px 0;
            border: none;
            background: #1e90ff;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #104e8b;
        }
        .redirect-login {
            margin-top: 15px;
            color: #00bfff;
        }
        .redirect-login a {
            text-decoration: none;
            color: #1e90ff;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        .redirect-login a:hover {
            color: #87cefa;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registrar</h2>
        <form action="register.php" method="POST">
            <input type="text" name="UserName" placeholder="User Name" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Registrar</button>
        </form>
        <div class="redirect-login">
            <p>Já possui uma conta? <a href="login.php">Faça login aqui</a></p>
        </div>
    </div>
</body>
</html>
