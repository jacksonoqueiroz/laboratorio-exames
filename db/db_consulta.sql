-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21/02/2026 às 15:24
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_consulta`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamentos_exames`
--

CREATE TABLE `agendamentos_exames` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) DEFAULT NULL,
  `tipo_exame_id` int(11) DEFAULT NULL,
  `nome_paciente` varchar(150) NOT NULL,
  `data_exame` date DEFAULT NULL,
  `horario` time DEFAULT NULL,
  `usuario_id` varchar(220) DEFAULT NULL,
  `status` enum('Agendado','Cancelado','Em Atendimento','Check-in','Em analise','Realizado') DEFAULT 'Agendado',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `agendamentos_exames`
--

INSERT INTO `agendamentos_exames` (`id`, `paciente_id`, `tipo_exame_id`, `nome_paciente`, `data_exame`, `horario`, `usuario_id`, `status`, `created_at`) VALUES
(1, 2, 5, '', '2026-02-18', '08:15:00', '1', 'Realizado', '2026-02-18 15:12:39'),
(2, 2, 3, '', '2026-02-18', '09:30:00', '2', 'Realizado', '2026-02-18 17:04:18');

-- --------------------------------------------------------

--
-- Estrutura para tabela `agenda_exames`
--

CREATE TABLE `agenda_exames` (
  `id` int(11) NOT NULL,
  `tipo_exame_id` int(11) NOT NULL,
  `dia_semana` int(11) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `intervalo_minutos` int(11) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `agenda_exames`
--

INSERT INTO `agenda_exames` (`id`, `tipo_exame_id`, `dia_semana`, `hora_inicio`, `hora_fim`, `intervalo_minutos`, `ativo`, `created_at`) VALUES
(1, 2, 1, '08:00:00', '17:00:00', 15, 1, '2026-02-13 19:13:39'),
(2, 2, 2, '08:00:00', '17:00:00', 15, 1, '2026-02-13 19:13:39'),
(3, 2, 3, '08:00:00', '17:00:00', 15, 1, '2026-02-13 19:13:40'),
(4, 2, 4, '08:00:00', '17:00:00', 15, 1, '2026-02-13 19:13:40'),
(5, 2, 5, '08:00:00', '17:00:00', 15, 1, '2026-02-13 19:13:40'),
(6, 2, 6, '08:00:00', '14:00:00', 15, 1, '2026-02-13 19:13:40'),
(7, 5, 1, '08:00:00', '12:00:00', 15, 1, '2026-02-16 17:07:02'),
(8, 5, 2, '08:00:00', '12:00:00', 15, 1, '2026-02-16 17:07:02'),
(9, 5, 3, '08:00:00', '12:00:00', 15, 1, '2026-02-16 17:07:02'),
(10, 5, 4, '08:00:00', '12:00:00', 15, 1, '2026-02-16 17:07:02'),
(11, 5, 5, '08:00:00', '12:00:00', 15, 1, '2026-02-16 17:07:02'),
(12, 5, 6, '08:00:00', '12:00:00', 15, 1, '2026-02-16 17:07:02'),
(13, 3, 1, '08:00:00', '17:00:00', 15, 1, '2026-02-18 17:02:56'),
(14, 3, 2, '08:00:00', '17:00:00', 15, 1, '2026-02-18 17:02:56'),
(15, 3, 3, '08:00:00', '17:00:00', 15, 1, '2026-02-18 17:02:56'),
(16, 3, 4, '08:00:00', '17:00:00', 15, 1, '2026-02-18 17:02:56'),
(17, 3, 5, '08:00:00', '17:00:00', 15, 1, '2026-02-18 17:02:56'),
(18, 3, 6, '08:00:00', '17:00:00', 15, 1, '2026-02-18 17:02:56');

-- --------------------------------------------------------

--
-- Estrutura para tabela `consultas`
--

CREATE TABLE `consultas` (
  `id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `horario` time NOT NULL,
  `status` enum('Agendada','Confirmada','Check-in','Chamado','Em atendimento','Cancelada','Remarcada','Concluída','Finalizada') DEFAULT 'Agendada',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `consultas`
--

INSERT INTO `consultas` (`id`, `medico_id`, `paciente_id`, `data`, `horario`, `status`, `criado_em`, `updated_at`) VALUES
(1, 1, 2, '2026-02-18', '08:00:00', 'Concluída', '2026-01-10 19:50:41', '2026-02-18 14:26:37'),
(2, 2, 1, '2026-02-10', '14:00:00', 'Concluída', '2026-01-11 20:36:39', '2026-02-18 14:26:44'),
(3, 2, 2, '2026-02-18', '08:00:00', 'Em atendimento', '2026-02-18 17:29:33', '2026-02-18 14:30:35');

-- --------------------------------------------------------

--
-- Estrutura para tabela `especialidades`
--

CREATE TABLE `especialidades` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `criado_em` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `especialidades`
--

INSERT INTO `especialidades` (`id`, `nome`, `criado_em`) VALUES
(1, 'Clínico Geral', '2026-01-01'),
(2, 'Clínica Ortopedista', '2026-01-01'),
(3, 'Clínico Urulogista', '2026-01-01'),
(4, 'Clínico Ginecologista', '2026-01-01');

-- --------------------------------------------------------

--
-- Estrutura para tabela `exames`
--

CREATE TABLE `exames` (
  `id` int(11) NOT NULL,
  `pedido_exame_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `tipo_exame` varchar(150) NOT NULL,
  `status` enum('Solicitado','Em Analise','Realizado','Cancelado') DEFAULT 'Solicitado',
  `resultado` text DEFAULT NULL,
  `data_solicitacao` datetime DEFAULT current_timestamp(),
  `data_realizacao` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `exames`
--

INSERT INTO `exames` (`id`, `pedido_exame_id`, `paciente_id`, `tipo_exame`, `status`, `resultado`, `data_solicitacao`, `data_realizacao`) VALUES
(1, 1, 2, 'Endoscopia', 'Realizado', 'Gastrite.', '2026-02-18 14:31:23', '2026-02-18 16:58:38'),
(2, 2, 2, 'Ultrasom', 'Realizado', 'Tudo os indices normais. Nada consta.', '2026-02-18 17:02:06', '2026-02-18 17:08:34'),
(3, 3, 2, 'Sangue', 'Realizado', 'Indices normais', '2026-02-18 17:11:34', '2026-02-20 18:05:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `exame_sangue`
--

CREATE TABLE `exame_sangue` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `data_exame` date NOT NULL,
  `status` enum('Em Atendimento','Coleta','Em Analise','Realizado','Check-in') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `exame_sangue`
--

INSERT INTO `exame_sangue` (`id`, `paciente_id`, `data_exame`, `status`, `created_at`) VALUES
(1, 2, '2026-02-20', 'Realizado', '2026-02-20 21:05:17'),
(2, 1, '2026-02-20', 'Em Analise', '2026-02-20 23:32:55');

-- --------------------------------------------------------

--
-- Estrutura para tabela `feriados`
--

CREATE TABLE `feriados` (
  `id` int(11) NOT NULL,
  `data` date NOT NULL,
  `descricao` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `feriados`
--

INSERT INTO `feriados` (`id`, `data`, `descricao`) VALUES
(1, '2026-02-16', 'Carnaval'),
(2, '2026-01-01', 'Confraternização Universal'),
(3, '2026-04-21', 'Tiradentes'),
(4, '2026-05-01', 'Dia do Trabalhador'),
(5, '2026-09-07', 'Independência do Brasil'),
(6, '2026-10-12', 'Nossa Senhora Aparecida'),
(7, '2026-11-02', 'Finados'),
(8, '2026-11-15', 'Proclamação da República'),
(9, '2026-12-25', 'Natal');

-- --------------------------------------------------------

--
-- Estrutura para tabela `folgas_medico`
--

CREATE TABLE `folgas_medico` (
  `id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `motivo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `folgas_medico`
--

INSERT INTO `folgas_medico` (`id`, `medico_id`, `data`, `motivo`) VALUES
(2, 2, '2026-02-09', 'Banco de Horas');

-- --------------------------------------------------------

--
-- Estrutura para tabela `horarios_medico`
--

CREATE TABLE `horarios_medico` (
  `id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `dia_semana` tinyint(4) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `intervalo` int(11) NOT NULL,
  `hora_inicio_refeicao` time DEFAULT NULL,
  `hora_fim_refeicao` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `horarios_medico`
--

INSERT INTO `horarios_medico` (`id`, `medico_id`, `dia_semana`, `hora_inicio`, `hora_fim`, `intervalo`, `hora_inicio_refeicao`, `hora_fim_refeicao`) VALUES
(2, 2, 1, '08:00:00', '17:00:00', 30, '11:00:00', '12:20:00'),
(6, 2, 3, '08:00:00', '17:00:00', 30, '12:00:00', '13:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `laboratorio_exames`
--

CREATE TABLE `laboratorio_exames` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `material` varchar(100) DEFAULT NULL,
  `prazo_resultado` int(11) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `medicos`
--

CREATE TABLE `medicos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `crm` varchar(30) NOT NULL,
  `especialidade_id` int(11) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `ativo` tinyint(4) DEFAULT 1,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `status` enum('Ativo','Inativo') DEFAULT 'Ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `medicos`
--

INSERT INTO `medicos` (`id`, `nome`, `crm`, `especialidade_id`, `telefone`, `ativo`, `criado_em`, `email`, `senha`, `status`) VALUES
(1, 'Pedro Augusto da Silva', '2345', 1, '(11)94567-9900', 1, '2026-01-01 23:30:30', 'pedro.augusto@gmail.com', '$2y$10$qevMZgP12zN8fDBNgis2h.INPRa5ashqlT7Im2os.gA8NhNw534yS', 'Ativo'),
(2, 'Andréa Silva Andrade', '2344', 4, '((95560-9968', 1, '2026-01-02 23:23:55', 'andreia.silva@gmail.com', '$2y$10$qevMZgP12zN8fDBNgis2h.INPRa5ashqlT7Im2os.gA8NhNw534yS', 'Ativo'),
(3, 'Jorge Haroldo Paiva', '4567', 2, '(11)96709-2255', 1, '2026-01-02 23:40:56', 'jorge.haroldo@gmail.com', '$2y$10$qevMZgP12zN8fDBNgis2h.INPRa5ashqlT7Im2os.gA8NhNw534yS', 'Ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pacientes`
--

CREATE TABLE `pacientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `tipo_atendimento` enum('Particular','Convenio') NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `status` enum('Ativo','Inativo') DEFAULT 'Ativo',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pacientes`
--

INSERT INTO `pacientes` (`id`, `nome`, `cpf`, `data_nascimento`, `tipo_atendimento`, `telefone`, `email`, `observacoes`, `status`, `criado_em`) VALUES
(1, 'Jackson de Oliveira Queiroz', '11906905860', '1972-12-04', 'Particular', '11940697068', 'jackson.oqueiroz@gmail.com', '', 'Ativo', '2026-01-03 20:51:37'),
(2, 'Elisangela M. Santos Queiroz', '11122233344', '1974-08-21', 'Particular', '11976596840', 'eli.santos.msq@gmail.com', 'Minha Linda', 'Ativo', '2026-01-05 22:58:19'),
(3, 'Vinicius Queiroz', '12345678901', NULL, 'Particular', '11222334455', 'vinicius.queiroz@gmail.com', NULL, 'Ativo', '2026-01-07 21:29:31'),
(4, 'Gabriel Queiroz', '12476834590', NULL, 'Particular', '11930678934', 'gabriel.queiroz@gmail.com', NULL, 'Ativo', '2026-01-07 22:54:14'),
(5, 'Pedro Cassimiro', '2334456789', NULL, 'Particular', '11944666778', 'pedro.piroca@gmail.com', NULL, 'Ativo', '2026-01-07 22:59:11'),
(6, 'Edilson Souza', '12345678912', '1946-05-18', 'Particular', NULL, NULL, NULL, 'Ativo', '2026-02-21 01:07:58'),
(7, 'Ilda Maria dos Santos', '2345667878', '1952-01-19', 'Particular', NULL, NULL, NULL, 'Ativo', '2026-02-21 01:12:26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos_exames`
--

CREATE TABLE `pedidos_exames` (
  `id` int(11) NOT NULL,
  `consulta_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `status_geral` enum('Aberto','Concluido','Cancelado') DEFAULT 'Aberto',
  `data_pedido` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos_exames`
--

INSERT INTO `pedidos_exames` (`id`, `consulta_id`, `paciente_id`, `medico_id`, `observacoes`, `status_geral`, `data_pedido`) VALUES
(1, 3, 2, 2, 'Exames para futuros diagnoticos.', 'Aberto', '2026-02-18 14:31:23'),
(2, 3, 2, 2, 'Ultrasom do esofago.', 'Aberto', '2026-02-18 17:02:06'),
(3, 3, 2, 2, 'Análises para diabetes.', 'Aberto', '2026-02-18 17:11:34');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos_exames_itens`
--

CREATE TABLE `pedidos_exames_itens` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `exame_id` int(11) NOT NULL,
  `status` enum('Solicitado','Coletado','Em Analise','Finalizado') DEFAULT 'Solicitado',
  `resultado` text DEFAULT NULL,
  `data_resultado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `prescricoes`
--

CREATE TABLE `prescricoes` (
  `id` int(11) NOT NULL,
  `consulta_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `prescricao` text NOT NULL,
  `orientacoes` text DEFAULT NULL,
  `data_prescricao` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `prescricoes`
--

INSERT INTO `prescricoes` (`id`, `consulta_id`, `medico_id`, `paciente_id`, `prescricao`, `orientacoes`, `data_prescricao`, `created_at`) VALUES
(1, 2, 2, 1, 'Dorflex 25mg - tomar a cada 8 horas', 'Tomar durante 30 dias.', '2026-01-25', '2026-01-25 21:26:54'),
(2, 2, 2, 1, 'Dorflex 25mg - tomar a cada 8 horas', 'Tomar durante 30 dias.', '2026-01-25', '2026-01-25 21:29:36'),
(3, 1, 1, 2, 'Dipirona', 'Tomar de 6 em 6 horas', '2026-01-26', '2026-01-26 22:56:55'),
(4, 1, 1, 2, 'Dipirona 500 mg', 'Tomar a cada 6 horas', '2026-01-27', '2026-01-27 22:30:05'),
(5, 1, 1, 2, 'dipirona 500 mg', 'Tomar a cada 6 horas', '2026-01-31', '2026-01-31 16:55:36'),
(6, 1, 1, 2, 'dipirona 500 mg', 'Tomar a cada 6 horas', '2026-01-31', '2026-01-31 17:07:56'),
(7, 1, 1, 2, 'Dipirona 500mg', 'Tomar a cada 6 horas', '2026-01-31', '2026-01-31 17:18:42'),
(8, 1, 1, 2, 'Dipirona 500mg', 'Tomar a cada 6 horas', '2026-01-31', '2026-01-31 17:24:47');

-- --------------------------------------------------------

--
-- Estrutura para tabela `prontuarios`
--

CREATE TABLE `prontuarios` (
  `id` int(11) NOT NULL,
  `consulta_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `data_atendimento` date NOT NULL,
  `queixa` text DEFAULT NULL,
  `anamnese` text DEFAULT NULL,
  `exame` text DEFAULT NULL,
  `diagnostico` text DEFAULT NULL,
  `conduta` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `prontuarios`
--

INSERT INTO `prontuarios` (`id`, `consulta_id`, `medico_id`, `paciente_id`, `data_atendimento`, `queixa`, `anamnese`, `exame`, `diagnostico`, `conduta`, `observacoes`, `created_at`) VALUES
(1, 2, 1, 1, '2026-01-14', 'teste', 'teste', 'teste', 'teste', 'teste', 'teste', '2026-01-14 22:04:24'),
(2, 1, 1, 2, '2026-01-22', 'teste 2', 'teste 2', 'teste 2', 'teste 2', 'teste 2', 'teste 2', '2026-01-22 22:46:04'),
(4, 1, 1, 2, '2026-01-25', 'Dor de Cabeça', 'Cealeia', 'encefalograma', 'Aguardar o resultado do exame', 'Não especificado', 'Não especificado', '2026-01-25 18:16:29'),
(5, 2, 2, 1, '2026-01-25', 'Dores no Pé', 'Esporão', 'Raio X', 'Aguardar exames...', 'NE', 'NE', '2026-01-25 18:32:28');

-- --------------------------------------------------------

--
-- Estrutura para tabela `resultado_exame`
--

CREATE TABLE `resultado_exame` (
  `id` int(11) NOT NULL,
  `tipo_exame_id` int(11) NOT NULL,
  `paciente_id` int(11) DEFAULT NULL,
  `data_exame` date NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `resultado` text NOT NULL,
  `laudo` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `resultado_exame`
--

INSERT INTO `resultado_exame` (`id`, `tipo_exame_id`, `paciente_id`, `data_exame`, `usuario_id`, `resultado`, `laudo`, `created_at`) VALUES
(1, 5, 2, '2026-02-18', 2, 'Gastrite.', 'Paciente, constata, pequena inflamação estomacal.', '2026-02-18 16:58:38'),
(2, 1, 2, '2026-02-20', 1, 'Indices normais', 'Paciente não constata, alguma anomalia.', '2026-02-20 18:05:59'),
(3, 1, 1, '2026-02-20', 1, 'Indices normais.', 'Paciente, não apresenta anomalias.', '2026-02-20 20:48:30');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_exames`
--

CREATE TABLE `tipos_exames` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `precisa_agendamento` tinyint(1) DEFAULT 1,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tipos_exames`
--

INSERT INTO `tipos_exames` (`id`, `nome`, `precisa_agendamento`, `ativo`, `created_at`) VALUES
(1, 'Sangue', 0, 1, '2026-02-13 17:38:40'),
(2, 'Ecocardiograma', 1, 1, '2026-02-13 17:38:40'),
(3, 'Ultrassom', 1, 1, '2026-02-13 17:38:40'),
(4, 'Raio X', 1, 1, '2026-02-13 17:38:40'),
(5, 'Endoscopia', 1, 1, '2026-02-13 17:51:07');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(220) NOT NULL,
  `usuario` varchar(220) NOT NULL,
  `senha` varchar(220) NOT NULL,
  `imagem` varchar(220) NOT NULL,
  `perfil` enum('Admin','Atendente') NOT NULL DEFAULT 'Atendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `usuario`, `senha`, `imagem`, `perfil`) VALUES
(1, 'Jackson Queiroz', 'jackson.oqueiroz@gmail.com', '$2y$10$f.LrBA08y8t9.ZpVVJVSZOGYWwflwYVBFZ94m5o3DuW/FzAWE9Mle', 'semfoto.png', 'Admin'),
(2, 'Atendente 1', 'atendimento@gmail.com', '$2y$10$f1DjfrNXSupJWdVmYMpjQ.Bx4b6O5jpMoWIi2W9LMCTkxC7W33HgO', 'semfoto.png', 'Atendente');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario_lab`
--

CREATE TABLE `usuario_lab` (
  `id` int(11) NOT NULL,
  `nome` varchar(220) NOT NULL,
  `usuario` varchar(220) NOT NULL,
  `senha` varchar(220) NOT NULL,
  `imagem` varchar(220) NOT NULL DEFAULT 'semfoto.png',
  `perfil` enum('Admin','Atendente') NOT NULL DEFAULT 'Atendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuario_lab`
--

INSERT INTO `usuario_lab` (`id`, `nome`, `usuario`, `senha`, `imagem`, `perfil`) VALUES
(1, 'Laboratório', 'jackson.oqueiroz@gmail.com', '$2y$10$GEFjdWzA5bD2x8w4XvCGXORABgwOY8OV3.qLj2ERJKAut6QM3Y25G', 'semfoto.png', 'Atendente');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamentos_exames`
--
ALTER TABLE `agendamentos_exames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_agendamento_paciente` (`paciente_id`);

--
-- Índices de tabela `agenda_exames`
--
ALTER TABLE `agenda_exames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_exame_id` (`tipo_exame_id`);

--
-- Índices de tabela `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `medico_id` (`medico_id`,`data`,`horario`),
  ADD KEY `paciente_id` (`paciente_id`);

--
-- Índices de tabela `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `exames`
--
ALTER TABLE `exames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_exames_pedido` (`pedido_exame_id`);

--
-- Índices de tabela `exame_sangue`
--
ALTER TABLE `exame_sangue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`);

--
-- Índices de tabela `feriados`
--
ALTER TABLE `feriados`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `folgas_medico`
--
ALTER TABLE `folgas_medico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medico_id` (`medico_id`);

--
-- Índices de tabela `horarios_medico`
--
ALTER TABLE `horarios_medico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medico_id` (`medico_id`);

--
-- Índices de tabela `laboratorio_exames`
--
ALTER TABLE `laboratorio_exames`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `especialidade_id` (`especialidade_id`);

--
-- Índices de tabela `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Índices de tabela `pedidos_exames`
--
ALTER TABLE `pedidos_exames`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pedidos_exames_itens`
--
ALTER TABLE `pedidos_exames_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `exame_id` (`exame_id`);

--
-- Índices de tabela `prescricoes`
--
ALTER TABLE `prescricoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `prontuarios`
--
ALTER TABLE `prontuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consulta_id` (`consulta_id`);

--
-- Índices de tabela `resultado_exame`
--
ALTER TABLE `resultado_exame`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tipos_exames`
--
ALTER TABLE `tipos_exames`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos_exames`
--
ALTER TABLE `agendamentos_exames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `agenda_exames`
--
ALTER TABLE `agenda_exames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `consultas`
--
ALTER TABLE `consultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `exames`
--
ALTER TABLE `exames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `exame_sangue`
--
ALTER TABLE `exame_sangue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `feriados`
--
ALTER TABLE `feriados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `folgas_medico`
--
ALTER TABLE `folgas_medico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `horarios_medico`
--
ALTER TABLE `horarios_medico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `laboratorio_exames`
--
ALTER TABLE `laboratorio_exames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `pedidos_exames`
--
ALTER TABLE `pedidos_exames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `pedidos_exames_itens`
--
ALTER TABLE `pedidos_exames_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `prescricoes`
--
ALTER TABLE `prescricoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `prontuarios`
--
ALTER TABLE `prontuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `resultado_exame`
--
ALTER TABLE `resultado_exame`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tipos_exames`
--
ALTER TABLE `tipos_exames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamentos_exames`
--
ALTER TABLE `agendamentos_exames`
  ADD CONSTRAINT `fk_agendamento_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`);

--
-- Restrições para tabelas `agenda_exames`
--
ALTER TABLE `agenda_exames`
  ADD CONSTRAINT `agenda_exames_ibfk_1` FOREIGN KEY (`tipo_exame_id`) REFERENCES `tipos_exames` (`id`);

--
-- Restrições para tabelas `consultas`
--
ALTER TABLE `consultas`
  ADD CONSTRAINT `consultas_ibfk_1` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`),
  ADD CONSTRAINT `consultas_ibfk_2` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`);

--
-- Restrições para tabelas `exames`
--
ALTER TABLE `exames`
  ADD CONSTRAINT `fk_exames_pedido` FOREIGN KEY (`pedido_exame_id`) REFERENCES `pedidos_exames` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `exame_sangue`
--
ALTER TABLE `exame_sangue`
  ADD CONSTRAINT `exame_sangue_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`);

--
-- Restrições para tabelas `folgas_medico`
--
ALTER TABLE `folgas_medico`
  ADD CONSTRAINT `folgas_medico_ibfk_1` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `horarios_medico`
--
ALTER TABLE `horarios_medico`
  ADD CONSTRAINT `horarios_medico_ibfk_1` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`);

--
-- Restrições para tabelas `medicos`
--
ALTER TABLE `medicos`
  ADD CONSTRAINT `medicos_ibfk_1` FOREIGN KEY (`especialidade_id`) REFERENCES `especialidades` (`id`);

--
-- Restrições para tabelas `pedidos_exames_itens`
--
ALTER TABLE `pedidos_exames_itens`
  ADD CONSTRAINT `pedidos_exames_itens_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos_exames` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedidos_exames_itens_ibfk_2` FOREIGN KEY (`exame_id`) REFERENCES `laboratorio_exames` (`id`);

--
-- Restrições para tabelas `prontuarios`
--
ALTER TABLE `prontuarios`
  ADD CONSTRAINT `prontuarios_ibfk_1` FOREIGN KEY (`consulta_id`) REFERENCES `consultas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
