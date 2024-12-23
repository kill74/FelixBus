<?php
session_start();
require_once 'PHP/db_connection.php';

$message = ""; // Variável para mensagens de erro ou sucesso

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $stmt = $conn->prepare("SELECT id, palavra_passe, tipo_utilizador_id FROM utilizadores WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($senha, $user["palavra_passe"])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['tipo_utilizador_id'] == 2 ? 'funcionario' : ($user['tipo_utilizador_id'] == 3 ? 'admin' : 'cliente');
                header("Location: index.php");
                exit();
            } else {
                $message = "Login mal sucedido! Verifique suas credenciais.";
            }
        } else {
            $message = "Login mal sucedido! Verifique suas credenciais.";
        }
        $stmt->close();
    } else {
        error_log("Erro ao preparar a consulta: " . $conn->error);
        $message = "Erro ao conectar ao sistema.";
    }
}
$conn->close();
?>