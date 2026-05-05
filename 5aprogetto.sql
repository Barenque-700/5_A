-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 05, 2026 alle 09:31
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
-- Struttura della tabella `commenti`
--

CREATE TABLE `commenti` (
  `Utente` varchar(40) NOT NULL,
  `Id_Post` int(30) NOT NULL,
  `Contenuto` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `follow`
--

CREATE TABLE `follow` (
  `Seguente` varchar(40) NOT NULL,
  `Seguito` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `follow`
--

INSERT INTO `follow` (`Seguente`, `Seguito`) VALUES
('Cagoia', 'Giandix67'),
('Giandix67', 'MarcusRisula'),
('MarcusRisula', 'Giandix67');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `post`
--

INSERT INTO `post` (`Id_Post`, `NumLike`, `Condivisioni`, `Allegato`, `Descrizione`, `Data_post`, `Utente`) VALUES
(1, 0, 0, NULL, 'aaaaaaaaaaa', '2026-05-05 08:19:50', 'Giandix67'),
(2, 0, 0, NULL, 'pussyklaat non so risolvere pls aiutate', '2026-05-05 08:57:47', 'Cagoia');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`Nome`, `Cognome`, `NomeUtente`, `DataNascita`, `Foto`, `Descrizione`, `NumPost`, `Password`, `Livello`) VALUES
('Yasser', 'Adday', 'Cagoia', '2026-04-30', 'Utente.png', '', 0, 'a', 1),
('Gianni', 'Giandagoberto', 'Giandix67', '2005-12-07', '136ed596ad43b458f5c057eaff8802ae8e5dfea42570033cd9526565576ead56.png', 'Il più grande utilizzatore di Valorant, non esco di casa e mi metto a giocare ad Overwatch. Ultima volta che ho toccato l\'erba: la mia nascita', 0, 'a', 0),
('Marcus', 'Risula', 'MarcusRisula', '2018-01-03', 'Utente.png', 'questo è un mega test', 0, 'ababababa', 1);

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
  MODIFY `Id_Post` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `commenti`
--
ALTER TABLE `commenti`
  ADD CONSTRAINT `FK_post` FOREIGN KEY (`Id_Post`) REFERENCES `post` (`Id_Post`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_utenti` FOREIGN KEY (`Utente`) REFERENCES `utenti` (`NomeUtente`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `follow`
--
ALTER TABLE `follow`
  ADD CONSTRAINT `FK_Seguente` FOREIGN KEY (`Seguente`) REFERENCES `utenti` (`NomeUtente`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Seguito` FOREIGN KEY (`Seguito`) REFERENCES `utenti` (`NomeUtente`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_utente` FOREIGN KEY (`Utente`) REFERENCES `utenti` (`NomeUtente`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
