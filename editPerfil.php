<?php
// Inicia a sessão
session_start();

// Inclui a ligação à base de dados
require_once 'db_connection.php';

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['user_id'])) {
    die("Erro: Necessita de estar autenticado para aceder a esta página.");
}

// Obtém o ID do utilizador da sessão
$user_id = $_SESSION['user_id'];

// Variável para mensagens de sucesso ou erro
$mensagem = "";
$classe_mensagem = ""; // Classe CSS para a mensagem

// Busca os dados do utilizador na base de dados
$sql = "SELECT nome, estado FROM utilizadores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Armazena os dados do utilizador
} else {
    die("Erro: Utilizador não encontrado.");
}

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $nome = htmlspecialchars($_POST['nome']);
    $estado = htmlspecialchars($_POST['estado']);

    // Atualiza os dados na base de dados
    $sql = "UPDATE utilizadores SET nome = ?, estado = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nome, $estado, $user_id);

    if ($stmt->execute()) {
        $mensagem = "Perfil atualizado com sucesso!";
        $classe_mensagem = "mensagem-sucesso"; // Classe para mensagem de sucesso
        // Atualiza os dados na variável $user
        $user['nome'] = $nome;
        $user['estado'] = $estado;
    } else {
        $mensagem = "Ocorreu um erro ao editar o seu perfil.";
        $classe_mensagem = "mensagem-erro"; // Classe para mensagem de erro
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="styleEditarPerfil.css">
</head>
<body>
    <div class="container">
        <h1>Editar Perfil</h1>

        <!-- Mostra mensagem de sucesso ou erro apenas após o envio do formulário -->
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <p class="<?= $classe_mensagem ?>"><?= $mensagem ?></p>
        <?php endif; ?>

        <!-- Formulário para editar o perfil -->
        <form method="POST">
            <label>Nome:</label>
            <input type="text" name="nome" value="<?= $user['nome'] ?>" required>

            <label>Estado:</label>
            <input type="text" name="estado" value="<?= $user['estado'] ?>" required>

            <button type="submit">Guardar Alterações</button>
        </form>

        <!-- Botão para voltar ao perfil -->
        <a href="perfil.php">
            <button>Voltar ao Perfil</button>
        </a>
    </div>
</body>
</html>