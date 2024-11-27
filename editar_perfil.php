

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="style/styleEditarPerfil.css">
</head>
<body>
    <div class="container">
        <h1>Editar Perfil</h1>
        <form action="guardar_perfil.php" method="POST">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Insira o seu nome" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Insira o seu email" required> <!--sera obrigatorio inserir o email pois esta a trocar o email -->
            </div>
            <div class="form-group">
                <label for="senha">Nova Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Insira uma nova senha">
            </div>
            <button type="submit" class="botao">Guardar</button>
        </form>
    </div>
</body>
</html>
