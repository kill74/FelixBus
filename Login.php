<?php
// Inicia a sessão para poder armazenar dados temporários do utilizador, como o ID e tipo de utilizador
session_start();

// Inclui o ficheiro que contém a configuração da base de dados
require_once 'db_connection.php';

// exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os valores do formulário e remove espaços extras com "trim"
    $nome = trim($_POST["nome"]);
    $senha = $_POST["senha"];

    // Verifica se os campos estão preenchidos
    if (!empty($nome) && !empty($senha)) {
        // Estabelece uma conexão com a base de dados
        $conn = new mysqli("localhost", "root", "", "trabalho_php");


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
    <link rel="stylesheet" href="styleLogin.css">
    <title>Login</title>
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
