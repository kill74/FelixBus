<?php
session_start(); 

// Redireciona para a página de login se o usuário não estiver logado
if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FelixBus</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        header {
            background-color: #2d3e50;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        .container {
            max-width: 500px;
            margin: 2rem auto;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .container label {
            font-size: 16px;
            font-weight: bold;
            display: block;
            margin-top: 1rem;
        }
        .container input {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.5rem 1.5rem;
            background-color: #2d3e50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #ffd700;
        }
    </style>
</head>
<body>
 <?php require 'PHP/navbar.php' ?>
    <div class="container">
        <label for="partida">Partida:</label>
        <input type="text" id="partida" placeholder="Adicione local de Partida">

        <label for="saida">Hora de Saída:</label>
        <input type="time" id="saida">

        <label for="destino">Destino:</label>
        <input type="text" id="destino" placeholder="Adicione local de Destino">

        <label for="chegada">Hora de Chegada:</label>
        <input type="time" id="chegada">

        <label for="tipo">Tipo de Viagem:</label>
        <input type="text" id="tipo" placeholder="Adicione o tipo de Viagem">

        <a href="GestaoHorario.html" class="button">Adicionar Horário</a>
    </div>
    <?php require 'PHP/footer.php' ?>
</body>
</html>
