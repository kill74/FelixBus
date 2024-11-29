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
        <?php require 'PHP/navbar/navbar.php' ?>
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
  <?php require 'PHP/navbar/footer/footer.php' ?>
  </body>
</html>
