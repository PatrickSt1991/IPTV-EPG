-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Gegenereerd op: 21 jan 2022 om 13:20
-- Serverversie: 10.0.28-MariaDB-2+b1
-- PHP-versie: 7.3.33-1+0~20211119.91+debian10~1.gbp618351

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iptv`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `epg_channels`
--

CREATE TABLE `epg_channels` (
  `EntityId` int(11) NOT NULL,
  `channelId` varchar(255) NOT NULL,
  `channelName` varchar(255) NOT NULL,
  `channelIcon` varchar(255) NOT NULL,
  `channelUrl` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `epg_config`
--

CREATE TABLE `epg_config` (
  `EntityId` int(11) NOT NULL,
  `epg_setting` varchar(255) NOT NULL,
  `epg_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `epg_m3ufile`
--

CREATE TABLE `epg_m3ufile` (
  `EntityId` int(11) NOT NULL,
  `m3uChannelName` varchar(255) NOT NULL,
  `guidChannelName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `epg_program`
--

CREATE TABLE `epg_program` (
  `EntityId` int(11) NOT NULL,
  `programStartTime` varchar(255) NOT NULL,
  `programEndTime` varchar(255) NOT NULL,
  `programChannel` varchar(255) NOT NULL,
  `programTitle` varchar(255) NOT NULL,
  `programTitleLang` varchar(255) NOT NULL,
  `programIcon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `epg_channels`
--
ALTER TABLE `epg_channels`
  ADD PRIMARY KEY (`EntityId`);

--
-- Indexen voor tabel `epg_config`
--
ALTER TABLE `epg_config`
  ADD PRIMARY KEY (`EntityId`);

--
-- Indexen voor tabel `epg_m3ufile`
--
ALTER TABLE `epg_m3ufile`
  ADD PRIMARY KEY (`EntityId`);

--
-- Indexen voor tabel `epg_program`
--
ALTER TABLE `epg_program`
  ADD PRIMARY KEY (`EntityId`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `epg_channels`
--
ALTER TABLE `epg_channels`
  MODIFY `EntityId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=228;
--
-- AUTO_INCREMENT voor een tabel `epg_config`
--
ALTER TABLE `epg_config`
  MODIFY `EntityId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT voor een tabel `epg_m3ufile`
--
ALTER TABLE `epg_m3ufile`
  MODIFY `EntityId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=327;
--
-- AUTO_INCREMENT voor een tabel `epg_program`
--
ALTER TABLE `epg_program`
  MODIFY `EntityId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17574;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
