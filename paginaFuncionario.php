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
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
      }

      :root {
        --primary-color: #0891b2;
        --sidebar-width: 250px;
      }

      body {
        background-color: #f3f4f6;
      }

      .container {
        display: flex;
        min-height: 100vh;
      }

      /* Sidebar */
      .sidebar {
        width: var(--sidebar-width);
        background-color: #164e63;
        color: white;
        padding: 1rem;
        position: fixed;
        height: 100vh;
      }

      .logo {
        font-size: 1.5rem;
        font-weight: bold;
        padding: 1rem;
        border-bottom: 1px solid #334155;
        margin-bottom: 2rem;
      }

      .nav-item {
        padding: 0.75rem 1rem;
        margin: 0.5rem 0;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: background-color 0.3s;
      }

      .nav-item:hover {
        background-color: #155e75;
      }

      /* Main Content */
      .main-content {
        margin-left: var(--sidebar-width);
        flex: 1;
      }

      .header {
        background-color: white;
        padding: 1rem 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
      }

      .role-badge {
        background-color: #0891b2;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
      }

      .search-bar {
        padding: 0.5rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        width: 300px;
      }

      .dashboard {
        padding: 2rem;
      }

      .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
      }

      .stat-card {
        background-color: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      }

      .stat-card h3 {
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
      }

      .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #111827;
      }

      .quick-actions {
        background-color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      }

      .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
      }

      .action-button {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.375rem;
        background-color: var(--primary-color);
        color: white;
        cursor: pointer;
        transition: background-color 0.3s;
      }

      .action-button:hover {
        background-color: #0e7490;
      }

      .tables-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
      }

      .table-section {
        background-color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      }

      table {
        width: 100%;
        border-collapse: collapse;
      }

      th,
      td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
      }

      th {
        color: #6b7280;
        font-weight: 600;
      }

      .status {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
      }

      .status-active {
        background-color: #dcfce7;
        color: #166534;
      }

      .status-pending {
        background-color: #fef3c7;
        color: #92400e;
      }

      .action-link {
        color: var(--primary-color);
        text-decoration: none;
        font-size: 0.875rem;
      }

      .action-link:hover {
        text-decoration: underline;
      }
    </style>
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
