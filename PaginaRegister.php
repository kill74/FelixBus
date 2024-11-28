<?php
session_start(); 

// para nao conseguir entrar pelo url
if (!isset($_SESSION ['user_id'])){
    //Se o user nao tiver feito o login ira ser redirecionado para a pagina de login
    header("Location: PaginaRegister.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
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
        .register-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #1877f2;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #1877f2;
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
        a {
            color: #1877f2;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2 style="text-align: center;">Criar Conta</h2>
        <form method="POST" action="login.php">
            <div class="form-group">
                <input type="text" name="username" required placeholder="Nome de user">
            </div>
            <div class="form-group">
                <input type="email" name="email" required placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" name="password" required placeholder="Senha">
            </div>
            <input type="hidden" name="action" value="register">
            <button type="submit">Registrar</button>
        </form>
        <div class="login-link">
            <a href="login.php">Já tem uma conta? Entre aqui</a>
        </div>
    </div>
</body>
</html>