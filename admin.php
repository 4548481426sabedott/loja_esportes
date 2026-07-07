<?php
include('conexao.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

$admin_nome = $_SESSION['admin_nome'];

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div style="max-width:1100px;margin:2rem auto;">
        <header style="display:flex;justify-content:space-between;align-items:center;">
            <h1>Administrador</h1>
            <div>
                Olá, <?php echo htmlspecialchars($admin_nome); ?> |
                <a href="admin_logout.php">Sair</a>
            </div>
        </header>

        <section style="margin-top:2rem; display:grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap:1rem;">
            <a class="card" href="admin_produtos.php">Gerenciar Produtos</a>
            <a class="card" href="#">Gerenciar Ofertas</a>
            <a class="card" href="#">Gerenciar Pedidos</a>
            <a class="card" href="#">Configurações</a>
        </section>
    </div>
</body>
</html>
