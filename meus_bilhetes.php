<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

$utilizador_id = $_SESSION['user_id'];
$bilhetes = [];

// Buscar bilhetes comprados pelo utilizador
$query = "SELECT b.codigo_validacao, b.data_viagem, b.estado, b.preco, b.quantidade, v.origem, v.destino, v.hora_saida 
          FROM bilhetes b
          JOIN viagens v ON b.viagem_id = v.id
          WHERE b.utilizador_id = ?
          ORDER BY b.data_viagem DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $utilizador_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bilhetes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Bilhetes - FelixBus</title>
    <link rel="stylesheet" href="style/styleIndex.css">
</head>
<body>
    <?php require 'navbar.php'; ?>

    <main class="container">
        <h1>Meus Bilhetes</h1>

        <!-- Mensagem de sucesso ou erro -->
        <?php if (isset($_GET['success'])): ?>
            <div class="mensagem sucesso">Bilhete comprado com sucesso!</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="mensagem erro">Erro ao comprar o bilhete.</div>
        <?php endif; ?>

        <!-- Lista de bilhetes comprados -->
        <?php if (count($bilhetes) > 0): ?>
            <table class="Tabela">
                <thead>
                    <tr>
                        <th>Código de Validação</th>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Data da Viagem</th>
                        <th>Hora Saída</th>
                        <th>Quantidade</th>
                        <th>Preço</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bilhetes as $bilhete): ?>
                        <tr>
                            <td><?= htmlspecialchars($bilhete['codigo_validacao']) ?></td>
                            <td><?= htmlspecialchars($bilhete['origem']) ?></td>
                            <td><?= htmlspecialchars($bilhete['destino']) ?></td>
                            <td><?= htmlspecialchars($bilhete['data_viagem']) ?></td>
                            <td><?= htmlspecialchars($bilhete['hora_saida']) ?></td>
                            <td><?= htmlspecialchars($bilhete['quantidade']) ?></td>
                            <td><?= htmlspecialchars($bilhete['preco']) ?> €</td>
                            <td><?= htmlspecialchars($bilhete['estado']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum bilhete comprado ainda.</p>
        <?php endif; ?>
    </main>

    <?php require 'footer.php'; ?>
</body>
</html>