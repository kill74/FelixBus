<?php
// Conectar ao banco de dados
$pdo = new PDO("mysql:host=localhost;dbname=login_db", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Processar Registro
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    try {
        // Verifica se email já existe
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$_POST['email']]);

        if ($stmt->fetch()) {
            echo "Este email já está cadastrado!";
        } else {
            // Registra novo usuário
            $senha_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['username'], $_POST['email'], $senha_hash]);
            echo "Registro realizado com sucesso!";
        }
    } catch(PDOException $e) {
        echo "Erro no registro: " . $e->getMessage();
    }
}

// Processar Login
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$_POST['email']]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($_POST['password'], $usuario['password'])) {
            session_start();
            $_SESSION['user_id'] = $usuario['id'];
            echo "Login realizado com sucesso!";
        } else {
            echo "Email ou senha incorretos!";
        }
    } catch(PDOException $e) {
        echo "Erro no login: " . $e->getMessage();
    }
}
?>