    <?php
session_start(); //Isto ira iniciar a sessao (teremos de meter isto em todas as páginas)

// Conectar ao banco de dados
$pdo = new PDO("mysql:host=localhost;dbname=trabalho_php", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Função para sanitizar entradas
function limpar_input($dados) {
    return htmlspecialchars(stripslashes(trim($dados)));
}
// Registo
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    $username = limpar_input($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    try {
        // Verificar se o email já existe na base de dados
        $stmt = $pdo->prepare("SELECT id FROM login WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo "Este email já está registado!";
        } else {
            // Encriptar a palavra-passe
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            // Guardar o utilizador na base de dados
            $stmt = $pdo->prepare("INSERT INTO login (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password_hash]);
            echo "Registo concluído com sucesso!";
            // Redirecionar para a página de login
            header("Location: login.html");
            exit();
        }
    } catch (PDOException $erro) {
        echo "Erro no registo: " . $erro->getMessage();
    }
}

// Login
// Verifica se o formulário foi submetido com o campo 'action' e se o valor dele é 'login'
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    // Ir buscar os dados do formulário
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    // Verificar se o email é válido
    if ($email === false) {
        $_SESSION['erro'] = "O email não é válido!";
        header("Location: login.html");
        exit();
    }
    // Procurar o utilizador na base de dados
    $query = "SELECT * FROM login WHERE email = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);
    $utilizador = $stmt->fetch();
    // Verificar se encontrou o utilizador e se a palavra-passe está certa
    if ($utilizador && password_verify($password, $utilizador['password'])) {
        $_SESSION['user_id'] = $utilizador['id'];
        header("Location: index.html");
        exit();
    } else {
        // Guardar a mensagem de erro
        $_SESSION['erro'] = "Email ou palavra-passe estão errados!";
        header("Location: login.html");
        exit();
    }
}

?>
