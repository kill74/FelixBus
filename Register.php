<?php
// Conexão com o banco de dados
$conn = new mysqli("127.0.0.1", "root", "", "trabalho_php");

// Verifica conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
    $tipo = 2; // Definido manualmente como padrão (Ex.: utilizador comum)
    $estado = 'ativo';

    $stmt = $conn->prepare("INSERT INTO utilizadores (nome, email, palavra_passe, tipo_utilizador_id, estado) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $nome, $email, $senha, $tipo, $estado);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso! <a href='login.php'>Login aqui</a>";
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
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
</head>
<body>
    <div class="form-container">
        <h2>Registrar</h2>
        <form action="register.php" method="POST">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Registrar</button>
        </form>
    </div>
</body>
</html>