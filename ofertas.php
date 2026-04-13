<?php
include("header.php");

$sql = "SELECT * FROM produtos WHERE destaque = TRUE ORDER BY id DESC LIMIT 8";
$result = mysqli_query($conn, $sql);
?>

<div class="container">
    <div class="section-title">
        <h2>Ofertas Imperdíveis</h2>
        <p>Aproveite os descontos exclusivos!</p>
    </div>
    
    <div class="produtos-grid">
        <?php while($produto = mysqli_fetch_assoc($result)): ?>
            <div class="produto-card">
                <div class="produto-badge" style="background: #ef4444;">-10% OFF</div>
                <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" class="produto-imagem" onerror="this.src='https://placehold.co/300x240/1e293b/white?text=<?php echo urlencode($produto['nome']); ?>'">
                <div class="produto-info">
                    <div class="produto-categoria"><?php echo $produto['categoria']; ?> • <?php echo $produto['marca']; ?></div>
                    <div class="produto-nome"><?php echo $produto['nome']; ?></div>
                    <div class="produto-preco">
                        <span style="text-decoration: line-through; color: #999; font-size: 0.9rem;"><?php echo formatar_preco($produto['preco'] * 1.1); ?></span>
                        <span style="color: #ef4444;"><?php echo formatar_preco($produto['preco']); ?></span>
                    </div>
                    <a href="adicionar_carrinho.php?id=<?php echo $produto['id']; ?>">
                        <button class="produto-btn" style="background: #ef4444;">Comprar Agora</button>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include("footer.php"); ?>