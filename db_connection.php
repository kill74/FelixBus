<?php
// Definir as configurações do banco de dados
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'trabalho_php');

// Criação da ligação ao banco de dados
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Verificar se a ligação foi bem-sucedida
if ($conn->connect_error) {
    die("Erro de ligação a base de dados: " . $conn->connect_error);
}

// Definir a codificação de caracteres para evitar problemas com caracteres especiais
$conn->set_charset("utf8mb4");
?>
