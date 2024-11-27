<?php
// Inicia a sessão para guardar dados entre páginas
session_start(); 

// Estabelece a ligação à base de dados
// Utiliza PDO para maior segurança e melhor gestão de erros
$pdo = new PDO("mysql:host=localhost;dbname=trabalho_php", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Função para limpar e sanitizar os dados introduzidos pelo utilizador
// Previne ataques XSS e injections
function limpar_input($dados) {
    return htmlspecialchars(stripslashes(trim($dados)));
}

// Sistema de Registo
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    // Recolhe e limpa os dados do formulário
    $username = limpar_input($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = limpar_input($_POST['password']); 

    try {
        // Verifica se já existe um utilizador com este email
        $stmt = $pdo->prepare("SELECT id FROM login WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            // Se o email já existir, mostra mensagem de erro e redireciona após 3 segundos
            echo "
            <script>
                alert('Este email já está registado!');
                setTimeout(function() {
                    window.location.href = 'register.html';
                }, 3000);
            </script>";
        } else {
            // Define o tipo de utilizador com base na palavra-passe
            if ($password === "246810") {
                $role = "admin";
            } elseif ($password === "10203040") {
                $role = "funcionario";
            } else {
                $role = "cliente";
            }
            // Encripta a palavra-passe antes de guardar na base de dados
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            // Insere o novo utilizador na base de dados
            $stmt = $pdo->prepare("INSERT INTO login (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $password_hash, $role]);
            // Mostra mensagem de sucesso e redireciona após 3 segundos
            echo "
            <script>
                alert('Registo concluído com sucesso!');
                setTimeout(function() {
                    window.location.href = 'login.html';
                }, 3000);
            </script>";
        }
    } catch (PDOException $erro) {
        // Em caso de erro na base de dados, mostra mensagem de erro
        echo "
        <script>
            alert('Erro ao registar: " . $erro->getMessage() . "');
            setTimeout(function() {
                window.location.href = 'register.html';
            }, 3000);
        </script>"; 
    }
}
// Sistema de Login
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    // Recolhe e valida os dados do formulário
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = limpar_input($_POST['password']);
    // Verifica se o email é válido
    if (!$email) {
        echo "
        <script>
            alert('Email inválido!');
            setTimeout(function() {
                window.location.href = 'login.html';
            }, 3000);
        </script>";
        exit();
    }
    // Procura o utilizador na base de dados
    $stmt = $pdo->prepare("SELECT * FROM login WHERE email = ?");
    $stmt->execute([$email]);
    $utilizador = $stmt->fetch();
    // Verifica se o utilizador existe
    if (!$utilizador) {
        echo "
        <script>
            alert('Email não encontrado!');
            setTimeout(function() {
                window.location.href = 'login.html';
            }, 3000);
        </script>";
        exit();
    }
    // Verifica as credenciais e redireciona conforme o tipo de utilizador
    if ($utilizador['role'] === 'admin' && $password === "246810") {
        // Define as variáveis de sessão para o administrador
        $_SESSION['user_id'] = $utilizador['id'];
        $_SESSION['role'] = 'admin';
        $_SESSION['username'] = $utilizador['username'];
        header("Location: paginaAdmin.html");
        exit();
    } 
    else if ($utilizador['role'] === 'funcionario' && $password === "10203040") {
        // Define as variáveis de sessão para o funcionário
        $_SESSION['user_id'] = $utilizador['id'];
        $_SESSION['role'] = 'funcionario';
        $_SESSION['username'] = $utilizador['username'];
        header("Location: paginaFuncionario.html");
        exit();
    }
    else {
        // Se a palavra-passe estiver incorreta, mostra erro e redireciona após 3 segundos
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Erro de Login</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    background-color: #f0f2f5;
                }
                .error-container {
                    text-align: center;
                    background-color: white;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }
                .countdown {
                    font-size: 1.2em;
                    color: #dc3545;
                    margin-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class='error-container'>
                <h2>Palavra-passe incorreta!</h2>
                <p>Será redirecionado para a página de login em <span id='countdown'>3</span> segundos...</p>
            </div>
            <script>
                // Mostra alerta
                alert('Palavra-passe incorreta!');
                // Inicia contagem regressiva
                let timeLeft = 3;
                const countdownElement = document.getElementById('countdown');
                const countdownTimer = setInterval(function() {
                    timeLeft--;
                    countdownElement.textContent = timeLeft;
                    if (timeLeft <= 0) {
                        clearInterval(countdownTimer);
                        window.location.href = 'login.html';
                    }
                }, 1000);
                // Redireciona após 3 segundos
                setTimeout(function() {
                    window.location.href = 'login.html';
                }, 3000);
            </script>
        </body>
        </html>";
        exit();
    }
}
?>