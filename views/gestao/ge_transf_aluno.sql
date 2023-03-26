-- phpMyAdmin SQL Dump
-- version 4.6.5.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 31-Jan-2017 às 12:55
-- Versão do servidor: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ge`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `ge_transf_aluno`
--

CREATE TABLE `ge_transf_aluno` (
  `id_transf` int(11) NOT NULL,
  `fk_id_pessoa` int(11) NOT NULL,
  `n_pessoa` varchar(100) NOT NULL,
  `cod_inst_o` int(11) NOT NULL,
  `n_escola_origem` varchar(100) NOT NULL,
  `cod_inst_d` int(11) NOT NULL,
  `n_escola_destino` varchar(100) NOT NULL,
  `codigo_classe_o` varchar(11) NOT NULL,
  `codigo_classe_d` varchar(11) DEFAULT NULL,
  `turmaid` int(11) NOT NULL,
  `chamada` int(11) NOT NULL,
  `dt_solicitacao` date NOT NULL,
  `status_transf` varchar(50) NOT NULL,
  `dt_aprovacao` date DEFAULT NULL,
  `periodo_letivo` varchar(4) NOT NULL,
  `dt_matricula` date NOT NULL,
  `obs_transf` varchar(100) DEFAULT NULL,
  `dt_liberacao` date NOT NULL,
  `dt_cancelamento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ge_transf_aluno`
--
ALTER TABLE `ge_transf_aluno`
  ADD PRIMARY KEY (`id_transf`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ge_transf_aluno`
--
ALTER TABLE `ge_transf_aluno`
  MODIFY `id_transf` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
