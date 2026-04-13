<?php
include("header.php");

$itens_carrinho = [];
$total = 0;

if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    $carrinho_limitado = array_slice($_SESSION['carrinho'], 0, 30);
    $quantidades = array_count_values($carrinho_limitado);
    
    // Criar array de itens únicos para controle
    $itens_processados = [];
    
    foreach ($_SESSION['carrinho'] as $index => $id) {
        // Buscar detalhes do item
        $cor = $_SESSION['carrinho_detalhes'][$index]['cor'] ?? null;
        $tamanho = $_SESSION['carrinho_detalhes'][$index]['tamanho'] ?? null;
        $chave_unica = $id . '_' . $cor . '_' . $tamanho;
        
        if (!isset($itens_processados[$chave_unica])) {
            $sql = "SELECT * FROM produtos WHERE id = " . intval($id);
            $result = mysqli_query($conn, $sql);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $produto = mysqli_fetch_assoc($result);
                $produto['quantidade'] = 1;
                $produto['cor'] = $cor;
                $produto['tamanho'] = $tamanho;
                $produto['indices'] = [$index];
                $itens_processados[$chave_unica] = $produto;
            }
        } else {
            $itens_processados[$chave_unica]['quantidade']++;
            $itens_processados[$chave_unica]['indices'][] = $index;
        }
    }
    
    // Calcular totais
    foreach ($itens_processados as $item) {
        if ($item['quantidade'] > 10) $item['quantidade'] = 10;
        $item['subtotal'] = $item['preco'] * $item['quantidade'];
        $itens_carrinho[] = $item;
        $total += $item['subtotal'];
    }
}
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="display-6 fw-bold">
            <i class="fas fa-shopping-cart text-primary me-2"></i> Meu Carrinho
        </h2>
        <a href="index.php" class="btn btn-outline-gradient px-4 py-2">
            <i class="fas fa-arrow-left me-2"></i> Continuar Comprando
        </a>
    </div>
    
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-<?php echo $_SESSION['mensagem']['tipo']; ?> alert-dismissible fade show shadow-sm">
            <i class="fas fa-<?php echo $_SESSION['mensagem']['tipo'] == 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
            <?php 
            echo htmlspecialchars($_SESSION['mensagem']['texto']);
            unset($_SESSION['mensagem']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (empty($itens_carrinho)): ?>
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="fas fa-shopping-cart fa-5x text-muted mb-3"></i>
            <h3 class="mb-3">Seu carrinho está vazio</h3>
            <p class="text-muted mb-4">Que tal conferir nossos produtos em destaque?</p>
            <a href="index.php#destaques" class="btn btn-gradient btn-lg px-5 py-3">
                <i class="fas fa-store me-2"></i> Ver Produtos
            </a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-0">
                        <?php foreach ($itens_carrinho as $item): ?>
                            <div class="d-flex p-4 border-bottom align-items-center">
                                <img src="<?php echo htmlspecialchars($item['imagem']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['nome']); ?>" 
                                     style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;"
                                     onerror="this.src='https://placehold.co/100x100/667eea/white?text=<?php echo urlencode($item['nome']); ?>'">
                                
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold mb-2"><?php echo htmlspecialchars($item['nome']); ?></h5>
                                    <?php if(!empty($item['cor'])): ?>
                                        <span class="badge bg-light text-dark me-2 mb-1">
                                            <i class="fas fa-palette"></i> Cor: <?php echo htmlspecialchars($item['cor']); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if(!empty($item['tamanho'])): ?>
                                        <span class="badge bg-light text-dark mb-1">
                                            <i class="fas fa-ruler"></i> Tamanho: <?php echo htmlspecialchars($item['tamanho']); ?>
                                        </span>
                                    <?php endif; ?>
                                    <div class="mt-2">
                                        <small class="text-muted">Preço unitário: <?php echo formatar_preco($item['preco']); ?></small>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center border rounded">
                                        <a href="atualizar_carrinho.php?id=<?php echo $item['id']; ?>&acao=remover&index=<?php echo $item['indices'][0]; ?>" 
                                           class="btn btn-link text-dark p-2 text-decoration-none">
                                            <i class="fas fa-minus"></i>
                                        </a>
                                        <span class="px-3 fw-bold"><?php echo $item['quantidade']; ?></span>
                                        <a href="atualizar_carrinho.php?id=<?php echo $item['id']; ?>&acao=adicionar" 
                                           class="btn btn-link text-dark p-2 text-decoration-none">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                    
                                    <div class="text-end">
                                        <div class="fw-bold text-primary h5 mb-1">
                                            <?php echo formatar_preco($item['subtotal']); ?>
                                        </div>
                                        <a href="remover_carrinho.php?id=<?php echo $item['id']; ?>&index=<?php echo $item['indices'][0]; ?>" 
                                           class="text-danger small text-decoration-none"
                                           onclick="return confirm('Remover este item do carrinho?')">
                                            <i class="fas fa-trash-alt me-1"></i> Remover
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 100px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Resumo do Pedido</h5>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <span class="fw-bold"><?php echo formatar_preco($total); ?></span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Frete</span>
                            <span class="text-success">
                                <?php echo $total >= 299 ? '<i class="fas fa-check-circle me-1"></i> Grátis' : 'A calcular'; ?>
                            </span>
                        </div>
                        
                        <?php if($total >= 299): ?>
                            <div class="alert alert-success mb-3 py-2">
                                <i class="fas fa-truck me-2"></i> Parabéns! Você ganhou frete grátis!
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-3 py-2">
                                <i class="fas fa-info-circle me-2"></i> Faltam R$ <?php echo number_format(299 - $total, 2, ',', '.'); ?> para frete grátis
                            </div>
                        <?php endif; ?>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5 fw-bold">Total</span>
                            <span class="h5 fw-bold text-primary"><?php echo formatar_preco($total); ?></span>
                        </div>
                        
                        <?php if (esta_logado()): ?>
                            <a href="checkout.php" class="btn btn-gradient w-100 py-3 fs-5">
                                <i class="fas fa-credit-card me-2"></i> Finalizar Compra
                            </a>
                        <?php else: ?>
                            <button onclick="abrirModalLogin()" class="btn btn-gradient w-100 py-3 fs-5">
                                <i class="fas fa-sign-in-alt me-2"></i> Fazer Login para Finalizar
                            </button>
                        <?php endif; ?>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-lock me-1"></i> Compra 100% segura
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de Login -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Faça seu Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Senha</label>
                        <input type="password" name="senha" class="form-control form-control-lg" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-gradient w-100 py-2 fs-5">
                        Entrar
                    </button>
                </form>
                <p class="text-center mt-3 mb-0">
                    Não tem conta? <a href="cadastro.php" class="text-primary fw-bold">Cadastre-se</a>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        transition: all 0.3s;
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
    .btn-outline-gradient {
        background: transparent;
        border: 2px solid #667eea;
        color: #667eea;
        transition: all 0.3s;
    }
    .btn-outline-gradient:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
    }
</style>

<script>
function abrirModalLogin() {
    var myModal = new bootstrap.Modal(document.getElementById('loginModal'));
    myModal.show();
}
</script>

<?php include("footer.php"); ?>