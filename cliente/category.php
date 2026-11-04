<?php
include("header.php");

$categoria = isset($_GET['cat']) ? mysqli_real_escape_string($conn, $_GET['cat']) : '';

if (empty($categoria)) {
    redirect("index.php");
}

$sql = "SELECT * FROM produtos WHERE categoria = '$categoria' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
$total_produtos = mysqli_num_rows($result);
?>

<div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h1 class="display-4 fw-bold mb-3">
            <i class="fas fa-tag me-2 text-primary"></i>
            <?php echo htmlspecialchars($categoria); ?>
        </h1>
        <p class="lead text-muted">
            Encontramos <strong class="text-primary"><?php echo $total_produtos; ?></strong> produtos na categoria 
            <strong><?php echo htmlspecialchars($categoria); ?></strong>
        </p>
    </div>
    
    <?php if ($total_produtos > 0): ?>
        <div class="row g-4">
            <?php while($produto = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6 col-lg-4 col-xl-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="product-card position-relative h-100">
                        <?php if($produto['estoque'] > 0): ?>
                            <div class="product-badge">✅ Em estoque</div>
                        <?php else: ?>
                            <div class="product-badge" style="background: #dc3545;">❌ Esgotado</div>
                        <?php endif; ?>
                        
                        <div style="overflow: hidden; height: 250px;">
                            <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" 
                                 alt="<?php echo htmlspecialchars($produto['nome']); ?>" 
                                 class="product-img w-100 h-100"
                                 style="object-fit: cover;"
                                 onerror="this.src='https://placehold.co/400x400/667eea/white?text=<?php echo urlencode($produto['nome']); ?>'">
                        </div>
                        
                        <div class="p-3">
                            <div class="text-muted small mb-2">
                                <i class="fas fa-building me-1"></i> <?php echo htmlspecialchars($produto['marca']); ?>
                                <span class="mx-1">•</span>
                                <i class="fas fa-tag me-1"></i> <?php echo htmlspecialchars($produto['categoria']); ?>
                            </div>
                            
                            <h5 class="fw-bold mb-2" style="min-height: 50px;">
                                <?php echo htmlspecialchars($produto['nome']); ?>
                            </h5>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h3 text-primary fw-bold mb-0">
                                    <?php echo formatar_preco($produto['preco']); ?>
                                </span>
                                <?php if($produto['estoque'] > 0 && $produto['estoque'] < 10): ?>
                                    <span class="badge bg-warning text-dark">Últimos <?php echo $produto['estoque']; ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <?php if($produto['estoque'] > 0): ?>
                                    <a href="produto.php?id=<?php echo $produto['id']; ?>" class="btn btn-outline-gradient flex-grow-1">
                                        <i class="fas fa-eye me-1"></i> Ver Produto
                                    </a>
                                    <a href="produto.php?id=<?php echo $produto['id']; ?>" class="btn btn-gradient">
                                        <i class="fas fa-cart-plus"></i>
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary flex-grow-1" disabled>Esgotado</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <h3>Nenhum produto encontrado nesta categoria</h3>
            <p class="text-muted">Que tal conferir nossos <a href="index.php#destaques">produtos em destaque</a>?</p>
            <a href="index.php" class="btn btn-gradient mt-3">
                <i class="fas fa-arrow-left me-2"></i> Voltar para Home
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include("footer.php"); ?>