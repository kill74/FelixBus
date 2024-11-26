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
    <link rel="stylesheet" href="styleIndex.css">
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

    </style>
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
    <br><br>
    <div>
        <div class="slideshow-container">
        <!-- Radio buttons for controlling the slides -->
            <input type="radio" name="slider" id="slide1" checked>
            <input type="radio" name="slider" id="slide2">
            <input type="radio" name="slider" id="slide3">
        <!-- Slides -->
        <div class="slides">
          <div class="slide">
            <img src="img/img8 (2).jpg" alt="Image 1">
          </div>
          <div class="slide">
            <img src="img/img10 (1).jpeg" alt="Image 2">
          </div>
          <div class="slide">
            <img src="img/img3.jpg" alt="Image 3">
          </div>
        </div>
        <!-- Navigation buttons -->
        <div class="navigation">
          <label for="slide1" class="nav-button"></label>
          <label for="slide2" class="nav-button"></label>
          <label for="slide3" class="nav-button"></label>
        </div>
      </div>
      
    </div>

    <main>
        <!-- Secção dos horarios -->
        <section id="horarios">
            <h2>Pesquisar Horários</h2>
            <form>
                <label for="origem">Origem:</label>
                <input type="text" id="origem" name="origem" placeholder="Cidade de origem">
                <label for="destino">Destino:</label>
                <input type="text" id="destino" name="destino" placeholder="Cidade de destino">
                <label for="data">Data:</label>
                <input type="date" id="data" name="data">
                <!-- isto tem de ser mandado para a pagina de bilhetes-->
                <!--<a href="horarios.php">
                    <button>Pesquisar</button>
                </a> --> <!-- temos de ver se isto da para fazer -->
            </form>
            <br>
            <center>
            <a href="horarios.php" target="_blank">
                    <button>Pesquisar</button>
                </a>
            </center>
            <br>
        <!-- Sobre Secçãpo -->
        <section id="sobre">
            <h2>Sobre Nós</h2>
            <p>Somos uma empresa dedicada a oferecer transporte seguro e confortável para destinos em todo o país. 
            Com nossa plataforma de fácil uso, você pode planejar suas viagens, gerenciar seu perfil, 
            e manter-se atualizado com nossos horários e promoções.</p>
        </section>
    </main>
    <footer>
        <section id="contacto">
            <h2>Contato</h2>
            <p><strong>Endereço:</strong> Rua Principal, 123, Castelo Branco</p>
            <p><strong>Telefone:</strong> +351 912 345 678</p>
            <p><strong>Email:</strong> contato@autocarros.com</p>
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
