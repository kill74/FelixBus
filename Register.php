<?php
session_start();
require_once 'db_connection.php'; // Certifique-se de que o caminho está correto

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos do formulário foram enviados
    if (isset($_POST['UserName']) && isset($_POST['senha'])) {
        // Recolher dados do formulário
        $nome = trim($_POST['UserName']); // Nome do utilizador
        $palavra_passe = trim($_POST['senha']); // Palavra-passe
        $tipo_utilizador_id = 1; // ID para 'cliente'

        // Verificar se o nome de utilizador já existe
        $stmt = $conn->prepare("SELECT id FROM utilizadores WHERE nome = ?");
        $stmt->bind_param("s", $nome); // "s" indica que o parâmetro é uma string
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['erro'] = "Impossível criar conta, tente de novo."; // Nome de utilizador já existe
        } else {
            // Encriptar a palavra-passe
            $palavra_passe_hash = password_hash($palavra_passe, PASSWORD_DEFAULT);

            // Inserir novo utilizador na base de dados
            $stmt = $conn->prepare("INSERT INTO utilizadores (nome, palavra_passe, tipo_utilizador_id) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $nome, $palavra_passe_hash, $tipo_utilizador_id); // "ssi" indica dois strings e um inteiro

            // Executar a inserção do utilizador
            if ($stmt->execute()) {
                $user_id = $stmt->insert_id; // Obter o ID do novo utilizador

                // Criar carteira para o novo utilizador
                $stmt = $conn->prepare("INSERT INTO carteira (utilizador_id, saldo) VALUES (?, 0.00)");
                $stmt->bind_param("i", $user_id); // "i" indica que o parâmetro é um inteiro

                // Executar a criação da carteira
                if ($stmt->execute()) {
                    $_SESSION['sucesso'] = "Utilizador e carteira criados com sucesso!";
                } else {
                    $_SESSION['erro'] = "Erro ao criar carteira.";
                }
            } else {
                $_SESSION['erro'] = "Erro ao criar utilizador.";
            }
        }
    } else {
        $_SESSION['erro'] = "Por favor, preencha todos os campos.";
    }

    // Redirecionar para evitar reenvio do formulário
    header("Location: Login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleRegister.css">
    <title>Registar</title>
</head>
<body>
    <div class="form-container">
        <h2>Registar</h2>

        <!-- Exibir mensagens de erro ou sucesso -->
        <?php
        if (isset($_SESSION['erro'])) {
            echo '<div class="mensagem-erro">' . $_SESSION['erro'] . '</div>';
            unset($_SESSION['erro']); // Limpar a mensagem após exibir
        }
        if (isset($_SESSION['sucesso'])) {
            echo '<div class="mensagem-sucesso">' . $_SESSION['sucesso'] . '</div>';
            unset($_SESSION['sucesso']); // Limpar a mensagem após exibir
        }
        ?>

        <!-- Formulário de registo -->
        <form action="" method="POST">
            <input type="text" name="UserName" placeholder="Nome" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Registar</button>
        </form>

        <!-- Link para login -->
        <div class="redirect-login">
            <p>Já tem uma conta? <a href="Login.php">Faça login aqui</a></p>
        </div>
    </div>
</body>
</html>