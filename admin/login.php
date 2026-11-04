<?php
require_once __DIR__ . '/../conexao.php';
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
    <link rel="stylesheet" href="css/style.css?v=2">
</head>
<body class="admin-page admin-login-page">
    <div class="admin-login-card">
        <span class="admin-kicker">SPORTSHOP</span>
        <h2>Painel Administrativo</h2>
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
