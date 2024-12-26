<?php
// Inicia a sessão para poder armazenar dados temporários do utilizador, como o ID e tipo de utilizador
session_start();

// Inclui o ficheiro que contém a configuração da base de dados
require_once 'db_connection.php';

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os valores do formulário e remove espaços extras com "trim"
    $nome = trim($_POST["nome"]);
    $senha = $_POST["senha"];

    // Verifica se os campos estão preenchidos
    if (!empty($nome) && !empty($senha)) {
        // Estabelece uma conexão com a base de dados
        $conn = new mysqli("localhost", "root", "", "trabalho_php");

        // Verifica se houve algum erro na conexão
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error); // Termina o script em caso de erro
        }

        // Prepara uma consulta SQL para evitar SQL Injection
        $stmt = $conn->prepare("SELECT id, nome, palavra_passe, tipo_utilizador_id FROM utilizadores WHERE nome = ? AND estado = 'ativo'");
        $stmt->bind_param("s", $nome); // Liga o parâmetro da consulta ao valor do nome
        $stmt->execute(); // Executa a consulta
        $result = $stmt->get_result(); // Obtém os resultados da consulta

        // Verifica se foi encontrado algum utilizador
        if ($result->num_rows > 0) {
            // Obtém os dados do utilizador encontrado
            $user = $result->fetch_assoc();

            // Verifica se o utilizador é o administrador
            if ($nome === 'admin' && $senha === 'admin' && $user['tipo_utilizador_id'] == 3) {
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["tipo_utilizador"] = $user['tipo_utilizador_id'];
                $_SESSION["nome"] = $user['nome'];
                header("Location: index.php"); // Redireciona para a página principal
                exit(); // Termina o script após o redirecionamento
            }

            // Verifica se o utilizador é um funcionário
            if ($nome === 'funcionario' && $senha === 'funcionario' && $user['tipo_utilizador_id'] == 2) {
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["tipo_utilizador"] = $user['tipo_utilizador_id'];
                $_SESSION["nome"] = $user['nome'];
                header("Location: index.php");
                exit();
            }

            // Verifica a senha para outros utilizadores utilizando "password_verify"
            if (password_verify($senha, $user['palavra_passe'])) {
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["tipo_utilizador"] = $user['tipo_utilizador_id'];
                $_SESSION["nome"] = $user['nome'];
                header("Location: index.php");
                exit();
            } else {
                $erro = "Login inválido."; // Mensagem de erro caso a senha esteja errada
            }
        } else {
            $erro = "Utilizador não encontrado ou inativo."; // Mensagem de erro caso não haja utilizador correspondente
        }

        // Fecha a consulta e a conexão com a base de dados
        $stmt->close();
        $conn->close();
    } else {
        $erro = "Por favor, preencha todos os campos."; // Mensagem de erro caso algum campo esteja vazio
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Estilo básico para melhorar a aparência da página */
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
        a {
            color: #00bfff;
            text-decoration: none;
        }
        .error {
            color: #ff0000;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <form action="Login.php" method="POST">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <br>
        <div class="visitor-section">
            <a href="Register.php" class="visitor-button">Registar se não tiver conta</a>
        </div>
        <br>
        <div class="visitor-section">
            <a href="index.php" class="visitor-button">Continuar como Visitante</a>
        </div>
        <?php
        // Mostra a mensagem de erro, se existir
        if (!empty($erro)) {
            echo "<p class='error'>$erro</p>";
        }
        ?>
    </div>
</body>
</html>
