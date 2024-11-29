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
<link rel="stylesheet" href="stylePerfil.css">
<style>
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
    <?php require 'PHP/navbar/navbar.php' ?>
    <main>
        <br><br><br>
        <div class="add">
            <label class="fonte" >Nome:</label><br>
            <input class="texto" type="text" placeholder="Adicione o Nome"/>
          <br><br>
            <label class="fonte" >Email:</label><br>
            <input class="texto" type="email" placeholder="Adicione o Email"/>  
          <br><br>
            <label class="fonte" >Data de Nascimento</label><br>
            <input type="date"/>   
          <br><br>
            <label class="fonte" >Telefone:</label><br>
            <input class="texto" type="text" placeholder="Adicione o Telefone"/>
          <br><br>
            <label class="fonte" >Endereço:</label><br>
            <input class="texto" type="text" placeholder="Adicione o Endereço"/>  
          <br><br>
            <a href="GestaoPerfil.html" class="button">Alterar Perfil</a>
        </div>
    </main>
    <?php require 'PHP/navbar/footer/footer.php' ?>
</body>
</html>
