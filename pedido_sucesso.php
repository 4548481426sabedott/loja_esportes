<?php
include("header.php");

$id = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "SELECT p.*, u.nome, u.email 
        FROM pedidos p 
        JOIN usuarios u ON p.usuario_id = u.id 
        WHERE p.id = $id";
$result = mysqli_query($conn, $sql);
$pedido = mysqli_fetch_assoc($result);

if (!$pedido) {
    redirect("index.php");
}
?>

<div class="container">
    <div style="max-width: 600px; margin: 0 auto; text-align: center; background: white; border-radius: 20px; padding: 3rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <div style="font-size: 5rem; color: #28a745; margin-bottom: 1rem;">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1 style="color: #333; margin-bottom: 1rem;">Pedido Realizado com Sucesso!</h1>
        
        <p style="color: #666; margin-bottom: 2rem;">
            Seu pedido #<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?> foi confirmado.
        </p>
        
        <div style="background: #f8f9fa; border-radius: 10px; padding: 1.5rem; margin-bottom: 2rem; text-align: left;">
            <h3 style="margin-bottom: 1rem;">Detalhes do Pedido</h3>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: #666;">Número:</span>
                <span style="font-weight: bold;">#<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></span>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: #666;">Data:</span>
                <span><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></span>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: #666;">Total:</span>
                <span style="font-weight: bold; color: #1e3c72;"><?php echo formatar_preco($pedido['total']); ?></span>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: #666;">Forma de Pagamento:</span>
                <span style="text-transform: capitalize;"><?php echo str_replace('_', ' ', $pedido['forma_pagamento']); ?></span>
            </div>
            
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #666;">Status:</span>
                <span style="color: #ffc107; font-weight: bold; text-transform: uppercase;">
                    <?php echo $pedido['status']; ?>
                </span>
            </div>
        </div>
        
        <div style="margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem;">Informações de Contato</h3>
            <p style="color: #666;">Um email com os detalhes do pedido foi enviado para:</p>
            <p style="font-weight: bold;"><?php echo $pedido['email']; ?></p>
        </div>
        
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="index.php">
                <button style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    Continuar Comprando
                </button>
            </a>
            
            <a href="meus_pedidos.php">
                <button style="background: transparent; color: #1e3c72; border: 2px solid #1e3c72;">
                    Meus Pedidos
                </button>
            </a>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>