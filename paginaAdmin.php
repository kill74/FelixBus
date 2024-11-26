<?php
session_start(); 

// para nao conseguir entrar pelo url
if (!isset($_SESSION ['user_id'])){
  //Se o user nao tiver feito o login ira ser redirecionado para a pagina de login
  header("Location: PaginaLogin.php");
  exit();
}

// Login process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
   // $password_hash = password_hash($password, PASSWORD_DEFAULT); nao e preciso utilizar isto 

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user["email"] === "admin@email.com" && $user["password"] === "246810" && $user["role"] === "admin") {
        // Admin login
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = "admin";
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}

// Admin dashboard
if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
    // Display admin-specific functionality
} else {
    header("Location: user_dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Painel Administrativo</title>
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
      }

      :root {
        --primary-color: #2563eb;
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
        background-color: #1e293b;
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
        background-color: #334155;
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

      .table-container {
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

      .status-inactive {
        background-color: #fee2e2;
        color: #991b1b;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <!-- Sidebar -->
      <div class="sidebar">
        <div class="logo">AdminPanel</div>
        <nav>
          <div class="nav-item">Dashboard</div>
          <div class="nav-item">Usuários</div>
          <div class="nav-item">Pedidos</div>
          <div class="nav-item">Configurações</div>
          <div class="nav-item"><button> Pagina Principal </button></div>
        </nav>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <header class="header">
          <input type="text" placeholder="Pesquisar..." class="search-bar" />
          <div>
            <img
              src="/api/placeholder/32/32"
              alt="Admin"
              style="border-radius: 50%" />
          </div>
        </header>

        <main class="dashboard">
          <div class="stats-grid">
            <div class="stat-card">
              <h3>Total de Usuários</h3>
              <div class="stat-value">1,234</div>
            </div>
            <div class="stat-card">
              <h3>Vendas Mensais</h3>
              <div class="stat-value">R$ 45,678</div>
            </div>
            <div class="stat-card">
              <h3>Produtos Ativos</h3>
              <div class="stat-value">345</div>
            </div>
            <div class="stat-card">
              <h3>Novos Pedidos</h3>
              <div class="stat-value">28</div>
            </div>
          </div>

          <div class="table-container">
            <h2 style="margin-bottom: 1rem">Usuários Recentes</h2>
            <table>
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>João Silva</td>
                  <td>joao@email.com</td>
                  <td><span class="status status-active">Ativo</span></td>
                  <td>Editar</td>
                </tr>
                <tr>
                  <td>Maria Santos</td>
                  <td>maria@email.com</td>
                  <td><span class="status status-inactive">Inativo</span></td>
                  <td>Editar</td>
                </tr>
                <tr>
                  <td>Pedro Costa</td>
                  <td>pedro@email.com</td>
                  <td><span class="status status-active">Ativo</span></td>
                  <td>Editar</td>
                </tr>
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>
  </body>
</html>
