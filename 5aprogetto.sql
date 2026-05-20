-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 20, 2026 alle 09:46
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
  `Id_Commento` int(50) NOT NULL,
  `Utente` varchar(15) NOT NULL,
  `Id_Post` int(50) NOT NULL,
  `Contenuto` varchar(500) NOT NULL,
  `Data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `commenti`
--

INSERT INTO `commenti` (`Id_Commento`, `Utente`, `Id_Post`, `Contenuto`, `Data`) VALUES
(1, 'Giandix67', 14, 'testiamolo insieme', '2026-05-09 22:23:48'),
(11, 'Giandix67', 20, 'aaaa', '2026-05-16 00:04:51'),
(19, 'Giandix67', 20, 'adesso @MarcusRisula #testing', '2026-05-20 09:13:07');

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
('Giandix67', 'MarcusRisula');

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
('Giandix67', 14),
('Giandix67', 20),
('MarcusRisula', 3),
('MarcusRisula', 10),
('MarcusRisula', 14);

-- --------------------------------------------------------

--
-- Struttura della tabella `notifiche`
--

CREATE TABLE `notifiche` (
  `Id_Notifica` int(100) NOT NULL,
  `Letto` int(1) NOT NULL DEFAULT 0,
  `Mittente` varchar(30) NOT NULL,
  `Destinatario` varchar(30) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `Id_Post` int(50) DEFAULT NULL,
  `Id_Commento` int(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `notifiche`
--

INSERT INTO `notifiche` (`Id_Notifica`, `Letto`, `Mittente`, `Destinatario`, `tipo`, `Id_Post`, `Id_Commento`) VALUES
(9, 1, 'Giandix67', 'MarcusRisula', 'follow', NULL, NULL),
(10, 1, 'Giandix67', 'Giandix67', 'commento', 20, 11),
(11, 1, 'Giandix67', 'Giandix67', 'like', 20, NULL),
(13, 1, 'Giandix67', 'MarcusRisula', 'menzione', 23, NULL),
(22, 1, 'Giandix67', 'Giandix67', 'commento', 20, 19),
(27, 0, 'MarcusRisula', 'Giandix67', 'like', 14, NULL);

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
(10, 1, 0, '3e4799ed542a619bcb1621de73c1a3c42726274647e199c5a5ff26030770ef14.png', 'testa foto', '2026-05-07 22:06:56', 'Giandix67'),
(11, 0, 0, NULL, 'vediamo se va #testing', '2026-05-09 16:43:51', 'Giandix67'),
(12, 0, 0, NULL, 'questo serve per gli utenti consigliati #testing', '2026-05-09 17:27:53', 'MarcusRisula'),
(13, 0, 0, '3e4799ed542a619bcb1621de73c1a3c42726274647e199c5a5ff26030770ef14.png', 'aaaaaaa', '2026-05-09 17:43:15', 'MarcusRisula'),
(14, 2, 0, NULL, 'con più hashtag funziona? #testing #funzionerà', '2026-05-09 18:05:23', 'Giandix67'),
(15, 0, 0, NULL, 'testiamo hashtag e tag #testing @MarcusRisula', '2026-05-09 18:25:49', 'Giandix67'),
(19, 0, 0, NULL, 'ciao @MarcusRisula', '2026-05-15 22:16:26', 'Giandix67'),
(20, 1, 0, NULL, 'ciao @MarcusRisula', '2026-05-15 22:24:52', 'Giandix67'),
(21, 0, 0, NULL, 'oi @MarcuRisula', '2026-05-16 00:05:14', 'Giandix67'),
(22, 0, 0, NULL, 'oi @MarcuRisula', '2026-05-16 00:19:31', 'Giandix67'),
(23, 0, 0, NULL, 'come stai @MarcusRisula', '2026-05-16 00:20:15', 'Giandix67'),
(24, 0, 0, NULL, 'test @boh', '2026-05-16 00:20:31', 'Giandix67');

-- --------------------------------------------------------

--
-- Struttura della tabella `tag`
--

CREATE TABLE `tag` (
  `Id_Tag` int(30) NOT NULL,
  `NomeTag` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tag`
--

INSERT INTO `tag` (`Id_Tag`, `NomeTag`) VALUES
(6, 'funzionerà'),
(1, 'Testing');

-- --------------------------------------------------------

--
-- Struttura della tabella `tagpost`
--

CREATE TABLE `tagpost` (
  `Id_Post` int(30) NOT NULL,
  `Id_Tag` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tagpost`
--

INSERT INTO `tagpost` (`Id_Post`, `Id_Tag`) VALUES
(11, 1),
(12, 1),
(14, 1),
(14, 6),
(15, 1);

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
  `Password` varchar(100) NOT NULL,
  `Livello` int(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`Nome`, `Cognome`, `NomeUtente`, `DataNascita`, `Foto`, `Descrizione`, `Password`, `Livello`) VALUES
('Gianni', 'Giandagoberto', 'Giandix67', '2005-12-07', 'Utente.png', 'Il più grande utilizzatore di Valorant, non esco di casa e mi metto a giocare ad Overwatch. Ultima volta che ho toccato l\'erba: la mia nascita', 'a', 0),
('Marcus', 'Risula', 'MarcusRisula', '2007-01-03', 'Utente.png', 'questo è un mega test', 'aaa', 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `commenti`
--
ALTER TABLE `commenti`
  ADD PRIMARY KEY (`Id_Commento`),
  ADD KEY `FK_utenteCommento` (`Utente`),
  ADD KEY `FK_postCommento` (`Id_Post`);

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
-- Indici per le tabelle `notifiche`
--
ALTER TABLE `notifiche`
  ADD PRIMARY KEY (`Id_Notifica`),
  ADD KEY `FK_MittenteUtente` (`Mittente`),
  ADD KEY `FK_DestinatarioUtente` (`Destinatario`),
  ADD KEY `FK_notifichePost` (`Id_Post`),
  ADD KEY `FK_notificheCommenti` (`Id_Commento`);

--
-- Indici per le tabelle `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`Id_Post`),
  ADD KEY `FK_utente` (`Utente`);

--
-- Indici per le tabelle `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`Id_Tag`),
  ADD UNIQUE KEY `NomeTag` (`NomeTag`);

--
-- Indici per le tabelle `tagpost`
--
ALTER TABLE `tagpost`
  ADD PRIMARY KEY (`Id_Post`,`Id_Tag`),
  ADD KEY `FK_Tag` (`Id_Tag`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`NomeUtente`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `commenti`
--
ALTER TABLE `commenti`
  MODIFY `Id_Commento` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT per la tabella `notifiche`
--
ALTER TABLE `notifiche`
  MODIFY `Id_Notifica` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT per la tabella `post`
--
ALTER TABLE `post`
  MODIFY `Id_Post` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT per la tabella `tag`
--
ALTER TABLE `tag`
  MODIFY `Id_Tag` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `commenti`
--
ALTER TABLE `commenti`
  ADD CONSTRAINT `FK_postCommento` FOREIGN KEY (`Id_Post`) REFERENCES `post` (`Id_Post`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_utenteCommento` FOREIGN KEY (`Utente`) REFERENCES `utenti` (`NomeUtente`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Limiti per la tabella `notifiche`
--
ALTER TABLE `notifiche`
  ADD CONSTRAINT `FK_DestinatarioUtente` FOREIGN KEY (`Destinatario`) REFERENCES `utenti` (`NomeUtente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MittenteUtente` FOREIGN KEY (`Mittente`) REFERENCES `utenti` (`NomeUtente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_notificheCommenti` FOREIGN KEY (`Id_Commento`) REFERENCES `commenti` (`Id_Commento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_notifichePost` FOREIGN KEY (`Id_Post`) REFERENCES `post` (`Id_Post`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_utente` FOREIGN KEY (`Utente`) REFERENCES `utenti` (`NomeUtente`) ON DELETE CASCADE;

--
-- Limiti per la tabella `tagpost`
--
ALTER TABLE `tagpost`
  ADD CONSTRAINT `FK_Tag` FOREIGN KEY (`Id_Tag`) REFERENCES `tag` (`Id_Tag`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_postTag` FOREIGN KEY (`Id_Post`) REFERENCES `post` (`Id_Post`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
