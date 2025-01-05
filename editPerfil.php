<?php
session_start();
require_once 'db_connection.php';

/* exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

if (!isset($_SESSION['user_id'])) {
    die("Erro: Necessita de estar autenticado para aceder a esta página.");
}

$user_id = $_SESSION['user_id'];
$mensagem = "";

// Busca os dados do utilizador
$sql = "SELECT nome, estado FROM utilizadores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc() or die("Erro: Utilizador não encontrado.");

// Atualiza os dados se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = htmlspecialchars($_POST['nome']);
    $estado = htmlspecialchars($_POST['estado']);

    $sql = "UPDATE utilizadores SET nome = ?, estado = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nome, $estado, $user_id);

    if ($stmt->execute()) {
        $mensagem = "Perfil atualizado com sucesso!";
        $user['nome'] = $nome;
        $user['estado'] = $estado;
    } else {
        $mensagem = "Ocorreu um erro ao editar o seu perfil.";
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

        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <p class="<?= $mensagem ? 'mensagem-sucesso' : 'mensagem-erro' ?>"><?= $mensagem ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Nome:</label>
            <input type="text" name="nome" value="<?= $user['nome'] ?>" required>

            <label>Estado:</label>
            <input type="text" name="estado" value="<?= $user['estado'] ?>" required>

            <button type="submit">Guardar Alterações</button>
        </form>

        <a href="perfil.php"><button>Voltar ao Perfil</button></a>
    </div>
</body>

</html>
