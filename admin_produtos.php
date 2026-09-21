<?php
include('conexao.php');
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: admin_login.php'); exit; }

$res = mysqli_query($conn, "SELECT * FROM produtos ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Produtos</title>
    <link rel="stylesheet" href="css/style.css?v=2">
</head>
<body class="admin-page">
    <div class="admin-shell admin-products">
        <header class="admin-header">
            <h1>Produtos</h1>
            <div class="admin-user"><a href="admin.php">Voltar</a> <span>|</span> <a href="admin_logout.php">Sair</a></div>
        </header>

        <div class="admin-toolbar">
            <div>
                <span class="admin-kicker">Catálogo</span>
                <p class="admin-caption">Gerencie os produtos disponíveis na loja.</p>
            </div>
            <a class="admin-button" href="admin_produto_editar.php"><span>+</span> Novo produto</a>
        </div>

        <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Promoção</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while($p = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td style="width:80px;">
                            <?php if (!empty($p['imagem'])): ?>
                                <img class="admin-product-image" src="<?php echo htmlspecialchars($p['imagem']); ?>">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($p['nome']); ?></td>
                        <td><?php echo htmlspecialchars($p['categoria']); ?></td>
                        <td><?php echo formatar_preco($p['preco']); ?></td>
                        <td>
                            <?php if (!empty($p['promocao_ativa'])): ?>
                                <?php echo formatar_preco($p['preco_promocional']); ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?php echo intval($p['estoque']); ?></td>
                        <td>
                            <a href="admin_produto_editar.php?id=<?php echo $p['id']; ?>">Editar</a> |
                            <form method="POST" action="admin_produto_actions.php" class="admin-delete-form" onsubmit="return confirm('Confirma exclusão?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                <button type="submit" class="admin-delete">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
</body>
</html>
