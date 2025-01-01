<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    die("Erro: Usuário não autenticado.");
}

$user_id = $_SESSION['user_id'];
$carteira_felixbus_id = 1; // ID da carteira da FelixBus
$mensagem = "";
$saldo_atual = 0.00;

// Processa o formulário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['valor'], $_POST['tipo_operacao'])) {
    $valor = (float) $_POST['valor'];
    $tipo_operacao = $_POST['tipo_operacao'];

    if ($valor > 0) {
        // Obtém o saldo atual
        $stmt = $conn->prepare("SELECT id, saldo FROM carteira WHERE utilizador_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $carteira = $stmt->get_result()->fetch_assoc();

        if ($carteira) {
            $carteira_id = $carteira['id'];
            $saldo_atual = $carteira['saldo'];

            // Verifica se é possível realizar a operação
            if ($tipo_operacao == "levantamento" && $valor > $saldo_atual) {
                $mensagem = "Erro: Saldo insuficiente.";
            } else {
                // Calcula o novo saldo
                $novo_saldo = ($tipo_operacao == "carregamento") ? $saldo_atual + $valor : $saldo_atual - $valor;

                // Atualiza o saldo
                $stmt = $conn->prepare("UPDATE carteira SET saldo = ? WHERE id = ?");
                $stmt->bind_param("di", $novo_saldo, $carteira_id);
                $stmt->execute();

                // Registra a transação
                $carteira_origem = ($tipo_operacao == "carregamento") ? $carteira_felixbus_id : $carteira_id;
                $carteira_destino = ($tipo_operacao == "carregamento") ? $carteira_id : $carteira_felixbus_id;

                $stmt = $conn->prepare("INSERT INTO transacoes (utilizador_id, carteira_origem, carteira_destino, valor, tipo, data_transacao) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param("iiids", $user_id, $carteira_origem, $carteira_destino, $valor, $tipo_operacao);
                $stmt->execute();

                $mensagem = "Operação de $tipo_operacao realizada com sucesso!";
            }
        } else {
            $mensagem = "Erro: Carteira não encontrada.";
        }
    } else {
        $mensagem = "Erro: Valor inválido.";
    }
}

// Obtém o saldo atual
$stmt = $conn->prepare("SELECT saldo FROM carteira WHERE utilizador_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$saldo_atual = $stmt->get_result()->fetch_assoc()['saldo'] ?? 0.00;
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carteira - FelixBus</title>
    <link rel="stylesheet" href="styleIndex.css">
    <style>
        .saldo-section {
            margin-bottom: 20px;
            text-align: center;
        }

        .saldo {
            font-size: 24px;
            font-weight: bold;
            color: green;
        }

        .mensagem {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .historico-list {
            list-style: none;
            padding: 0;
        }

        .historico-list li {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <?php require 'navbar.php'; ?>

    <main class="container">
        <h1>Gerir Carteira</h1>

        <?php if ($mensagem): ?>
            <div class="mensagem"><?= htmlspecialchars($mensagem) ?></div>
        <?php endif; ?>

        <section class="saldo-section">
            <h2>Saldo Atual</h2>
            <div class="saldo">€<?= number_format($saldo_atual, 2, ',', '.') ?></div>
        </section>

        <section class="form-section">
            <form method="POST" action="carteira.php">
                <label for="valor">Valor:</label>
                <input type="number" id="valor" name="valor" step="0.01" placeholder="Insira o valor" required>
                <button type="submit" name="tipo_operacao" value="carregamento">Carregar Saldo</button>
                <button type="submit" name="tipo_operacao" value="levantamento">Levantar Saldo</button>
            </form>
        </section>

        <section class="historico-section">
            <h2>Histórico de Transações</h2>
            <ul class="historico-list">
                <?php
                $stmt = $conn->prepare("SELECT valor, tipo, data_transacao FROM transacoes WHERE utilizador_id = ? ORDER BY data_transacao DESC");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $historico = $stmt->get_result();

                while ($linha = $historico->fetch_assoc()) {
                    echo "<li><span class='tipo'>{$linha['tipo']}</span> de <span class='valor'>€{$linha['valor']}</span> em <span class='data'>{$linha['data_transacao']}</span></li>";
                }
                ?>
            </ul>
        </section>
    </main>

    <?php require 'footer.php'; ?>
</body>

</html>
