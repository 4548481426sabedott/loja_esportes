<?php
include("conexao.php");

// DESATIVAR VERIFICAÇÃO DE CHAVE ESTRANGEIRA
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

// ADICIONAR COLUNAS CORES E TAMANHOS SE NÃO EXISTIREM
mysqli_query($conn, "ALTER TABLE produtos ADD COLUMN IF NOT EXISTS cores TEXT");
mysqli_query($conn, "ALTER TABLE produtos ADD COLUMN IF NOT EXISTS tamanhos TEXT");
mysqli_query($conn, "ALTER TABLE produtos ADD COLUMN IF NOT EXISTS tamanhos_desc TEXT");

// LIMPAR PRODUTOS ANTIGOS (opcional - comente se não quiser apagar)
// mysqli_query($conn, "TRUNCATE TABLE produtos");

// REATIVAR VERIFICAÇÃO DE CHAVE ESTRANGEIRA
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

$produtos = [];

// ==================== CHUTEIRAS (15 PRODUTOS) ====================
$chuteiras = [
    [
        'nome' => 'Chuteira Nike Mercurial Vapor 15 Elite',
        'categoria' => 'Chuteiras',
        'marca' => 'Nike',
        'preco' => 1299.90,
        'descricao' => '🏆 A CHUTEIRA MAIS RÁPIDA DO MUNDO! Tecnologia Air Zoom para máxima explosão. Cabedal em Flyknit que se adapta ao seu pé como uma luva. Sola em carbono para leveza extrema.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/6a0b1c2d-3e4f-5a6b-7c8d-9e0f1a2b3c4d/mercurial-vapor-15-elite-fg-chuteira.png',
        'estoque' => 50,
        'destaque' => 1,
        'cores' => 'Preto/Rosa,Verde Lima,Branco/Dourado,Azul Elétrico',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Chuteira Adidas Predator 24 Elite',
        'categoria' => 'Chuteiras',
        'marca' => 'Adidas',
        'preco' => 1199.90,
        'descricao' => '🎯 CONTROLE ABSOLUTO! As lâminas de borracha estratégicas aumentam o spin da bola em até 30%. Tecnologia HybridTouch 2.0 para toque de bola premium.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/9a8c1d2f3e4a5b6c7d8e9f0a1b2c3d4e_9366/Chuteira_Predator_24_Elite_FG_Branco_IE7482_01_standard.jpg',
        'estoque' => 35,
        'destaque' => 1,
        'cores' => 'Preto/Vermelho,Branco/Dourado,Azul Royal,Vermelho/Preto',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Chuteira Puma Ultra 5 Elite',
        'categoria' => 'Chuteiras',
        'marca' => 'Puma',
        'preco' => 1099.90,
        'descricao' => '⚡ A CHUTEIRA MAIS LEVE DO MERCADO! Ultraweave para máximo suporte. Sola Peba para sensação de velocidade. Ideal para atacantes velozes.',
        'imagem' => 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/107480/01/sv01/fnd/BRC/fmt/png/Ultra-5-Elite-FG-Chuteira',
        'estoque' => 40,
        'destaque' => 1,
        'cores' => 'Azul/Amarelo,Preto/Laranja,Branco/Azul,Roxo/Verde',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Chuteira New Balance Furon v7 Pro',
        'categoria' => 'Chuteiras',
        'marca' => 'New Balance',
        'preco' => 899.90,
        'descricao' => '🔥 VELOCIDADE HIPERSÔNICA! Cabedal Hypoknit para toque de bola superior. Solado de alta resposta para arrancadas explosivas.',
        'imagem' => 'https://nb.scene7.com/is/image/NB/msftv7-pro_1?$dw_detail_main$',
        'estoque' => 30,
        'destaque' => 1,
        'cores' => 'Preto/Dourado,Branco/Verde,Azul Marinho',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Chuteira Mizuno Morelia Neo IV Pro',
        'categoria' => 'Chuteiras',
        'marca' => 'Mizuno',
        'preco' => 1599.90,
        'descricao' => '👑 COURO KANGAROO PREMIUM! Conforto incomparável e toque de bola macio. Tecnologia Barefoot para sensação natural.',
        'imagem' => 'https://mizuno.com.br/media/catalog/product/cache/1/image/1000x1000/9df78eab33525d08d6e5fb8d27136e95/p1gd2483_01.jpg',
        'estoque' => 25,
        'destaque' => 1,
        'cores' => 'Branco/Dourado,Preto/Couro,Azul Royal',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Chuteira Nike Phantom GX Elite',
        'categoria' => 'Chuteiras',
        'marca' => 'Nike',
        'preco' => 1249.90,
        'descricao' => '🎨 CONTROLE CRIATIVO! Design inovador para dribles desconcertantes. Tecnologia Gripknit para toque texturizado.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/7b1c2d3e-4f5a-6b7c-8d9e-0f1a2b3c4d5e/phantom-gx-elite-fg-chuteira.png',
        'estoque' => 45,
        'destaque' => 1,
        'cores' => 'Verde/Preto,Branco/Rosa,Azul Claro',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Chuteira Adidas X Crazyfast Elite',
        'categoria' => 'Chuteiras',
        'marca' => 'Adidas',
        'preco' => 1149.90,
        'descricao' => '💨 FEITA PARA VELOCIDADE EXTREMA! Camada Aeropacity para toque de seco. Sola com Speedframe para resposta rápida.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e_9366/Chuteira_X_Crazyfast_Elite_FG_Preto_IH1234_01_standard.jpg',
        'estoque' => 38,
        'destaque' => 1,
        'cores' => 'Preto/Verde,Branco/Preto,Azul/Vermelho',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Chuteira Umbro Speciali Pro',
        'categoria' => 'Chuteiras',
        'marca' => 'Umbro',
        'preco' => 699.90,
        'descricao' => '⭐ CLÁSSICO MODERNIZADO! Couro premium para toque macio. Design tradicional com tecnologia atual.',
        'imagem' => 'https://umbro.com.br/cdn/shop/files/SPECIALI_PRO_01.png?v=123456',
        'estoque' => 50,
        'destaque' => 0,
        'cores' => 'Branco/Preto,Preto/Branco,Vermelho',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Chuteira Penalty Campo Society',
        'categoria' => 'Chuteiras',
        'marca' => 'Penalty',
        'preco' => 199.90,
        'descricao' => '⚽ CUSTO-BENEFÍCIO! Chuteira society com sola multitração. Cabedal em material sintético resistente.',
        'imagem' => 'https://penalty.com.br/media/catalog/product/cache/1/image/1000x1000/9df78eab33525d08d6e5fb8d27136e95/c/a/campo_society_preto_1.jpg',
        'estoque' => 100,
        'destaque' => 0,
        'cores' => 'Preto/Branco,Azul/Preto,Vermelho',
        'tamanhos' => '35,36,37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '35(23cm),36(23.5cm),37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Chuteira Topper Samba Pro',
        'categoria' => 'Chuteiras',
        'marca' => 'Topper',
        'preco' => 249.90,
        'descricao' => '🇧🇷 TRADIÇÃO BRASILEIRA! Chuteira society com design clássico. Ideal para quadras society e gramado sintético.',
        'imagem' => 'https://topper.com.br/cdn/shop/files/samba_pro_preto_01.png',
        'estoque' => 85,
        'destaque' => 0,
        'cores' => 'Branco/Azul,Preto/Branco,Vermelho',
        'tamanhos' => '36,37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '36(23.5cm),37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Chuteira Nike Tiempo Legend 10 Elite',
        'categoria' => 'Chuteiras',
        'marca' => 'Nike',
        'preco' => 1349.90,
        'descricao' => '🎭 TOQUE DE COURO PREMIUM! Flytouch Pro para sensação natural. Conforto excepcional para jogadores de toque refinado.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d/tiempo-legend-10-elite-fg-chuteira.png',
        'estoque' => 40,
        'destaque' => 1,
        'cores' => 'Branco/Dourado,Preto/Prata,Azul Marinho',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Chuteira Adidas Copa Pure 2 Elite',
        'categoria' => 'Chuteiras',
        'marca' => 'Adidas',
        'preco' => 1099.90,
        'descricao' => '🍃 COURO K-TRM MACIO! Sensação de usar meias. Controle de bola superior e toque aveludado.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f_9366/Copa_Pure_2_Elite_FG_Preto_IH5678_01_standard.jpg',
        'estoque' => 35,
        'destaque' => 1,
        'cores' => 'Preto/Dourado,Branco/Preto',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Chuteira Puma Future 7 Ultimate',
        'categoria' => 'Chuteiras',
        'marca' => 'Puma',
        'preco' => 1149.90,
        'descricao' => '🔄 ADAPTATIVA! FuelCell+ para máximo retorno de energia. Cabedal que se molda ao seu formato de pé.',
        'imagem' => 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/107579/01/sv01/fnd/BRC/fmt/png/Future-7-Ultimate-FG-Chuteira',
        'estoque' => 30,
        'destaque' => 1,
        'cores' => 'Verde/Preto,Rosa/Preto,Branco/Verde',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Chuteira Diadora Brasil IT',
        'categoria' => 'Chuteiras',
        'marca' => 'Diadora',
        'preco' => 799.90,
        'descricao' => '🇮🇹 ESTILO ITALIANO! Couro macio e toque refinado. Design clássico para puristas do futebol.',
        'imagem' => 'https://diadora.com.br/media/catalog/product/b/r/brasil_it_preto_01.jpg',
        'estoque' => 25,
        'destaque' => 0,
        'cores' => 'Preto/Branco,Branco/Preto',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Chuteira Futsal Nike React Gato',
        'categoria' => 'Chuteiras',
        'marca' => 'Nike',
        'preco' => 599.90,
        'descricao' => '🏟️ PARA QUADRAS COBERTAS! Amortecimento React para máximo conforto. Sola específica para futsal.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/4d5e6f7a-8b9c-0d1e-2f3a-4b5c6d7e8f9a/react-gato-futsal-tenis.png',
        'estoque' => 60,
        'destaque' => 0,
        'cores' => 'Preto/Branco,Azul/Preto,Vermelho',
        'tamanhos' => '35,36,37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '35(23cm),36(23.5cm),37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ]
];

// ==================== CAMISAS DE TIMES (20 PRODUTOS) ====================
$camisas = [
    [
        'nome' => 'Camisa Brasil I 2024 - Seleção Brasileira',
        'categoria' => 'Camisas',
        'marca' => 'Nike',
        'preco' => 379.90,
        'descricao' => '🇧🇷 CAMISA OFICIAL DA SELEÇÃO BRASILEIRA - Vista as cores do Hexa! Tecnologia Dri-FIT para máximo conforto. Escudo e patch bordados.',
        'imagem' => 'https://imgnike-a.akamaihd.net/1300x1300/024789IDA21.jpg',
        'estoque' => 120,
        'destaque' => 1,
        'cores' => 'Amarelo/Azul,Azul/Branco,Branco/Azul',
        'tamanhos' => 'P,M,G,GG,XG,XXG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm),XXG(Peito 111-116cm)'
    ],
    [
        'nome' => 'Camisa Flamengo I 2024 - Manto Sagrado',
        'categoria' => 'Camisas',
        'marca' => 'Adidas',
        'preco' => 349.90,
        'descricao' => '🦅 MANTO SAGRADO - A camisa mais desejada do Brasil! Tecnologia Climalite que absorve o suor. Escudo termocolado.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/1a2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d_9366/Camisa_Flamengo_I_2024_Vermelho_IP1234_01_standard.jpg',
        'estoque' => 95,
        'destaque' => 1,
        'cores' => 'Vermelho/Preto,Preto/Vermelho,Branco',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Corinthians I 2024 - Camisa do Povo',
        'categoria' => 'Camisas',
        'marca' => 'Nike',
        'preco' => 329.90,
        'descricao' => '🖤🤍 CAMISA DO POVO - 12 milhões não se enganam! Torcida mais apaixonada do Brasil.',
        'imagem' => 'https://imgnike-a.akamaihd.net/1300x1300/024790IDA21.jpg',
        'estoque' => 88,
        'destaque' => 1,
        'cores' => 'Preto/Branco,Branco/Preto',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Real Madrid I 2024',
        'categoria' => 'Camisas',
        'marca' => 'Adidas',
        'preco' => 399.90,
        'descricao' => '👑 CAMISA DO REAL MADRID - 14 vezes campeão europeu! O clube mais vitorioso do mundo.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f_9366/Camisa_Real_Madrid_I_2024_Branco_IP9012_01_standard.jpg',
        'estoque' => 110,
        'destaque' => 1,
        'cores' => 'Branco/Dourado,Preto/Dourado',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Argentina I 2024 - Campeã do Mundo',
        'categoria' => 'Camisas',
        'marca' => 'Adidas',
        'preco' => 389.90,
        'descricao' => '🏆🏆🏆 TRICAMPEÃ MUNDIAL! Com 3 estrelas no escudo. Tecnologia HEAT.RDY para máximo conforto.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a_9366/Camisa_Argentina_I_2024_Azul_IP6789_01_standard.jpg',
        'estoque' => 75,
        'destaque' => 1,
        'cores' => 'Azul/Branco,Branco/Azul',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa São Paulo I 2024',
        'categoria' => 'Camisas',
        'marca' => 'New Balance',
        'preco' => 319.90,
        'descricao' => '🔴⚪⚫ O MAIOR CAMPEÃO NACIONAL! Tricolor paulista com 3 mundiais. Torcida que nunca abandona.',
        'imagem' => 'https://nb.scene7.com/is/image/NB/sao-paulo-2024-tricolor_01',
        'estoque' => 80,
        'destaque' => 1,
        'cores' => 'Branco/Vermelho/Preto,Preto/Branco',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Palmeiras I 2024 - Academia de Futebol',
        'categoria' => 'Camisas',
        'marca' => 'Puma',
        'preco' => 339.90,
        'descricao' => '🐷 ACADEMIA DE FUTEBOL! O atual bicampeão brasileiro. Verde que representa a força do porco.',
        'imagem' => 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/123456/01/sv01/fnd/BRC/fmt/png/Palmeiras-2024-Home-Jersey',
        'estoque' => 92,
        'destaque' => 1,
        'cores' => 'Verde/Branco,Branco/Verde',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Internacional I 2024 - Colorado',
        'categoria' => 'Camisas',
        'marca' => 'Adidas',
        'preco' => 309.90,
        'descricao' => '🔴❤️ CAMISA DO COLORADO! Patrimônio do povo gaúcho. O clube do povo com tradição e raça.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a0b_9366/Camisa_Internacional_I_2024_Vermelho_IP2345_01_standard.jpg',
        'estoque' => 70,
        'destaque' => 0,
        'cores' => 'Vermelho/Branco,Branco/Vermelho',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Grêmio I 2024 - Imortal Tricolor',
        'categoria' => 'Camisas',
        'marca' => 'Umbro',
        'preco' => 319.90,
        'descricao' => '🔵⚪⚫ IMORTAL TRICOLOR! O clube da Liberta de 2017. Tradição e paixão gaúcha.',
        'imagem' => 'https://umbro.com.br/cdn/shop/files/gremio_2024_home_01.png',
        'estoque' => 65,
        'destaque' => 0,
        'cores' => 'Azul/Branco/Preto,Branco/Azul',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Cruzeiro I 2024 - Cabuloso',
        'categoria' => 'Camisas',
        'marca' => 'Adidas',
        'preco' => 299.90,
        'descricao' => '🔵⚪ CABULOSO! O maior campeão da Copa do Brasil. Raposa que nunca desiste.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/6f7a8b9c0d1e2f3a4b5c6d7e8f9a0b1c_9366/Camisa_Cruzeiro_I_2024_Azul_IP3456_01_standard.jpg',
        'estoque' => 85,
        'destaque' => 0,
        'cores' => 'Azul/Branco,Branco/Azul',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Atlético Mineiro I 2024 - Galo Doido',
        'categoria' => 'Camisas',
        'marca' => 'Puma',
        'preco' => 319.90,
        'descricao' => '🐔 GALO FORTE E VINGADOR! Time mais querido de Minas Gerais. Raça e determinação.',
        'imagem' => 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/234567/01/sv01/fnd/BRC/fmt/png/Atletico-MG-2024-Home-Jersey',
        'estoque' => 72,
        'destaque' => 0,
        'cores' => 'Preto/Branco,Branco/Preto',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Vasco I 2024 - Gigante da Colina',
        'categoria' => 'Camisas',
        'marca' => 'Kappa',
        'preco' => 289.90,
        'descricao' => '⚓ GIGANTE DA COLINA! Camisa mais tradicional do Rio. História e tradição do expresso da vitória.',
        'imagem' => 'https://kappa.com.br/cdn/shop/files/vasco_2024_home_01.png',
        'estoque' => 60,
        'destaque' => 0,
        'cores' => 'Preto/Branco,Branco/Preto',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Santos I 2024 - Peixe',
        'categoria' => 'Camisas',
        'marca' => 'Umbro',
        'preco' => 279.90,
        'descricao' => '🐟 PEIXE - Time do Rei Pelé! O clube que revelou o maior jogador de todos os tempos.',
        'imagem' => 'https://umbro.com.br/cdn/shop/files/santos_2024_home_01.png',
        'estoque' => 55,
        'destaque' => 0,
        'cores' => 'Branco/Preto,Preto/Branco',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Liverpool I 2024 - You\'ll Never Walk Alone',
        'categoria' => 'Camisas',
        'marca' => 'Nike',
        'preco' => 429.90,
        'descricao' => '🔴⚽ CAMPIONÍSSIMO! 19 títulos ingleses e 6 Champions League. A emoção de Anfield.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d/liverpool-2024-home-stadium-jersey.png',
        'estoque' => 95,
        'destaque' => 1,
        'cores' => 'Vermelho/Branco,Branco/Vermelho',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Barcelona I 2024 - Mais que um Clube',
        'categoria' => 'Camisas',
        'marca' => 'Nike',
        'preco' => 419.90,
        'descricao' => '🔵🔴 MÉS QUE UN CLUB! O time da Catalunha. Jogadores icônicos como Messi, Ronaldinho e Cruyff.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e/barcelona-2024-home-jersey.png',
        'estoque' => 100,
        'destaque' => 1,
        'cores' => 'Vermelho/Azul/Grená,Branco',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Manchester City I 2024 - Campeão Europeu',
        'categoria' => 'Camisas',
        'marca' => 'Puma',
        'preco' => 399.90,
        'descricao' => '🩵⚽ TRICAMPEÃO INGLÊS! Primeira Champions League do clube. Guardiola e Haaland.',
        'imagem' => 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/345678/01/sv01/fnd/GBR/fmt/png/Manchester-City-2024-Home-Jersey',
        'estoque' => 85,
        'destaque' => 1,
        'cores' => 'Azul/Branco,Branco/Azul',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Bayern de Munique I 2024',
        'categoria' => 'Camisas',
        'marca' => 'Adidas',
        'preco' => 409.90,
        'descricao' => '🔴 MIA SAN MIA! O maior clube da Alemanha. 33 títulos alemães e 6 Champions League.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/9c0d1e2f3a4b5c6d7e8f9a0b1c2d3e4f_9366/Bayern-Munich-2024-Home-Jersey_Red_IP7890_01_standard.jpg',
        'estoque' => 80,
        'destaque' => 1,
        'cores' => 'Vermelho/Branco,Branco/Vermelho',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa França I 2024 - Bicampeã Mundial',
        'categoria' => 'Camisas',
        'marca' => 'Nike',
        'preco' => 369.90,
        'descricao' => '🇫🇷🐓 DEUX ÉTOILES! Campeã mundial 1998 e 2018. Geração de ouro com Mbappé.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/9d0e1f2a-3b4c-5d6e-7f8a-9b0c1d2e3f4a/france-2024-home-jersey.png',
        'estoque' => 70,
        'destaque' => 0,
        'cores' => 'Azul/Branco/Branco/Azul',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Portugal I 2024 - Navegadores',
        'categoria' => 'Camisas',
        'marca' => 'Nike',
        'preco' => 359.90,
        'descricao' => '🇵🇹⚽ CAMPEÃ EUROPEIA 2016! O país de Cristiano Ronaldo. Tecnologia VaporKnit.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/0e1f2a3b-4c5d-6e7f-8a9b-0c1d2e3f4a5b/portugal-2024-home-jersey.png',
        'estoque' => 65,
        'destaque' => 0,
        'cores' => 'Vermelho/Verde,Verde/Vermelho',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ],
    [
        'nome' => 'Camisa Inglaterra I 2024 - Three Lions',
        'categoria' => 'Camisas',
        'marca' => 'Nike',
        'preco' => 369.90,
        'descricao' => '🦁🏆 TRÊS LEÕES! Campeã mundial 1966. Geração de ouro com Kane, Bellingham e Foden.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/1f2a3b4c-5d6e-7f8a-9b0c-1d2e3f4a5b6c/england-2024-home-jersey.png',
        'estoque' => 75,
        'destaque' => 0,
        'cores' => 'Branco/Azul,Azul/Branco',
        'tamanhos' => 'P,M,G,GG,XG',
        'tamanhos_desc' => 'P(Peito 86-91cm),M(Peito 91-96cm),G(Peito 96-101cm),GG(Peito 101-106cm),XG(Peito 106-111cm)'
    ]
];

// ==================== TÊNIS (20 PRODUTOS) ====================
$tenis = [
    [
        'nome' => 'Tênis Nike Air Max 90 Essential',
        'categoria' => 'Tênis',
        'marca' => 'Nike',
        'preco' => 699.90,
        'descricao' => '👟 ÍCONE DO STREETWEAR - O clássico que nunca sai de moda! Amortecimento visível Air Max. Cabedal em couro e mesh.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d/air-max-90-tenis.png',
        'estoque' => 60,
        'destaque' => 1,
        'cores' => 'Preto,Branco,Cinza,Azul Marinho,Vermelho',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis Adidas Ultraboost 23',
        'categoria' => 'Tênis',
        'marca' => 'Adidas',
        'preco' => 899.90,
        'descricao' => '🏃 MÁXIMO AMORTECIMENTO - O melhor tênis para corrida! Tecnologia Boost para retorno de energia. Cabedal Primeknit.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e_9366/Tenis_Ultraboost_23_Preto_IP5678_01_standard.jpg',
        'estoque' => 42,
        'destaque' => 1,
        'cores' => 'Preto,Branco,Cinza,Azul,Verde',
        'tamanhos' => '37,38,39,40,41,42,43,44,45',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm),45(29cm)'
    ],
    [
        'nome' => 'Tênis New Balance 574 Classic',
        'categoria' => 'Tênis',
        'marca' => 'New Balance',
        'preco' => 549.90,
        'descricao' => '📸 ESTILO RETRÔ - O clássico dos anos 80 renovado! Conforto durável e design atemporal.',
        'imagem' => 'https://nb.scene7.com/is/image/NB/m5740gc_nb_02_i?$dw_detail_main$',
        'estoque' => 55,
        'destaque' => 1,
        'cores' => 'Marinho/Branco,Cinza/Preto,Verde,Salmão',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis Nike Revolution 6',
        'categoria' => 'Tênis',
        'marca' => 'Nike',
        'preco' => 349.90,
        'descricao' => '💪 CORRIDA DIÁRIA COM CONFORTO - Cabedal em mesh respirável. Solado em borracha durável.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/3c4d5e6f-7a8b-9c0d-1e2f-3a4b5c6d7e8f/revolution-6-tenis-corrida.png',
        'estoque' => 100,
        'destaque' => 1,
        'cores' => 'Preto,Branco,Azul,Verde,Rosa',
        'tamanhos' => '35,36,37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '35(23cm),36(23.5cm),37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis Adidas Superstar',
        'categoria' => 'Tênis',
        'marca' => 'Adidas',
        'preco' => 499.90,
        'descricao' => '🎤 O CLÁSSICO DO HIP HOP! Bico de borracha icônico. Casual e atemporal.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a_9366/Tenis_Superstar_Preto_IP6789_01_standard.jpg',
        'estoque' => 85,
        'destaque' => 1,
        'cores' => 'Preto/Branco,Branco/Preto,Preto/Dourado',
        'tamanhos' => '36,37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '36(23.5cm),37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis Asics Gel-Kayano 30',
        'categoria' => 'Tênis',
        'marca' => 'Asics',
        'preco' => 999.90,
        'descricao' => '🏃‍♂️ ESTABILIDADE PREMIUM - Tecnologia PureGEL e amortecimento FF BLAST PLUS ECO.',
        'imagem' => 'https://asics.com.br/media/catalog/product/k/a/kayano_30_blue_01.jpg',
        'estoque' => 35,
        'destaque' => 1,
        'cores' => 'Azul/Branco,Preto/Cinza,Verde',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis Puma Suede Classic',
        'categoria' => 'Tênis',
        'marca' => 'Puma',
        'preco' => 399.90,
        'descricao' => '🎨 ÍCONE DA CULTURA URBANA - Camurça macia e design atemporal.',
        'imagem' => 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/123456/01/sv01/fnd/BRC/fmt/png/Suede-Classic',
        'estoque' => 75,
        'destaque' => 1,
        'cores' => 'Preto,Azul,Verde,Roxo,Vermelho',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis Olympikus Corre 4',
        'categoria' => 'Tênis',
        'marca' => 'Olympikus',
        'preco' => 399.90,
        'descricao' => '🇧🇷 ELEITO O MELHOR TÊNIS DE CORRIDA DO BRASIL! Eleito pelo RUNNER\'S WORLD.',
        'imagem' => 'https://olympikus.com.br/cdn/shop/files/corre4_blue_01.png',
        'estoque' => 120,
        'destaque' => 1,
        'cores' => 'Azul/Branco,Preto/Verde,Vermelho/Branco',
        'tamanhos' => '35,36,37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '35(23cm),36(23.5cm),37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis Mizuno Wave Rider 27',
        'categoria' => 'Tênis',
        'marca' => 'Mizuno',
        'preco' => 799.90,
        'descricao' => '🌊 AMORTECIMENTO WAVE - Mais de 25 anos de evolução. Máximo conforto para corrida.',
        'imagem' => 'https://mizuno.com.br/media/catalog/product/cache/1/image/1000x1000/9df78eab33525d08d6e5fb8d27136e95/w/a/wave_rider_27_blue.jpg',
        'estoque' => 45,
        'destaque' => 0,
        'cores' => 'Azul/Prata,Preto/Vermelho,Branco/Azul',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis Vans Old Skool',
        'categoria' => 'Tênis',
        'marca' => 'Vans',
        'preco' => 449.90,
        'descricao' => '🛹 MODELO MAIS ICÔNICO DO SKATE! Listra lateral inconfundível. Sola waffle.',
        'imagem' => 'https://vans.com.br/cdn/shop/files/old_skool_black_01.jpg',
        'estoque' => 90,
        'destaque' => 1,
        'cores' => 'Preto/Branco,Branco/Preto,Azul Marinho',
        'tamanhos' => '36,37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '36(23.5cm),37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis Nike Air Force 1 \'07',
        'categoria' => 'Tênis',
        'marca' => 'Nike',
        'preco' => 699.90,
        'descricao' => '✈️ O CLÁSSICO DO BASQUETE QUE DOMINOU O MUNDO! Couro premium e amortecimento Air.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/5e6f7a8b-9c0d-1e2f-3a4b-5c6d7e8f9a0b/air-force-1-07-tenis.png',
        'estoque' => 80,
        'destaque' => 1,
        'cores' => 'Branco/Preto,Preto/Branco,Branco/Azul',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis Adidas NMD_R1',
        'categoria' => 'Tênis',
        'marca' => 'Adidas',
        'preco' => 799.90,
        'descricao' => '🌆 ESTILO URBANO REVOLUCIONÁRIO! Boost no calcanhar e design futurista.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/6f7a8b9c0d1e2f3a4b5c6d7e8f9a0b1c_9366/Tenis_NMD_R1_Preto_IP9012_01_standard.jpg',
        'estoque' => 55,
        'destaque' => 0,
        'cores' => 'Preto/Branco,Branco/Preto,Azul',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis New Balance 327',
        'categoria' => 'Tênis',
        'marca' => 'New Balance',
        'preco' => 599.90,
        'descricao' => '🔥 O TÊNIS DO MOMENTO! Design inspirado nos anos 70. Silhueta arrojada e conforto.',
        'imagem' => 'https://nb.scene7.com/is/image/NB/u327wtn_nb_02_i?$dw_detail_main$',
        'estoque' => 65,
        'destaque' => 1,
        'cores' => 'Preto/Branco/Cinza,Azul/Branco,Verde',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis Asics Gel-Nimbus 25',
        'categoria' => 'Tênis',
        'marca' => 'Asics',
        'preco' => 999.90,
        'descricao' => '☁️ O TÊNIS MAIS MACIO DO MERCADO! Amortecimento premium para longas distâncias.',
        'imagem' => 'https://asics.com.br/media/catalog/product/n/i/nimbus_25_white_01.jpg',
        'estoque' => 30,
        'destaque' => 0,
        'cores' => 'Branco/Preto,Azul/Preto,Preto/Verde',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis Puma RS-X',
        'categoria' => 'Tênis',
        'marca' => 'Puma',
        'preco' => 649.90,
        'descricao' => '🎮 TÊNIS CHUNKY - Design ousado e colorido. Tecnologia RS (Running System).',
        'imagem' => 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/456789/01/sv01/fnd/BRC/fmt/png/RS-X',
        'estoque' => 50,
        'destaque' => 0,
        'cores' => 'Multicolorido,Preto/Verde,Rosa/Preto',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis Fila Disruptor II',
        'categoria' => 'Tênis',
        'marca' => 'Fila',
        'preco' => 549.90,
        'descricao' => '⚡ TÊNIS CHUNKY DA FILA! Modelo mais famoso da marca. Sola bulbosa e design robusto.',
        'imagem' => 'https://fila.com.br/cdn/shop/files/disruptor_2_white_01.jpg',
        'estoque' => 70,
        'destaque' => 0,
        'cores' => 'Branco,Preto/Rosa,Azul',
        'tamanhos' => '36,37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '36(23.5cm),37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis Converse Chuck Taylor All Star',
        'categoria' => 'Tênis',
        'marca' => 'Converse',
        'preco' => 349.90,
        'descricao' => '⭐ O TÊNIS MAIS ICÔNICO DO MUNDO! Desde 1917. Design atemporal e versátil.',
        'imagem' => 'https://converse.com.br/cdn/shop/files/chuck_taylor_all_star_black_01.jpg',
        'estoque' => 150,
        'destaque' => 1,
        'cores' => 'Preto,Branco,Vermelho,Azul Marinho',
        'tamanhos' => '35,36,37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '35(23cm),36(23.5cm),37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ],
    [
        'nome' => 'Tênis Saucony Triumph 22',
        'categoria' => 'Tênis',
        'marca' => 'Saucony',
        'preco' => 1099.90,
        'descricao' => '🏆 MÁXIMO CONFORTO PARA MARATONAS! Tecnologia PWRRUN+ para amortecimento premium.',
        'imagem' => 'https://saucony.com.br/media/catalog/product/t/r/triumph_22_blue_01.jpg',
        'estoque' => 25,
        'destaque' => 0,
        'cores' => 'Azul/Verde,Preto/Prata,Branco/Rosa',
        'tamanhos' => '37,38,39,40,41,42,43,44',
        'tamanhos_desc' => '37(24cm),38(24.5cm),39(25cm),40(25.5cm),41(26cm),42(26.5cm),43(27.5cm),44(28cm)'
    ]
];

// ==================== MEIAS (12 PRODUTOS) ====================
$meias = [
    [
        'nome' => 'Meião Nike Dri-FIT Cano Alto',
        'categoria' => 'Meias',
        'marca' => 'Nike',
        'preco' => 79.90,
        'descricao' => '🧦 MEIÃO PROFISSIONAL - Dri-FIT para manter os pés secos. Amortecimento nas áreas de impacto.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/4b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e/meiao-cano-alto-dri-fit.png',
        'estoque' => 200,
        'destaque' => 1,
        'cores' => 'Branco,Preto,Vermelho,Azul,Verde',
        'tamanhos' => '37-39,40-42,43-44',
        'tamanhos_desc' => '37-39(24-25cm pé),40-42(25.5-26.5cm pé),43-44(27-28cm pé)'
    ],
    [
        'nome' => 'Meião Adidas Climacool Cano Longo',
        'categoria' => 'Meias',
        'marca' => 'Adidas',
        'preco' => 89.90,
        'descricao' => '💨 MÁXIMA VENTILAÇÃO! Tecnologia Climacool para circulação de ar.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a_9366/Meiao_Adidas_Cano_Longo_Branco_IP3456_01_standard.jpg',
        'estoque' => 180,
        'destaque' => 1,
        'cores' => 'Branco,Preto,Vermelho,Azul,Rosa',
        'tamanhos' => '37-40,41-44',
        'tamanhos_desc' => '37-40(24-25.5cm pé),41-44(26-28cm pé)'
    ],
    [
        'nome' => 'Meião Puma Cano Médio Futebol',
        'categoria' => 'Meias',
        'marca' => 'Puma',
        'preco' => 69.90,
        'descricao' => '⚽ MEIÃO DE FUTEBOL - Dri-Cell para controle de umidade. Design ergonômico.',
        'imagem' => 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/567890/01/sv01/fnd/BRC/fmt/png/Meiao-Futebol-Rapido',
        'estoque' => 150,
        'destaque' => 0,
        'cores' => 'Branco,Preto,Vermelho,Azul',
        'tamanhos' => '37-39,40-42,43-44',
        'tamanhos_desc' => '37-39(24-25cm pé),40-42(25.5-26.5cm pé),43-44(27-28cm pé)'
    ],
    [
        'nome' => 'Meião Umbro Cano Alto Profissional',
        'categoria' => 'Meias',
        'marca' => 'Umbro',
        'preco' => 59.90,
        'descricao' => '🏆 MEIÃO TRADICIONAL INGLÊS! Algodão premium e compressão moderada.',
        'imagem' => 'https://umbro.com.br/cdn/shop/files/meiao_cano_alto_white_01.png',
        'estoque' => 130,
        'destaque' => 0,
        'cores' => 'Branco,Preto,Vermelho',
        'tamanhos' => '37-40,41-44',
        'tamanhos_desc' => '37-40(24-25.5cm pé),41-44(26-28cm pé)'
    ],
    [
        'nome' => 'Meião Topper Cano Longo',
        'categoria' => 'Meias',
        'marca' => 'Topper',
        'preco' => 39.90,
        'descricao' => '🇧🇷 CUSTO-BENEFÍCIO! Algodão confortável e durável.',
        'imagem' => 'https://topper.com.br/cdn/shop/files/meiao_cano_longo_black_01.png',
        'estoque' => 200,
        'destaque' => 0,
        'cores' => 'Branco,Preto,Azul',
        'tamanhos' => '37-40,41-44',
        'tamanhos_desc' => '37-40(24-25.5cm pé),41-44(26-28cm pé)'
    ],
    [
        'nome' => 'Meião Penalty Jogo Cano Alto',
        'categoria' => 'Meias',
        'marca' => 'Penalty',
        'preco' => 39.90,
        'descricao' => '⚽ MEIÃO DE JOGO OFICIAL! Tecnologia Comfort Fit para encaixe perfeito.',
        'imagem' => 'https://penalty.com.br/media/catalog/product/cache/1/image/1000x1000/9df78eab33525d08d6e5fb8d27136e95/m/e/meiao_jogo_preto_1.jpg',
        'estoque' => 250,
        'destaque' => 0,
        'cores' => 'Branco,Preto,Vermelho,Azul,Verde',
        'tamanhos' => '37-39,40-42,43-44',
        'tamanhos_desc' => '37-39(24-25cm pé),40-42(25.5-26.5cm pé),43-44(27-28cm pé)'
    ],
    [
        'nome' => 'Meião Nike Everyday Cushion Cano Curto',
        'categoria' => 'Meias',
        'marca' => 'Nike',
        'preco' => 49.90,
        'descricao' => '🏠 MEIAS PARA TREINO DIÁRIO! Amortecimento extra no calcanhar e dedos.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/6c7d8e9f-0a1b-2c3d-4e5f-6a7b8c9d0e1f/everyday-cushion-meias.png',
        'estoque' => 300,
        'destaque' => 0,
        'cores' => 'Branco,Preto,Cinza,Azul,Verde,Rosa',
        'tamanhos' => 'M(36-39),G(40-43),GG(44-46)',
        'tamanhos_desc' => 'M(36-39 - 23-25cm pé),G(40-43 - 25.5-27.5cm pé),GG(44-46 - 28-30cm pé)'
    ],
    [
        'nome' => 'Meião Adidas 3-Stripes Cano Médio',
        'categoria' => 'Meias',
        'marca' => 'Adidas',
        'preco' => 59.90,
        'descricao' => '👟 MEIAS COM AS 3 LISTRAS! Design esportivo e conforto premium.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c_9366/Meias_3-Stripes_Branco_IP0123_01_standard.jpg',
        'estoque' => 160,
        'destaque' => 0,
        'cores' => 'Branco,Preto,Cinza,Rosa',
        'tamanhos' => 'M, G, GG',
        'tamanhos_desc' => 'M(36-39 - 23-25cm pé),G(40-43 - 25.5-27.5cm pé),GG(44-46 - 28-30cm pé)'
    ],
    [
        'nome' => 'Meão Puma Active Cano Curto 2 Pares',
        'categoria' => 'Meias',
        'marca' => 'Puma',
        'preco' => 49.90,
        'descricao' => '🎁 PACOTE COM 2 PARES! Ideal para o dia a dia e treinos leves.',
        'imagem' => 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/678901/01/sv01/fnd/BRC/fmt/png/Meias-Active-2-Pares',
        'estoque' => 200,
        'destaque' => 0,
        'cores' => 'Preto/Branco,Branco/Preto',
        'tamanhos' => '37-40,41-44',
        'tamanhos_desc' => '37-40(24-25.5cm pé),41-44(26-28cm pé)'
    ],
    [
        'nome' => 'Meião New Balance Performance',
        'categoria' => 'Meias',
        'marca' => 'New Balance',
        'preco' => 69.90,
        'descricao' => '🏃 TECNOLOGIA NB DRY! Controle de umidade e amortecimento estratégico.',
        'imagem' => 'https://nb.scene7.com/is/image/NB/meia-performance_01',
        'estoque' => 100,
        'destaque' => 0,
        'cores' => 'Branco,Preto,Azul',
        'tamanhos' => '37-40,41-44',
        'tamanhos_desc' => '37-40(24-25.5cm pé),41-44(26-28cm pé)'
    ]
];

// ==================== EQUIPAMENTOS (10 PRODUTOS) ====================
$equipamentos = [
    [
        'nome' => 'Bola Nike Flight Oficial',
        'categoria' => 'Equipamentos',
        'marca' => 'Nike',
        'preco' => 299.90,
        'descricao' => '⚽ A BOLA OFICIAL - Tecnologia Aerowtrac para voo mais estável! 60% mais retenção de ar.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d/bola-flight-oficial.png',
        'estoque' => 50,
        'destaque' => 1,
        'cores' => 'Branco/Preto,Azul/Branco,Amarelo/Preto',
        'tamanhos' => 'Tamanho 5, Tamanho 4',
        'tamanhos_desc' => 'Tamanho 5(68-70cm circunferência),Tamanho 4(63-66cm circunferência)'
    ],
    [
        'nome' => 'Luva de Goleiro Predator Pro',
        'categoria' => 'Equipamentos',
        'marca' => 'Adidas',
        'preco' => 249.90,
        'descricao' => '🧤 DEFESA TOTAL! Luvas profissionais com tecnologia URG 2.0 para máxima aderência.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a0b_9366/Luva_Predator_Pro_Preta_IP7890_01_standard.jpg',
        'estoque' => 35,
        'destaque' => 1,
        'cores' => 'Preto/Verde,Azul/Roxo,Roxo/Verde',
        'tamanhos' => '7,8,9,10,11',
        'tamanhos_desc' => '7(18.4-19.1cm mão),8(19.5-20.3cm),9(20.8-21.6cm),10(22.1-23.1cm),11(23.5-25cm)'
    ],
    [
        'nome' => 'Bola Adidas Al Rihla Oficial',
        'categoria' => 'Equipamentos',
        'marca' => 'Adidas',
        'preco' => 349.90,
        'descricao' => '🏆 A BOLA DA COPA 2022! Tecnologia CTR-CORE para máxima precisão.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/8f9a0b1c2d3e4f5a6b7c8d9e0f1a2b3c_9366/Bola_Al_Rihla_Branca_IP4567_01_standard.jpg',
        'estoque' => 45,
        'destaque' => 1,
        'cores' => 'Branco/Preto/Verde',
        'tamanhos' => 'Tamanho 5',
        'tamanhos_desc' => 'Tamanho 5(68-70cm circunferência)'
    ],
    [
        'nome' => 'Caneleira Nike Jogo Profissional',
        'categoria' => 'Equipamentos',
        'marca' => 'Nike',
        'preco' => 79.90,
        'descricao' => '🛡️ PROTEÇÃO PREMIUM! Caneleiras anatômicas com proteção rígida.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/2d3e4f5a-6b7c-8d9e-0f1a-2b3c4d5e6f7a/caneleira-jogo-pro.png',
        'estoque' => 80,
        'destaque' => 0,
        'cores' => 'Preto,Branco,Azul,Vermelho',
        'tamanhos' => 'P, M, G, XG',
        'tamanhos_desc' => 'P(abaixo 1.50m),M(1.51-1.70m),G(1.71-1.85m),XG(acima 1.86m)'
    ],
    [
        'nome' => 'Caneleira Adidas Predator',
        'categoria' => 'Equipamentos',
        'marca' => 'Adidas',
        'preco' => 89.90,
        'descricao' => '🎯 CANELEIRA PREDATOR! Tecnologia de absorção de impacto.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/9e0f1a2b3c4d5e6f7a8b9c0d1e2f3a4b_9366/Caneleira_Predator_Preta_IP8901_01_standard.jpg',
        'estoque' => 70,
        'destaque' => 0,
        'cores' => 'Preto/Branco,Preto/Azul',
        'tamanhos' => 'P, M, G, XG',
        'tamanhos_desc' => 'P(abaixo 1.50m),M(1.51-1.70m),G(1.71-1.85m),XG(acima 1.86m)'
    ],
    [
        'nome' => 'Bola Penalty Max 1000',
        'categoria' => 'Equipamentos',
        'marca' => 'Penalty',
        'preco' => 79.90,
        'descricao' => '⚽ A BOLA MAIS VENDIDA DO BRASIL! Custo-benefício incomparável.',
        'imagem' => 'https://penalty.com.br/media/catalog/product/cache/1/image/1000x1000/9df78eab33525d08d6e5fb8d27136e95/m/a/max_1000_white_1.jpg',
        'estoque' => 200,
        'destaque' => 0,
        'cores' => 'Branco/Preto,Branco/Azul,Branco/Verde',
        'tamanhos' => 'Tamanho 5, Tamanho 4',
        'tamanhos_desc' => 'Tamanho 5(68-70cm circunferência),Tamanho 4(63-66cm circunferência)'
    ],
    [
        'nome' => 'Bomba de Ar Manual Penalty',
        'categoria' => 'Equipamentos',
        'marca' => 'Penalty',
        'preco' => 29.90,
        'descricao' => '💨 BOMBA DE AR PORTÁTIL! Mantenha suas bolas sempre cheias.',
        'imagem' => 'https://penalty.com.br/media/catalog/product/cache/1/image/500x500/9df78eab33525d08d6e5fb8d27136e95/b/o/bomba_manual_1.jpg',
        'estoque' => 150,
        'destaque' => 0,
        'cores' => 'Preto,Azul',
        'tamanhos' => 'Único',
        'tamanhos_desc' => 'Único(20cm de tamanho)'
    ],
    [
        'nome' => 'Kit Caneleira + Meião Nike',
        'categoria' => 'Equipamentos',
        'marca' => 'Nike',
        'preco' => 129.90,
        'descricao' => '🎁 KIT COMPLETO! Inclui caneleira e meião Nike Dri-FIT.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/3e4f5a6b-7c8d-9e0f-1a2b-3c4d5e6f7a8b/kit-caneleira-meiao.png',
        'estoque' => 60,
        'destaque' => 0,
        'cores' => 'Preto/Branco',
        'tamanhos' => 'M, G',
        'tamanhos_desc' => 'M(1.51-1.70m),G(1.71-1.85m)'
    ],
    [
        'nome' => 'Mochila Esportiva Nike Brasilia',
        'categoria' => 'Equipamentos',
        'marca' => 'Nike',
        'preco' => 199.90,
        'descricao' => '🎒 MOCHILA BRASILIA! Espaçosa e durável para levar todo seu equipamento.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/4f5a6b7c-8d9e-0f1a-2b3c-4d5e6f7a8b9c/mochila-brasilia.png',
        'estoque' => 40,
        'destaque' => 0,
        'cores' => 'Preto,Azul Marinho,Cinza',
        'tamanhos' => 'Único',
        'tamanhos_desc' => 'Único(50x30x20cm)'
    ]
];

// ==================== ACESSÓRIOS (10 PRODUTOS) ====================
$acessorios = [
    [
        'nome' => 'Boné Nike Heritage86',
        'categoria' => 'Acessórios',
        'marca' => 'Nike',
        'preco' => 89.90,
        'descricao' => '🧢 ESTILO E PROTEÇÃO! Boné estruturado da Nike. Ajuste por tira de velcro.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/7c8d9e0f-1a2b-3c4d-5e6f-7a8b9c0d1e2f/bone-heritage86-nike.png',
        'estoque' => 95,
        'destaque' => 1,
        'cores' => 'Preto,Branco,Azul,Vermelho,Verde',
        'tamanhos' => 'Único',
        'tamanhos_desc' => 'Único(ajustável 56-60cm)'
    ],
    [
        'nome' => 'Boné Adidas Linear',
        'categoria' => 'Acessórios',
        'marca' => 'Adidas',
        'preco' => 99.90,
        'descricao' => '⚾ BONÉ CLÁSSICO ADIDAS! Material Climalite para absorção de suor.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/8c9d0e1f2a3b4c5d6e7f8a9b0c1d2e3f_9366/Bone_Linear_Preto_IP2345_01_standard.jpg',
        'estoque' => 85,
        'destaque' => 0,
        'cores' => 'Preto,Branco,Azul,Rosa',
        'tamanhos' => 'Único',
        'tamanhos_desc' => 'Único(ajustável 55-59cm)'
    ],
    [
        'nome' => 'Garrafa Térmica Nike 750ml',
        'categoria' => 'Acessórios',
        'marca' => 'Nike',
        'preco' => 59.90,
        'descricao' => '💧 GARRAFA INOX! Mantém bebidas geladas por 12h. Tampa esportiva.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/9d0e1f2a-3b4c-5d6e-7f8a-9b0c1d2e3f4a/garrafa-termica-750ml.png',
        'estoque' => 120,
        'destaque' => 0,
        'cores' => 'Preto,Branco,Azul,Rosa',
        'tamanhos' => '750ml',
        'tamanhos_desc' => '750ml(altura 25cm)'
    ],
    [
        'nome' => 'Mochila Adidas Prime 25L',
        'categoria' => 'Acessórios',
        'marca' => 'Adidas',
        'preco' => 149.90,
        'descricao' => '🎒 MOCHILA PRIME 25L! Compartimento acolchoado para notebook.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/0e1f2a3b4c5d6e7f8a9b0c1d2e3f4a5b_9366/Mochila_Prime_25L_Preta_IP3456_01_standard.jpg',
        'estoque' => 50,
        'destaque' => 0,
        'cores' => 'Preto,Roxo,Azul Marinho',
        'tamanhos' => '25L',
        'tamanhos_desc' => '25L(48x30x15cm)'
    ],
    [
        'nome' => 'Pulseira Esportiva Nike Loop',
        'categoria' => 'Acessórios',
        'marca' => 'Nike',
        'preco' => 29.90,
        'descricao' => '⌚ PULSEIRA ABSORVENTE! Ideal para corrida e academia.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/1f2a3b4c-5d6e-7f8a-9b0c-1d2e3f4a5b6c/pulseira-loop.png',
        'estoque' => 200,
        'destaque' => 0,
        'cores' => 'Preto,Branco,Azul,Rosa,Verde',
        'tamanhos' => 'Único',
        'tamanhos_desc' => 'Único(regulável 15-22cm pulso)'
    ],
    [
        'nome' => 'Chaveiro Flamengo Oficial',
        'categoria' => 'Acessórios',
        'marca' => 'Adidas',
        'preco' => 24.90,
        'descricao' => '🔑 CHAVEIRO DO MANTO SAGRADO! Leve a paixão rubro-negra para todos os lugares.',
        'imagem' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/2a3b4c5d6e7f8a9b0c1d2e3f4a5b6c7d_9366/Chaveiro_Flamengo_IP4567_01_standard.jpg',
        'estoque' => 300,
        'destaque' => 0,
        'cores' => 'Vermelho/Preto',
        'tamanhos' => 'Único',
        'tamanhos_desc' => 'Único(5cm)'
    ],
    [
        'nome' => 'Óculos de Natação Speedo Hydrosity',
        'categoria' => 'Acessórios',
        'marca' => 'Speedo',
        'preco' => 79.90,
        'descricao' => '🏊 ÓCULOS DE NATAÇÃO! Antivapor e proteção UV.',
        'imagem' => 'https://speedo.com.br/cdn/shop/files/hydrosity_blue_01.jpg',
        'estoque' => 60,
        'destaque' => 0,
        'cores' => 'Azul,Preto,Roxo',
        'tamanhos' => 'Único',
        'tamanhos_desc' => 'Único(ajustável)'
    ],
    [
        'nome' => 'Touca de Natação Silicone Speedo',
        'categoria' => 'Acessórios',
        'marca' => 'Speedo',
        'preco' => 49.90,
        'descricao' => '🏊‍♂️ TOUCA DE SILICONE! Durável e confortável para natação.',
        'imagem' => 'https://speedo.com.br/cdn/shop/files/touca_silicone_black_01.jpg',
        'estoque' => 80,
        'destaque' => 0,
        'cores' => 'Preto,Azul,Branco,Rosa',
        'tamanhos' => 'Único',
        'tamanhos_desc' => 'Único(estica até 55cm)'
    ],
    [
        'nome' => 'Faixa de Cabelo Esportiva Nike',
        'categoria' => 'Acessórios',
        'marca' => 'Nike',
        'preco' => 29.90,
        'descricao' => '🎀 FAIXA DE CABELO! Dri-FIT para manter o suor longe dos olhos.',
        'imagem' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/3b4c5d6e-7f8a-9b0c-1d2e-3f4a5b6c7d8e/faixa-cabelo-dri-fit.png',
        'estoque' => 150,
        'destaque' => 0,
        'cores' => 'Preto,Branco,Rosa,Verde,Roxo',
        'tamanhos' => 'Único',
        'tamanhos_desc' => 'Único(estica até 30cm)'
    ],
    [
        'nome' => 'Meia de Compressão Esportiva MCR',
        'categoria' => 'Acessórios',
        'marca' => 'MCR',
        'preco' => 69.90,
        'descricao' => '🦵 MEIA DE COMPRESSÃO! Melhora a circulação e reduz fadiga muscular.',
        'imagem' => 'https://mcr.com.br/cdn/shop/files/meia_compressao_black_01.jpg',
        'estoque' => 100,
        'destaque' => 0,
        'cores' => 'Preto,Branco,Azul,Rosa',
        'tamanhos' => 'P, M, G, GG',
        'tamanhos_desc' => 'P(33-35cm panturrilha),M(35-38cm),G(38-42cm),GG(42-45cm)'
    ]
];

// Combinar todos os produtos
$todos_produtos = array_merge($chuteiras, $camisas, $tenis, $meias, $equipamentos, $acessorios);

$inseridos = 0;
foreach ($todos_produtos as $p) {
    $cores = is_array($p['cores']) ? implode(',', $p['cores']) : $p['cores'];
    $tamanhos = is_array($p['tamanhos']) ? implode(',', $p['tamanhos']) : $p['tamanhos'];
    $tamanhos_desc = $p['tamanhos_desc'] ?? '';
    
    $sql = "INSERT INTO produtos 
            (nome, categoria, marca, preco, descricao, imagem, estoque, destaque, cores, tamanhos, tamanhos_desc) 
            VALUES (
                '{$p['nome']}', 
                '{$p['categoria']}', 
                '{$p['marca']}', 
                {$p['preco']}, 
                '{$p['descricao']}', 
                '{$p['imagem']}', 
                {$p['estoque']}, 
                {$p['destaque']}, 
                '$cores', 
                '$tamanhos',
                '$tamanhos_desc'
            )";
    if (mysqli_query($conn, $sql)) {
        $inseridos++;
    } else {
        echo "Erro: " . mysqli_error($conn) . "<br>";
    }
}

echo "<div style='text-align: center; padding: 50px; font-family: Arial;'>";
echo "<h1 style='color: green;'>✅ " . $inseridos . " produtos inseridos com sucesso!</h1>";
echo "<h3>📊 Resumo por categoria:</h3>";

$categorias_lista = ['Chuteiras', 'Camisas', 'Tênis', 'Meias', 'Equipamentos', 'Acessórios'];
foreach ($categorias_lista as $cat) {
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