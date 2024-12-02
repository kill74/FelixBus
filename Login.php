<?php

require 'PHP/db_connection.php';    

$message = ""; // Variável para armazenar a mensagem de erro

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $stmt = $conn->prepare("SELECT id, palavra_passe FROM utilizadores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($senha, $user["palavra_passe"])) {
            echo "<script>alert('Login bem-sucedido! Bem-vindo, utilizador ID: " . $user["id"] . "');</script>";
            header ("Location: index.php");
        } else {
            $message = "Senha incorreta.";
        }
    } else {
        $message = "Email não encontrado.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="style/stylePerfil.css">
 <title>Login</title>
</head>
<body>
 <div class="form-container">
 <h2>Login</h2>
 <form action="login.php" method="POST">
 <input type="email" name="email" placeholder="Email" required>
 <input type="password" name="senha" placeholder="Senha" required>
 <button type="submit">Entrar</button>
 </form>
 
 <!-- New button to visit page without login -->
 <div class="visitor-section">
     <p>Ou</p>
     <a href="pagina-visitante.php" class="visitor-button">Continuar como Visitante</a>
 </div>
 </div>
 
 <!-- Exibe pop-up caso exista mensagem de erro -->
 <?php if (!empty($message)): ?>
 <script>
alert("<?php echo $message; ?>");
</script>
 <?php endif; ?>
</body>
</html>