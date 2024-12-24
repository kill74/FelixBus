<?php
session_start(); 
require 'db_connection.php';
//falta aqui coisas

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FelixBus</title>
    <link rel="stylesheet" href="style/stylePerfil.css">
    
    <style>
        div {
          text-align: center;
        }

        label{
            color: black;
            font-size: 30px;
            font-weight: bolder;
            min-width: 1000px;
            min-height: 1000px;
        }

        .texto{
            height: 40px;
            width: 200px;
            font-size: 20px;
        }

        .caixa-carregamento{
            margin: auto;
            width: 50%;
            width: 400px;
            border: 3px;
            content: 50px;
            padding: 10px;
        }

        footer {
        text-align: center;
        padding: 15px 0;
        background-color: 2d3e50;
        color: #fff;
        font-size: 14px;
        }

        .social-links {
        display: flex;
        justify-content: center;
        gap: 20px; /* Espaçamento entre os ícones */
        list-style: none;
        padding: 0;
        margin: 20px 0;
        }

        .social-links li {
        display: flex;
        align-items: center;
        }

        .social-links a {
        text-decoration: none;
        color: #fff; /* Cor do texto */
        display: flex;
        align-items: center;
        gap: 5px; /* Espaço entre o ícone e o texto */
        font-weight: bold;
        }

        .social-links img {
        width: 24px; /* Tamanho da imagem */
        height: 24px;
        }
</style>

</head>
<body>
    <?php require 'PHP/navbar.php' ?>
    <br><br>
        <div>
            <br>
            <img src="img/logofelixbus.png" alt="Imagem 1">
            <br>
            <label for="valor">Valor:</label><br>
                <input class="texto" type="number" id="valor" name="valor"><br><br><br>
            
                <a href="perfil.html" class="button">Editar Saldo</a>
        </div>
        <br><br>
        <div class="caixa-carregamento">
            <p> - Carregamento de €20.00 em 01/11/2024</p> <br>
        </div>
        <br><br>
        <?php require 'PHP/footer.php' ?>
</body>
</html>