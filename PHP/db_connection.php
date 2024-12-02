<?php
//iremos fazer deste genero para nao estar sempre a fazer a mesma coisa em todas as paginas (porque temos perguica);
// ira ligar a base de dados;
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
