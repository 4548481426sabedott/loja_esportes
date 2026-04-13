<?php
include("header.php");
?>

<!-- Hero Section -->
<section class="hero-premium text-white text-center">
    <div class="container position-relative" style="z-index: 1;">
        <h1 class="hero-title mb-4">
            🏆 NOVA COLEÇÃO ESPORTIVA 2026
        </h1>
        <p class="hero-subtitle mb-5">
            Os melhores produtos para você performar melhor com estilo
        </p>
        <a href="#destaques" class="btn btn-gradient btn-lg">
            🔥 VER PRODUTOS EM DESTAQUE 🔥
        </a>
    </div>
</section>

<!-- Products Section -->
<div class="container my-5" id="destaques">
    <div class="text-center mb-5" data-aos="fade-up">
        <h2 class="display-5 fw-bold">
            <span style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; background-clip: text; color: transparent;">
                ✨ Produtos em Destaque ✨
            </span>
        </h2>
        <p class="text-muted fs-5">Os mais vendidos e preferidos pelos nossos clientes</p>
    </div>
    
    <?php
    $sql = "SELECT * FROM produtos WHERE destaque = TRUE ORDER BY id DESC LIMIT 12";
    $result = mysqli_query($conn, $sql);
    $tem_produtos = mysqli_num_rows($result) > 0;
    ?>
    
    <?php if ($tem_produtos): ?>
        <div class="row g-4">
            <?php while($produto = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6 col-lg-4 col-xl-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="product-card position-relative">
                        <?php if($produto['estoque'] > 0): ?>
                            <div class="product-badge">✅ Em estoque</div>
                        <?php else: ?>
                            <div class="product-badge" style="background: #dc3545;">❌ Esgotado</div>
                        <?php endif; ?>
                        
                        <div style="overflow: hidden;">
                            <img src="<?php echo $produto['imagem']; ?>" 
                                 alt="<?php echo $produto['nome']; ?>" 
                                 class="product-img"
                                 onerror="this.src='https://placehold.co/400x400/667eea/white?text=<?php echo urlencode($produto['nome']); ?>'">
                        </div>
                        
                        <div class="p-3">
                            <div class="text-muted small mb-2">
                                <i class="fas fa-tag me-1"></i> <?php echo $produto['categoria']; ?> • <?php echo $produto['marca']; ?>
                            </div>
                            <h5 class="fw-bold mb-2"><?php echo $produto['nome']; ?></h5>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h4 text-primary fw-bold mb-0">
                                    <?php echo formatar_preco($produto['preco']); ?>
                                </span>
                                <?php if($produto['estoque'] > 0): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Disponível
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <?php if($produto['estoque'] > 0): ?>
                                    <a href="produto.php?id=<?php echo $produto['id']; ?>" class="btn btn-gradient flex-grow-1">
                                        <i class="fas fa-cart-plus me-1"></i> Comprar
                                    </a>
                                    <a href="produto.php?id=<?php echo $produto['id']; ?>" class="btn btn-outline-gradient">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary flex-grow-1" disabled>
                                        <i class="fas fa-times-circle me-1"></i> Indisponível
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
            <h3>Nenhum produto em destaque no momento</h3>
            <p class="text-muted">Execute o arquivo inserir_produtos_completos.php para adicionar produtos!</p>
            <a href="inserir_produtos_completos.php" class="btn btn-gradient mt-3">
                <i class="fas fa-database me-2"></i> Inserir Produtos
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Benefits Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3" data-aos="fade-up">
                <div class="p-4">
                    <i class="fas fa-truck fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold">🚚 Frete Grátis</h5>
                    <p class="text-muted">Para compras acima de R$ 299</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="p-4">
                    <i class="fas fa-credit-card fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold">💳 Parcele em até 6x</h5>
                    <p class="text-muted">Sem juros no cartão de crédito</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="p-4">
                    <i class="fas fa-exchange-alt fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold">🔄 Trocas Fáceis</h5>
                    <p class="text-muted">30 dias para trocar seu produto</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="p-4">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold">🔒 Compra Segura</h5>
                    <p class="text-muted">Sistema protegido e criptografado</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include("footer.php"); ?>