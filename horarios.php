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
    $rotaId = (int)$_POST['rota_id'];
    $codigoValidacao = uniqid('BILHETE_', true);

    // Inicia uma transação
    $conn->begin_transaction();

    try {
        // Obtém o saldo do cliente
        $query_saldo = "SELECT saldo FROM carteira WHERE utilizador_id = ?";
        $stmt = $conn->prepare($query_saldo);
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $saldo = $stmt->get_result()->fetch_assoc()['saldo'] ?? 0;

        // Obtém o preço do bilhete
        $query_preco = "SELECT preco FROM rotas WHERE id = ?";
        $stmt = $conn->prepare($query_preco);
        $stmt->bind_param("i", $rotaId);
        $stmt->execute();
        $preco_bilhete = $stmt->get_result()->fetch_assoc()['preco'] ?? 10.00;

        if ($saldo >= $preco_bilhete) {
            // Insere o bilhete
            $stmt = $conn->prepare("INSERT INTO bilhetes (utilizador_id, rota_id, codigo_validacao, estado) VALUES (?, ?, ?, 'comprado')");
            $stmt->bind_param("iis", $_SESSION['user_id'], $rotaId, $codigoValidacao);
            $stmt->execute();

            // Atualiza o saldo do cliente
            $novo_saldo = $saldo - $preco_bilhete;
            $stmt = $conn->prepare("UPDATE carteira SET saldo = ? WHERE utilizador_id = ?");
            $stmt->bind_param("di", $novo_saldo, $_SESSION['user_id']);
            $stmt->execute();

            // Obtém o ID da carteira do usuário
            $query_carteira_id = "SELECT id FROM carteira WHERE utilizador_id = ?";
            $stmt = $conn->prepare($query_carteira_id);
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $carteira_usuario_id = $stmt->get_result()->fetch_assoc()['id'] ?? null;

            if (!$carteira_usuario_id) {
                throw new Exception("Carteira do usuário não encontrada.");
            }

            // Registra a transação
            $carteira_felixbus_id = 1; // ID da carteira da FelixBus
            $stmt = $conn->prepare("INSERT INTO transacoes (utilizador_id, carteira_origem, carteira_destino, valor, tipo, data_transacao) VALUES (?, ?, ?, ?, 'compra', NOW())");
            $stmt->bind_param("iiid", $_SESSION['user_id'], $carteira_usuario_id, $carteira_felixbus_id, $preco_bilhete);
            $stmt->execute();

            // Commit da transação
            $conn->commit();

            $_SESSION['mensagem'] = "Compra realizada com sucesso!";
            header("Location: horarios.php");
            exit();
        } else {
            $conn->rollback();
            $_SESSION['erro'] = $saldo === null ? "Carteira não encontrada." : "Saldo insuficiente.";
            header("Location: horarios.php");
            exit();
        }
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['erro'] = "Erro ao processar a compra: " . $e->getMessage();
        header("Location: horarios.php");
        exit();
    }
}

// Busca rotas e bilhetes
$rotas = $conn->query("SELECT id, origem, destino, data, hora, preco FROM rotas")->fetch_all(MYSQLI_ASSOC);
$bilhetes = $isLoggedIn && $userRole === 'cliente' ? $conn->query("SELECT b.id, r.origem, r.destino, r.data, r.hora, b.codigo_validacao, b.estado FROM bilhetes b JOIN rotas r ON b.rota_id = r.id WHERE b.utilizador_id = {$_SESSION['user_id']}")->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horários - FelixBus</title>
    <link rel="stylesheet" href="styleIndex.css">
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

        .mensagem.sucesso {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <?php require 'navbar.php'; ?>
    <div class="container">
        <h1>Horários e Rotas</h1>

        <?php if (isset($_SESSION['erro'])): ?>
            <div class="mensagem">
                <?= htmlspecialchars($_SESSION['erro']) ?>
            </div>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="mensagem sucesso">
                <?= htmlspecialchars($_SESSION['mensagem']) ?>
            </div>
            <?php unset($_SESSION['mensagem']); ?>
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
                        <th>Preço</th>
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
                            <td>€<?= number_format($rota['preco'], 2, ',', '.') ?></td>
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