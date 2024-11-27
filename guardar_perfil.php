<?php
// Configuração da ligação à base de dados existente
$host = "localhost"; // Servidor da base de dados
$usuario = "root";   // Nome de utilizador do MySQL
$senha = "";        
$basedados = "trabalho_php"; // Nome da base de dados existente

// Cria a ligação à base de dados
$conn = mysqli_connect($host, $usuario, $senha, $basedados);

// Verifica a ligação
if (!$conn) {
    die("Erro na ligação à base de dados: " . mysqli_connect_error());
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recolhe os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Encripta a senha
    $senha_encriptada = password_hash($senha, PASSWORD_DEFAULT);

    // Insere os dados na tabela 'usuarios'
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha_encriptada')";

    if (mysqli_query($conn, $sql)) {
        echo "<h1>Perfil guardado com sucesso!</h1>";
        echo "<p><strong>Nome:</strong> $nome</p>";
        echo "<p><strong>Email:</strong> $email</p>";
    } else {
        echo "<p>Erro ao guardar o perfil: " . mysqli_error($conn) . "</p>";
    }
}

// Fecha a ligação
mysqli_close($conn);
?>
