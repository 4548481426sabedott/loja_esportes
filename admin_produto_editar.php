<?php
include('conexao.php');
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: admin_login.php'); exit; }

$is_edit = false;
$produto = [ 'id'=>0,'nome'=>'','categoria'=>'','marca'=>'','preco'=>'','preco_promocional'=>'','promocao_ativa'=>0,'descricao'=>'','imagem'=>'','estoque'=>0,'cores'=>'','tamanhos'=>'' ];
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $res = mysqli_query($conn, "SELECT * FROM produtos WHERE id = $id LIMIT 1");
    if (mysqli_num_rows($res) > 0) { $produto = mysqli_fetch_assoc($res); $is_edit = true; }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo $is_edit ? 'Editar' : 'Novo'; ?> Produto</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div style="max-width:900px;margin:2rem auto;">
        <header style="display:flex;justify-content:space-between;align-items:center;">
            <h1><?php echo $is_edit ? 'Editar' : 'Novo'; ?> Produto</h1>
            <div><a href="admin_produtos.php">Voltar</a></div>
        </header>

        <form method="POST" action="admin_produto_actions.php" enctype="multipart/form-data" style="margin-top:1rem;">
            <input type="hidden" name="action" value="save">
            <?php if ($is_edit): ?><input type="hidden" name="id" value="<?php echo $produto['id']; ?>"><?php endif; ?>

            <div class="form-group">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control" required value="<?php echo htmlspecialchars($produto['nome']); ?>">
            </div>

            <div class="form-group">
                <label>Categoria</label>
                <input type="text" name="categoria" class="form-control" value="<?php echo htmlspecialchars($produto['categoria']); ?>">
            </div>

            <div class="form-group">
                <label>Marca</label>
                <input type="text" name="marca" class="form-control" value="<?php echo htmlspecialchars($produto['marca']); ?>">
            </div>

            <div style="display:flex;gap:8px;">
                <div class="form-group" style="flex:1;">
                    <label>Preço</label>
                    <input type="number" step="0.01" name="preco" class="form-control" required value="<?php echo htmlspecialchars($produto['preco']); ?>">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Preço Promocional</label>
                    <input type="number" step="0.01" name="preco_promocional" class="form-control" value="<?php echo htmlspecialchars($produto['preco_promocional']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label><input type="checkbox" name="promocao_ativa" value="1" <?php echo (!empty($produto['promocao_ativa']) ? 'checked' : ''); ?>> Promoção ativa</label>
            </div>

            <div class="form-group">
                <label>Descrição</label>
                <textarea name="descricao" class="form-control" rows="5"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Estoque</label>
                <input type="number" name="estoque" class="form-control" value="<?php echo intval($produto['estoque']); ?>">
            </div>

            <div class="form-group">
                <label>Cores (separadas por vírgula)</label>
                <input type="text" name="cores" class="form-control" value="<?php echo htmlspecialchars($produto['cores']); ?>">
            </div>

            <div class="form-group">
                <label>Tamanhos (separados por vírgula)</label>
                <input type="text" name="tamanhos" class="form-control" value="<?php echo htmlspecialchars($produto['tamanhos']); ?>">
            </div>

            <div class="form-group">
                <label>Imagem (envie para substituir)</label>
                <?php if (!empty($produto['imagem'])): ?>
                    <div style="margin-bottom:8px;"><img src="<?php echo htmlspecialchars($produto['imagem']); ?>" style="width:120px;height:120px;object-fit:cover;border-radius:6px;"></div>
                <?php endif; ?>
                <input type="file" name="imagem" accept="image/*">
            </div>

            <button type="submit" class="btn"><?php echo $is_edit ? 'Salvar alterações' : 'Criar produto'; ?></button>
        </form>
    </div>
</body>
</html>
