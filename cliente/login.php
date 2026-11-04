<?php
include("conexao.php");
session_start();

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = md5($_POST['senha']);
    
    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        
        $_SESSION['mensagem'] = [
            'tipo' => 'success',
            'texto' => 'Login realizado com sucesso!'
        ];
        
        header("Location: index.php");
        exit;
    } else {
        $erro = "Email ou senha inválidos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SportShop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    
    <div style="background: white; border-radius: 20px; padding: 3rem; width: 100%; max-width: 400px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="color: #333; margin-bottom: 0.5rem;">Bem-vindo!</h1>
            <p style="color: #666;">Faça login para continuar</p>
        </div>
        
        <?php if (isset($erro)): ?>
            <div class="alert alert-error"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            
            <button type="submit" name="login" style="width: 100%; padding: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; font-size: 1.1rem; font-weight: bold; cursor: pointer; margin-bottom: 1rem;">
                Entrar
            </button>
            
            <p style="text-align: center;">
                Não tem conta? <a href="cadastro.php" style="color: #667eea; text-decoration: none; font-weight: bold;">Cadastre-se</a>
            </p>
        </form>
    </div>
    
</body>
</html>