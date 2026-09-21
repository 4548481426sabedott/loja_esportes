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
    <link rel="stylesheet" href="css/style.css?v=2">
</head>
<body class="admin-page">
    <div class="admin-shell admin-dashboard">
        <header class="admin-header">
            <h1>Administrador</h1>
            <div class="admin-user">
                Olá, <?php echo htmlspecialchars($admin_nome); ?> |
                <a href="admin_logout.php">Sair</a>
            </div>
        </header>

        <section class="admin-intro">
            <span class="admin-kicker">Painel de controle</span>
            <h2>O que você deseja gerenciar?</h2>
            <p>Acompanhe e atualize o catálogo da sua loja.</p>
        </section>

        <section class="admin-menu">
            <a class="admin-card admin-card-primary" href="admin_produtos.php">
                <span class="admin-card-icon">+</span>
                <span><strong>Produtos</strong><small>Adicionar, editar e organizar o catálogo</small></span>
                <span class="admin-card-arrow">&#8594;</span>
            </a>
            <a class="admin-card" href="admin_pedidos.php">
                <span class="admin-card-icon">&#9776;</span>
                <span><strong>Pedidos</strong><small>Consultar e acompanhar pedidos</small></span>
                <span class="admin-card-arrow">&#8594;</span>
            </a>
        </section>
    </div>
</body>
</html>
