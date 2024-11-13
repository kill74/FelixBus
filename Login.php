<?php
session_start();

// Conectar ao banco de dados
$pdo = new PDO("mysql:host=localhost;dbname=trabalho_db", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Função para sanitizar entradas
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Processar Registro
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    $username = sanitize_input($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?"); 
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            echo "Este email já está cadastrado!";
        } else {
            $senha_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $senha_hash]);
            echo "Registro realizado com sucesso!";
            // header("Location: success.html"); // Opcional: redirecionamento
            exit();
        }
    } catch(PDOException $e) {
        echo "Erro no registro: " . $e->getMessage();
    }
}

// Processar Login
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['user_id'] = $usuario['id'];
            echo "Login realizado com sucesso!";
            // header("Location: dashboard.php"); // Opcional: redirecionamento
            exit();
        } else {
            echo "Email ou senha incorretos!";
        }
    } catch(PDOException $e) {
        echo "Erro no login: " . $e->getMessage();
    }
}
?>
