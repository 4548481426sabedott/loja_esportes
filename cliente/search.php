<?php
require_once __DIR__ . '/../header.php';

$termo = isset($_GET['q']) ? trim($_GET['q']) : '';
$termo_sql = mysqli_real_escape_string($conn, $termo);
$produtos = [];

if ($termo !== '') {
    $sql = "SELECT * FROM produtos
            WHERE nome LIKE '%$termo_sql%'
               OR marca LIKE '%$termo_sql%'
               OR categoria LIKE '%$termo_sql%'
               OR descricao LIKE '%$termo_sql%'
            ORDER BY id DESC";
    $resultado = mysqli_query($conn, $sql);

    if ($resultado) {
        while ($produto = mysqli_fetch_assoc($resultado)) {
            $produtos[] = $produto;
        }
    }
}
?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">
            <i class="fas fa-search me-2 text-primary"></i>Resultados da busca
        </h1>
        <p class="lead text-muted">
            <?php if ($termo !== ''): ?>
                <?php echo count($produtos); ?> produto(s) encontrado(s) para
                <strong><?php echo htmlspecialchars($termo); ?></strong>
            <?php else: ?>
                Digite um produto para começar a pesquisa.
            <?php endif; ?>
        </p>
    </div>

    <?php if (count($produtos) > 0): ?>
        <div class="row g-4">
            <?php foreach ($produtos as $produto): ?>
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="product-card position-relative h-100">
                        <?php if ($produto['estoque'] > 0): ?>
                            <div class="product-badge">✅ Em estoque</div>
                        <?php else: ?>
                            <div class="product-badge" style="background: #dc3545;">❌ Esgotado</div>
                        <?php endif; ?>

                        <div style="overflow: hidden; height: 250px;">
                            <img src="<?php echo htmlspecialchars($produto['imagem']); ?>"
                                 alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                                 class="product-img w-100 h-100"
                                 style="object-fit: cover;">
                        </div>

                        <div class="p-3">
                            <div class="text-muted small mb-2">
                                <i class="fas fa-tag me-1"></i>
                                <?php echo htmlspecialchars($produto['categoria']); ?>
                            </div>
                            <h5 class="fw-bold mb-2" style="min-height: 50px;">
                                <?php echo htmlspecialchars($produto['nome']); ?>
                            </h5>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h3 text-primary fw-bold mb-0">
                                    <?php echo formatar_preco($produto['preco']); ?>
                                </span>
                            </div>
                            <a href="produto.php?id=<?php echo (int) $produto['id']; ?>" class="btn btn-gradient w-100">
                                <i class="fas fa-eye me-1"></i> Ver Produto
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($termo !== ''): ?>
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
            <h3>Nenhum produto encontrado</h3>
            <p class="text-muted">Tente buscar por outro nome, marca ou categoria.</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>