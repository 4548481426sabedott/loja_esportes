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

        <?php if (!empty($_SESSION['pix_qr']) && $pedido['forma_pagamento'] === 'pix'): ?>
            <div style="margin-bottom: 2rem; text-align: center;">
                <h3>Pagamento via PIX</h3>
                <?php
                    $pix = $_SESSION['pix_qr'];
                    // Se retorno em JSON com campo 'payload' ou 'qr_code' exiba imagem/PNG ou payload
                    // Normalize response: some helpers return ['body' => ...], others return the payload directly
                    $pixData = [];
                    if (!empty($pix['body']) && is_array($pix['body'])) {
                        $pixData = $pix['body'];
                    } elseif (is_array($pix)) {
                        $pixData = $pix;
                    }

                    // If there's a base64 image already
                    if (!empty($pixData['qrcode_base64'])) {
                        echo '<div style="margin: 1rem 0;"><img src="' . htmlspecialchars($pixData['qrcode_base64']) . '" alt="QR Code"></div>';
                    } elseif (!empty($pixData['image'])) {
                        // some APIs return raw base64 without data: prefix
                        $img = $pixData['image'];
                        if (strpos($img, 'data:image') === false) {
                            $img = 'data:image/png;base64,' . $img;
                        }
                        echo '<div style="margin: 1rem 0;"><img src="' . htmlspecialchars($img) . '" alt="QR Code"></div>';
                    } elseif (!empty($pixData['code']) || !empty($pixData['payload'])) {
                        $payload = $pixData['code'] ?? $pixData['payload'];
                        echo '<div style="margin: 1rem 0;"><img src="https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($payload) . '&size=300x300" alt="QR Code"></div>';
                        echo '<p style="font-size:0.9rem; color:#666;">Copie o payload abaixo para pagar no seu internet banking:</p>';
                        echo '<textarea readonly style="width:100%; height:80px;">' . htmlspecialchars($payload) . '</textarea>';
                    } elseif (!empty($pix['success']) && !empty($pix['response_raw'])) {
                        // If success but unknown shape, try to decode response_raw
                        $raw = $pix['response_raw'];
                        $decoded = @json_decode($raw, true);
                        if (is_array($decoded) && !empty($decoded['qrcode_base64'])) {
                            echo '<div style="margin: 1rem 0;"><img src="' . htmlspecialchars($decoded['qrcode_base64']) . '" alt="QR Code"></div>';
                        } elseif (is_array($decoded) && !empty($decoded['code'])) {
                            $payload = $decoded['code'];
                            echo '<div style="margin: 1rem 0;"><img src="https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($payload) . '&size=300x300" alt="QR Code"></div>';
                            echo '<textarea readonly style="width:100%; height:80px;">' . htmlspecialchars($payload) . '</textarea>';
                        } else {
                            echo '<div class="alert alert-error">Não foi possível gerar o QR Code PIX automaticamente. Entre em contato com a loja.</div>';
                            // Mostrar detalhes para depuração (evite expor em produção)
                            echo '<div style="margin-top:1rem; text-align:left; max-width:560px; margin-left:auto; margin-right:auto;">';
                            if (!empty($pix['error'])) {
                                echo '<p><strong>Erro:</strong> ' . htmlspecialchars($pix['error']) . '</p>';
                            }
                            if (!empty($pix['http_code'])) {
                                echo '<p><strong>HTTP code:</strong> ' . htmlspecialchars($pix['http_code']) . '</p>';
                            }
                            if (!empty($pix['response_raw'])) {
                                echo '<details style="background:#fff; padding:0.5rem; border-radius:6px;"><summary>Resposta bruta (clique para ver)</summary><pre style="white-space:pre-wrap;">' . htmlspecialchars($pix['response_raw']) . '</pre></details>';
                            } elseif (!empty($pixData)) {
                                echo '<details style="background:#fff; padding:0.5rem; border-radius:6px;"><summary>Dados (clique para ver)</summary><pre style="white-space:pre-wrap;">' . htmlspecialchars(print_r($pixData, true)) . '</pre></details>';
                            }
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="alert alert-error">Não foi possível gerar o QR Code PIX automaticamente. Entre em contato com a loja.</div>';
                        echo '<div style="margin-top:1rem; text-align:left; max-width:560px; margin-left:auto; margin-right:auto;">';
                        if (!empty($pix['error'])) {
                            echo '<p><strong>Erro:</strong> ' . htmlspecialchars($pix['error']) . '</p>';
                        }
                        if (!empty($pix['http_code'])) {
                            echo '<p><strong>HTTP code:</strong> ' . htmlspecialchars($pix['http_code']) . '</p>';
                        }
                        if (!empty($pix['response_raw'])) {
                            echo '<details style="background:#fff; padding:0.5rem; border-radius:6px;"><summary>Resposta bruta (clique para ver)</summary><pre style="white-space:pre-wrap;">' . htmlspecialchars($pix['response_raw']) . '</pre></details>';
                        }
                        echo '</div>';
                    }
                ?>
            </div>
            <?php unset($_SESSION['pix_qr']); ?>
        <?php endif; ?>
        
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