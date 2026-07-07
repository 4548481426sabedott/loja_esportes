-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Tempo de geração: 07/07/2026 às 13:18
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `loja_esportes`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `admin_users`
--

INSERT INTO `admin_users` (`id`, `nome`, `email`, `senha`, `data_criacao`) VALUES
(1, 'Administrador', 'admin@sportshop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-06-22 10:50:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `tamanho` varchar(10) DEFAULT NULL,
  `cor` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `forma_pagamento` varchar(50) DEFAULT NULL,
  `endereco_entrega` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pendente',
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `descricao` text DEFAULT NULL,
  `imagem` text DEFAULT NULL,
  `estoque` int(11) DEFAULT 0,
  `destaque` tinyint(1) DEFAULT 0,
  `cores` text DEFAULT NULL,
  `tamanhos` text DEFAULT NULL,
  `preco_promocional` decimal(10,2) DEFAULT NULL,
  `promocao_ativa` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `categoria`, `marca`, `preco`, `descricao`, `imagem`, `estoque`, `destaque`, `cores`, `tamanhos`, `preco_promocional`, `promocao_ativa`) VALUES
(1, 'Chuteira Nike Mercurial Vapor 15', 'Chuteiras', 'Nike', 499.90, 'A chuteira mais rápida do mercado. Tecnologia Aerowtrac para máxima velocidade e controle de bola. Cabedal em material sintético de alta durabilidade.', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&h=500&fit=crop', 50, 1, 'Preto,Vermelho,Azul,Branco', '34,35,36,37,38,39,40,41,42,43,44', NULL, 0),
(2, 'Camisa Flamengo I 2024', 'Camisas', 'Adidas', 299.90, 'Camisa oficial do Flamengo 2024. Tecido Climalite que absorve o suor. Tecnologia de ventilação nas laterais.', 'https://images.pexels.com/photos/1884574/pexels-photo-1884574.jpeg?w=500&h=500&fit=crop', 100, 1, 'Vermelho,Preto,Branco', 'P,M,G,GG,XG', NULL, 0),
(3, 'Tênis Running Nike Revolution 6', 'Tênis', 'Nike', 349.90, 'Conforto e amortecimento para suas corridas diárias. Solado em borracha durável e cabedal em mesh respirável.', 'https://images.pexels.com/photos/2529148/pexels-photo-2529148.jpeg?w=500&h=500&fit=crop', 75, 1, 'Preto,Branco,Cinza,Azul', '34,35,36,37,38,39,40,41,42,43,44,45', NULL, 0),
(4, 'Bola de Futebol Campo Oficial', 'Equipamentos', 'Penalty', 129.90, 'Bola oficial tamanho 5. Couro sintético costurado à máquina. Câmara de butil para retenção de ar.', 'https://images.pexels.com/photos/4777735/pexels-photo-4777735.jpeg?w=500&h=500&fit=crop', 30, 1, 'Branco/Preto,Branco/Azul,Branco/Vermelho', 'Tamanho 5 (Oficial),Tamanho 4,Júnior', NULL, 0),
(5, 'Meião Esportivo Cano Alto', 'Meias', 'Nike', 49.90, 'Meião cano alto com amortecimento nas áreas de impacto. Tecnologia Dri-FIT para manter os pés secos.', 'https://images.pexels.com/photos/2529149/pexels-photo-2529149.jpeg?w=500&h=500&fit=crop', 200, 0, 'Branco,Preto,Vermelho,Azul', 'P,M,G', NULL, 0),
(6, 'Chuteira Adidas Predator 24', 'Chuteiras', 'Adidas', 599.90, 'Controle de bola excepcional com as lâminas de borracha. Cabedal em Primeknit para máximo conforto.', 'https://images.unsplash.com/photo-1511882150382-421056c89033?w=500&h=500&fit=crop', 35, 1, 'Preto,Vermelho,Azul', '36,37,38,39,40,41,42,43,44', NULL, 0),
(7, 'Chuteira Nike Mercurial Vapor 15 Elite', 'Chuteiras', 'Nike', 1299.90, 'A chuteira mais rápida do mundo! Tecnologia Air Zoom para máxima explosão. Cabedal em Flyknit que se adapta ao seu pé como uma luva.', 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/6a0b1c2d-3e4f-5a6b-7c8d-9e0f1a2b3c4d/mercurial-vapor-15-elite-fg-chuteira.png', 50, 1, 'Preto/Rosa,Verde Lima,Branco/Dourado', '37,38,39,40,41,42,43,44', NULL, 0),
(8, 'Chuteira Adidas Predator 24 Elite', 'Chuteiras', 'Adidas', 1199.90, 'Controle absoluto! Lâminas de borracha estratégicas aumentam o spin da bola em até 30%.', 'https://assets.adidas.com/images/w_600,f_auto,q_auto/9a8c1d2f3e4a5b6c7d8e9f0a1b2c3d4e_9366/Chuteira_Predator_24_Elite_FG_Branco_IE7482_01_standard.jpg', 35, 1, 'Preto/Vermelho,Branco/Dourado,Azul Royal', '37,38,39,40,41,42,43,44', NULL, 0),
(9, 'Chuteira Puma Ultra 5 Elite', 'Chuteiras', 'Puma', 1099.90, 'A chuteira mais leve do mercado! Ultraweave para máximo suporte. Sola Peba para sensação de velocidade.', 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/107480/01/sv01/fnd/BRC/fmt/png/Ultra-5-Elite-FG-Chuteira', 40, 1, 'Azul/Amarelo,Preto/Laranja,Branco/Azul', '37,38,39,40,41,42,43,44', NULL, 0),
(10, 'Chuteira New Balance Furon v7 Pro', 'Chuteiras', 'New Balance', 899.90, 'Velocidade hipersônica! Cabedal Hypoknit para toque de bola superior.', 'https://nb.scene7.com/is/image/NB/msftv7-pro_1?$dw_detail_main$', 30, 0, 'Preto/Dourado,Branco/Verde,Azul Marinho', '37,38,39,40,41,42,43,44', NULL, 0),
(11, 'Camisa Brasil I 2024 - Seleção', 'Camisas', 'Nike', 379.90, 'Camisa oficial da Seleção Brasileira! Tecnologia Dri-FIT para máximo conforto.', 'https://imgnike-a.akamaihd.net/1300x1300/024789IDA21.jpg', 120, 1, 'Amarelo/Azul,Azul/Branco,Branco/Azul', 'P,M,G,GG,XG', NULL, 0),
(12, 'Camisa Flamengo I 2024', 'Camisas', 'Adidas', 349.90, 'Manto Sagrado! A camisa mais desejada do Brasil. Tecnologia Climalite.', 'https://assets.adidas.com/images/w_600,f_auto,q_auto/1a2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d_9366/Camisa_Flamengo_I_2024_Vermelho_IP1234_01_standard.jpg', 95, 1, 'Vermelho/Preto,Preto/Vermelho,Branco', 'P,M,G,GG,XG', NULL, 0),
(13, 'Camisa Corinthians I 2024', 'Camisas', 'Nike', 329.90, 'Camisa do Povo! 12 milhões não se enganam!', 'https://imgnike-a.akamaihd.net/1300x1300/024790IDA21.jpg', 88, 1, 'Preto/Branco,Branco/Preto', 'P,M,G,GG,XG', NULL, 0),
(14, 'Camisa Real Madrid I 2024', 'Camisas', 'Adidas', 399.90, '14 vezes campeão europeu! O clube mais vitorioso do mundo.', 'https://assets.adidas.com/images/w_600,f_auto,q_auto/3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f_9366/Camisa_Real_Madrid_I_2024_Branco_IP9012_01_standard.jpg', 110, 1, 'Branco/Dourado,Preto/Dourado', 'P,M,G,GG,XG', NULL, 0),
(15, 'Camisa Argentina I 2024', 'Camisas', 'Adidas', 389.90, 'Tricampeã Mundial! Com 3 estrelas no escudo.', 'https://assets.adidas.com/images/w_600,f_auto,q_auto/4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a_9366/Camisa_Argentina_I_2024_Azul_IP6789_01_standard.jpg', 75, 1, 'Azul/Branco,Branco/Azul', 'P,M,G,GG,XG', NULL, 0),
(16, 'Tênis Nike Air Max 90', 'Tênis', 'Nike', 699.90, 'Ícone do streetwear! O clássico que nunca sai de moda!', 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d/air-max-90-tenis.png', 60, 1, 'Preto,Branco,Cinza,Azul Marinho', '37,38,39,40,41,42,43,44', NULL, 0),
(17, 'Tênis Adidas Ultraboost 23', 'Tênis', 'Adidas', 899.90, 'Máximo amortecimento! Tecnologia Boost para retorno de energia.', 'https://assets.adidas.com/images/w_600,f_auto,q_auto/2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e_9366/Tenis_Ultraboost_23_Preto_IP5678_01_standard.jpg', 42, 1, 'Preto,Branco,Cinza,Azul,Verde', '37,38,39,40,41,42,43,44,45', NULL, 0),
(18, 'Tênis New Balance 574', 'Tênis', 'New Balance', 549.90, 'Estilo retrô! O clássico dos anos 80 renovado.', 'https://nb.scene7.com/is/image/NB/m5740gc_nb_02_i?$dw_detail_main$', 55, 1, 'Marinho/Branco,Cinza/Preto,Verde', '37,38,39,40,41,42,43,44', NULL, 0),
(19, 'Tênis Nike Revolution 6', 'Tênis', 'Nike', 349.90, 'Corrida diária com conforto. Cabedal em mesh respirável.', 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/3c4d5e6f-7a8b-9c0d-1e2f-3a4b5c6d7e8f/revolution-6-tenis-corrida.png', 100, 1, 'Preto,Branco,Azul,Verde,Rosa', '35,36,37,38,39,40,41,42,43,44', NULL, 0),
(20, 'Meião Nike Dri-FIT Cano Alto', 'Meias', 'Nike', 79.90, 'Meião profissional! Dri-FIT para manter os pés secos.', 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/4b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e/meiao-cano-alto-dri-fit.png', 200, 1, 'Branco,Preto,Vermelho,Azul,Verde', '37-39,40-42,43-44', NULL, 0),
(21, 'Meião Adidas Climacool Cano Longo', 'Meias', 'Adidas', 89.90, 'Máxima ventilação! Tecnologia Climacool para circulação de ar.', 'https://assets.adidas.com/images/w_600,f_auto,q_auto/4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a_9366/Meiao_Adidas_Cano_Longo_Branco_IP3456_01_standard.jpg', 180, 1, 'Branco,Preto,Vermelho,Azul,Rosa', '37-40,41-44', NULL, 0),
(22, 'Bola Nike Flight Oficial', 'Equipamentos', 'Nike', 299.90, 'A bola oficial! Tecnologia Aerowtrac para voo mais estável.', 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d/bola-flight-oficial.png', 50, 1, 'Branco/Preto,Azul/Branco,Amarelo/Preto', 'Tamanho 5,Tamanho 4', NULL, 0),
(23, 'Luva de Goleiro Predator Pro', 'Equipamentos', 'Adidas', 249.90, 'Defesa total! Luvas profissionais com tecnologia URG 2.0.', 'https://assets.adidas.com/images/w_600,f_auto,q_auto/5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a0b_9366/Luva_Predator_Pro_Preta_IP7890_01_standard.jpg', 35, 1, 'Preto/Verde,Azul/Roxo,Roxo/Verde', '7,8,9,10,11', NULL, 0),
(24, 'Boné Nike Heritage86', 'Acessórios', 'Nike', 89.90, 'Estilo e proteção! Boné estruturado da Nike.', 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/7c8d9e0f-1a2b-3c4d-5e6f-7a8b9c0d1e2f/bone-heritage86-nike.png', 95, 1, 'Preto,Branco,Azul,Vermelho,Verde', 'Único', NULL, 0),
(25, 'Garrafa Térmica Nike 750ml', 'Acessórios', 'Nike', 59.90, 'Garrafa inox! Mantém bebidas geladas por 12h.', 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/9d0e1f2a-3b4c-5d6e-7f8a-9b0c1d2e3f4a/garrafa-termica-750ml.png', 120, 0, 'Preto,Branco,Azul,Rosa', '750ml', NULL, 0),
(26, 'Bola Penalty Max 1000', 'Equipamentos', 'Penalty', 79.90, 'A bola mais vendida do Brasil! Custo-benefício incomparável.', 'https://penalty.com.br/media/catalog/product/cache/1/image/1000x1000/9df78eab33525d08d6e5fb8d27136e95/m/a/max_1000_white_1.jpg', 200, 0, 'Branco/Preto,Branco/Azul,Branco/Verde', 'Tamanho 5,Tamanho 4', NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
