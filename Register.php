<?php
session_start();
// erros
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);


require_once 'db_connection.php';

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Debug: Imprimir dados recebidos
    echo "Dados recebidos:<br>";
    echo "Nome: " . $_POST["UserName"] . "<br>";
    
    // Obter os dados do formulário
    $nome = trim($_POST["UserName"]);
    $senha = trim($_POST["senha"]);
    $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);
    $tipo = 1;
    $estado = 'ativo';

    // Verificar se o nome de usuário já existe
    $stmt = $conn->prepare("SELECT id FROM utilizadores WHERE nome = ?");
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }
    
    $stmt->bind_param("s", $nome);
    if (!$stmt->execute()) {
        die("Erro ao executar a consulta: " . $stmt->error);
    }
    
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Erro: Este nome de usuário já está registado.";
    } else {
        // Preparar a consulta de inserção
        $stmt = $conn->prepare("INSERT INTO utilizadores (nome, palavra_passe, tipo_utilizador_id, estado) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            die("Erro na preparação da inserção: " . $conn->error);
        }

        $stmt->bind_param("ssis", $nome, $senha_hashed, $tipo, $estado);
        
        // Executar a inserção
        if ($stmt->execute()) {
            echo "Usuário registrado com sucesso! ID: " . $stmt->insert_id;
            header("Location: Login.php");
            exit();
        } else {
            echo "Erro ao registrar: " . $stmt->error;
        }
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
    <title>Registar</title>
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
            width: 100%;
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
        .redirect-login a {
            color: #1e90ff;
            text-decoration: none;
        }
        .redirect-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registar</h2>
        <form action="Register.php" method="POST">
            <input type="text" name="UserName" placeholder="Nome" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Registrar</button>
        </form>
        <div class="redirect-login">
            <p>Já possui uma conta? <a href="Login.php">Faça login aqui</a></p>
        </div>
    </div>
</body>
</html>