<?php
session_start();

// Conexão com a base de dados
$pdo = new PDO("mysql:host=localhost;dbname=trabalho_php", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Função para limpar os dados
function limpar_input($dados) {
    return htmlspecialchars(trim($dados));
}

// **Registro de utilizadores**
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    $nome = limpar_input($_POST['nome']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = limpar_input($_POST['password']);
    
    if (!$email || empty($password) || empty($nome)) {
        echo "<script>alert('Por favor, preencha todos os campos!'); window.location.href = 'PaginaRegister.php';</script>";
        exit();
    }

    // Verifica se o email já está registado
    $stmt = $pdo->prepare("SELECT id FROM utilizadores WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo "<script>alert('Este email já está registado!'); window.location.href = 'PaginaRegister.php';</script>";
        exit();
    }

    // Define o tipo de utilizador com base na senha (apenas para teste)
    $tipo_utilizador_id = ($password === "admin") ? 1 : (($password === "funcionario") ? 2 : 3);
    
    // Encripta a senha e insere o utilizador
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO utilizadores (nome, email, palavra_passe, tipo_utilizador_id, estado) VALUES (?, ?, ?, ?, 'ativo')");
    $stmt->execute([$nome, $email, $password_hash, $tipo_utilizador_id]);
    
    echo "<script>alert('Registo efetuado com sucesso!'); window.location.href = 'PaginaLogin.php';</script>";
    exit();
}

// **Login de utilizadores**
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = limpar_input($_POST['password']);

    if (!$email || empty($password)) {
        echo "<script>alert('Por favor, preencha todos os campos!'); window.location.href = 'PaginaLogin.php';</script>";
        exit();
    }

    // Procura o utilizador pelo email
    $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE email = ?");
    $stmt->execute([$email]);
    $utilizador = $stmt->fetch();

    if (!$utilizador || !password_verify($password, $utilizador['palavra_passe'])) {
        echo "<script>alert('Credenciais inválidas!'); window.location.href = 'PaginaLogin.php';</script>";
        exit();
    }

    if ($utilizador['estado'] !== 'ativo') {
        echo "<script>alert('Conta inativa. Por favor, contacte o suporte.'); window.location.href = 'PaginaLogin.php';</script>";
        exit();
    }

    // Salva informações na sessão
    $_SESSION['user_id'] = $utilizador['id'];
    $_SESSION['nome'] = $utilizador['nome'];
    $_SESSION['tipo_utilizador_id'] = $utilizador['tipo_utilizador_id'];

    // Redireciona para a página correta
    if ($utilizador['tipo_utilizador_id'] == 1) {
        header("Location: paginaAdmin.php");
    } elseif ($utilizador['tipo_utilizador_id'] == 2) {
        header("Location: paginaFuncionario.php");
    } else {
        header("Location: paginaCliente.php");
    }
    exit();
}
?>
