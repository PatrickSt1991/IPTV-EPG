<?php 
include('_config.php');

if($_GET['action'] == 'update'){
	if(isset($_GET['channelName']) && !empty($_GET['channelName']) && isset($_GET['channelId']) && !empty($_GET['channelId'])){
		$newChannel = $_GET['channelName'];
		$channelId = $_GET['channelId'];

		$channelUpdate = "UPDATE epg_m3ufile SET guidChannelName = '" . $newChannel . "' WHERE EntityId = '" . $channelId . "'";

		if (mysqli_query($conn, $channelUpdate)){
			echo "Record updated successfully";
		}else{
			echo "Error updating record: " . mysqli_error($connect);
		}
		
		die;
	}
}

if($_GET['action'] == 'clear'){
	$channelId = $_GET['channelId'];
	
	$channelClear = "UPDATE epg_m3ufile SET guidChannelName = null WHERE EntityId = '" . $channelId . "'";
	
	if (mysqli_query($conn, $channelClear)){
		echo "Record cleared successfully";
	}else{
		echo "Error clearing record: " . mysqli_error($connect);
	}
	
	die;
}
?>