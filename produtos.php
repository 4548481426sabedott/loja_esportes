<?php
include("header.php");

$id = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "SELECT * FROM produtos WHERE id = $id";
$result = mysqli_query($conn, $sql);
$produto = mysqli_fetch_assoc($result);

if (!$produto) {
    redirect("index.php");
}
?>

<div class="container">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <div>
            <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" style="width: 100%; border-radius: 10px;" onerror="this.src='https://placehold.co/500x500/1e293b/white?text=<?php echo urlencode($produto['nome']); ?>'">
        </div>
        
        <div>
            <div style="color: #666; margin-bottom: 1rem;"><?php echo $produto['categoria']; ?> / <?php echo $produto['marca']; ?></div>
            <h1 style="font-size: 2.5rem; color: #333; margin-bottom: 1rem;"><?php echo $produto['nome']; ?></h1>
            
            <div style="font-size: 2rem; color: #1e3c72; font-weight: bold; margin-bottom: 1.5rem;">
                <?php echo formatar_preco($produto['preco']); ?>
            </div>
            
            <div style="background: #f8f9fa; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
                <p style="color: <?php echo $produto['estoque'] > 0 ? '#28a745' : '#dc3545'; ?>; font-weight: bold;">
                    <i class="fas <?php echo $produto['estoque'] > 0 ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                    <?php echo $produto['estoque'] > 0 ? 'Em estoque - ' . $produto['estoque'] . ' unidades' : 'Esgotado'; ?>
                </p>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1rem;">Descrição do Produto</h3>
                <p style="line-height: 1.6; color: #666;"><?php echo nl2br($produto['descricao']); ?></p>
            </div>
            
            <?php if($produto['estoque'] > 0): ?>
                <div style="display: flex; gap: 1rem;">
                    <a href="adicionar_carrinho.php?id=<?php echo $produto['id']; ?>" style="flex: 2;">
                        <button style="width: 100%; padding: 1rem; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; border: none; border-radius: 10px; font-size: 1.1rem; font-weight: bold; cursor: pointer;">
                            <i class="fas fa-cart-plus"></i> Adicionar ao Carrinho
                        </button>
                    </a>
                    
                    <a href="#" style="flex: 1;" onclick="comprarAgora(<?php echo $produto['id']; ?>)">
                        <button style="width: 100%; padding: 1rem; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none; border-radius: 10px; font-size: 1.1rem; font-weight: bold; cursor: pointer;">
                            Comprar Agora
                        </button>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function comprarAgora(id) {
    window.location.href = 'adicionar_carrinho.php?id=' + id + '&checkout=1';
}
</script>

<?php include("footer.php"); ?>