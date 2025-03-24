-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2025 at 09:18 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `praktiker`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `content`
--

CREATE TABLE `content` (
  `id` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `content`, `created_at`) VALUES
(1, '<h2>orzel</h2><p><u>fawffaw</u></p>', '2025-03-18 08:18:56'),
(2, '<h2>orzel</h2><p><u>fawf</u></p>', '2025-03-18 09:00:13'),
(3, '<h2>orzel</h2><p><u>fawf</u></p><p><em>NO A TY KONRAD RYJ</em></p>', '2025-03-20 19:12:48'),
(4, '<h2>orzel</h2><p><u>fawf</u></p><p><em>NO A TY KONRAD </em></p>', '2025-03-20 19:14:32'),
(5, '<h2>orzel</h2><p><u>fawf</u></p><p><em>NO A TY KONRAD fnkanfakfnafaf</em></p>', '2025-03-20 19:18:08'),
(6, '<h2>orzel</h2><p><u>fawf</u></p><p><em>NO A TY KONRAD fnkanfakfnafaf</em></p>', '2025-03-20 20:00:22'),
(7, '<h2>orzel</h2><p><u>fawf</u></p><p><em>NO A TY KONRAD fn</em><em style=\"color: rgb(0, 138, 0);\">kanfakfnafaf</em></p>', '2025-03-22 08:26:47'),
(8, '<p><strong class=\"ql-size-large\">Roman Maciejewski (1910–1998) to jeden z najwybitniejszych polskich kompozytorów XX wieku. Jego twórczość łączyła tradycję z nowoczesnością, a największym dziełem pozostaje monumentalne Requiem – Missa pro defunctis, uznawane za arcydzieło muzyki chóralnej. Był także mistrzem fortepianu, komponując nastrojowe mazurki, inspirowane muzyką Chopina i polskim folklorem. Życie Maciejewskiego naznaczone było emigracją – mieszkał w Szwecji, Anglii i USA, ale zawsze pozostawał wierny polskim korzeniom. Jego muzyka emanuje głęboką emocjonalnością i duchowością, czyniąc go jednym z najważniejszych, choć wciąż nieodkrytych w pełni kompozytorów polskiej muzyki.</strong></p>', '2025-03-22 10:54:22'),
(9, '<p><strong class=\"ql-size-large\">Roman Maciejewski (1910–1998) to jeden z najwybitniejszych polskich kompozytorów XX wieku. Jego twórczość łączyła tradycję z nowoczesnością, a największym dziełem pozostaje monumentalne Requiem – Missa pro defunctis, uznawane za arcydzieło muzyki chóralnej. Był także mistrzem fortepianu, komponując nastrojowe mazurki, inspirowane muzyką Chopina i polskim folklorem. Życie Maciejewskiego naznaczone było emigracją – mieszkał w Szwecji, Anglii i USA, ale zawsze pozostawał wierny polskim korzeniom. Jego muzyka emanuje głęboką emocjonalnością i duchowością, czyniąc go jednym z najważniejszych, choć wciąż nieodkrytych w pełni kompozytorów polskiej muzyki</strong></p>', '2025-03-24 19:18:35'),
(10, '<p><strong class=\"ql-size-large\">Roman Maciejewski (1910–1998) to jeden z najwybitniejszych polskich kompozytorów XX wieku. Jego twórczość łączyła tradycję z nowoczesnością, a największym dziełem pozostaje monumentalne Requiem – Missa pro defunctis, uznawane za arcydzieło muzyki chóralnej. Był także mistrzem fortepianu, komponując nastrojowe mazurki, inspirowane muzyką Chopina i polskim folklorem. Życie Maciejewskiego naznaczone było emigracją – mieszkał w Szwecji, Anglii i USA, ale zawsze pozostawał wierny polskim korzeniom. Jego muzyka emanuje głęboką emocjonalnością i duchowością, czyniąc go jednym z najważniejszych, choć wciąż nieodkrytych w pełni kompozytorów polskiej muzyk</strong></p>', '2025-03-24 19:20:10');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `songs`
--

CREATE TABLE `songs` (
  `id` int(11) NOT NULL,
  `performer` text NOT NULL,
  `title` text NOT NULL,
  `cover_link` text NOT NULL,
  `link` text NOT NULL,
  `downloads` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`id`, `performer`, `title`, `cover_link`, `link`, `downloads`) VALUES
(2, 'Roman Maciejwski', 'Tell Me Why', 'img/covers/malik.jpg', 'audio/Active Bass - Tell My Why  .mp3', 0),
(3, 'EKWADOR', 'Infinity', 'img/covers/album_cover.jpg', 'audio/Topmodelz - l Esperanza 2012 (Single Mix).mp3', 1),
(11, 'Depeche Mode', 'Enjoy The Silence', 'img/covers/nissan.png', 'audio/Depeche Mode - Enjoy The Silence (Official Video).mp3', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` text NOT NULL,
  `password` text NOT NULL,
  `downloading` enum('YES','NO','','') NOT NULL DEFAULT 'YES',
  `token` text NOT NULL,
  `isAdmin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `downloading`, `token`, `isAdmin`) VALUES
(12, 'a', '$2y$10$w.dV1KyOVNbemLXcI.hb1OvqURvpVCNyxR88hFxARz39l6.0zgcl.', 'NO', '', 0),
(13, 'admin', '$2y$10$cBlPy0o8J1MqSMnoReRpb.M7MdakvXQA0myrH7jcX/h6eeKSMbHay', 'YES', '', 1),
(15, 'norbi', '$2y$10$QuJ7.9OgYpwzjuwW4UF4uet/bdJt8g4qsALnuAxWS/0XSQQ5ljKgO', 'NO', '', 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
