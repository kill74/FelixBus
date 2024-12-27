<?php
// Inicia a sessão
session_start();

// Inclui a ligação à base de dados
require_once 'db_connection.php';

/* exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['user_id'])) {
    die("Erro: Utilizador não autenticado.");
}

// Obtém o ID do utilizador da sessão
$user_id = $_SESSION['user_id'];

// Verifica se a conexão ainda está aberta
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Busca as informações do utilizador na base de dados usando prepared statements
$sql = "SELECT nome, tipo_utilizador_id, estado FROM utilizadores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se encontrou o utilizador
if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Obtém os dados do utilizador
} else {
    die("Erro: Utilizador não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Utilizador</title>
    <link rel="stylesheet" href="stylePerfil.css">
</head>
<body>
    <?php require 'navbar.php' ?>
    <div class="container">
        <h1>Perfil do Utilizador</h1>
        <div class="profile">
            <!-- Foto de perfil -->
            <img src="img/perfil.jpg" alt="Foto de Perfil" class="profile-pic">
            <div class="profile-info">
                <p><strong>Nome:</strong> <?= htmlspecialchars($user['nome']) ?></p>
                <p><strong>Tipo de Utilizador:</strong> <?= htmlspecialchars($user['tipo_utilizador_id']) ?></p>
                <p><strong>Estado:</strong> <?= htmlspecialchars($user['estado']) ?></p>
            </div>
        </div>
        <div class="buttons">
            <!-- Botões para editar perfil e voltar -->
            <a href="editPerfil.php" class="button">Editar Perfil</a>
            <a href="index.php" class="button">Voltar à Página Inicial</a>
        </div>
    </div>
    <?php require 'footer.php' ?>
</body>
</html>