-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13-Ago-2025 às 22:28
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
(2, '10PERCENT', 'Desconta 10% do valor total na primeira compra', '10.00', 'percentual', NULL, 'ativo');

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

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_pedido` datetime DEFAULT current_timestamp(),
  `status` enum('no_carrinho','aguardando_pagamento','pago','enviado','entregue','cancelado') DEFAULT 'no_carrinho',
  `forma_pagamento` varchar(50) DEFAULT NULL,
  `valor_total` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(4, 'Café Espresso Gourmet 3 Corações', 'Café Torrado em Grãos Espresso Gourmet 3 Corações Pacote 500g', '65.87', 123, 'graos', 2, 'assets/images/produtos/6863509076b72-3coracoes.jpg', 'a venda');

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
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome`, `email`, `senha`, `tipo`, `data_cadastro`) VALUES
(1, 'Trombeta', 'trombeta@gmail.com', '$2y$10$X2iV02mfs4Ih4fuNtn1p5uNtisYF7v5uIslyLr8/FmOk0UeqdhmPm', 'cliente', '2025-06-20 23:05:40'),
(2, 'Ademiros', 'admin@gmail.com', '$2y$10$X2iV02mfs4Ih4fuNtn1p5uNtisYF7v5uIslyLr8/FmOk0UeqdhmPm', 'admin', '2025-06-21 20:57:29'),
(3, 'Geraldo', 'gerente@gmail.com', '$2y$10$FxOZo.RKkSN4kS3.51rlzOfkk2sAfbtsrsulQPvGDVvtcIRyyKVCq', 'gerente', '2025-06-21 21:41:47'),
(4, 'Vergil', 'vendedor@gmail.com', '$2y$10$WGFUj7LRLX0ZFaguDA83puJdUnRcEEVUz7e0McC2STWcjCb1Pv5n2', 'vendedor', '2025-06-21 21:43:11');

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
  MODIFY `id_cupom` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `cupons_usados`
--
ALTER TABLE `cupons_usados`
  MODIFY `id_cupom_usado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
