<?php
// Inicia a sessão para armazenar dados do utilizador temporariamente
session_start();

// Inclui o ficheiro de configuração da base de dados
require_once 'db_connection.php';

// Exibe erros para facilitar o debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se o formulário foi submetido via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém e limpa os dados do formulário
    $nome = trim($_POST["nome"]);
    $senha = $_POST["senha"];

    // Verifica se os campos estão preenchidos
    if (!empty($nome) && !empty($senha)) {
        // Conecta-se à base de dados
        $conn = new mysqli("localhost", "root", "", "trabalho_php");

        // Prepara uma consulta SQL segura
        $stmt = $conn->prepare("SELECT id, nome, palavra_passe, tipo_utilizador_id FROM utilizadores WHERE nome = ? AND estado = 'ativo'");
        $stmt->bind_param("s", $nome); // Liga o parâmetro do nome
        $stmt->execute(); // Executa a consulta
        $result = $stmt->get_result(); // Obtém os resultados

        // Verifica se encontrou algum utilizador
        if ($result->num_rows > 0) {
            // Obtém os dados do utilizador
            $user = $result->fetch_assoc();

            // Verifica se é o administrador
            if ($nome === 'admin' && $senha === 'admin' && $user['tipo_utilizador_id'] == 3) {
                // Inicia a sessão do administrador
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["tipo_utilizador"] = $user['tipo_utilizador_id'];
                $_SESSION["nome"] = $user['nome'];
                header("Location: index.php"); // Redireciona para a página principal
                exit();
            }

            // Verifica se é um funcionário
            if ($nome === 'funcionario' && $senha === 'funcionario' && $user['tipo_utilizador_id'] == 2) {
                // Inicia a sessão do funcionário
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["tipo_utilizador"] = $user['tipo_utilizador_id'];
                $_SESSION["nome"] = $user['nome'];
                header("Location: index.php");
                exit();
            }

            // Verifica a senha para outros utilizadores
            if (password_verify($senha, $user['palavra_passe'])) {
                // Inicia a sessão do utilizador comum
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["tipo_utilizador"] = $user['tipo_utilizador_id'];
                $_SESSION["nome"] = $user['nome'];
                header("Location: index.php");
                exit();
            } else {
                $erro = "Login inválido."; // Senha incorreta
            }
        } else {
            $erro = "Utilizador não encontrado ou inativo."; // Usuário não existe ou está inativo
        }

        // Fecha a consulta e a conexão
        $stmt->close();
        $conn->close();
    } else {
        $erro = "Por favor, preencha todos os campos."; // Campos vazios
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
        // Exibe a mensagem de erro, se existir
        if (!empty($erro)) {
            echo "<p class='error'>$erro</p>";
        }
        ?>
    </div>
</body>

</html>
