<?php
// Inicia a sessão para armazenar dados temporários do utilizador
session_start();

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os valores do formulário e remove espaços extras
    $nome = trim($_POST["nome"]);
    $senha = $_POST["senha"];

    // Verifica se os campos estão preenchidos
    if (!empty($nome) && !empty($senha)) {
        // Simula uma conexão com a base de dados (substitua pelo seu código real)
        $utilizadores = [
            [
                'id' => 1,
                'nome' => 'admin',
                'palavra_passe' => password_hash('admin', PASSWORD_DEFAULT),
                'tipo_utilizador_id' => 3,
                'estado' => 'ativo'
            ],
            [
                'id' => 2,
                'nome' => 'funcionario',
                'palavra_passe' => password_hash('funcionario', PASSWORD_DEFAULT),
                'tipo_utilizador_id' => 2,
                'estado' => 'ativo'
            ],
            [
                'id' => 3,
                'nome' => 'user',
                'palavra_passe' => password_hash('user', PASSWORD_DEFAULT),
                'tipo_utilizador_id' => 1,
                'estado' => 'ativo'
            ]
        ];

        // Procura o utilizador na base de dados simulada
        $user = null;
        foreach ($utilizadores as $u) {
            if ($u['nome'] === $nome && $u['estado'] === 'ativo') {
                $user = $u;
                break;
            }
        }

        // Verifica se o utilizador foi encontrado
        if ($user) {
            // Verifica a senha para o administrador e funcionário (senhas em texto plano para exemplo)
            if (($nome === 'admin' && $senha === 'admin') || ($nome === 'funcionario' && $senha === 'funcionario')) {
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["tipo_utilizador"] = $user['tipo_utilizador_id'];
                $_SESSION["nome"] = $user['nome'];
                header("Location: index.php");
                exit();
            }

            // Verifica a senha para outros utilizadores
            if (password_verify($senha, $user['palavra_passe'])) {
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["tipo_utilizador"] = $user['tipo_utilizador_id'];
                $_SESSION["nome"] = $user['nome'];
                header("Location: index.php");
                exit();
            } else {
                $erro = "Login inválido."; // Senha incorreta
            }
        } else {
            $erro = "Utilizador não encontrado ou inativo."; // Utilizador não encontrado
        }
    } else {
        $erro = "Por favor, preencha todos os campos."; // Campos vazios
    }
}

// Se o utilizador clicar em "Continuar como Visitante", redireciona para a página principal
if (isset($_GET['visitante'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleLogin.css">
    <title>Login</title>
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
            <a href="Login.php?visitante=true" class="visitor-button">Continuar como Visitante</a>
        </div>
        <?php
        // Mostra a mensagem de erro, se existir
        if (!empty($erro)) {
            echo "<p class='error'>$erro</p>";
        }
        ?>
    </div>
</body>
</html>