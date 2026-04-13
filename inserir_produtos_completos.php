<?php
include("conexao.php");

// DESATIVAR VERIFICAÇÃO DE CHAVE ESTRANGEIRA
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

// ADICIONAR COLUNAS CORES E TAMANHOS SE NÃO EXISTIREM
mysqli_query($conn, "ALTER TABLE produtos ADD COLUMN IF NOT EXISTS cores TEXT");
mysqli_query($conn, "ALTER TABLE produtos ADD COLUMN IF NOT EXISTS tamanhos TEXT");

// LIMPAR PRODUTOS ANTIGOS
mysqli_query($conn, "TRUNCATE TABLE produtos");

// REATIVAR VERIFICAÇÃO DE CHAVE ESTRANGEIRA
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

$produtos = [
    // ========== CHUTEIRAS ==========
    [
        'nome' => 'Chuteira Nike Mercurial Vapor 15 Elite',
        'categoria' => 'Chuteiras',
        'marca' => 'Nike',
        'preco' => 1299.90,
        'descricao' => '🏆 CHUTEIRA ELITE - A chuteira mais rápida do mundo!<br><br>📌 TECNOLOGIAS:<br>• Cabedal em Flyknit<br>• Sola em Carbono<br>• Tecnologia Air Zoom',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/6a0b1c2d-3e4f-5a6b-7c8d-9e0f1a2b3c4d/mercurial-vapor-15-elite-fg-chuteira.png',
        'estoque' => 50,
        'destaque' => 1,
        'cores' => 'Preto/Rosa,Verde Lima,Branco/Dourado',
        'tamanhos' => '37,38,39,40,41,42,43,44'
    ],
    [
        'nome' => 'Chuteira Adidas Predator 24 Elite',
        'categoria' => 'Chuteiras',
        'marca' => 'Adidas',
        'preco' => 1199.90,
        'descricao' => '🎯 CONTROLE ABSOLUTO - A nova Predator revolucionou o futebol!',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/9a8c1d2f3e4a5b6c7d8e9f0a1b2c3d4e_9366/Chuteira_Predator_24_Elite_FG_Branco_IE7482_01_standard.jpg',
        'estoque' => 35,
        'destaque' => 1,
        'cores' => 'Preto/Vermelho,Branco/Dourado,Azul Royal',
        'tamanhos' => '37,38,39,40,41,42,43,44'
    ],
    
    // ========== CAMISAS DE TIMES ==========
    [
        'nome' => 'Camisa Brasil I 2024 - Seleção Brasileira',
        'categoria' => 'Camisas',
        'marca' => 'Nike',
        'preco' => 379.90,
        'descricao' => '🇧🇷 CAMISA OFICIAL DA SELEÇÃO BRASILEIRA - Use as cores do Hexa!',
        'imagem' => 'https://imgnike-a.akamaihd.net/1300x1300/024789IDA21.jpg',
        'estoque' => 120,
        'destaque' => 1,
        'cores' => 'Amarelo/Azul,Azul/Branco,Branco/Azul',
        'tamanhos' => 'P,M,G,GG,XG'
    ],
    [
        'nome' => 'Camisa Flamengo I 2024 - Manto Sagrado',
        'categoria' => 'Camisas',
        'marca' => 'Adidas',
        'preco' => 349.90,
        'descricao' => '🦅 Manto Sagrado - A camisa mais desejada do Brasil!',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/1a2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d_9366/Camisa_Flamengo_I_2024_Vermelho_IP1234_01_standard.jpg',
        'estoque' => 95,
        'destaque' => 1,
        'cores' => 'Vermelho/Preto,Preto/Vermelho,Branco',
        'tamanhos' => 'P,M,G,GG,XG'
    ],
    [
        'nome' => 'Camisa Corinthians I 2024 - Camisa do Povo',
        'categoria' => 'Camisas',
        'marca' => 'Nike',
        'preco' => 329.90,
        'descricao' => '🖤🤍 Camisa do Povo - 12 milhões não se enganam!',
        'imagem' => 'https://imgnike-a.akamaihd.net/1300x1300/024790IDA21.jpg',
        'estoque' => 88,
        'destaque' => 1,
        'cores' => 'Preto/Branco,Branco/Preto',
        'tamanhos' => 'P,M,G,GG,XG'
    ],
    [
        'nome' => 'Camisa Real Madrid I 2024',
        'categoria' => 'Camisas',
        'marca' => 'Adidas',
        'preco' => 399.90,
        'descricao' => '👑 CAMISA DO REAL MADRID - 14 vezes campeão europeu!',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f_9366/Camisa_Real_Madrid_I_2024_Branco_IP9012_01_standard.jpg',
        'estoque' => 110,
        'destaque' => 1,
        'cores' => 'Branco/Dourado,Preto/Dourado',
        'tamanhos' => 'P,M,G,GG,XG'
    ],
    
    // ========== TÊNIS ==========
    [
        'nome' => 'Tênis Nike Air Max 90 Essential',
        'categoria' => 'Tênis',
        'marca' => 'Nike',
        'preco' => 699.90,
        'descricao' => '👟 ÍCONE DO STREETWEAR - O clássico que nunca sai de moda!',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d/air-max-90-tenis.png',
        'estoque' => 60,
        'destaque' => 1,
        'cores' => 'Preto,Branco,Cinza,Azul Marinho',
        'tamanhos' => '37,38,39,40,41,42,43,44'
    ],
    [
        'nome' => 'Tênis Adidas Ultraboost 23',
        'categoria' => 'Tênis',
        'marca' => 'Adidas',
        'preco' => 899.90,
        'descricao' => '🏃 MÁXIMO AMORTECIMENTO - O melhor tênis para corrida!',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e_9366/Tenis_Ultraboost_23_Preto_IP5678_01_standard.jpg',
        'estoque' => 42,
        'destaque' => 1,
        'cores' => 'Preto,Branco,Cinza,Azul',
        'tamanhos' => '37,38,39,40,41,42,43,44,45'
    ],
    [
        'nome' => 'Tênis New Balance 574 Classic',
        'categoria' => 'Tênis',
        'marca' => 'New Balance',
        'preco' => 549.90,
        'descricao' => '📸 ESTILO RETRÔ - O clássico dos anos 80 renovado!',
        'imagem' => 'https://nb.scene7.com/is/image/NB/m5740gc_nb_02_i?$dw_detail_main$',
        'estoque' => 55,
        'destaque' => 1,
        'cores' => 'Marinho/Branco,Cinza/Preto,Verde',
        'tamanhos' => '37,38,39,40,41,42,43,44'
    ],
    
    // ========== MEIAS ==========
    [
        'nome' => 'Meião Nike Dri-FIT Cano Alto',
        'categoria' => 'Meias',
        'marca' => 'Nike',
        'preco' => 79.90,
        'descricao' => '🧦 MEIÃO PROFISSIONAL - O preferido dos atletas!',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/4b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e/meiao-cano-alto-dri-fit.png',
        'estoque' => 200,
        'destaque' => 1,
        'cores' => 'Branco,Preto,Vermelho,Azul',
        'tamanhos' => '37,38,39,40,41,42,43,44'
    ],
    [
        'nome' => 'Meião Adidas Climacool Cano Longo',
        'categoria' => 'Meias',
        'marca' => 'Adidas',
        'preco' => 89.90,
        'descricao' => '💨 MÁXIMA VENTILAÇÃO - Tecnologia Climacool!',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a_9366/Meiao_Adidas_Cano_Longo_Branco_IP3456_01_standard.jpg',
        'estoque' => 180,
        'destaque' => 1,
        'cores' => 'Branco,Preto,Vermelho,Azul',
        'tamanhos' => '37,38,39,40,41,42,43,44'
    ],
    
    // ========== EQUIPAMENTOS ==========
    [
        'nome' => 'Bola Nike Flight Oficial',
        'categoria' => 'Equipamentos',
        'marca' => 'Nike',
        'preco' => 299.90,
        'descricao' => '⚽ A BOLA OFICIAL - Tecnologia Aerowtrac!',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d/bola-flight-oficial.png',
        'estoque' => 50,
        'destaque' => 1,
        'cores' => 'Branco/Preto,Azul/Branco',
        'tamanhos' => 'Tamanho 5'
    ],
    [
        'nome' => 'Luva de Goleiro Predator Pro',
        'categoria' => 'Equipamentos',
        'marca' => 'Adidas',
        'preco' => 249.90,
        'descricao' => '🧤 DEFESA TOTAL - Luva profissional!',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a0b_9366/Luva_Predator_Pro_Preta_IP7890_01_standard.jpg',
        'estoque' => 35,
        'destaque' => 1,
        'cores' => 'Preto/Verde,Azul/Roxo',
        'tamanhos' => '7,8,9,10,11'
    ],
    
    // ========== ACESSÓRIOS ==========
    [
        'nome' => 'Boné Nike Heritage86',
        'categoria' => 'Acessórios',
        'marca' => 'Nike',
        'preco' => 89.90,
        'descricao' => '🧢 ESTILO E PROTEÇÃO - Boné estruturado da Nike!',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/7c8d9e0f-1a2b-3c4d-5e6f-7a8b9c0d1e2f/bone-heritage86-nike.png',
        'estoque' => 95,
        'destaque' => 1,
        'cores' => 'Preto,Branco,Azul,Vermelho',
        'tamanhos' => 'Único'
    ]
];

$inseridos = 0;
foreach ($produtos as $p) {
    $sql = "INSERT INTO produtos (nome, categoria, marca, preco, descricao, imagem, estoque, destaque, cores, tamanhos) 
            VALUES (
                '{$p['nome']}', 
                '{$p['categoria']}', 
                '{$p['marca']}', 
                {$p['preco']}, 
                '{$p['descricao']}', 
                '{$p['imagem']}', 
                {$p['estoque']}, 
                {$p['destaque']}, 
                '{$p['cores']}', 
                '{$p['tamanhos']}'
            )";
    if (mysqli_query($conn, $sql)) {
        $inseridos++;
    }
}

echo "<div style='text-align: center; padding: 50px; font-family: Arial;'>";
echo "<h1 style='color: green;'>✅ " . $inseridos . " produtos inseridos com sucesso!</h1>";
echo "<h3>📊 Resumo por categoria:</h3>";

$categorias = ['Chuteiras', 'Camisas', 'Tênis', 'Meias', 'Equipamentos', 'Acessórios'];
foreach ($categorias as $cat) {
    $sql = "SELECT COUNT(*) as total FROM produtos WHERE categoria = '$cat'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo "<p>📌 <strong>$cat:</strong> {$row['total']} produtos</p>";
}

echo "<br><br>";
echo "<a href='index.php' style='background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 12px 30px; text-decoration: none; border-radius: 10px; font-size: 18px;'>🏠 Ir para a Loja</a>";
echo "</div>";

mysqli_close($conn);
?>