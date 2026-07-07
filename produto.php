<?php
include("header.php");

$id = mysqli_real_escape_string($conn, $_GET['id']);
$sql = "SELECT * FROM produtos WHERE id = $id";
$result = mysqli_query($conn, $sql);
$produto = mysqli_fetch_assoc($result);

if (!$produto) {
    redirect("index.php");
}

$cores = !empty($produto['cores']) ? explode(',', $produto['cores']) : ['Branco', 'Preto', 'Azul', 'Vermelho'];
$tamanhos = !empty($produto['tamanhos']) ? explode(',', $produto['tamanhos']) : ['P', 'M', 'G', 'GG'];
$tamanhos_desc = !empty($produto['tamanhos_desc']) ? explode(',', $produto['tamanhos_desc']) : [];

// Criar um array associativo tamanho => descrição
$tamanhos_desc_array = [];
foreach ($tamanhos_desc as $td) {
    if (strpos($td, '(') !== false) {
        $parts = explode('(', $td);
        $tamanho_key = trim($parts[0]);
        $desc_full = '(' . $parts[1];
        $tamanhos_desc_array[$tamanho_key] = $desc_full;
    }
}
?>

<div class="container my-5">
    <div class="mb-4">
        <a href="javascript:history.back()" class="btn btn-outline-gradient px-4 py-2">
            <i class="fas fa-arrow-left me-2"></i> Voltar
        </a>
    </div>
    
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <img src="<?php echo $produto['imagem']; ?>" 
                     alt="<?php echo $produto['nome']; ?>" 
                     class="img-fluid"
                     style="height: 500px; width: 100%; object-fit: cover;"
                     onerror="this.src='https://placehold.co/500x500/667eea/white?text=<?php echo urlencode($produto['nome']); ?>'">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 p-4">
                <div class="mb-3">
                    <span class="badge bg-primary bg-gradient px-3 py-2 fs-6 me-2">
                        <i class="fas fa-tag me-1"></i> <?php echo $produto['categoria']; ?>
                    </span>
                    <span class="badge bg-secondary bg-gradient px-3 py-2 fs-6">
                        <i class="fas fa-building me-1"></i> <?php echo $produto['marca']; ?>
                    </span>
                </div>
                
                <h1 class="display-5 fw-bold mb-3"><?php echo $produto['nome']; ?></h1>
                
                <div class="mb-4">
                    <span class="display-4 text-primary fw-bold">
                        <?php echo formatar_preco($produto['preco']); ?>
                    </span>
                    <span class="text-muted ms-2">
                        ou até 6x de R$ <?php echo number_format($produto['preco'] / 6, 2, ',', '.'); ?> sem juros
                    </span>
                </div>
                
                <div class="alert alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Produto disponível em estoque!</strong> - <?php echo $produto['estoque']; ?> unidades
                </div>
                
                <!-- CORES -->
                <div class="mb-4">
                    <label class="fw-bold mb-3">
                        <i class="fas fa-palette me-2"></i> Escolha a Cor:
                    </label>
                    <div class="d-flex gap-2 flex-wrap" id="colorOptions">
                        <?php foreach($cores as $cor): 
                            $corTrim = trim($cor);
                        ?>
                            <button type="button" class="color-option btn btn-outline-secondary" 
                                    data-cor="<?php echo htmlspecialchars($corTrim); ?>"
                                    style="border-radius: 25px; padding: 10px 20px;">
                                <?php echo htmlspecialchars($corTrim); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" id="corSelecionada">
                    <div id="corError" class="text-danger small mt-1" style="display: none;">Selecione uma cor</div>
                </div>
                
                <!-- TAMANHOS COM DESCRIÇÃO EM CENTÍMETROS -->
                <div class="mb-4">
                    <label class="fw-bold mb-3">
                        <i class="fas fa-ruler-combined me-2"></i> Escolha o Tamanho:
                    </label>
                    <div class="d-flex gap-2 flex-wrap" id="sizeOptions">
                        <?php foreach($tamanhos as $tamanho): 
                            $tamanhoTrim = trim($tamanho);
                            $desc_tamanho = isset($tamanhos_desc_array[$tamanhoTrim]) ? $tamanhos_desc_array[$tamanhoTrim] : '';
                        ?>
                            <button type="button" class="size-option btn btn-outline-secondary position-relative" 
                                    data-tamanho="<?php echo htmlspecialchars($tamanhoTrim); ?>"
                                    data-desc="<?php echo htmlspecialchars($desc_tamanho); ?>"
                                    style="min-width: 70px; border-radius: 10px;">
                                <?php echo htmlspecialchars($tamanhoTrim); ?>
                                <?php if($desc_tamanho): ?>
                                    <br><small style="font-size: 0.65rem;"><?php echo htmlspecialchars($desc_tamanho); ?></small>
                                <?php endif; ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" id="tamanhoSelecionado">
                    <div id="tamanhoError" class="text-danger small mt-1" style="display: none;">Selecione um tamanho</div>
                </div>
                
                <!-- DESCRIÇÃO -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i> Descrição</h5>
                    <p class="text-muted"><?php echo nl2br($produto['descricao']); ?></p>
                </div>
                
                <!-- BOTÕES -->
                <div class="d-flex gap-3">
                    <button onclick="adicionarAoCarrinho(<?php echo $produto['id']; ?>)" 
                            class="btn btn-gradient flex-grow-1 py-3 fs-5" id="addToCartBtn">
                        <i class="fas fa-cart-plus me-2"></i> Adicionar ao Carrinho
                    </button>
                    <button onclick="comprarAgora(<?php echo $produto['id']; ?>)" 
                            class="btn btn-success flex-grow-1 py-3 fs-5" id="buyNowBtn" style="background: linear-gradient(135deg, #28a745, #20c997);">
                        <i class="fas fa-bolt me-2"></i> Comprar Agora
                    </button>
                </div>
                
                <!-- INFO ADICIONAL -->
                <div class="mt-4 pt-3 border-top">
                    <div class="row text-center">
                        <div class="col-4">
                            <i class="fas fa-truck text-primary fa-lg"></i>
                            <small class="d-block text-muted mt-1">Frete Grátis*</small>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-undo-alt text-primary fa-lg"></i>
                            <small class="d-block text-muted mt-1">Trocas Fáceis</small>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-lock text-primary fa-lg"></i>
                            <small class="d-block text-muted mt-1">Compra Segura</small>
                        </div>
                    </div>
                    <small class="text-muted d-block text-center mt-3">*Frete grátis para compras acima de R$ 299</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.color-option.selected, .size-option.selected {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    border-color: transparent !important;
    transform: scale(1.05);
}
</style>

<script>
let corSelecionada = null;
let tamanhoSelecionado = null;

document.querySelectorAll('.color-option').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.color-option').forEach(b => {
            b.classList.remove('selected');
            b.classList.remove('btn-gradient');
            b.classList.add('btn-outline-secondary');
        });
        this.classList.remove('btn-outline-secondary');
        this.classList.add('selected');
        this.classList.add('btn-gradient');
        corSelecionada = this.dataset.cor;
        document.getElementById('corSelecionada').value = corSelecionada;
        document.getElementById('corError').style.display = 'none';
        verificarSelecao();
    });
});

document.querySelectorAll('.size-option').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.size-option').forEach(b => {
            b.classList.remove('selected');
            b.classList.remove('btn-gradient');
            b.classList.add('btn-outline-secondary');
        });
        this.classList.remove('btn-outline-secondary');
        this.classList.add('selected');
        this.classList.add('btn-gradient');
        tamanhoSelecionado = this.dataset.tamanho;
        document.getElementById('tamanhoSelecionado').value = tamanhoSelecionado;
        document.getElementById('tamanhoError').style.display = 'none';
        verificarSelecao();
    });
});

function verificarSelecao() {
    const addBtn = document.getElementById('addToCartBtn');
    const buyBtn = document.getElementById('buyNowBtn');
    
    if (corSelecionada && tamanhoSelecionado) {
        addBtn.disabled = false;
        buyBtn.disabled = false;
        addBtn.style.opacity = '1';
        buyBtn.style.opacity = '1';
    } else {
        addBtn.disabled = true;
        buyBtn.disabled = true;
        addBtn.style.opacity = '0.6';
        buyBtn.style.opacity = '0.6';
    }
}

function adicionarAoCarrinho(id) {
    if (!corSelecionada) {
        document.getElementById('corError').style.display = 'block';
        return;
    }
    if (!tamanhoSelecionado) {
        document.getElementById('tamanhoError').style.display = 'block';
        return;
    }
    
    fetch('salvar_selecao_produto.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            produto_id: id,
            cor: corSelecionada,
            tamanho: tamanhoSelecionado
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'adicionar_carrinho.php?id=' + id;
        }
    });
}

function comprarAgora(id) {
    if (!corSelecionada) {
        document.getElementById('corError').style.display = 'block';
        return;
    }
    if (!tamanhoSelecionado) {
        document.getElementById('tamanhoError').style.display = 'block';
        return;
    }
    
    fetch('salvar_selecao_produto.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            produto_id: id,
            cor: corSelecionada,
            tamanho: tamanhoSelecionado
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'adicionar_carrinho.php?id=' + id + '&checkout=1';
        }
    });
}
</script>

<?php include("footer.php"); ?>