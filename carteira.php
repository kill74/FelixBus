<?php
// Ativar a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar a sessão
session_start();
require_once 'db_connection.php';

$mensagem = ""; // Variável para mensagens
$saldo_atual = 0.00; // Inicializar saldo

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    die("Erro: Usuário não autenticado.");
}

$user_id = $_SESSION['user_id'];

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valor = (float) $_POST['valor'];
    $tipo_operacao = $_POST['tipo_operacao'];

    if ($valor > 0) {
        // Obtém o saldo atual da carteira do utilizador
        $query_saldo = "SELECT saldo FROM carteira WHERE utilizador_id = ?";
        $stmt = $conn->prepare($query_saldo);
        $stmt->bind_param("i", $user_id); // Bind o user_id como inteiro
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $saldo_atual = $resultado->fetch_assoc()['saldo'];

            // Processa a operação
            if ($tipo_operacao == "carregamento") {
                $novo_saldo = $saldo_atual + $valor;
            } elseif ($tipo_operacao == "levantamento" && $valor <= $saldo_atual) {
                $novo_saldo = $saldo_atual - $valor;
            } else {
                $mensagem = "Erro: Saldo insuficiente.";
            }

            // Atualiza o saldo e registra a transação
            if (isset($novo_saldo)) {
                // Atualiza o saldo na carteira
                $update_query = "UPDATE carteira SET saldo = ? WHERE utilizador_id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("di", $novo_saldo, $user_id); // Bind o novo saldo e user_id
                $stmt->execute();

                // Registra a transação
                $insert_query = "INSERT INTO transacoes (utilizador_id, valor, tipo, data_transacao) 
                                 VALUES (?, ?, ?, NOW())";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("ids", $user_id, $valor, $tipo_operacao); // Bind user_id, valor e tipo
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

// Recuperar o saldo atual da carteira do usuário
$query_saldo_atual = "SELECT saldo FROM carteira WHERE utilizador_id = ?";
$stmt = $conn->prepare($query_saldo_atual);
$stmt->bind_param("i", $user_id); // Bind user_id como inteiro
$stmt->execute();
$resultado_saldo_atual = $stmt->get_result();

if ($resultado_saldo_atual->num_rows > 0) {
    $saldo_atual = $resultado_saldo_atual->fetch_assoc()['saldo'];
} else {
    $mensagem = "Erro: Carteira não encontrada.";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carteira - FelixBus</title>
    <link rel="stylesheet" href="styleIndex.css">
</head>
<body>
    <?php require 'PHP/navbar.php'; ?>

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

    </style>

    <main class="container">
        <h1>Gerir Carteira</h1>

        <!-- Mensagem de erro ou sucesso -->
        <?php if ($mensagem): ?>
            <div class="mensagem">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <!-- Exibição do saldo atual -->
        <section class="saldo-section">
            <h2>Saldo Atual</h2>
            <div class="saldo">
                <span>€<?= number_format($saldo_atual, 2, ',', '.') ?></span>
            </div>
        </section>

        <!-- Formulário para carregar ou levantar saldo -->
        <section class="form-section">
            <form method="POST" action="carteira.php" class="carteira-form">
                <label for="valor">Valor:</label>
                <input type="number" id="valor" name="valor" step="0.01" placeholder="Insira o valor" required>
                <div class="button-group">
                    <button type="submit" name="tipo_operacao" value="carregamento" class="btn btn-carregar">Carregar Saldo</button>
                    <button type="submit" name="tipo_operacao" value="levantamento" class="btn btn-levantar">Levantar Saldo</button>
                </div>
            </form>
        </section>

        <!-- Histórico de transações -->
        <section class="historico-section">
            <h2>Histórico de Transações</h2>
            <ul class="historico-list">
                <?php
                // Apresenta o histórico de transações do usuário
                $query_historico = "SELECT valor, tipo, data_transacao 
                                    FROM transacoes WHERE utilizador_id = ? 
                                    ORDER BY data_transacao DESC";
                $stmt = $conn->prepare($query_historico);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $historico = $stmt->get_result();

                if ($historico) {
                    while ($linha = $historico->fetch_assoc()) {
                        echo "<li><span class='tipo'>{$linha['tipo']}</span> de <span class='valor'>€{$linha['valor']}</span> em <span class='data'>{$linha['data_transacao']}</span></li>";
                    }
                } else {
                    echo "<li>Erro ao obter histórico de transações.</li>";
                }
                ?>
            </ul>
        </section>
    </main>

    <?php require 'PHP/footer.php'; ?>
</body>
</html>
