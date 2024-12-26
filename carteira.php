<?php
// Ativar a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Este código é responsável por gerir o saldo da carteira de utilizadores no sistema. 
// Ele implementa duas operações principais: adicionar saldo e retirar saldo. 
// Além disso, todas as operações realizadas na carteira são registadas na tabela transacoes para efeitos de auditoria.

session_start();
require_once 'db_connection.php';

$mensagem = ""; // Variável para utilizar ao longo do código

if (!isset($_SESSION['user_id'])) {
    die("Erro: Usuário não autenticado.");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valor = (float) $_POST['valor'];
    $tipo_operacao = $_POST['tipo_operacao'];

    if ($valor > 0) {
        // Obtém o saldo atual da carteira do utilizador
        $query_saldo = "SELECT saldo FROM carteira WHERE utilizador_id = $user_id";
        $resultado = $conn->query($query_saldo);

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

            // Atualiza o saldo e regista a transação
            if (isset($novo_saldo)) {
                $conn->query("UPDATE carteira SET saldo = $novo_saldo WHERE utilizador_id = $user_id");
                $conn->query("INSERT INTO transacoes (utilizador_id, valor, tipo, data_transacao) 
                               VALUES ($user_id, $valor, '$tipo_operacao', NOW())");
                $mensagem = "Operação de $tipo_operacao realizada com sucesso!";
            }
        } else {
            $mensagem = "Erro: Carteira não encontrada.";
        }
    } else {
        $mensagem = "Erro: Valor inválido.";
    }
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

    <main class="container">
        <h1>Gerir Carteira</h1>

        <section class="form-section">
            <form method="POST" action="carteira.php" class="carteira-form">
                <label for="valor">Valor:</label>
                <input type="number" id="valor" name="valor" step="0.01" placeholder="Insira o valor" required>
                <div class="button-group">
                    <button type="submit" name="tipo_operacao" value="carregamento" class="btn btn-carregar">Carregar Saldo</button>
                    <button type="submit" name="tipo_operacao" value="levantamento" class="btn btn-levantar">Levantar Saldo</button>
                </div>
            </form>
            <p class="mensagem"><?= htmlspecialchars($mensagem) ?></p>
        </section>

        <section class="historico-section">
            <h2>Histórico de Transações</h2>
            <ul class="historico-list">
                <?php
                // Apresenta o histórico de transações do user
                $query_historico = "SELECT valor, tipo, data_transacao 
                                    FROM transacoes WHERE utilizador_id = $user_id 
                                    ORDER BY data_transacao DESC";
                $historico = $conn->query($query_historico);

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
