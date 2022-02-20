-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Gegenereerd op: 20 feb 2022 om 08:40
-- Serverversie: 10.3.31-MariaDB-0+deb10u1
-- PHP-versie: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
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

--
-- Gegevens worden geëxporteerd voor tabel `epg_config`
--

INSERT INTO `epg_config` (`EntityId`, `epg_setting`, `epg_value`) VALUES
(1, 'm3u_file', 'download_iptv.m3u'),
(2, 'basexml', 'https://iptv-org.github.io/epg/guides/nl/delta.nl.epg.xml'),
(3, 'xmloutputfile', 'custom.nl.epg.xml'),
(4, 'Original_M3U_File', 'on'),
(5, 'EPG_Conversion_Table', 'on'),
(6, 'm3uoutputfile', 'custom_iptv.m3u'),
(7, 'url_inputfile', 'http://youriptvurl.com/get?username=123456789&password=987654321');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `epg_conversion`
--

CREATE TABLE `epg_conversion` (
  `EntityId` int(11) NOT NULL,
  `m3uChannelName` varchar(255) DEFAULT NULL,
  `customName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `m3u_channels`
--

CREATE TABLE `m3u_channels` (
  `EntityId` int(11) NOT NULL,
  `tvg_name` varchar(255) NOT NULL,
  `tvg_logo` varchar(255) NOT NULL,
  `group_title` varchar(255) NOT NULL,
  `stream_url` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0
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
-- Indexen voor tabel `epg_conversion`
--
ALTER TABLE `epg_conversion`
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
-- Indexen voor tabel `m3u_channels`
--
ALTER TABLE `m3u_channels`
  ADD PRIMARY KEY (`EntityId`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `epg_channels`
--
ALTER TABLE `epg_channels`
  MODIFY `EntityId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `epg_config`
--
ALTER TABLE `epg_config`
  MODIFY `EntityId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `epg_conversion`
--
ALTER TABLE `epg_conversion`
  MODIFY `EntityId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `epg_m3ufile`
--
ALTER TABLE `epg_m3ufile`
  MODIFY `EntityId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `epg_program`
--
ALTER TABLE `epg_program`
  MODIFY `EntityId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `m3u_channels`
--
ALTER TABLE `m3u_channels`
  MODIFY `EntityId` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
