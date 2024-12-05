<?php
session_start(); 

//faltam coisas aqui

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FelixBus</title>
    <link rel="stylesheet" href="style/stylePerfil.css">
</head>
<body>
    <?php require 'PHP/navbar.php'  ?>
    <main>
        <!-- Secção do Perfil -->
        <section id="perfil">
            <br>
            <br>
            <br>
            <h2>Perfil</h2>
            <img width="150px" height="200px" src="img/perfil.jpg" alt="">
            <br><br>
            <p><strong>Nome: </strong> Trabalho PHP </p>
            <p><strong>Email: </strong> EST@ipcb.pt</p> 
            <p><strong>Data de Nascimento: </strong> 15/05/2005</p>
            <p><strong>Telefone: </strong> +351 912 345 678</p>
            <p><strong>Endereço: </strong> Rua Principal, 123, Castelo Branco</p><br>
            <br>
            <center>
            <a href="EditarPefil.php" target="_blank">
                    <button>Editar Perfil</button>
                </a>
            </center>
    </main>
    <?php require 'PHP/footer.php' ?>
</body>
</html>
