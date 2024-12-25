<?php
session_start();

// Credenciais permitidas
$users = [
    'admin' => 'admin',
    'funcionario' => 'funcionario',
];

// Processa o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verifica as credenciais
    if (isset($users[$username]) && $users[$username] === $password) {
        // Define a sessão
        $_SESSION['user'] = $username;
        $_SESSION['role'] = $username === 'admin' ? 'admin' : 'funcionario';
    } else {
        $error = "Credenciais inválidas.";
    }
}

// Verifica se o usuário está autenticado
$isAuthenticated = isset($_SESSION['user']) && in_array($_SESSION['role'], ['admin', 'funcionario']);

// Encerra a sessão (logout)
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FelixBus - Gestão de Horários</title>
    <link rel="stylesheet" href="stylePerfil.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        header {
            background-color: #2d3e50;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        .container {
            max-width: 500px;
            margin: 2rem auto;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .container label {
            font-size: 16px;
            font-weight: bold;
            display: block;
            margin-top: 1rem;
        }
        .container input {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.5rem 1.5rem;
            background-color: #2d3e50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #ffd700;
        }
        .error {
            color: red;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>FelixBus - Gestão de Horários</h1>
    </header>
    <?php require 'PHP/navbar.php'; ?>
    <div class="container">
        <?php if ($isAuthenticated): ?>
            <!-- Página restrita -->
            <h2>Bem-vindo, <?= htmlspecialchars($_SESSION['user']); ?>!</h2>

            <label for="partida">Partida:</label>
            <input type="text" id="partida" placeholder="Adicione local de Partida">

            <label for="saida">Hora de Saída:</label>
            <input type="time" id="saida">

            <label for="destino">Destino:</label>
            <input type="text" id="destino" placeholder="Adicione local de Destino">

            <label for="chegada">Hora de Chegada:</label>
            <input type="time" id="chegada">

            <label for="tipo">Tipo de Viagem:</label>
            <input type="text" id="tipo" placeholder="Adicione o tipo de Viagem">

            <a href="?logout=1" class="button">Sair</a>
        <?php else: ?>
            <!-- Formulário de login -->
            <h2>Login</h2>
            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="POST">
                <label for="username">Usuário:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="button">Entrar</button>
            </form>
        <?php endif; ?>
    </div>
</body>
<?php require 'PHP/footer.php';   ?>
</html>
