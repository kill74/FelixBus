<?php
session_start();
require_once 'PHP/db_connection.php';

$message = ""; // Variável para mensagens de erro ou sucesso

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $stmt = $conn->prepare("SELECT id, palavra_passe FROM utilizadores WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($senha, $user["palavra_passe"])) {
                echo "<script>alert('Login bem-sucedido! Bem-vindo, utilizador ID: " . $user["id"] . "');</script>";
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

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e90ff, #00bfff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }
        .form-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            background: #1e90ff;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #104e8b;
        }
        .visitor-section p {
            margin: 15px 0 5px;
        }
        .visitor-button {
            text-decoration: none;
            color: #00bfff;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        .visitor-button:hover {
            color: #1e90ff;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <form action="index.php" method="POST">
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <br>
        <div class="visitor-section">
            <a href="Register.php" class="visitor-button">Registar se não tiver conta</a>
        </div>
        <br>
        <div class="visitor-section">
            <a href="index.php" class="visitor-button">Continuar como Visitante</a>
        </div>
    </div>

    <?php if (!empty($message)): ?>
    <script>
        alert("<?php echo $message; ?>");
    </script>
    <?php endif; ?>
</body>
</html>
