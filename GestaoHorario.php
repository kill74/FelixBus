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
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Horarios dos Autocarros</title>
    <link rel="stylesheet" href="style/stylePerfil.css" />
    <style> 
        .btt{
            text-align: center;
        }
    </style>
  </head>
  <body>
    <header>
      <h1>FelixBus</h1>
      <br />
      <nav>
        <ul>
            <li><a href="GestaoCarteira.php">Gestao da Carteira</a></li>
            <li><a href="GestaoHorario.php">Gestao dos bilhetes</a></li>
            <li><a href="GestaoPerfil.php">Editar Dados Pessoais</a></li>
        </ul>
      </nav>
      <br>
      <div class="search">
        <form action="#">
            <input type="text" placeholder="Procurar Destino"
                   name="search">
            <button>
                <i class="fa fa-search"></i>
            </button>
        </form>
    </div>
</div>
    </header>
    <br><br><br>
<table class="Tabela">
  <thead> <!--TITULOS DA TABELA-->
    <tr>
      <th>Partida</th>
      <th>Hora Saida</th>
      <th>Destino</th>
      <th>Hora Chegada</th>
      <th>Tipo Viagem</th>
      <th>Comprar</th>
    </tr>
  </thead>
  <tbody> <!--O RESTO-->
    <tr class="Skibidi">
      <td>Castelo Branco</td>
      <td>21:54</td>
      <td>Lisboa</td>
      <td>23:25</td>
      <td>Direta</td>
      <td><a href="#BIlhetes" class="Butao-comprar"> Comprar </td>
    </tr>
    <tr class="Skibidi">
      <td>Lisboa</td>
      <td>15:32</td>
      <td>Porto</td>
      <td>18:20</td>
      <td>Direta</td>
      <td><a href="#BIlhetes" class="Butao-comprar"> Comprar </td>
    </tr>
    <tr class="Skibidi">
      <td>Porto</td>
      <td>10:32</td>
      <td>Covilha</td>
      <td>14:54</td>
      <td>Regional</td>
      <td><a href="#BIlhetes" class="Butao-comprar"> Comprar </td>
    </tr>
    <tr class="Skibidi">
      <td>Braga</td>
      <td>12:32</td>
      <td>Bragança</td>
      <td>14:12</td>
      <td>Direto</td>
      <td><a href="#BIlhetes" class="Butao-comprar"> Comprar </td>
    </tr>
    <tr class="Skibidi">
      <td>Coimbra</td>
      <td>15:56</td>
      <td>Évora</td>
      <td>19:45</td>
      <td>Regional</td>
      <td><a href="#BIlhetes" class="Butao-comprar"> Comprar </td>
    </tr>
    <tr class="Skibidi">
      <td>Coimbra</td>
      <td>15:56</td>
      <td>Castelo Branco</td>
      <td>17:36</td>
      <td>Direto</td>
      <td><a href="#BIlhetes" class="Butao-comprar"> Comprar </td>
    </tr>
  </tbody>
</table>

    <div class="btt">
        <a href="AddHorario.html" class="button">Adicionar Horário</a>
    </div>

   <br><br><br>
    <footer>
      <img src="" alt="" />
      <!--Adicionar aqui uma imagem para ficar mais bonito-->
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
              <img src="img/FacebookLogo.png" alt="Imagem Facebook" /> Facebook
            </a>
          </li>
          <li>
            <a href="https://instagram.com" target="_blank">
              <img src="img/InstagramLogo.png" alt="Imagem Instagram" />
              Instagram
            </a>
          </li>
          <li>
            <a href="https://twitter.com" target="_blank">
              <img src="img/TwitterLogo.png" alt="Imagem Twitter" /> Twitter
            </a>
          </li>
        </ul>
      </section>
      <p>&copy; FelixBus. Todos os direitos reservados.</p>
    </footer>
  </body>
</html>
