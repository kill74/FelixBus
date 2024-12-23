<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $senha = $_POST["senha"];

    if (!empty($nome) && !empty($senha)) {
        $conn = new mysqli("localhost", "root", "", "trabalho_php");

        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }

        // Alterado para incluir todos os campos necessários
        $stmt = $conn->prepare("SELECT id, nome, palavra_passe, tipo_utilizador_id FROM utilizadores WHERE nome = ? AND estado = 'ativo'");
        $stmt->bind_param("s", $nome);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verifica a senha do admin
            if ($nome === 'admin' && $senha === 'admin') {
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["tipo_utilizador"] = $user['tipo_utilizador_id'];
                $_SESSION["nome"] = $user['nome'];
                header("Location: index.php");
                exit();
            }
            
            // Para outros usuários, verifica a senha normalmente
            if (password_verify($senha, $user['palavra_passe'])) {
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["tipo_utilizador"] = $user['tipo_utilizador_id'];
                $_SESSION["nome"] = $user['nome'];
                header("Location: index.php");
                exit();
            } else {
                $erro = "Senha incorreta.";
            }
        } else {
            $erro = "Utilizador não encontrado ou inativo.";
        }

        $stmt->close();
        $conn->close();
    } else {
        $erro = "Por favor, preencha todos os campos.";
    }
}
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
        a {
            color: #00bfff;
            text-decoration: none;
        }
        .error {
            color: #ff0000;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <form action="Login.php" method="POST">
            <input type="text" name="nome" placeholder="Nome" required>
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
        <?php
        if (!empty($erro)) {
            echo "<p class='error'>$erro</p>";
        }
        ?>
    </div>
</body>
</html>