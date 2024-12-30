<?php
session_start(); // Inicia a sessão
require_once 'db_connection.php'; // Conecta ao banco de dados

// Verifica se o usuário está logado
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = 'visitante'; // Define o papel do usuário como visitante por padrão

if ($isLoggedIn) {
    $userId = $_SESSION['user_id'];
    $tipoUtilizador = $_SESSION['tipo_utilizador'];

    // Define o papel do usuário com base no tipo
    if ($tipoUtilizador == 1) {
        $userRole = 'cliente';
    } elseif ($tipoUtilizador == 2) {
        $userRole = 'funcionario';
    } elseif ($tipoUtilizador == 3) {
        $userRole = 'admin';
    }
}

// Busca todas as rotas disponíveis
$queryRotas = "SELECT * FROM rotas";
$resultRotas = $conn->query($queryRotas);
$rotas = $resultRotas ? $resultRotas->fetch_all(MYSQLI_ASSOC) : [];

// Busca os bilhetes do usuário logado
$bilhetes = [];
if ($isLoggedIn && $userRole === 'cliente') {
    $queryBilhetes = "SELECT b.id, r.origem, r.destino, r.data, r.hora, b.codigo_validacao, b.estado 
                      FROM bilhetes b 
                      JOIN rotas r ON b.rota_id = r.id 
                      WHERE b.utilizador_id = $userId";
    $resultBilhetes = $conn->query($queryBilhetes);
    $bilhetes = $resultBilhetes ? $resultBilhetes->fetch_all(MYSQLI_ASSOC) : [];
}

// Processa o formulário de compra de bilhete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comprar_bilhete'])) {
    $rotaId = $_POST['rota_id'];
    $codigoValidacao = uniqid('BILHETE_', true); // Gera um código único

    // Insere o bilhete no banco de dados
    $query = "INSERT INTO bilhetes (utilizador_id, rota_id, codigo_validacao, estado) VALUES ($userId, $rotaId, '$codigoValidacao', 'comprado')";
    $conn->query($query);
}

// Processa o formulário de adicionar rota (apenas admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_rota']) && $userRole === 'admin') {
    $origem = $_POST['origem'];
    $destino = $_POST['destino'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $capacidade = $_POST['capacidade'];

    // Insere a nova rota no banco de dados
    $query = "INSERT INTO rotas (origem, destino, data, hora, capacidade) VALUES ('$origem', '$destino', '$data', '$hora', $capacidade)";
    $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horários - FelixBus</title>
    <link rel="stylesheet" href="style/styleIndex.css">
    <style>
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #2d3e50;
            color: white;
        }
        .form-section {
            margin-bottom: 20px;
        }
        .form-section input, .form-section button {
            padding: 8px;
            margin: 5px 0;
            width: 100%;
        }
        .button {
            background-color: #2d3e50;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #ffd700;
        }
    </style>
</head>
<body>
    <?php require 'navbar.php'; ?>
    <div class="container">
        <h1>Horários e Rotas</h1>

        <!-- Rotas Disponíveis -->
        <section>
            <h2>Rotas Disponíveis</h2>
            <table>
                <thead>
                    <tr>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rotas as $rota): ?>
                        <tr>
                            <td><?= htmlspecialchars($rota['origem']) ?></td>
                            <td><?= htmlspecialchars($rota['destino']) ?></td>
                            <td><?= htmlspecialchars($rota['data']) ?></td>
                            <td><?= htmlspecialchars($rota['hora']) ?></td>
                            <td>
                                <?php if ($isLoggedIn && $userRole === 'cliente'): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="rota_id" value="<?= $rota['id'] ?>">
                                        <button type="submit" name="comprar_bilhete" class="button">Comprar Bilhete</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Meus Bilhetes (Cliente) -->
        <?php if ($isLoggedIn && $userRole === 'cliente'): ?>
            <section>
                <h2>Meus Bilhetes</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Código</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bilhetes as $bilhete): ?>
                            <tr>
                                <td><?= htmlspecialchars($bilhete['id']) ?></td>
                                <td><?= htmlspecialchars($bilhete['origem']) ?></td>
                                <td><?= htmlspecialchars($bilhete['destino']) ?></td>
                                <td><?= htmlspecialchars($bilhete['data']) ?></td>
                                <td><?= htmlspecialchars($bilhete['hora']) ?></td>
                                <td><?= htmlspecialchars($bilhete['codigo_validacao']) ?></td>
                                <td><?= htmlspecialchars($bilhete['estado']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        <?php endif; ?>

        <!-- Adicionar Nova Rota (Admin) -->
        <?php if ($isLoggedIn && $userRole === 'admin'): ?>
            <section>
                <h2>Adicionar Nova Rota</h2>
                <form method="POST" class="form-section">
                    <input type="text" name="origem" placeholder="Origem" required>
                    <input type="text" name="destino" placeholder="Destino" required>
                    <input type="date" name="data" required>
                    <input type="time" name="hora" required>
                    <input type="number" name="capacidade" placeholder="Capacidade" required>
                    <button type="submit" name="adicionar_rota" class="button">Adicionar Rota</button>
                </form>
            </section>
        <?php endif; ?>
    </div>
    <?php require 'footer.php'; ?>
</body>
</html>