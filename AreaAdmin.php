<?php
// Inicia a sessão para aceder às variáveis de sessão
session_start();

// Inclui o ficheiro de conexão à base de dados
require_once 'db_connection.php';

// Verifica se o utilizador está autenticado e se é um administrador (tipo_utilizador = 3)
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_utilizador'] != 3) {
    header("Location: Login.php"); // Redireciona para a página de login
    exit();
}

// Processa ações relacionadas com a gestão de horários
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao_horario'])) {
    $acao = $_POST['acao_horario']; // Ação a ser realizada (adicionar, editar, excluir)
    $id = $_POST['id'] ?? null; // ID do horário (se aplicável)

    if ($acao === 'adicionar') {
        // Adiciona um novo horário
        $origem = $_POST['origem'];
        $destino = $_POST['destino'];
        $data = $_POST['data'];
        $hora = $_POST['hora'];
        $capacidade = $_POST['capacidade'];

        $sql = "INSERT INTO rotas (origem, destino, data, hora, capacidade) VALUES ('$origem', '$destino', '$data', '$hora', $capacidade)";
        if ($conn->query($sql)) {
            echo '<div class="alert success">Horário adicionado com sucesso!</div>';
        } else {
            echo '<div class="alert error">Erro ao adicionar horário: ' . $conn->error . '</div>';
        }
    } elseif ($acao === 'excluir' && $id) {
        // Exclui um horário existente
        $sql = "DELETE FROM rotas WHERE id=$id";
        if ($conn->query($sql)) {
            echo '<div class="alert success">Horário excluído com sucesso!</div>';
        } else {
            echo '<div class="alert error">Erro ao excluir horário: ' . $conn->error . '</div>';
        }
    }
}

// Processa ações relacionadas com a gestão de alertas/promoções
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao_alerta'])) {
    $acao = $_POST['acao_alerta']; // Ação a ser realizada (adicionar, excluir)
    $id = $_POST['id'] ?? null; // ID do alerta/promoção (se aplicável)

    if ($acao === 'adicionar') {
        // Adiciona um novo alerta/promoção
        $mensagem = $_POST['mensagem'];
        $sql = "INSERT INTO alertas (mensagem) VALUES ('$mensagem')";
        if ($conn->query($sql)) {
            echo '<div class="alert success">Alerta/Promoção adicionado com sucesso!</div>';
        } else {
            echo '<div class="alert error">Erro ao adicionar alerta/promoção: ' . $conn->error . '</div>';
        }
    } elseif ($acao === 'excluir' && $id) {
        // Exclui um alerta/promoção existente
        $sql = "DELETE FROM alertas WHERE id=$id";
        if ($conn->query($sql)) {
            echo '<div class="alert success">Alerta/Promoção excluído com sucesso!</div>';
        } else {
            echo '<div class="alert error">Erro ao excluir alerta/promoção: ' . $conn->error . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="areaadmin.css">
</head>

<body>
    <div class="container">
        <!-- Barra lateral -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h2>Painel Admin</h2>
            </div>
            <ul class="nav">
                <li><a class="nav-link active" href="#gestao-horarios">Gestão de Horários</a></li>
                <li><a class="nav-link" href="#gestao-alertas">Gestão de Alertas/Promoções</a></li>
                <li><a class="nav-link" href="index.php">Página Principal</a></li>
                <li><a class="nav-link" href="Login.php">Sair</a></li>
            </ul>
        </nav>

        <!-- Conteúdo principal -->
        <main>
            <div class="header">
                <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?></h1>
            </div>

            <!-- Gestão de Horários -->
            <section id="gestao-horarios">
                <h2>Gestão de Horários</h2>
                <!-- Formulário para adicionar novo horário -->
                <form method="POST">
                    <input type="hidden" name="acao_horario" value="adicionar">
                    <div class="form-group">
                        <label for="origem">Origem</label>
                        <input type="text" id="origem" name="origem" required>
                    </div>
                    <div class="form-group">
                        <label for="destino">Destino</label>
                        <input type="text" id="destino" name="destino" required>
                    </div>
                    <div class="form-group">
                        <label for="data">Data</label>
                        <input type="date" id="data" name="data" required>
                    </div>
                    <div class="form-group">
                        <label for="hora">Hora</label>
                        <input type="time" id="hora" name="hora" required>
                    </div>
                    <div class="form-group">
                        <label for="capacidade">Capacidade</label>
                        <input type="number" id="capacidade" name="capacidade" required>
                    </div>
                    <button type="submit" class="btn-primary">Adicionar Horário</button>
                </form>

                <!-- Lista de horários existentes -->
                <div class="table-container">
                    <h3>Horários Existentes</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Origem</th>
                                <th>Destino</th>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Capacidade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM rotas ORDER BY data, hora";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $row['id'] . '</td>';
                                    echo '<td>' . htmlspecialchars($row['origem']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['destino']) . '</td>';
                                    echo '<td>' . $row['data'] . '</td>';
                                    echo '<td>' . $row['hora'] . '</td>';
                                    echo '<td>' . $row['capacidade'] . '</td>';
                                    echo '<td>';
                                    echo '<form method="POST" style="display:inline;">
                                        <input type="hidden" name="acao_horario" value="excluir">
                                        <input type="hidden" name="id" value="' . $row['id'] . '">
                                        <button type="submit" class="btn-danger" onclick="return confirm(\'Tem certeza que deseja excluir este horário?\')">Excluir</button>
                                      </form>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="7">Nenhum horário encontrado.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Gestão de Alertas/Promoções -->
            <section id="gestao-alertas">
                <h2>Gestão de Alertas/Promoções</h2>
                <!-- Formulário para adicionar novo alerta/promoção -->
                <form method="POST">
                    <input type="hidden" name="acao_alerta" value="adicionar">
                    <div class="form-group">
                        <label for="mensagem">Alerta/Promoção</label>
                        <textarea id="mensagem" name="mensagem" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Adicionar Alerta/Promoção</button>
                </form>

                <!-- Lista de alertas/promoções existentes -->
                <div class="table-container">
                    <h3>Alertas/Promoções Existentes</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Mensagem</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM alertas ORDER BY data_criacao DESC";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $row['id'] . '</td>';
                                    echo '<td>' . htmlspecialchars($row['mensagem']) . '</td>';
                                    echo '<td>' . $row['data_criacao'] . '</td>';
                                    echo '<td>';
                                    echo '<form method="POST" style="display:inline;">
                                        <input type="hidden" name="acao_alerta" value="excluir">
                                        <input type="hidden" name="id" value="' . $row['id'] . '">
                                        <button type="submit" class="btn-danger" onclick="return confirm(\'Tem certeza que deseja excluir este alerta/promoção?\')">Excluir</button>
                                      </form>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4">Nenhum alerta/promoção encontrado.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>

</html>