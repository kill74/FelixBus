<?php

//Este código é responsável por gerir o saldo da carteira de utilizadores no sistema. Ele implementa duas operações principais: adicionar saldo e retirar saldo. Além disso, todas as operações realizadas na carteira são registadas na tabela transacoes para efeitos de auditoria.

session_start();

require_once 'PHP/db_connection.php';

$mensagem = ""; //variavel para utilizar ao longo do codigo

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valor = (float) $_POST['valor'];
    $tipo_operacao = $_POST['tipo_operacao'];
    $user_id = $_SESSION['user_id'];
echo "$valor $tipo_operacao $user_id<br>";

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
        $mensagem = "Erro: Valor inválido.";The error message "Erro: Saldo insuficiente." should be translated to English for consistency with the rest of the code comments.

        
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Carteira - FelixBus</title>
</head>
<body>
    <?php require 'PHP/navbar.php'; ?>
    <h2>Gerir Carteira</h2>
    <form method="POST" action="carteira.php">
        <label>Valor:</label>
        <input type="number" step="0.01" name="valor" required>
        <button type="submit" name="tipo_operacao" value="carregamento">Carregar Saldo</button>
        <button type="submit" name="tipo_operacao" value="levantamento">Levantar Saldo</button>
    </form>
    <p><?= $mensagem ?></p>

    <h3>Histórico de Transações</h3>
    <ul>
        <?php
        // Apresenta o histórico de transações do user
        $query_historico = "SELECT valor, tipo, data_transacao 
                            FROM transacoes WHERE utilizador_id = $user_id 
                            ORDER BY data_transacao DESC";
        $historico = $conn->query($query_historico);

        while ($linha = $historico->fetch_assoc()) {
            echo "<li>{$linha['tipo']} de €{$linha['valor']} em {$linha['data_transacao']}</li>";
        }
        ?>
    </ul>
    <?php require 'PHP/footer.php'; ?>
</body>
</html>
