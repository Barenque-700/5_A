-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mar 24, 2026 alle 09:47
-- Versione del server: 10.4.21-MariaDB
-- Versione PHP: 8.0.10

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
-- Struttura della tabella `post`
--

CREATE TABLE `post` (
  `Id_Post` int(30) NOT NULL,
  `NumLike` int(4) DEFAULT NULL,
  `Commenti` int(4) DEFAULT NULL,
  `Condivisioni` int(4) DEFAULT NULL,
  `Tag` varchar(15) NOT NULL,
  `Allegato` varchar(50) NOT NULL,
  `Utente` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `Nome` varchar(15) NOT NULL,
  `Cognome` varchar(15) NOT NULL,
  `NomeUtente` varchar(15) NOT NULL,
  `DataNascita` date DEFAULT NULL,
  `Seguiti` int(4) DEFAULT 0,
  `Follower` int(7) DEFAULT 0,
  `Descrizione` varchar(200) DEFAULT NULL,
  `NumPost` int(5) DEFAULT 0,
  `Password` varchar(100) NOT NULL,
  `Livello` int(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`Nome`, `Cognome`, `NomeUtente`, `DataNascita`, `Seguiti`, `Follower`, `Descrizione`, `NumPost`, `Password`, `Livello`) VALUES
('Gianni', 'Giandagoberto', 'Giandix67', '2005-12-07', 12, 34, 'Il più grande utilizzatore di Valorant, non esco di casa e mi metto a giocare ad Overwatch. Ultima volta che ho toccato l\'erba: la mia nascita', 0, 'CiaoCiao', 0),
('Marcus', 'Risula', 'MarcusRisula', '2018-01-03', 0, 0, NULL, 0, 'ababababa', 1);

--
-- Indici per le tabelle scaricate
--

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
  MODIFY `Id_Post` int(30) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_utente` FOREIGN KEY (`Utente`) REFERENCES `utenti` (`NomeUtente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
