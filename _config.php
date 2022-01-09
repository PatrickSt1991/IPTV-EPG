<?php
$hostname = 'localhost';
$database = '********';
$username = '********';
$password = '********';

$conn = mysqli_connect($hostname, $database, $username, $password);
if (!$conn) {
	die('Could not connect: ' . mysqli_error());
}

$m3u_file = 'test3.m3u';
$customEpgFile = 'custom.nl.epg.xml';
$epg_url = 'https://iptv-org.github.io/epg/guides/nl/delta.nl.epg.xml';

?>