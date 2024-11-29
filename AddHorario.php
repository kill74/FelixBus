<?php
session_start(); 

// para nao conseguir entrar pelo url
if (!isset($_SESSION ['user_id'])){
    //Se o user nao tiver feito o login ira ser redirecionado para a pagina de login
    header("Location: PaginaLogin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FelixBus</title>
    <link rel="stylesheet" href="style/styleIndex.css">

    <style>
        footer {
        text-align: center;
        padding: 15px 0;
        background-color: #2d3e50;
        color: #fff;
        font-size: 14px;
      }

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
        }

        body {
        color: #333;
        background-color: #f4f4f4;
        line-height: 1.6;
        }

        /* Header */
        header {
        background-color: #2d3e50;
        color: #fff;
        padding: 1rem;
        text-align: center;
        }

        header h1 {
        font-size: 2em;
        }

        header nav ul {
        list-style: none;
        display: flex;
        justify-content: center;
        padding-top: 0.5rem;
        }

        header nav ul li {
        margin: 0 1rem;
        }

        header nav ul li a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        }

        header nav ul li a:hover {
        color: #ffd700;
        }

        /* Perfil Section */
        main {
        max-width: 800px;
        margin: 2rem auto;
        padding: 1rem;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        }

        .add{
            text-align: center;
            margin: auto;
            border-radius: 1rem;
            background-color: white;
            width: 500px;
            height: 500px;
        }

        .titulo{
            text-align: center;
            color: #2d3e50;
        }

        .button {
            display: inline-block;
            padding: 0.4rem 1.2rem;
            color: #fff;
            background-color: #2d3e50;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .texto{
            height: 30px;
            width: 175px;
            font-size: 15px;

        }

        .texto1{
            height: 30px;
            width: 182px;
            font-size: 15px;
        }

        .fonte{
            font-family: serif;
            font-size: 23px;
        }

    </style>
</head>
<body>
    <?php require 'PHP/navbar/navbar.php'; ?>
    <br>
        <h1 class="titulo" > Adicionar Horario </h1>
        <br>
        <div class="add">
            <label class="fonte" >Partida:</label><br>
            <input class="texto" type="text" placeholder="Adicione local de Partida"/>
          <br><br>
            <label class="fonte" >Hora de Saída:</label><br>
            <input type="time"/>
          <br><br>
            <label class="fonte" >Destino:</label><br>
            <input class="texto" type="text" placeholder="Adicione local de Destino"/>  
          <br><br>
            <label class="fonte" >Hora de Chegada:</label><br>
            <input type="time"/>
          <br><br>
            <label class="fonte" >Tipo de Viagem:</label><br>
            <input class="texto1" type="text" placeholder="Adicione o tipo de Viagem"/>   
          <br><br>
            <a href="GestaoHorario.html" class="button">Adicionar Horário</a>
        </div>
          <br><br><br>
    
          <?php require 'PHP/navbar/footer/footer.php';?> <!-- isto vai poupar mesmo muitas linhas de codigo -->
</body>
</html>
