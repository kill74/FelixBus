<?php
session_start(); 

// para nao conseguir entrar pelo url
if (!isset($_SESSION ['user_id'])){
    //Se o user nao tiver feito o login ira ser redirecionado para a pagina de login
    header("Location: index.html");
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
</head>
<body>
    <header>
        <h1>FelixBus</h1>
        <br>
        <nav>
            <ul>
                <li><a href="index.php">Página Principal</a></li>
                <li><a href="perfil.php">Perfil</a></li>
                <li><a href="carteira.php">Carteira</a></li>
                <li><a href="horarios.php">Horários</a></li>
            </ul>
        </nav>
    </header>
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
    <footer>
        <img src="" alt=""> <!--Adicionar aqui uma imagem para ficar mais bonito-->
        <section id="contacto">
            <h2>Contato</h2>
            <p><strong>Endereço: </strong> Rua Principal, 123, Castelo Branco</p>
            <p><strong>Telefone: </strong> +351 912 345 678</p>
            <p><strong>Email: </strong> contato@autocarros.com</p>
            <p>Siga-nos nas redes sociais:</p>
            <!--Cena para os links-->
            <ul class="social-links">
                <li>
                    <a href="https://facebook.com" target="_blank">
                        <img src="img/FacebookLogo.png" alt="Imagem Facebook"> Facebook
                    </a>
                </li>
                <li>
                    <a href="https://instagram.com" target="_blank">
                        <img src="img/InstagramLogo.png" alt="Imagem Instagram"> Instagram
                    </a>
                </li>
                <li>
                    <a href="https://twitter.com" target="_blank">
                        <img src="img/TwitterLogo.png" alt="Imagem Twitter"> Twitter
                    </a>
                </li>
            </ul>            
        </section>
        <p>&copy; FelixBus. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
