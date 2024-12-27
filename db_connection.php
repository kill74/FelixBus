<?php
// Verificar se a extensão MySQLi está ativa
if (!extension_loaded('mysqli')) {
    die('A extensão MySQLi não está ativa.');
}

// Definir as configurações da base de dados
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'trabalho_php');

// Criar a ligação à base de dados
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Verificar se a ligação foi bem-sucedida
if ($conn->connect_error) {
    // Registar o erro (num ambiente de produção, deve registar isto num ficheiro de log)
    error_log("Erro de ligação à base de dados: " . $conn->connect_error);
    // Mostrar uma mensagem genérica ao utilizador
    die("Desculpe, ocorreu um erro ao ligar à base de dados. Por favor, tente novamente mais tarde.");
}

// Definir a codificação de caracteres para evitar problemas com caracteres especiais
$conn->set_charset("utf8mb4");
?>