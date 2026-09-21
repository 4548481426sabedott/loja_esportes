<?php
include('conexao.php');
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: admin_login.php'); exit; }

$statuses_permitidos = ['pendente', 'pago', 'enviado', 'entregue', 'cancelado'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'], $_POST['status'])) {
    $pedido_id = intval($_POST['pedido_id']);
    $status = $_POST['status'];
    if ($pedido_id > 0 && in_array($status, $statuses_permitidos, true)) {
        $status_seguro = mysqli_real_escape_string($conn, $status);
        mysqli_query($conn, "UPDATE pedidos SET status = '$status_seguro' WHERE id = $pedido_id");
    }
    header('Location: admin_pedidos.php');
    exit;
}

$sql = "SELECT p.id, p.total, p.forma_pagamento, p.status, p.data_pedido,
               u.nome AS cliente, u.email
        FROM pedidos p
        INNER JOIN usuarios u ON u.id = p.usuario_id
        ORDER BY p.data_pedido DESC";
$res = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Pedidos</title>
    <link rel="stylesheet" href="css/style.css?v=2">
</head>
<body class="admin-page">
    <div class="admin-shell admin-products">
        <header class="admin-header">
            <h1>Pedidos</h1>
            <div class="admin-user"><a href="admin.php">Voltar</a> <span>|</span> <a href="admin_logout.php">Sair</a></div>
        </header>

        <div class="admin-toolbar">
            <div>
                <span class="admin-kicker">Pedidos</span>
                <p class="admin-caption">Consulte e acompanhe os pedidos realizados na loja.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Pagamento</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res && mysqli_num_rows($res) > 0): ?>
                        <?php while ($pedido = mysqli_fetch_assoc($res)): ?>
                            <tr>
                                <td>#<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($pedido['cliente']); ?><br>
                                    <small><?php echo htmlspecialchars($pedido['email']); ?></small>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                                <td><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $pedido['forma_pagamento']))); ?></td>
                                <td><?php echo formatar_preco($pedido['total']); ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="pedido_id" value="<?php echo intval($pedido['id']); ?>">
                                        <select name="status" class="form-control" onchange="this.form.submit()">
                                            <?php foreach ($statuses_permitidos as $status): ?>
                                                <option value="<?php echo $status; ?>" <?php echo $pedido['status'] === $status ? 'selected' : ''; ?>>
                                                    <?php echo ucfirst($status); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Nenhum pedido encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
