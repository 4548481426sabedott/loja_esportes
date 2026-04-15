<?php
// Configuração para envio de e-mails
// ATENÇÃO: Configure com seus dados de e-mail!

function enviar_email_pedido($email_destino, $nome_cliente, $pedido_id, $total, $itens, $forma_pagamento, $endereco_entrega) {
    
    // Configurações do servidor de e-mail
    // Para Gmail, use estas configurações:
    $smtp_host = 'smtp.gmail.com';
    $smtp_user = 'seuemail@gmail.com';     // Mude para seu e-mail
    $smtp_pass = 'sua_senha_app';          // Mude para sua senha de app
    $smtp_port = 587;
    
    // Para teste local ou se não tiver SMTP configurado, use mail() padrão
    $usar_mail_nativo = true; // Mude para false se quiser usar SMTP
    
    if ($usar_mail_nativo) {
        return enviar_email_simples($email_destino, $nome_cliente, $pedido_id, $total);
    }
    
    // Tenta usar PHPMailer se disponível
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = $smtp_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtp_user;
            $mail->Password   = $smtp_pass;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $smtp_port;
            
            $mail->setFrom($smtp_user, 'SportShop');
            $mail->addAddress($email_destino, $nome_cliente);
            $mail->addReplyTo($smtp_user, 'SportShop');
            
            $mail->isHTML(true);
            $mail->Subject = "SportShop - Pedido #" . str_pad($pedido_id, 6, '0', STR_PAD_LEFT) . " Confirmado!";
            
            $itens_html = '';
            foreach ($itens as $item) {
                $itens_html .= "
                <tr>
                    <td style='padding: 10px; border-bottom: 1px solid #eee;'>{$item['nome']}</td>
                    <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: center;'>{$item['quantidade']}</td>
                    <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>R$ " . number_format($item['preco'], 2, ',', '.') . "</td>
                    <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>R$ " . number_format($item['subtotal'], 2, ',', '.') . "</td>
                </tr>
                ";
            }
            
            $corpo = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .container { max-width: 600px; margin: 0 auto; }
                    .header { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; }
                    .pedido-info { background: #f5f5f5; padding: 15px; border-radius: 10px; margin: 20px 0; }
                    table { width: 100%; border-collapse: collapse; }
                    th { background: #667eea; color: white; padding: 10px; }
                    .total { font-size: 18px; font-weight: bold; text-align: right; margin-top: 20px; padding-top: 10px; border-top: 2px solid #667eea; }
                    .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>🎉 Pedido Confirmado! 🎉</h1>
                    </div>
                    <div class='content'>
                        <h2>Olá, {$nome_cliente}!</h2>
                        <p>Seu pedido foi recebido com sucesso e já está sendo processado.</p>
                        
                        <div class='pedido-info'>
                            <p><strong>📦 Número do Pedido:</strong> #" . str_pad($pedido_id, 6, '0', STR_PAD_LEFT) . "</p>
                            <p><strong>📅 Data:</strong> " . date('d/m/Y H:i') . "</p>
                            <p><strong>💰 Forma de Pagamento:</strong> " . strtoupper(str_replace('_', ' ', $forma_pagamento)) . "</p>
                            <p><strong>🚚 Endereço de Entrega:</strong><br>{$endereco_entrega}</p>
                        </div>
                        
                        <h3>🛍️ Itens do Pedido:</h3>
                        <table>
                            <thead>
                                <tr><th>Produto</th><th>Qtd</th><th>Unitário</th><th>Subtotal</th></tr>
                            </thead>
                            <tbody>
                                {$itens_html}
                            </tbody>
                        </table>
                        
                        <div class='total'>
                            <p><strong>Total do Pedido:</strong> R$ " . number_format($total, 2, ',', '.') . "</p>
                        </div>
                        
                        <div style='background: #e8f5e9; padding: 15px; border-radius: 10px; margin-top: 20px;'>
                            <p style='margin: 0;'><strong>✅ Próximos passos:</strong></p>
                            <p style='margin: 5px 0 0;'>1️⃣ Aguarde a confirmação de pagamento<br>2️⃣ Após confirmado, seu pedido será enviado<br>3️⃣ Você receberá o código de rastreio por e-mail</p>
                        </div>
                    </div>
                    <div class='footer'>
                        <p>SportShop - Sua Loja de Esportes Premium</p>
                        <p>© 2024 - Todos os direitos reservados</p>
                        <p>Dúvidas? Entre em contato: contato@sportshop.com</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            $mail->Body = $corpo;
            $mail->AltBody = "Pedido #" . str_pad($pedido_id, 6, '0', STR_PAD_LEFT) . " confirmado! Total: R$ " . number_format($total, 2, ',', '.');
            
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail: " . $e->getMessage());
            return enviar_email_simples($email_destino, $nome_cliente, $pedido_id, $total);
        }
    }
    
    return enviar_email_simples($email_destino, $nome_cliente, $pedido_id, $total);
}

function enviar_email_simples($email_destino, $nome_cliente, $pedido_id, $total) {
    $assunto = "SportShop - Pedido #" . str_pad($pedido_id, 6, '0', STR_PAD_LEFT) . " Confirmado!";
    $mensagem = "Olá $nome_cliente!\n\n";
    $mensagem .= "Seu pedido #" . str_pad($pedido_id, 6, '0', STR_PAD_LEFT) . " foi confirmado.\n";
    $mensagem .= "Total do pedido: R$ " . number_format($total, 2, ',', '.') . "\n\n";
    $mensagem .= "Acesse sua conta para acompanhar o status.\n\n";
    $mensagem .= "SportShop - Sua Loja de Esportes Premium\n";
    $mensagem .= "contato@sportshop.com";
    
    $headers = "From: contato@sportshop.com\r\n";
    $headers .= "Reply-To: contato@sportshop.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    return @mail($email_destino, $assunto, $mensagem, $headers);
}
?>