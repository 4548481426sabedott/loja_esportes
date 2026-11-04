<?php
include("header.php");

if (!esta_logado()) {
    $_SESSION['mensagem'] = [
        'tipo' => 'error',
        'texto' => 'Você precisa estar logado para acessar esta página.'
    ];
    redirect("login.php");
}

$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM pedidos WHERE usuario_id = $usuario_id ORDER BY data_pedido DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="container">
    <div class="section-title">
        <h2>Meus Pedidos</h2>
    </div>
    
    <?php if (mysqli_num_rows($result) > 0): ?>
        <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
            <?php while($pedido = mysqli_fetch_assoc($result)): ?>
                <div style="border-bottom: 1px solid #e2e8f0; padding: 1rem; margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="font-weight: bold;">Pedido #<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></span>
                        <span style="color: #666;"><?php echo date('d/m/Y', strtotime($pedido['data_pedido'])); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>Total: <?php echo formatar_preco($pedido['total']); ?></span>
                        <span style="text-transform: uppercase; font-size: 0.875rem; 
                            <?php 
                            if($pedido['status'] == 'pendente') echo 'color: #f59e0b;';
                            elseif($pedido['status'] == 'pago') echo 'color: #10b981;';
                            else echo 'color: #6b7280;';
                            ?>">
                            <?php echo $pedido['status']; ?>
                        </span>
                    </div>
                    <a href="pedido_detalhes.php?id=<?php echo $pedido['id']; ?>" style="font-size: 0.875rem; color: #3b82f6;">Ver detalhes →</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 3rem; background: white; border-radius: 1rem;">
            <i class="fas fa-shopping-bag" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
            <h3>Você ainda não fez nenhum pedido</h3>
            <p style="color: #666; margin-top: 1rem;">Que tal começar agora?</p>
            <a href="index.php#destaques">
                <button style="margin-top: 1rem; background: #3b82f6;">Ver produtos</button>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include("footer.php"); ?>