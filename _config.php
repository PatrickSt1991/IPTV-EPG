<?php
	/******************************************************/
	/*						      */
	/* IF YOU ARE NOT USING LOGIN, PUT THIS IN COMMENT    */
	/* IF YOU DO USE IT, PLEASE CHANGE autenticate.php    */
	/*						      */
	/******************************************************/
	/**/ session_start();				    /**/
	/**/ 	 					    /**/
	/**/ if (!isset($_SESSION['loggedin'])) {	    /**/
	/**/ 	header('Location: login.php');	            /**/
	/**/ 	exit;					    /**/
	/**/ }						    /**/				
	/******************************************************/
	/*						      */
	/* IF YOU ARE NOT USING LOGIN, PUT THIS IN COMMENT    */
	/* IF YOU DO USE IT, PLEASE CHANGE autenticate.php    */
	/*						      */
	/******************************************************/	
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	
	ignore_user_abort(true);
	set_time_limit(0);

	$hostname = 'localhost';
	$database = '*********';
	$username = '*********';
	$password = '*********';


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
	$original_m3u_file = $epg_value[4];
	$epg_conversion_table = $epg_value[5];
	$customM3uFile = $epg_value[6];
	$m3u_url = $epg_value[7];	
	$user_login_setting = $epg_value[8];

?>
