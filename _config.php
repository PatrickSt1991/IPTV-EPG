<?php

	$hostname = 'localhost';
	$database = '*********';
	$username = '*********';
	$password = '*********';

	$conn = mysqli_connect($hostname, $database, $username, $password);
	if (!$conn) {
		die('Could not connect: ' . mysqli_error());
	}

	$epgConfigSetting = mysqli_query($conn, "SELECT * FROM epg_config") or die ("Error in query: ".mysqli_error()); 
	$i = 0;

	while($epgConfigSettingResult = mysqli_fetch_array($epgConfigSetting))
	{
		$i++;
		$epg_setting[$i] = $epgConfigSettingResult['epg_setting'];
		$epg_value[$i] = $epgConfigSettingResult['epg_value'];
	}

	$m3u_file = $epg_setting[1];
	$customEpgFile = $epg_value[2];
	$epg_url = $epg_value[3];

?>