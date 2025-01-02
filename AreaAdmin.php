<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_utilizador'] != 3) {
    header("Location: Login.php");
    exit();
}

// Processar ações de gestão de alertas/promoções
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao_alerta'])) {
    $acao = $_POST['acao_alerta'];
    $id = $_POST['id'] ?? null;

    if ($acao === 'adicionar') {
        $mensagem = $_POST['mensagem'];
        $sql = "INSERT INTO alertas (mensagem) VALUES ('$mensagem')";
        if ($conn->query($sql)) {
            echo '<div class="alert alert-success">Alerta/Promoção adicionado com sucesso!</div>';
        } else {
            echo '<div class="alert alert-danger">Erro ao adicionar alerta/promoção.</div>';
        }
    } elseif ($acao === 'editar' && $id) {
        $mensagem = $_POST['mensagem'];
        $sql = "UPDATE alertas SET mensagem='$mensagem' WHERE id=$id";
        if ($conn->query($sql)) {
            echo '<div class="alert alert-success">Alerta/Promoção atualizado com sucesso!</div>';
        } else {
            echo '<div class="alert alert-danger">Erro ao atualizar alerta/promoção.</div>';
        }
    } elseif ($acao === 'excluir' && $id) {
        $sql = "DELETE FROM alertas WHERE id=$id";
        if ($conn->query($sql)) {
            echo '<div class="alert alert-success">Alerta/Promoção excluído com sucesso!</div>';
        } else {
            echo '<div class="alert alert-danger">Erro ao excluir alerta/promoção.</div>';
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
    <link rel="stylesheet" href="styleAreaAdmin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Barra lateral -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link active" href="#gestao-alertas">Gestão de Alertas/Promoções</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Página Principal</a></li>
                    <li class="nav-item"><a class="nav-link" href="Login.php">Sair</a></li>
                </ul>
            </div>
        </nav>

        <!-- Conteúdo principal -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?></h1>
            </div>

            <!-- Gestão de Alertas/Promoções -->
            <section id="gestao-alertas">
                <h2>Gestão de Alertas/Promoções</h2>
                <!-- Formulário para adicionar novo alerta/promoção -->
                <form method="POST">
                    <input type="hidden" name="acao_alerta" value="adicionar">
                    <div class="mb-3">
                        <label for="mensagem" class="form-label">Alerta/Promoção</label>
                        <textarea class="form-control" id="mensagem" name="mensagem" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Adicionar Alerta/Promoção</button>
                </form>

                <!-- Lista de alertas/promoções existentes -->
                <?php
                $sql = "SELECT * FROM alertas ORDER BY data_criacao DESC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo '<h3>Alertas/Promoções Existentes</h3>';
                    echo '<table class="table table-striped table-hover">';
                    echo '<thead><tr><th>ID</th><th>Mensagem</th><th>Data</th><th>Ações</th></tr></thead>';
                    echo '<tbody>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['id'] . '</td>';
                        echo '<td>' . htmlspecialchars($row['mensagem']) . '</td>';
                        echo '<td>' . $row['data_criacao'] . '</td>';
                        echo '<td>';
                        echo '<form method="POST" style="display:inline;">
                                <input type="hidden" name="acao_alerta" value="excluir">
                                <input type="hidden" name="id" value="' . $row['id'] . '">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Tem certeza que deseja excluir este alerta/promoção?\')">Excluir</button>
                              </form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<p>Nenhum alerta/promoção encontrado.</p>';
                }
                ?>
            </section>
        </main>
    </div>
</div>

<!-- Inclui o arquivo JavaScript externo -->
<script src="script.js"></script>
</body>
</html>
