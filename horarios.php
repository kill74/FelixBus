<?php
session_start();
require_once 'db_connection.php';

// Verifica se o utilizador está autenticado
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = 'visitor';

if ($isLoggedIn) {
    $userId = $_SESSION['user_id'];
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

// Função para buscar rotas disponíveis
function getRotas($conn) {
    $query = "SELECT * FROM rotas";
    $result = $conn->query($query);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Função para buscar bilhetes do utilizador
function getBilhetes($conn, $userId) {
    $query = "SELECT b.id, r.origem, r.destino, r.data, r.hora, b.estado 
              FROM bilhetes b 
              JOIN rotas r ON b.rota_id = r.id 
              WHERE b.utilizador_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Função para adicionar uma nova rota (apenas admin)
function addRota($conn, $origem, $destino, $data, $hora, $capacidade) {
    $query = "INSERT INTO rotas (origem, destino, data, hora, capacidade) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $origem, $destino, $data, $hora, $capacidade);
    return $stmt->execute();
}

// Processar formulários
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['comprar_bilhete'])) {
        // Comprar bilhete
        $rotaId = $_POST['rota_id'];
        $query = "INSERT INTO bilhetes (utilizador_id, rota_id, estado) VALUES (?, ?, 'pendente')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $userId, $rotaId);
        $stmt->execute();
    } elseif (isset($_POST['adicionar_rota']) && $userRole === 'admin') {
        // Adicionar rota (apenas admin)
        $origem = $_POST['origem'];
        $destino = $_POST['destino'];
        $data = $_POST['data'];
        $hora = $_POST['hora'];
        $capacidade = $_POST['capacidade'];
        addRota($conn, $origem, $destino, $data, $hora, $capacidade);
    }
}

// Buscar rotas e bilhetes
$rotas = getRotas($conn);
$bilhetes = $isLoggedIn ? getBilhetes($conn, $userId) : [];
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

        <!-- Consultar Rotas -->
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

        <!-- Gestão de Bilhetes (Cliente) -->
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
                                <td><?= htmlspecialchars($bilhete['estado']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        <?php endif; ?>

        <!-- Gestão de Rotas (Admin) -->
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