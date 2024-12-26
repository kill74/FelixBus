<?php
// Inicia a sessão
session_start();

// Ativa a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui a ligação à base de dados
require_once 'db_connection.php';

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['user_id'])) {
    die("Erro: Utilizador não autenticado.");
}

// Obtém o ID do utilizador da sessão
$user_id = $_SESSION['user_id'];

// Inicializa uma variável para mensagens
$mensagem = "";

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os valores enviados pelo formulário
    $nome = $_POST['nome'];
    $estado = $_POST['estado'];

    // Atualiza os dados na base de dados
    $sql = "UPDATE utilizadores 
            SET nome = '$nome', estado = '$estado'
            WHERE id = $user_id";

    if ($conn->query($sql) === TRUE) {
        $mensagem = "Perfil atualizado com sucesso!";
    } else {
        $mensagem = "Erro ao atualizar o perfil: " . $conn->error;
    }
} else {
    // Busca os dados atuais do utilizador para preencher o formulário
    $sql = "SELECT nome, estado FROM utilizadores WHERE id = $user_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        die("Erro: Utilizador não encontrado.");
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
</head>
<body>
    <h1>Editar Perfil</h1>

    <!-- Mostra a mensagem de sucesso ou erro -->
    <?php if ($mensagem): ?>
        <p><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <!-- Formulário para edição do perfil -->
    <form method="POST">
        <label>Nome:</label><br>
        <input type="text" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" required><br><br>

        <label>Estado:</label><br>
        <input type="text" name="estado" value="<?= htmlspecialchars($user['estado']) ?>" required><br><br>

        <button type="submit">Guardar Alterações</button>
    </form>

    <!-- Botão para voltar ao perfil -->
    <a href="perfil.php"><button>Voltar ao Perfil</button></a>
</body>
</html>
