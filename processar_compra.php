<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $viagem_id = $_POST['viagem_id'];
    $origem = $_POST['origem'];
    $horaSaida = $_POST['horaSaida'];
    $destino = $_POST['destino'];
    $horaChegada = $_POST['horaChegada'];
    $tipoViagem = $_POST['tipoViagem'];
    $data_viagem = $_POST['data'];
    $quantidade = $_POST['quantidade'];
    $utilizador_id = $_SESSION['user_id'];

    // Gerar um código de validação único
    $codigo_validacao = uniqid();

    // Definir o preço (podes ajustar esta lógica conforme necessário)
    $preco = 10.00; // Exemplo: preço fixo de 10€ por bilhete

    // Inserir o bilhete na base de dados
    $query = "INSERT INTO bilhetes (codigo_validacao, data_viagem, estado, metodo_pagamento, preco, quantidade, utilizador_id, viagem_id) 
              VALUES (?, ?, 'comprado', 'cartao', ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdiii", $codigo_validacao, $data_viagem, $preco, $quantidade, $utilizador_id, $viagem_id);

    if ($stmt->execute()) {
        // Redirecionar para a página de bilhetes com mensagem de sucesso
        header("Location: meus_bilhetes.php?success=1");
        exit();
    } else {
        // Redirecionar com mensagem de erro
        header("Location: meus_bilhetes.php?error=1");
        exit();
    }
}
?>