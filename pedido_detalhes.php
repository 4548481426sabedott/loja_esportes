<?php
include("conexao.php");
include("header.php");

if (!esta_logado()) {
    $_SESSION['mensagem'] = [
        'tipo' => 'error',
        'texto' => 'Você precisa estar logado para acessar esta página.'
    ];
    redirect("login.php");
}

// Validar ID do pedido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['mensagem'] = [
        'tipo' => 'error',
        'texto' => 'Pedido não encontrado.'
    ];
    redirect("meus_pedidos.php");
}

$pedido_id = intval($_GET['id']);
$usuario_id = $_SESSION['usuario_id'];

// Buscar dados do pedido
$sql = "SELECT * FROM pedidos WHERE id = $pedido_id AND usuario_id = $usuario_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['mensagem'] = [
        'tipo' => 'error',
        'texto' => 'Pedido não encontrado ou você não tem permissão para acessá-lo.'
    ];
    redirect("meus_pedidos.php");
}

$pedido = mysqli_fetch_assoc($result);

// Buscar itens do pedido
$sql_itens = "SELECT ip.*, p.nome, p.imagem FROM itens_pedido ip 
              JOIN produtos p ON ip.produto_id = p.id 
              WHERE ip.pedido_id = $pedido_id";
$result_itens = mysqli_query($conn, $sql_itens);
$itens = [];
while ($item = mysqli_fetch_assoc($result_itens)) {
    $itens[] = $item;
}
?>

<div class="container">
    <div class="section-title">
        <h2>Detalhes do Pedido #<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></h2>
    </div>
    
    <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 2rem;">
        
        <!-- Informações do Pedido -->
        <div style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid #e2e8f0;">
            <h3 style="margin-top: 0;">Informações do Pedido</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                <div>
                    <span style="color: #666; font-size: 0.875rem;">Data do Pedido</span>
                    <p style="font-weight: bold; margin: 0.5rem 0 0 0;"><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></p>
                </div>
                <div>
                    <span style="color: #666; font-size: 0.875rem;">Status</span>
                    <p style="font-weight: bold; margin: 0.5rem 0 0 0; text-transform: uppercase; 
                        <?php 
                        if($pedido['status'] == 'pendente') echo 'color: #f59e0b;';
                        elseif($pedido['status'] == 'pago') echo 'color: #10b981;';
                        else echo 'color: #6b7280;';
                        ?>">
                        <?php echo $pedido['status']; ?>
                    </p>
                </div>
                <div>
                    <span style="color: #666; font-size: 0.875rem;">Forma de Pagamento</span>
                    <p style="font-weight: bold; margin: 0.5rem 0 0 0; text-transform: capitalize;"><?php echo str_replace('_', ' ', $pedido['forma_pagamento']); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Itens do Pedido -->
        <div style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid #e2e8f0;">
            <h3 style="margin-top: 0;">Itens do Pedido</h3>
            <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        <th style="text-align: left; padding: 0.75rem; font-weight: bold;">Produto</th>
                        <th style="text-align: center; padding: 0.75rem; font-weight: bold;">Quantidade</th>
                        <th style="text-align: right; padding: 0.75rem; font-weight: bold;">Preço Unitário</th>
                        <th style="text-align: right; padding: 0.75rem; font-weight: bold;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 0.75rem; vertical-align: middle;">
                                <a href="produto.php?id=<?php echo $item['produto_id']; ?>" style="color: #3b82f6; text-decoration: none;">
                                    <?php echo htmlspecialchars($item['nome']); ?>
                                </a>
                            </td>
                            <td style="padding: 0.75rem; text-align: center;"><?php echo $item['quantidade']; ?></td>
                            <td style="padding: 0.75rem; text-align: right;"><?php echo formatar_preco($item['preco_unitario']); ?></td>
                            <td style="padding: 0.75rem; text-align: right; font-weight: bold;">
                                <?php echo formatar_preco($item['preco_unitario'] * $item['quantidade']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Endereço de Entrega -->
        <div style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid #e2e8f0;">
            <h3 style="margin-top: 0;">Endereço de Entrega</h3>
            <p style="margin: 0.5rem 0;"><?php echo htmlspecialchars($pedido['endereco_entrega']); ?></p>
        </div>
        
        <!-- Resumo Financeiro -->
        <div style="background: #f9fafb; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                <span>Subtotal dos Produtos:</span>
                <span><?php echo formatar_preco($pedido['total'] - $pedido['frete']); ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                <span>Frete:</span>
                <span><?php echo formatar_preco($pedido['frete']); ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; border-top: 2px solid #e2e8f0; padding-top: 0.75rem; font-size: 1.25rem; font-weight: bold;">
                <span>Total:</span>
                <span style="color: #10b981;"><?php echo formatar_preco($pedido['total']); ?></span>
            </div>
        </div>
        
        <!-- Botão voltar -->
        <div style="text-align: center;">
            <a href="meus_pedidos.php">
                <button style="background: #6b7280;">← Voltar aos Pedidos</button>
            </a>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
