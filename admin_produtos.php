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
    <link rel="stylesheet" href="css/style.css">
    <style>.table{width:100%;border-collapse:collapse}.table th,.table td{padding:8px;border:1px solid #ddd}</style>
</head>
<body>
    <div style="max-width:1100px;margin:2rem auto;">
        <header style="display:flex;justify-content:space-between;align-items:center;">
            <h1>Produtos</h1>
            <div><a href="admin.php">Voltar</a> | <a href="admin_logout.php">Sair</a></div>
        </header>

        <p><a class="btn" href="admin_produto_editar.php">+ Novo Produto</a></p>

        <table class="table">
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
                                <img src="<?php echo htmlspecialchars($p['imagem']); ?>" style="width:64px;height:64px;object-fit:cover;border-radius:4px;">
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
                            <form method="POST" action="admin_produto_actions.php" style="display:inline;" onsubmit="return confirm('Confirma exclusão?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                <button type="submit" style="background:none;border:none;color:#c00;cursor:pointer;padding:0;">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
