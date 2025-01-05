<?php
// Inicia a sessão para acessar dados do utilizador
session_start();

// Conecta ao banco de dados
require_once 'db_connection.php';

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php"); // Redireciona para a página de login
    exit();
}

$user_id = $_SESSION['user_id']; // ID do utilizador logado

// Inicializa as variáveis para evitar warnings
$saldo_atual = 0.00; // Saldo inicial
$mensagem = ""; // Mensagem inicial

// Função para sanitizar entradas do utilizador
function sanitizarEntrada($dados) {
    return htmlspecialchars(stripslashes(trim($dados)));
}

// Verifica se o formulário de carregamento ou levantamento foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['valor'], $_POST['tipo_operacao'])) {
    // Sanitiza e valida os dados do formulário
    $valor = filter_var($_POST['valor'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $tipo_operacao = sanitizarEntrada($_POST['tipo_operacao']);

    // Verifica se o valor é válido
    if ($valor > 0 && ($tipo_operacao == "carregamento" || $tipo_operacao == "levantamento")) {
        // Obtém a carteira do utilizador
        $stmt = $conn->prepare("SELECT id, saldo FROM carteira WHERE utilizador_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $carteira = $stmt->get_result()->fetch_assoc();

        if ($carteira) {
            $carteira_id = $carteira['id']; // ID da carteira do utilizador
            $saldo_atual = $carteira['saldo']; // Saldo atual do utilizador

            // Verifica se é um levantamento e se há saldo suficiente
            if ($tipo_operacao == "levantamento" && $valor > $saldo_atual) {
                $mensagem = "Erro: Saldo insuficiente.";
            } else {
                // Calcula o novo saldo
                $novo_saldo = ($tipo_operacao == "carregamento") ? $saldo_atual + $valor : $saldo_atual - $valor;

                // Atualiza o saldo na carteira do utilizador
                $stmt = $conn->prepare("UPDATE carteira SET saldo = ? WHERE id = ?");
                $stmt->bind_param("di", $novo_saldo, $carteira_id);
                $stmt->execute();

                // Define a carteira de origem e destino
                $carteira_origem = ($tipo_operacao == "carregamento") ? 1 : $carteira_id; // 1 = Carteira da FelixBus
                $carteira_destino = ($tipo_operacao == "carregamento") ? $carteira_id : 1;

                // Registra a transação no histórico
                $stmt = $conn->prepare("INSERT INTO transacoes (utilizador_id, carteira_origem, carteira_destino, valor, tipo, data_transacao) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param("iiids", $user_id, $carteira_origem, $carteira_destino, $valor, $tipo_operacao);
                $stmt->execute();

                // Mensagem de sucesso
                $_SESSION['mensagem'] = "Operação de $tipo_operacao realizada com sucesso!";
                header("Location: carteira.php"); // Redireciona para evitar reenvio do formulário
                exit();
            }
        } else {
            $mensagem = "Erro: Carteira não encontrada.";
        }
    } else {
        $mensagem = "Erro: Valor ou tipo de operação inválido.";
    }
}

// Obtém o saldo atual do utilizador
$stmt = $conn->prepare("SELECT saldo FROM carteira WHERE utilizador_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $saldo_atual = $result->fetch_assoc()['saldo']; // Atualiza o saldo atual
}

// Verifica se há uma mensagem na sessão
if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']); // Remove a mensagem da sessão após exibi-la
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carteira - FelixBus</title>
    <link rel="stylesheet" href="styleIndex.css">
    <style>
        .saldo-section { margin-bottom: 20px; text-align: center; }
        .saldo { font-size: 24px; font-weight: bold; color: green; }
        .mensagem { background-color: rgb(73, 180, 23); color: rgb(0, 0, 0); padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; }
        .historico-list { list-style: none; padding: 0; }
        .historico-list li { margin-bottom: 10px; }
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