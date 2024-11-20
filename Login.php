    <?php
session_start(); //Isto ira iniciar a sessao (teremos de meter isto em todas as páginas)

// Conectar ao banco de dados
$pdo = new PDO("mysql:host=localhost;dbname=trabalho_php", "root", "", [
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
        // Verificar se o email já está cadastrado
        $stmt = $pdo->prepare("SELECT id FROM login WHERE email = ?"); 
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            echo "Este email já está cadastrado!";
        } else {
            $senha_hash = password_hash($password, PASSWORD_DEFAULT); // encriptacao da password 
            $stmt = $pdo->prepare("INSERT INTO login (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $senha_hash]);
            echo "Registro realizado com sucesso!";
            // Redirecionamento após o registro (sem echo antes do header)
            header("Location: Login.html");
            exit();
        }
    } catch(PDOException $e) {
        echo "Erro no registro: " . $e->getMessage();
    }
}

// Processar Login
// Verifica se o formulário foi submetido com o campo 'action' e se o valor dele é 'login'
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = filter_var($_POST['password']);
    $stmt = $pdo->prepare("SELECT * FROM login WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    // Verifica se o usuário foi encontrado e se a senha está correta
    if ($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['user_id'] = $usuario['id'];
        header("Location: index.html");
        exit();
    } else {
        // Armazena mensagem de erro na sessão
        $_SESSION['error'] = "Email ou senha incorretos!";
        header("Location: login.html"); // Redireciona para a página de login
        exit();
    }
}
    //Login Admin (Ainda nao esta a funcionar)
    $stmt = $pdo->prepare("SELECT Nome, Password, is_admin FROM tb_utilizador WHERE Nome = :nome AND Password = :password");
    $stmt->execute([':nome' => $Nome, ':password' => $Password]);
    $login = $stmt->fetch(); 
    //Isto deve estar mal mas tenho de ver 
    if ($stmt->rowCount() > 0) {
        $login = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($login['is_admin'] == 1) {
            header('Location: PerfilAdm.html');
        } else {
            header('Location: Perfil.html');
        }
        exit();
    }    
?>
