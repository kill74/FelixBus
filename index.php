<?php
session_start();
require_once 'db_connection.php';

// Buscar alertas/promoções
$sql = "SELECT * FROM alertas ORDER BY data_criacao DESC";
$result = $conn->query($sql);
$alertas = $result->fetch_all(MYSQLI_ASSOC);
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

        main {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .alertas {
            margin-bottom: 20px;
        }

        .alerta {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <?php require 'navbar.php' ?>
    <main>
        <!-- Exibição de Alertas/Promoções -->
        <section class="alertas">
            <h2>Alertas/Promoções</h2>
            <?php if (!empty($alertas)): ?>
                <?php foreach ($alertas as $alerta): ?>
                    <div class="alerta">
                        <p><?php echo htmlspecialchars($alerta['mensagem']); ?></p>
                        <small><?php echo $alerta['data_criacao']; ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Não há alertas ou promoções no momento.</p>
            <?php endif; ?>
        </section>

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
                </form>
                <br>
                <center>
                    <a href="horarios.php" target="_blank">
                        <button>Pesquisar</button>
                    </a>
                </center>
                <br>
                <!-- Sobre Secção -->
                <section id="sobre">
                    <h2>Sobre Nós</h2>
                    <p>Somos uma empresa dedicada a oferecer transporte seguro e confortável para destinos em todo o país.
                        Com nossa plataforma de fácil uso, você pode planejar suas viagens, gerenciar seu perfil,
                        e manter-se atualizado com nossos horários e promoções.</p>
                </section>
            </section>
        </main>
    </main>
    <?php require 'footer.php' ?>
</body>

</html>
