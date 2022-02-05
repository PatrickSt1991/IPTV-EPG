<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	$hostname = 'localhost';
	$database = 'iptv';
	$username = '********';
	$password = '********';

	$conn = mysqli_connect($hostname, $username, $password, $database);
	if (!$conn) {
		die('Could not connect: ' . mysqli_affected_rows_error());
	}

	$epgConfigSetting = mysqli_query($conn, "SELECT * FROM epg_config") or die ("Error in query: ".mysqli_error()); 
	$i = 0;
	
	while($epgConfigSettingResult = mysqli_fetch_array($epgConfigSetting))
	{
		$i++;
		$epg_setting[$i] = $epgConfigSettingResult['epg_setting'];
		$epg_value[$i] = $epgConfigSettingResult['epg_value'];
	}

	$m3u_file = $epg_value[1];
	$epg_url = $epg_value[2];
	$customEpgFile = $epg_value[3];

?>