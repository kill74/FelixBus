<?php
session_start(); 

//faltam coisas aqui

?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Painel do Funcionário</title>
    <link rel="stylesheet" href="style/styleFuncionario.css">
  </head>
  <body>
    <div class="container">
      <!-- Sidebar -->
      <div class="sidebar">
        <div class="logo">Portal Funcionário</div>
        <nav>
          <div class="nav-item">Dashboard</div>
          <div class="nav-item">Clientes</div>
          <div class="nav-item">Visitantes</div>
          <div class="nav-item">Agendamentos</div>
          <div class="nav-item">Editar Perfil</div>
          <div class="nav-item">Página Principal</div>
        </nav>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <header class="header">
          <input
            type="text"
            placeholder="Pesquisar cliente ou visitante..."
            class="search-bar" />
          <div class="user-info">
            <span class="role-badge">Funcionário</span>
            <img
              src="/api/placeholder/32/32"
              alt="Funcionário"
              style="border-radius: 50%" />
          </div>
        </header>

        <main class="dashboard">
          <div class="quick-actions">
            <h2>Ações Rápidas</h2>
            <div class="action-buttons">
              <button class="action-button">Novo Cliente</button>
              <button class="action-button">Registrar Visitante</button>
              <button class="action-button">Criar Agendamento</button>
            </div>
          </div>

          <div class="stats-grid">
            <div class="stat-card">
              <h3>Clientes Ativos</h3>
              <div class="stat-value">487</div>
            </div>
            <div class="stat-card">
              <h3>Visitantes Hoje</h3>
              <div class="stat-value">24</div>
            </div>
            <div class="stat-card">
              <h3>Agendamentos Pendentes</h3>
              <div class="stat-value">12</div>
            </div>
            <div class="stat-card">
              <h3>Atendimentos do Dia</h3>
              <div class="stat-value">18</div>
            </div>
          </div>

          <div class="tables-container">
            <!-- Clientes Recentes -->
            <div class="table-section">
              <h2 style="margin-bottom: 1rem">Clientes Recentes</h2>
              <table>
                <thead>
                  <tr>
                    <th>Nome</th>
                    <th>Status</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Ana Silva</td>
                    <td><span class="status status-active">Ativo</span></td>
                    <td>
                      <a href="#" class="action-link">Ver Detalhes</a>
                    </td>
                  </tr>
                  <tr>
                    <td>Carlos Santos</td>
                    <td><span class="status status-active">Ativo</span></td>
                    <td>
                      <a href="#" class="action-link">Ver Detalhes</a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Visitantes -->
            <div class="table-section">
              <h2 style="margin-bottom: 1rem">Visitantes de Hoje</h2>
              <table>
                <thead>
                  <tr>
                    <th>Nome</th>
                    <th>Status</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Marina Costa</td>
                    <td>
                      <span class="status status-pending">Em Visita</span>
                    </td>
                    <td>
                      <a href="#" class="action-link">Finalizar Visita</a>
                    </td>
                  </tr>
                  <tr>
                    <td>Roberto Alves</td>
                    <td><span class="status status-active">Concluído</span></td>
                    <td>
                      <a href="#" class="action-link">Ver Registro</a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </main>
      </div>
    </div>
  </body>
</html>
