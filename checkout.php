<?php
include("header.php");
include("config_mail.php");

if (!esta_logado()) {
    $_SESSION['mensagem'] = [
        'tipo' => 'error',
        'texto' => 'Faça login para finalizar sua compra!'
    ];
    redirect("carrinho.php");
}

// Processar finalização
if (isset($_POST['finalizar'])) {
    $forma_pagamento = mysqli_real_escape_string($conn, $_POST['forma_pagamento']);
    $telefone = mysqli_real_escape_string($conn, $_POST['telefone']);
    $cep = mysqli_real_escape_string($conn, $_POST['cep']);
    $endereco = mysqli_real_escape_string($conn, $_POST['endereco']);
    $numero = mysqli_real_escape_string($conn, $_POST['numero']);
    $complemento = mysqli_real_escape_string($conn, $_POST['complemento']);
    $bairro = mysqli_real_escape_string($conn, $_POST['bairro']);
    $cidade = mysqli_real_escape_string($conn, $_POST['cidade']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);
    $frete_valor = floatval(str_replace(',', '.', $_POST['frete_valor'] ?? 0));
    
    $endereco_completo = "$endereco, $numero" . ($complemento ? " - $complemento" : "") . " - $bairro, $cidade/$estado - CEP: $cep";
    
    // Atualizar telefone do usuário
    $usuario_id = $_SESSION['usuario_id'];
    $update_telefone = "UPDATE usuarios SET telefone = '$telefone' WHERE id = $usuario_id";
    mysqli_query($conn, $update_telefone);
    
    // Calcular total
    $total_produtos = 0;
    $itens = [];
    if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
        $quantidades = array_count_values($_SESSION['carrinho']);
        foreach ($quantidades as $id => $qtd) {
            $sql = "SELECT preco, nome FROM produtos WHERE id = $id";
            $result = mysqli_query($conn, $sql);
            $produto = mysqli_fetch_assoc($result);
            if ($produto) {
                $subtotal = $produto['preco'] * $qtd;
                $total_produtos += $subtotal;
                $itens[] = [
                    'id' => $id,
                    'nome' => $produto['nome'],
                    'quantidade' => $qtd,
                    'preco' => $produto['preco'],
                    'subtotal' => $subtotal
                ];
            }
        }
    }
    
    $total_geral = $total_produtos + $frete_valor;
    
    if (empty($itens)) {
        $erro = "Seu carrinho está vazio!";
    } else {
        // Inserir pedido
        $sql = "INSERT INTO pedidos (usuario_id, total, forma_pagamento, endereco_entrega, status, frete) 
                VALUES ($usuario_id, $total_geral, '$forma_pagamento', '$endereco_completo', 'pendente', $frete_valor)";
        
        if (mysqli_query($conn, $sql)) {
            $pedido_id = mysqli_insert_id($conn);
            
            // Inserir itens do pedido
            foreach ($itens as $item) {
                $sql = "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) 
                        VALUES ($pedido_id, {$item['id']}, {$item['quantidade']}, {$item['preco']})";
                mysqli_query($conn, $sql);
            }
            
            // Buscar dados do usuário para o e-mail
            $sql_user = "SELECT * FROM usuarios WHERE id = $usuario_id";
            $result_user = mysqli_query($conn, $sql_user);
            $usuario_data = mysqli_fetch_assoc($result_user);
            
            // Enviar e-mail de confirmação
            $email_enviado = enviar_email_pedido(
                $usuario_data['email'],
                $usuario_data['nome'],
                $pedido_id,
                $total_geral,
                $itens,
                $forma_pagamento,
                $endereco_completo
            );
            
            // Limpar carrinho
            unset($_SESSION['carrinho']);
            unset($_SESSION['carrinho_detalhes']);
            
            $_SESSION['mensagem'] = [
                'tipo' => 'success',
                'texto' => 'Pedido realizado com sucesso! Número do pedido: #' . str_pad($pedido_id, 6, '0', STR_PAD_LEFT)
            ];
            
            redirect("pedido_sucesso.php?id=$pedido_id");
        } else {
            $erro = "Erro ao processar pedido. Tente novamente.";
        }
    }
}

// Buscar dados do usuário
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM usuarios WHERE id = $usuario_id";
$result = mysqli_query($conn, $sql);
$usuario = mysqli_fetch_assoc($result);

// Calcular total do carrinho
$total = 0;
$itens_carrinho = [];
if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
    $quantidades = array_count_values($_SESSION['carrinho']);
    foreach ($quantidades as $id => $qtd) {
        $sql = "SELECT * FROM produtos WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        $produto = mysqli_fetch_assoc($result);
        if ($produto) {
            $produto['quantidade'] = $qtd;
            $produto['subtotal'] = $produto['preco'] * $qtd;
            $itens_carrinho[] = $produto;
            $total += $produto['subtotal'];
        }
    }
}

// Se carrinho vazio, redirecionar
if (empty($itens_carrinho)) {
    $_SESSION['mensagem'] = [
        'tipo' => 'error',
        'texto' => 'Seu carrinho está vazio! Adicione produtos antes de finalizar.'
    ];
    redirect("carrinho.php");
}
?>

<div class="container">
    <div class="section-title">
        <h2>Finalizar Compra</h2>
        <p>Preencha os dados abaixo para concluir seu pedido</p>
    </div>
    
    <?php if (isset($erro)): ?>
        <div class="alert alert-error"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <div class="checkout-container">
        <div class="checkout-form">
            <form method="POST" id="checkoutForm">
                <h3 style="margin-bottom: 1.5rem;">1. Dados Pessoais</h3>
                
                <div class="form-group">
                    <label>Nome Completo</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['nome']); ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($usuario['email']); ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>Telefone para Contato *</label>
                    <input type="text" class="form-control" name="telefone" id="telefone" placeholder="(11) 99999-9999" value="<?php echo htmlspecialchars($usuario['telefone']); ?>" required>
                </div>
                
                <h3 style="margin: 2rem 0 1.5rem;">2. Endereço de Entrega</h3>
                
                <div class="form-group">
                    <label>CEP *</label>
                    <input type="text" class="form-control" name="cep" id="cep" placeholder="00000-000" required>
                </div>
                
                <div class="form-group">
                    <label>Endereço *</label>
                    <input type="text" class="form-control" name="endereco" id="endereco" placeholder="Rua, Avenida..." required>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Número *</label>
                        <input type="text" class="form-control" name="numero" placeholder="123" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Complemento</label>
                        <input type="text" class="form-control" name="complemento" placeholder="Apto, Bloco...">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Bairro *</label>
                    <input type="text" class="form-control" name="bairro" id="bairro" required>
                </div>
                
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Cidade *</label>
                        <input type="text" class="form-control" name="cidade" id="cidade" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Estado *</label>
                        <select class="form-control" name="estado" id="estado" required>
                            <option value="">Selecione</option>
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AP">Amapá</option>
                            <option value="AM">Amazonas</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espírito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PR">Paraná</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SP">São Paulo</option>
                            <option value="SE">Sergipe</option>
                            <option value="TO">Tocantins</option>
                        </select>
                    </div>
                </div>
                
                <div id="freteContainer" style="display: none;">
                    <h3 style="margin: 2rem 0 1.5rem;">3. Opções de Frete</h3>
                    <div class="form-group">
                        <select class="form-control" id="freteOptions" name="frete_opcao" onchange="atualizarTotalFrete(this)">
                            <option value="">Calculando frete...</option>
                        </select>
                        <input type="hidden" name="frete_valor" id="frete_valor" value="0">
                    </div>
                </div>
                
                <h3 style="margin: 2rem 0 1.5rem;">4. Forma de Pagamento</h3>
                
                <div class="payment-methods">
                    <div class="payment-method" onclick="selecionarPagamento(this, 'cartao_credito')">
                        <i class="fas fa-credit-card"></i>
                        <p>Cartão de Crédito</p>
                        <small style="color: #666;">Até 6x sem juros</small>
                    </div>
                    
                    <div class="payment-method" onclick="selecionarPagamento(this, 'cartao_debito')">
                        <i class="fas fa-credit-card"></i>
                        <p>Cartão de Débito</p>
                        <small style="color: #666;">Pagamento na entrega</small>
                    </div>
                    
                    <div class="payment-method" onclick="selecionarPagamento(this, 'pix')">
                        <i class="fas fa-qrcode"></i>
                        <p>PIX</p>
                        <small style="color: #666;">Desconto de 5%</small>
                    </div>
                    
                    <div class="payment-method" onclick="selecionarPagamento(this, 'boleto')">
                        <i class="fas fa-barcode"></i>
                        <p>Boleto Bancário</p>
                        <small style="color: #666;">Vencimento em 3 dias</small>
                    </div>
                </div>
                
                <input type="hidden" name="forma_pagamento" id="forma_pagamento" required>
            </form>
        </div>
        
        <div class="resumo-pedido">
            <h3 style="margin-bottom: 1.5rem;">Resumo do Pedido</h3>
            
            <?php foreach ($itens_carrinho as $item): ?>
                <div class="resumo-item">
                    <span><?php echo htmlspecialchars($item['nome']); ?> <strong>(x<?php echo $item['quantidade']; ?>)</strong></span>
                    <span><?php echo formatar_preco($item['subtotal']); ?></span>
                </div>
            <?php endforeach; ?>
            
            <div class="resumo-item" id="freteResumo" style="display: none;">
                <span>Frete</span>
                <span id="freteValorResumo"><strong>A calcular</strong></span>
            </div>
            
            <div class="resumo-total">
                <span><strong>Total</strong></span>
                <span><strong id="totalGeral"><?php echo formatar_preco($total); ?></strong></span>
            </div>
            
            <button type="submit" form="checkoutForm" name="finalizar" class="btn-finalizar" id="finalizarBtn">
                <i class="fas fa-check-circle"></i> Finalizar Pedido
            </button>
            
            <p style="text-align: center; margin-top: 1rem; color: #666; font-size: 0.85rem;">
                <i class="fas fa-lock"></i> Compra segura. Dados protegidos.
            </p>
        </div>
    </div>
</div>

<script>
let pagamentoSelecionado = null;
let freteSelecionado = null;

function selecionarPagamento(elemento, tipo) {
    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('selected');
    });
    elemento.classList.add('selected');
    document.getElementById('forma_pagamento').value = tipo;
}

function buscarCEP(cep) {
    cep = cep.replace(/\D/g, '');
    
    if (cep.length === 8) {
        showLoading('Buscando CEP...');
        
        fetch(`api_cep.php?cep=${cep}`)
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (!data.error) {
                    document.querySelector('input[name="endereco"]').value = data.logradouro || '';
                    document.querySelector('input[name="bairro"]').value = data.bairro || '';
                    document.querySelector('input[name="cidade"]').value = data.localidade || '';
                    document.querySelector('select[name="estado"]').value = data.uf || '';
                    calcularFrete();
                } else {
                    mostrarMensagem('CEP não encontrado!', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                mostrarMensagem('Erro ao buscar CEP', 'error');
            });
    }
}

function calcularFrete() {
    const cep = document.getElementById('cep').value.replace(/\D/g, '');
    if (cep.length !== 8) return;
    
    const produtos = <?php 
        $produtos_json = [];
        foreach ($itens_carrinho as $item) {
            $produtos_json[] = ['id' => $item['id'], 'quantidade' => $item['quantidade']];
        }
        echo json_encode($produtos_json);
    ?>;
    
    if (produtos.length === 0) return;
    
    showLoading('Calculando frete...');
    
    fetch(`api_correios.php?cep=${cep}&produtos=${encodeURIComponent(JSON.stringify(produtos))}`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success && data.opcoes && data.opcoes.length > 0) {
                const freteContainer = document.getElementById('freteContainer');
                const freteOptions = document.getElementById('freteOptions');
                const freteResumo = document.getElementById('freteResumo');
                
                if (data.frete_gratis) {
                    freteContainer.style.display = 'none';
                    freteResumo.style.display = 'flex';
                    document.getElementById('freteValorResumo').innerHTML = '<strong class="text-success">Grátis</strong>';
                    document.getElementById('frete_valor').value = 0;
                    atualizarTotalFinal(0);
                } else {
                    freteContainer.style.display = 'block';
                    freteResumo.style.display = 'flex';
                    
                    let html = '<option value="">Selecione uma opção de frete</option>';
                    data.opcoes.forEach((opcao, index) => {
                        html += `<option value="${opcao.valor}" data-prazo="${opcao.prazo}">
                            ${opcao.nome} - ${opcao.valor_formatado} (${opcao.prazo_texto})
                        </option>`;
                    });
                    freteOptions.innerHTML = html;
                    
                    if (data.opcoes.length === 1) {
                        freteOptions.value = data.opcoes[0].valor;
                        atualizarTotalFrete(freteOptions);
                    }
                }
            } else {
                mostrarMensagem('Não foi possível calcular o frete', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Erro:', error);
            mostrarMensagem('Erro ao calcular frete', 'error');
        });
}

function atualizarTotalFrete(selectElement) {
    const valorFrete = parseFloat(selectElement.value) || 0;
    document.getElementById('frete_valor').value = valorFrete;
    const freteFormatado = valorFrete > 0 ? formatarPrecoReal(valorFrete) : 'Grátis';
    document.getElementById('freteValorResumo').innerHTML = `<strong>${freteFormatado}</strong>`;
    atualizarTotalFinal(valorFrete);
}

function atualizarTotalFinal(valorFrete) {
    const totalProdutos = <?php echo $total; ?>;
    const totalGeral = totalProdutos + valorFrete;
    document.getElementById('totalGeral').innerHTML = formatarPrecoReal(totalGeral);
}

function formatarPrecoReal(valor) {
    return 'R$ ' + valor.toFixed(2).replace('.', ',');
}

function showLoading(mensagem = 'Processando...') {
    const loading = document.createElement('div');
    loading.id = 'loadingOverlay';
    loading.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999;';
    loading.innerHTML = `<div style="background: white; padding: 20px; border-radius: 10px; text-align: center;">
        <i class="fas fa-spinner fa-spin fa-2x" style="color: #667eea;"></i>
        <p style="margin-top: 10px;">${mensagem}</p>
    </div>`;
    document.body.appendChild(loading);
}

function hideLoading() {
    const loading = document.getElementById('loadingOverlay');
    if (loading) loading.remove();
}

function mostrarMensagem(mensagem, tipo) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${tipo}`;
    alert.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideIn 0.5s;';
    alert.innerHTML = mensagem;
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.style.animation = 'slideOut 0.5s';
        setTimeout(() => alert.remove(), 500);
    }, 3000);
}

// Máscaras
document.getElementById('cep').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 8) {
        if (value.length > 5) {
            value = value.replace(/^(\d{5})(\d)/, '$1-$2');
        }
        e.target.value = value;
        if (value.length === 9) {
            buscarCEP(value);
        }
    }
});

document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 11) {
        if (value.length > 2) {
            value = value.replace(/^(\d{2})(\d)/, '($1) $2');
        }
        if (value.length > 9) {
            value = value.replace(/(\d{5})(\d{4})$/, '$1-$2');
        }
        e.target.value = value;
    }
});

document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const formaPagamento = document.getElementById('forma_pagamento').value;
    if (!formaPagamento) {
        e.preventDefault();
        mostrarMensagem('Por favor, selecione uma forma de pagamento', 'error');
    }
});
</script>

<style>
@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
@keyframes slideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}
.text-success { color: #10b981 !important; }
</style>

<?php include("footer.php"); ?>