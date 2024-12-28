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
          <button class="Butao-Comprar" onclick="verificarLogin(1, 'Castelo Branco', '21:54', 'Lisboa', '23:25', 'Direta')">Comprar</button>
        </td>
      </tr>
      <!-- Repetir para as outras linhas da tabela -->
    </tbody>
  </table>
  
  <!-- Mostrar botão Editar Horários apenas para funcionários e admins -->
  <?php if ($isLoggedIn && ($userRole === 'funcionario' || $userRole === 'admin')): ?>
  <center>
    <button class="Butao-Comprar" onclick="window.location.href='AddHorario.php'">Editar Horários</button>
  </center>
  <?php endif; ?>
  
  <!-- Pop-up de compra de bilhete -->
  <div class="popup-overlay" id="popupOverlay">
    <div class="popup" id="popup">
      <h2>Comprar Bilhete</h2>
      <form id="compraForm" action="processar_compra.php" method="POST">
        <input type="hidden" id="viagem_id" name="viagem_id">
        <input type="hidden" id="origem" name="origem">
        <input type="hidden" id="horaSaida" name="horaSaida">
        <input type="hidden" id="destino" name="destino">
        <input type="hidden" id="horaChegada" name="horaChegada">
        <input type="hidden" id="tipoViagem" name="tipoViagem">
        <label for="data">Data da Viagem:</label>
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
    function verificarLogin(viagem_id, origem, horaSaida, destino, horaChegada, tipoViagem) {
      <?php if (!$isLoggedIn): ?>
        // Se o utilizador não estiver logado, redireciona para a página de login
        window.location.href = 'Login.php';
      <?php else: ?>
        // Se o utilizador estiver logado, abre o pop-up de compra
        document.getElementById('viagem_id').value = viagem_id;
        document.getElementById('origem').value = origem;
        document.getElementById('horaSaida').value = horaSaida;
        document.getElementById('destino').value = destino;
        document.getElementById('horaChegada').value = horaChegada;
        document.getElementById('tipoViagem').value = tipoViagem;
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