-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12-Nov-2025 às 21:23
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `aliexpresso`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cupons`
--

CREATE TABLE `cupons` (
  `id_cupom` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `valor_desconto` decimal(10,2) NOT NULL,
  `tipo` enum('fixo','percentual') NOT NULL DEFAULT 'fixo',
  `data_validade` date DEFAULT NULL,
  `status` enum('ativo','expirado') NOT NULL DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `cupons`
--

INSERT INTO `cupons` (`id_cupom`, `codigo`, `descricao`, `valor_desconto`, `tipo`, `data_validade`, `status`) VALUES
(1, '10OFF', 'Desconta 10 reais do valor total da compra', '10.00', 'fixo', '2025-07-30', 'ativo'),
(2, '10PERCENT', 'Desconta 10% do valor total na primeira compra', '10.00', 'percentual', NULL, 'ativo'),
(3, 'FIDELIDADE-68DC6C2AC1BFB', 'Cupom de 10% por ter feito 5 compras!', '10.00', 'percentual', '2025-11-30', ''),
(4, 'FIDELIDADE-68DC770B3D00A', 'Cupom de 10% por ter feito 10 compras!', '10.00', 'percentual', '2025-11-30', ''),
(5, 'FIDELIDADE-68DC79E392C2D', 'Cupom de 10% por ter feito 15 compras!', '10.00', 'percentual', '2025-11-30', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cupons_usados`
--

CREATE TABLE `cupons_usados` (
  `id_cupom_usado` int(11) NOT NULL,
  `id_cupom` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `data_uso` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `cupons_usados`
--

INSERT INTO `cupons_usados` (`id_cupom_usado`, `id_cupom`, `id_usuario`, `id_pedido`, `data_uso`) VALUES
(1, 3, 1, 7, '2025-09-30 21:25:04'),
(2, 4, 1, 11, '2025-09-30 21:34:36'),
(3, 5, 1, 16, '2025-09-30 21:46:44');

-- --------------------------------------------------------

--
-- Estrutura da tabela `enderecos`
--

CREATE TABLE `enderecos` (
  `id_endereco` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `logradouro` varchar(255) NOT NULL,
  `numero` varchar(30) NOT NULL,
  `complemento` varchar(40) DEFAULT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `is_principal` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `enderecos`
--

INSERT INTO `enderecos` (`id_endereco`, `id_usuario`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `uf`, `is_principal`) VALUES
(1, 1, '19200-009', 'Rua Angelo Salvatori', '123', '', 'Centro', 'Pirapozinho', 'SP', 0),
(2, 1, '19036-102', 'Rua Antonio Custódio Spiguel', '66', 'Casa do CamargoCasa do CamargoCasaCasa d', 'Conjunto Habitacional João Domingos Netto', 'Presidente Prudente', 'SP', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `id_item` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `itens_pedido`
--

INSERT INTO `itens_pedido` (`id_item`, `id_pedido`, `id_produto`, `quantidade`, `preco_unitario`) VALUES
(79, 1, 3, 1, '70.00'),
(80, 1, 1, 1, '20.00'),
(81, 1, 2, 6, '10.00'),
(94, 2, 3, 3, '70.00'),
(95, 3, 2, 1, '10.00'),
(96, 4, 4, 1, '65.87'),
(97, 5, 1, 1, '20.00'),
(123, 6, 1, 1, '20.00'),
(124, 6, 2, 1, '10.00'),
(126, 7, 1, 1, '20.00'),
(127, 7, 2, 1, '10.00'),
(129, 8, 1, 1, '20.00'),
(130, 8, 2, 1, '10.00'),
(132, 9, 2, 2, '10.00'),
(133, 10, 4, 1, '65.87'),
(137, 11, 3, 1, '70.00'),
(138, 11, 1, 1, '20.00'),
(139, 11, 2, 1, '10.00'),
(140, 12, 1, 1, '20.00'),
(141, 13, 2, 1, '10.00'),
(142, 14, 2, 1, '10.00'),
(143, 15, 2, 1, '10.00'),
(147, 16, 3, 1, '70.00'),
(148, 16, 1, 1, '20.00'),
(149, 16, 2, 1, '10.00'),
(571, 17, 1, 348, '20.00'),
(572, 17, 4, 2, '65.87'),
(573, 17, 2, 24, '10.00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_pedido` datetime DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'carrinho',
  `forma_pagamento` varchar(50) DEFAULT NULL,
  `valor_total` decimal(10,2) DEFAULT 0.00,
  `desconto` decimal(10,2) DEFAULT 0.00,
  `valor_final` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_usuario`, `data_pedido`, `status`, `forma_pagamento`, `valor_total`, `desconto`, `valor_final`) VALUES
(1, 1, '2025-09-29 19:34:23', 'concluido', NULL, '150.00', '0.00', '150.00'),
(2, 1, '2025-09-30 20:01:35', 'concluido', NULL, '210.00', '0.00', '210.00'),
(3, 1, '2025-09-30 20:47:03', 'concluido', NULL, '10.00', '0.00', '10.00'),
(4, 1, '2025-09-30 20:47:43', 'concluido', NULL, '65.87', '0.00', '65.87'),
(5, 1, '2025-09-30 20:47:54', 'concluido', NULL, '20.00', '0.00', '20.00'),
(6, 1, '2025-09-30 21:19:17', 'concluido', NULL, '30.00', '0.00', '30.00'),
(7, 1, '2025-09-30 21:25:13', 'concluido', NULL, '30.00', '0.00', '30.00'),
(8, 1, '2025-09-30 21:34:04', 'concluido', NULL, '30.00', '0.00', '30.00'),
(9, 1, '2025-09-30 21:34:11', 'concluido', NULL, '20.00', '0.00', '20.00'),
(10, 1, '2025-09-30 21:34:19', 'concluido', NULL, '65.87', '0.00', '65.87'),
(11, 1, '2025-09-30 21:34:36', 'concluido', NULL, '100.00', '10.00', '90.00'),
(12, 1, '2025-09-30 21:46:06', 'concluido', NULL, '20.00', '0.00', '20.00'),
(13, 1, '2025-09-30 21:46:15', 'concluido', NULL, '10.00', '0.00', '10.00'),
(14, 1, '2025-09-30 21:46:22', 'concluido', NULL, '10.00', '0.00', '10.00'),
(15, 1, '2025-09-30 21:46:27', 'concluido', NULL, '10.00', '0.00', '10.00'),
(16, 1, '2025-09-30 21:46:44', 'concluido', NULL, '100.00', '10.00', '90.00'),
(17, 5, '2025-11-12 17:11:21', 'concluido', NULL, '7331.74', '0.00', '7331.74'),
(18, 1, '2025-11-12 17:18:06', 'carrinho', NULL, '0.00', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id_produto` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `quantidade_estoque` int(11) DEFAULT 0,
  `categoria` varchar(50) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `status` enum('a venda','descontinuado') DEFAULT 'a venda'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id_produto`, `nome`, `descricao`, `preco`, `quantidade_estoque`, `categoria`, `id_usuario`, `imagem`, `status`) VALUES
(1, 'Chocolate cAMARGO', 'Chocolate meio cAMARGO', '20.00', 200, 'doces', 2, 'assets/images/produtos/68634f970f302-Chocolate C amargo.png', 'a venda'),
(2, 'Monster Tradicional', 'Bebida Energetica Monster Energy Green Com 473Ml', '10.00', 150, 'energetico', 2, 'assets/images/produtos/68634fcf4ffeb-monster.jpg', 'a venda'),
(3, 'Café Au Lait Dolce Gusto', 'Nescafé Dolce Gusto com 10 cápsulas', '70.00', 100, 'capsula', 2, 'assets/images/produtos/686350448fd93-capsula.jpg', 'a venda'),
(4, 'Café Espresso Gourmet 3 Corações', 'Café Torrado em Grãos Espresso Gourmet 3 Corações Pacote 500g', '65.87', 123, 'graos', 2, 'assets/images/produtos/6863509076b72-3coracoes.jpg', 'a venda'),
(5, '121212', '12', '12.00', 10, 'capsula', NULL, 'assets/images/produtos/68dc804ab3d71-coffeeCup.png', 'a venda');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('cliente','vendedor','gerente','admin') NOT NULL DEFAULT 'cliente',
  `imagem_perfil` varchar(255) DEFAULT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome`, `email`, `senha`, `tipo`, `imagem_perfil`, `data_cadastro`) VALUES
(1, 'Trombeta', 'trombeta@gmail.com', '$2y$10$X2iV02mfs4Ih4fuNtn1p5uNtisYF7v5uIslyLr8/FmOk0UeqdhmPm', 'cliente', 'assets/images/perfil/1_6914eac5d93cb.jpg', '2025-06-20 23:05:40'),
(2, 'Ademiros', 'admin@gmail.com', '$2y$10$X2iV02mfs4Ih4fuNtn1p5uNtisYF7v5uIslyLr8/FmOk0UeqdhmPm', 'admin', NULL, '2025-06-21 20:57:29'),
(3, 'Geraldo', 'gerente@gmail.com', '$2y$10$FxOZo.RKkSN4kS3.51rlzOfkk2sAfbtsrsulQPvGDVvtcIRyyKVCq', 'gerente', NULL, '2025-06-21 21:41:47'),
(4, 'Vergil', 'vendedor@gmail.com', '$2y$10$X2iV02mfs4Ih4fuNtn1p5uNtisYF7v5uIslyLr8/FmOk0UeqdhmPm', 'vendedor', NULL, '2025-06-21 21:43:11'),
(5, 'CamargoCamargoCamargoCamargoCamargoCamargoCamargoCamargoCamargoCamargoCamargoCamargoCamargoCamargoCa', 'Camargo@gmail.com', '$2y$10$pVxNLGrZ37UvohZxa4ZLx.yZ7DHmnDzo0H1RpYaAR0dN.Eu2Y2jl6', 'cliente', 'assets/images/perfil/5_6914eabf1c1ba.jpg', '2025-11-12 17:09:08');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cupons`
--
ALTER TABLE `cupons`
  ADD PRIMARY KEY (`id_cupom`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Índices para tabela `cupons_usados`
--
ALTER TABLE `cupons_usados`
  ADD PRIMARY KEY (`id_cupom_usado`),
  ADD KEY `id_cupom` (`id_cupom`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Índices para tabela `enderecos`
--
ALTER TABLE `enderecos`
  ADD PRIMARY KEY (`id_endereco`),
  ADD KEY `idx_id_usuario` (`id_usuario`);

--
-- Índices para tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices para tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `id_vendedor` (`id_usuario`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cupons`
--
ALTER TABLE `cupons`
  MODIFY `id_cupom` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `cupons_usados`
--
ALTER TABLE `cupons_usados`
  MODIFY `id_cupom_usado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `enderecos`
--
ALTER TABLE `enderecos`
  MODIFY `id_endereco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=842;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `cupons_usados`
--
ALTER TABLE `cupons_usados`
  ADD CONSTRAINT `cupons_usados_ibfk_1` FOREIGN KEY (`id_cupom`) REFERENCES `cupons` (`id_cupom`),
  ADD CONSTRAINT `cupons_usados_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `cupons_usados_ibfk_3` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`);

--
-- Limitadores para a tabela `enderecos`
--
ALTER TABLE `enderecos`
  ADD CONSTRAINT `fk_endereco_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`);

--
-- Limitadores para a tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
