-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 08, 2026 alle 23:12
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `5aprogetto`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `commenti`
--

CREATE TABLE `commenti` (
  `Utente` varchar(40) NOT NULL,
  `Id_Post` int(30) NOT NULL,
  `Contenuto` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `follow`
--

CREATE TABLE `follow` (
  `Seguente` varchar(40) NOT NULL,
  `Seguito` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `follow`
--

INSERT INTO `follow` (`Seguente`, `Seguito`) VALUES
('Giandix67', 'MarcusRisula'),
('MarcusRisula', 'Giandix67');

-- --------------------------------------------------------

--
-- Struttura della tabella `likepost`
--

CREATE TABLE `likepost` (
  `Utente` varchar(15) NOT NULL,
  `Id_Post` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `likepost`
--

INSERT INTO `likepost` (`Utente`, `Id_Post`) VALUES
('Giandix67', 10),
('MarcusRisula', 3),
('MarcusRisula', 10);

-- --------------------------------------------------------

--
-- Struttura della tabella `post`
--

CREATE TABLE `post` (
  `Id_Post` int(30) NOT NULL,
  `NumLike` int(4) DEFAULT 0,
  `Condivisioni` int(4) DEFAULT 0,
  `Allegato` varchar(100) DEFAULT NULL,
  `Descrizione` varchar(300) NOT NULL,
  `Data_post` datetime NOT NULL,
  `Utente` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `post`
--

INSERT INTO `post` (`Id_Post`, `NumLike`, `Condivisioni`, `Allegato`, `Descrizione`, `Data_post`, `Utente`) VALUES
(3, 1, 0, NULL, 'oooooooo', '2026-05-04 19:37:24', 'Giandix67'),
(8, 0, 0, '8b9a87cee07f2404458708c4f09a830c8336271d6dd63609b2bfc69189ec84a0.png', 'Ma quanto è bello quando hai un vero e proprio algoritmo?', '2026-05-07 18:44:05', 'Giandix67'),
(9, 0, 0, NULL, 'boh testa date', '2026-05-07 22:06:34', 'Giandix67'),
(10, 2, 0, '3e4799ed542a619bcb1621de73c1a3c42726274647e199c5a5ff26030770ef14.png', 'testa foto', '2026-05-07 22:06:56', 'Giandix67');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `Nome` varchar(15) NOT NULL,
  `Cognome` varchar(15) NOT NULL,
  `NomeUtente` varchar(15) NOT NULL,
  `DataNascita` date DEFAULT NULL,
  `Foto` varchar(300) NOT NULL DEFAULT 'Utente.png',
  `Descrizione` varchar(200) DEFAULT NULL,
  `NumPost` int(5) DEFAULT 0,
  `Password` varchar(100) NOT NULL,
  `Livello` int(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`Nome`, `Cognome`, `NomeUtente`, `DataNascita`, `Foto`, `Descrizione`, `NumPost`, `Password`, `Livello`) VALUES
('Gianni', 'Giandagoberto', 'Giandix67', '2005-12-07', 'Utente.png', 'Il più grande utilizzatore di Valorant, non esco di casa e mi metto a giocare ad Overwatch. Ultima volta che ho toccato l\'erba: la mia nascita', 0, 'a', 0),
('Marcus', 'Risula', 'MarcusRisula', '2007-01-03', 'Utente.png', 'questo è un mega test', 0, 'aaa', 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `commenti`
--
ALTER TABLE `commenti`
  ADD PRIMARY KEY (`Utente`,`Id_Post`),
  ADD KEY `FK_post` (`Id_Post`);

--
-- Indici per le tabelle `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`Seguente`,`Seguito`),
  ADD KEY `FK_Seguito` (`Seguito`);

--
-- Indici per le tabelle `likepost`
--
ALTER TABLE `likepost`
  ADD PRIMARY KEY (`Utente`,`Id_Post`),
  ADD KEY `FK_postLike` (`Id_Post`);

--
-- Indici per le tabelle `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`Id_Post`),
  ADD KEY `FK_utente` (`Utente`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`NomeUtente`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `post`
--
ALTER TABLE `post`
  MODIFY `Id_Post` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `commenti`
--
ALTER TABLE `commenti`
  ADD CONSTRAINT `FK_post` FOREIGN KEY (`Id_Post`) REFERENCES `post` (`Id_Post`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_utenti` FOREIGN KEY (`Utente`) REFERENCES `utenti` (`NomeUtente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `follow`
--
ALTER TABLE `follow`
  ADD CONSTRAINT `FK_Seguente` FOREIGN KEY (`Seguente`) REFERENCES `utenti` (`NomeUtente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Seguito` FOREIGN KEY (`Seguito`) REFERENCES `utenti` (`NomeUtente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `likepost`
--
ALTER TABLE `likepost`
  ADD CONSTRAINT `FK_postLike` FOREIGN KEY (`Id_Post`) REFERENCES `post` (`Id_Post`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_utentiLike` FOREIGN KEY (`Utente`) REFERENCES `utenti` (`NomeUtente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_utente` FOREIGN KEY (`Utente`) REFERENCES `utenti` (`NomeUtente`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
