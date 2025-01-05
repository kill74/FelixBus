<?php
// Exibir erros (útil durante o desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar a sessão e ligar à base de dados
session_start();
require_once 'db_connection.php';

$mensagem = ""; // Variável para guardar mensagens de sucesso ou erro

// Verificar se o utilizador está autenticado e é um funcionário ou administrador
if (!isset($_SESSION['user_id']) || ($_SESSION['tipo_utilizador'] != 2 && $_SESSION['tipo_utilizador'] != 3)) {
    die("Erro: Acesso não autorizado.");
}

// Função para sanitizar entradas do utilizador
function sanitizarEntrada($dados) {
    return htmlspecialchars(stripslashes(trim($dados)));
}

// Processar o formulário de atualização de saldo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atualizar_saldo'])) {
    $utilizador_id = (int) $_POST['utilizador_id']; // ID do utilizador
    $novo_saldo = filter_var($_POST['novo_saldo'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // Sanitiza o saldo

    // Verificar se o saldo é válido (não pode ser negativo)
    if ($novo_saldo >= 0) {
        // Buscar o ID da carteira associada ao utilizador
        $query = "SELECT id FROM carteira WHERE utilizador_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $utilizador_id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $carteira = $resultado->fetch_assoc();
            $carteira_id = $carteira['id']; // ID da carteira

            // Atualizar o saldo na tabela carteira
            $query = "UPDATE carteira SET saldo = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("di", $novo_saldo, $carteira_id); // "di" = double (saldo), integer (ID)
            $stmt->execute();

            // Registar a transação na tabela transacoes
            $query = "INSERT INTO transacoes (utilizador_id, carteira_origem, carteira_destino, valor, tipo, data_transacao)
                      VALUES (?, ?, ?, ?, 'carregamento', NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iiid", $utilizador_id, $carteira_id, $carteira_id, $novo_saldo); // "iiid" = integer, integer, integer, double
            $stmt->execute();

            $mensagem = "Saldo atualizado com sucesso!";
        } else {
            $mensagem = "Erro: Carteira não encontrada para este utilizador.";
        }
    } else {
        $mensagem = "Erro: O saldo não pode ser negativo.";
    }
}

// Processar o formulário de edição de bilhete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar_bilhete'])) {
    $bilhete_id = (int) $_POST['bilhete_id']; // ID do bilhete
    $novo_estado = sanitizarEntrada($_POST['novo_estado']); // Novo estado selecionado

    // Verificar se o estado é válido
    if (in_array($novo_estado, ['comprado', 'usado', 'cancelado'])) {
        // Atualizar o estado do bilhete na tabela bilhetes
        $query = "UPDATE bilhetes SET estado = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $novo_estado, $bilhete_id); // "si" = string (estado), integer (ID)
        $stmt->execute();

        $mensagem = "Estado do bilhete atualizado com sucesso!";
    } else {
        $mensagem = "Erro: Estado do bilhete inválido.";
    }
}

// Buscar todos os utilizadores (clientes) e seus saldos
$query = "SELECT u.id, u.nome, c.saldo
          FROM utilizadores u
          LEFT JOIN carteira c ON u.id = c.utilizador_id
          WHERE u.tipo_utilizador_id = 1"; // Apenas clientes
$result = $conn->query($query);
$utilizadores = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Buscar todos os bilhetes com informações detalhadas
$query = "SELECT b.id, u.nome AS cliente, r.origem, r.destino, r.data, r.hora, b.estado
          FROM bilhetes b
          JOIN utilizadores u ON b.utilizador_id = u.id
          JOIN rotas r ON b.rota_id = r.id";
$result = $conn->query($query);
$bilhetes = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área de Gestão - FelixBus</title>
    <link rel="stylesheet" href="styleIndex.css">
    <style>
        /* Estilos CSS para a página */
        .container {
            max-width: 1200px;
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
    <?php require 'navbar.php'; ?> <!-- Inclui a barra de navegação -->

    <div class="container">
        <h1>Área de Gestão - Funcionário/Admin</h1>

        <!-- Mensagem de erro ou sucesso -->
        <?php if ($mensagem): ?>
            <div class="mensagem">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <!-- Gestão de Saldo -->
        <section>
            <h2>Gestão de Saldo dos Utilizadores</h2>
            <table>
                <thead>
                    <tr>
                        <th>Utilizador</th>
                        <th>Saldo Atual</th>
                        <th>Novo Saldo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilizadores as $utilizador): ?>
                        <tr>
                            <td><?= htmlspecialchars($utilizador['nome']) ?></td>
                            <td>€<?= number_format($utilizador['saldo'] ?? 0.00, 2, ',', '.') ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="utilizador_id" value="<?= $utilizador['id'] ?>">
                                    <input type="number" name="novo_saldo" step="0.01" placeholder="Novo Saldo" required>
                            </td>
                            <td>
                                <button type="submit" name="atualizar_saldo" class="button">Atualizar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Gestão de Bilhetes -->
        <section>
            <h2>Gestão de Bilhetes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bilhetes as $bilhete): ?>
                        <tr>
                            <td><?= htmlspecialchars($bilhete['cliente']) ?></td>
                            <td><?= htmlspecialchars($bilhete['origem']) ?></td>
                            <td><?= htmlspecialchars($bilhete['destino']) ?></td>
                            <td><?= htmlspecialchars($bilhete['data']) ?></td>
                            <td><?= htmlspecialchars($bilhete['hora']) ?></td>
                            <td><?= htmlspecialchars($bilhete['estado']) ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="bilhete_id" value="<?= $bilhete['id'] ?>">
                                    <select name="novo_estado">
                                        <option value="comprado">Comprado</option>
                                        <option value="usado">Usado</option>
                                        <option value="cancelado">Cancelado</option>
                                    </select>
                                    <button type="submit" name="editar_bilhete" class="button">Atualizar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>

    <?php require 'footer.php'; ?> <!-- Inclui o rodapé -->
</body>

</html>