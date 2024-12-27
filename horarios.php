<?php
session_start(); // Inicia a sessão
require_once 'db_connection.php';

// Verificar se o utilizador está logado
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = 'visitor'; // Valor padrão para visitantes

if ($isLoggedIn) {
    $tipoUtilizador = $_SESSION['tipo_utilizador'];
    switch ($tipoUtilizador) {
        case 1:
            $userRole = 'cliente';
            break;
        case 2:
            $userRole = 'funcionario';
            break;
        case 3:
            $userRole = 'admin';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Horários dos Autocarros</title>
  <link rel="stylesheet" href="style/stylePerfil.css" />
</head>
<body>
  <?php require 'navbar.php'; ?>
  <br><br><br>
  <table class="Tabela">
    <thead>
      <tr>
        <th>Dia</th>
        <th>Hora Saída</th>
        <th>Destino</th>
        <th>Hora Chegada</th>
        <th>Tipo Viagem</th>
        <th>Comprar</th>
      </tr>
    </thead>
    <tbody>
      <tr class="Skibidi">
        <td>Castelo Branco</td>
        <td>21:54</td>
        <td>Lisboa</td>
        <td>23:25</td>
        <td>Direta</td>
        <td>
          <button class="Butao-Comprar" onclick="verificarLogin()">Comprar</button>
        </td>
      </tr>
      <tr class="Skibidi">
        <td>Lisboa</td>
        <td>15:32</td>
        <td>Porto</td>
        <td>18:20</td>
        <td>Direta</td>
        <td>
          <button class="Butao-Comprar" onclick="verificarLogin()">Comprar</button>
        </td>
      </tr>
      <tr class="Skibidi">
        <td>Porto</td>
        <td>10:32</td>
        <td>Covilhã</td>
        <td>14:54</td>
        <td>Regional</td>
        <td>
          <button class="Butao-Comprar" onclick="verificarLogin()">Comprar</button>
        </td>
      </tr>
      <tr class="Skibidi">
        <td>Braga</td>
        <td>12:32</td>
        <td>Bragança</td>
        <td>14:12</td>
        <td>Direto</td>
        <td>
          <button class="Butao-Comprar" onclick="verificarLogin()">Comprar</button>
        </td>
      </tr>
      <tr class="Skibidi">
        <td>Coimbra</td>
        <td>15:56</td>
        <td>Évora</td>
        <td>19:45</td>
        <td>Regional</td>
        <td>
          <button class="Butao-Comprar" onclick="verificarLogin()">Comprar</button>
        </td>
      </tr>
      <tr class="Skibidi">
        <td>Coimbra</td>
        <td>15:56</td>
        <td>Castelo Branco</td>
        <td>17:36</td>
        <td>Direto</td>
        <td>
          <button class="Butao-Comprar" onclick="verificarLogin()">Comprar</button>
        </td>
      </tr>
    </tbody>
  </table>
  
  <!-- Mostrar botão Editar Horários apenas para funcionários e admins -->
  <?php if ($isLoggedIn && ($userRole === 'funcionario' || $userRole === 'admin')): ?>
  <center>
    <button class="Butao-Comprar" onclick="window.location.href='AddHorario.php'">Editar Horários</button>
  </center>
  <?php endif; ?>
  
  <!-- Pop-up -->
  <div class="popup-overlay" id="popupOverlay">
    <div class="popup" id="popup">
      <h2>Comprar Bilhete</h2>
      <form>
        <label for="destino">Destino:</label>
        <input type="text" id="destino" name="destino" placeholder="Digite o destino" required>
        <br>
        <label for="data">Data:</label>
        <input type="date" id="data" name="data" required>
        <br>
        <label for="quantidade">Quantidade de Bilhetes:</label>
        <input type="number" id="quantidade" name="quantidade" min="1" max="10" required>
        <button type="submit" class="Butao-Comprar">Confirmar Compra</button>
      </form>
      <button class="close-popup" id="closePopup">Fechar</button>
    </div>
  </div>
  
  <script>
    function verificarLogin() {
      <?php if (!$isLoggedIn): ?>
        // Se o usuário não estiver logado, redireciona para a página de login
        window.location.href = 'Login.php';
      <?php else: ?>
        // Se o usuário estiver logado, abre o pop-up de compra
        document.getElementById('popupOverlay').style.display = 'flex';
      <?php endif; ?>
    }

    // Fechar o pop-up
    document.getElementById('closePopup').addEventListener('click', function() {
      document.getElementById('popupOverlay').style.display = 'none';
    });
  </script>
  <br><br><br>
  <?php require 'footer.php'; ?>
</body>
</html> 