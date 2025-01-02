<?php
session_start();
require_once 'db_connection.php';

// Verifica se o usuário está logado e define o papel
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $isLoggedIn ? match ($_SESSION['tipo_utilizador']) {
    1 => 'cliente',
    2 => 'funcionario',
    3 => 'admin',
    default => 'visitante'
} : 'visitante';

// Processa a compra de bilhete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comprar_bilhete'])) {
    $rotaId = $_POST['rota_id'];
    $codigoValidacao = uniqid('BILHETE_', true);

    $query_saldo = "SELECT saldo FROM carteira WHERE utilizador_id = ?";
    $stmt = $conn->prepare($query_saldo) or die("Erro ao preparar a consulta: " . $conn->error);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $saldo = $stmt->get_result()->fetch_assoc()['saldo'] ?? 0;

    $preco_bilhete = 10.00;

    if ($saldo >= $preco_bilhete) {
        $conn->query("INSERT INTO bilhetes (utilizador_id, rota_id, codigo_validacao, estado) VALUES ({$_SESSION['user_id']}, $rotaId, '$codigoValidacao', 'comprado')");

        $novo_saldo = $saldo - $preco_bilhete;
        $stmt = $conn->prepare("UPDATE carteira SET saldo = ? WHERE utilizador_id = ?") or die("Erro ao preparar a consulta: " . $conn->error);
        $stmt->bind_param("di", $novo_saldo, $_SESSION['user_id']);
        $stmt->execute();

        $stmt = $conn->prepare("INSERT INTO transacoes (utilizador_id, carteira_origem, carteira_destino, valor, tipo, data_transacao) VALUES (?, ?, ?, ?, 'compra', NOW())") or die("Erro ao preparar a consulta: " . $conn->error);
        $stmt->bind_param("iiid", $_SESSION['user_id'], $_SESSION['user_id'], 1, $preco_bilhete);
        $stmt->execute();

        header("Location: horarios.php?compra=sucesso");
        exit();
    } else {
        header("Location: horarios.php?erro=" . ($saldo === null ? 'carteira_nao_encontrada' : 'saldo_insuficiente'));
        exit();
    }
}

// Busca rotas e bilhetes
$rotas = $conn->query("SELECT * FROM rotas")->fetch_all(MYSQLI_ASSOC);
$bilhetes = $isLoggedIn && $userRole === 'cliente' ? $conn->query("SELECT b.id, r.origem, r.destino, r.data, r.hora, b.codigo_validacao, b.estado FROM bilhetes b JOIN rotas r ON b.rota_id = r.id WHERE b.utilizador_id = {$_SESSION['user_id']}")->fetch_all(MYSQLI_ASSOC) : [];
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

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #2d3e50;
            color: white;
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

        .mensagem {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php require 'navbar.php'; ?>
    <div class="container">
        <h1>Horários e Rotas</h1>

        <?php if (isset($_GET['erro'])): ?>
            <div class="mensagem">
                <?= $_GET['erro'] === 'saldo_insuficiente' ? "Erro: Saldo insuficiente para comprar o bilhete." : "Erro: Carteira não encontrada." ?>
            </div>
        <?php endif; ?>

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
    </div>
    <?php require 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="script.js"></script>
</body>

</html>
