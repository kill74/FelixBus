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
     // Obtém o valor do campo 'email' do formulário, validando-o como um endereço de email
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    // Obtém o valor do campo 'password' do formulário,com filtragem
    $password = filter_var($_POST['password']);
    $stmt = $pdo->prepare("SELECT * FROM login WHERE email = ?"); 
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();
    
    // Verifica se o usuário foi encontrado e se a senha está correta
    if ($usuario && password_verify($password, $usuario['password'])) {
        // Inicia a sessão do utilizador, guardando o ID na sessão
        $_SESSION['user_id'] = $usuario['id'];
        // Tenta redireccionar o utilizador para a página inicial (index.html)
        header("Location: index.html"); // comentei pois ainda não está a funcionar
        exit();
    } else {
        // Mensagem de erro caso o email ou a senha estejam incorretos
        echo "Email ou senha incorretos!";
    }

    //Login Admin (Ainda nao esta a funcionar)
    $stmt = $pdo->prepare("SELECT Nome, Password, is_admin FROM tb_utilizador WHERE Nome = :nome AND Password = :password");
    $stmt->execute([':nome' => $Nome, ':password' => $Password]);
    $login = $stmt->fetch(); 
    //Isto deve estar mal mas tenho de ver 
    if ($smt){
        if($smt[2] == 1){
            header('location: contaAdm.html');
        }
            header ('location: contanormal.html');
            exit(); //para acabar o procedimento 
    }
}
?>
