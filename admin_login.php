<?php
include('conexao.php');
session_start();

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM admin_users WHERE email = '" . $email . "' LIMIT 1";
    $res = mysqli_query($conn, $sql);
    if (mysqli_num_rows($res) > 0) {
        $admin = mysqli_fetch_assoc($res);
        if (password_verify($senha, $admin['senha'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_nome'] = $admin['nome'];
            $_SESSION['admin_email'] = $admin['email'];
            header('Location: admin.php');
            exit;
        } else {
            $erro = 'Email ou senha inválidos.';
        }
    } else {
        $erro = 'Email ou senha inválidos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background: #f4f4f4; display:flex; align-items:center; justify-content:center; min-height:100vh;">
    <div style="background:white; padding:2rem; border-radius:8px; width:100%; max-width:420px; box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h2 style="margin-top:0;">Painel Administrativo</h2>
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
            <button type="submit" name="login" class="btn">Entrar</button>
        </form>
    </div>
</body>
</html>
