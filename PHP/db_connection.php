<?php
// ira ligar a base de dados
$host = "127.0.0.1";
$user = "root";
$password = "";
$dbname = "trabalho_php";

$conn = new mysqli($host, $user, $password, $dbname);

//Verifica a conexao com a base de dados
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
