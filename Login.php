<?php
session_start(); // Inicia a sessão para partilhar dados entre páginas
// Conexão com a base de dados
$pdo = new PDO("mysql:host=localhost;dbname=trabalho_php", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
// Função para limpar entradas do utilizador
function limpar_input($dados) {
    return htmlspecialchars(stripslashes(trim($dados)));
}
//Register
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    $username = limpar_input($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = limpar_input($_POST['password']); // A senha que o utilizador define
    try {
        // Verificar se o email já está registado
        $stmt = $pdo->prepare("SELECT id FROM login WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo "Este email já está registado!";
        } else {
            // Determinar o tipo de utilizador com base na senha fornecida
            if ($password === "246810") {
                $role = "admin";
            } elseif ($password === "10203040") {
                $role = "funcionario";
            } else {
                $role = "cliente"; // Qualquer outra senha torna o utilizador um cliente
            }
            // Criptografar a senha
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            // Inserir os dados na base de dados
            $stmt = $pdo->prepare("INSERT INTO login (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $password_hash, $role]);
            echo "Registo concluído com sucesso!";
            header("Location: login.html");
            exit();
        }
    } catch (PDOException $erro) {
        echo "Erro ao registar: " . $erro->getMessage();
    }
}
//Login
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = limpar_input($_POST['password']); // Senha introduzida pelo utilizador
        $_SESSION['erro'] = "Email inválido!";
        header("Location: login.html");
        exit();
    }
    // Procurar o utilizador na base de dados
    $stmt = $pdo->prepare("SELECT * FROM login WHERE email = ?");
    $stmt->execute([$email]);
    $utilizador = $stmt->fetch();
    // Verificar se o utilizador existe e a senha está correcta
    if ($utilizador && password_verify($password, $utilizador['password'])) {
        $_SESSION['user_id'] = $utilizador['id'];
        $_SESSION['role'] = $utilizador['role']; // Guardar o tipo de utilizador na sessão
        // Redireccionar consoante o tipo de utilizador
        if ($utilizador['role'] === 'admin' && $password === "246810") {
            header("Location: admin_area.html");
        } elseif ($utilizador['role'] === 'funcionario' && $password === "10203040") {
            header("Location: funcionario_area.html");
        } elseif ($utilizador['role'] === 'cliente') {
            header("Location: index.html");
        } else {
            $_SESSION['erro'] = "Senha incorrecta para este tipo de utilizador!";
            header("Location: login.html");
        }
        exit();
    } else {
        $_SESSION['erro'] = "Email ou senha incorrectos!";
        header("Location: login.html");
        exit();
    }
?>