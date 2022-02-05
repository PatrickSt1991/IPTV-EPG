<?php 
include('_config.php');

if(isset($_GET['action'])){
	if($_GET['action'] == 'update'){
		if(isset($_GET['channelName']) && !empty($_GET['channelName']) && isset($_GET['channelId']) && !empty($_GET['channelId'])){
			$newChannel = $_GET['channelName'];
			$channelId = $_GET['channelId'];

			$channelUpdate = "UPDATE epg_m3ufile SET guidChannelName = '" . $newChannel . "' WHERE EntityId = '" . $channelId . "'";

			if (mysqli_query($conn, $channelUpdate)){
				$selectDefaultName = "SELECT m3uChannelName, guidChannelName FROM epg_m3ufile WHERE EntityId = '" . $channelId . "'";
				$selectDefaultNameSQL = mysqli_query($conn, $selectDefaultName);
				$defaultNameRow = mysqli_fetch_assoc($selectDefaultNameSQL);
				$channelName = $defaultNameRow['m3uChannelName'];
				$customName = $defaultNameRow['guidChannelName'];
				
				$selectGhostTable = "SELECT m3uChannelName, customName FROM epg_conversion WHERE m3uChannelName = '" . $channelName . "'";
				$selectGhostTableSQL = mysqli_query($conn, $selectGhostTable);
				$GhostTableResults = mysqli_num_rows($selectGhostTableSQL);

				if($GhostTableResults === 0){
					$GhostTable = "INSERT INTO epg_conversion (m3uChannelName, customName) VALUES ('" . $channelName . "', '" . $customName . "')";
					$GhostTableMessage = "Record inserted successfully to m3ufile and epg_conversion";
				}else{
					$GhostTable = "UPDATE epg_conversion SET customName = '" . $customName . "' WHERE m3uChannelName = '" . $channelName . "'";
					$GhostTableMessage = "Record updated successfully to m3ufile and epg_conversion";
				}
				if(mysqli_query($conn, $GhostTable)){
					echo $GhostTable;
				}else{
					echo "Error updating or inserting into ghost table: " . mysqli_error($conn);
				}
			}else{
				echo "Error updating record: " . mysqli_error($conn);
			}
			
			die;
		}
	}

	if($_GET['action'] == 'clear'){
		$channelId = $_GET['channelId'];
		
		$channelClear = "UPDATE epg_m3ufile SET guidChannelName = null WHERE EntityId = '" . $channelId . "'";
		
		if (mysqli_query($conn, $channelClear)){
				$selectDefaultName = "SELECT m3uChannelName, guidChannelName FROM epg_m3ufile WHERE EntityId = '" . $channelId . "'";
				$selectDefaultNameSQL = mysqli_query($conn, $selectDefaultName);
				$defaultNameRow = mysqli_fetch_assoc($selectDefaultNameSQL);
				$channelName = $defaultNameRow['m3uChannelName'];
				$customName = $defaultNameRow['guidChannelName'];
				
				$updateGhostTable = "UPDATE epg_conversion SET m3uChannelName = null, customName = null WHERE m3uChannelName = '" . $channelName . "' AND customName = '" . $customName . "'";
				
				if(mysqli_query($conn, $updateGhostTable)){
					echo "Record cleared successfully from m3ufile and epg_conversion";
				}else{
					echo "Error clearing ghost table: " . mysqli_error($conn);
				}
			
			echo "Record cleared successfully";
		}else{
			echo "Error clearing record: " . mysqli_error($conn);
		}
		
		die;
	}

	if ($_GET['action'] == 'setting'){
		$configSetting = $_GET['config'];
		$settingField = $_GET['setting'];
		
		$settingUpdate = "UPDATE epg_config SET epg_value = '" . $configSetting . "' WHERE epg_setting = '" . $settingField . "'";
		
		if (mysqli_query($conn, $settingUpdate)){
			echo "Settings updated";
		}else{
			echo "Error updating setting: " . mysqli_error($conn);
		}
		
		die;
	}

	if($_GET['action'] == 'filter'){
		$searchFilter = $_POST['groupSearch'];

		if($searchFilter == 'ALL'){
			$showResult = mysqli_query($conn, "SELECT * FROM m3u_channels");
		}else{
			$showResult = mysqli_query($conn, "SELECT * FROM m3u_channels WHERE group_title = '" . $searchFilter . "'");
		}
		
		while($row = mysqli_fetch_array($showResult))
		{
			echo '<tr>';
			echo '<td>' . $row['EntityId'] . '</td>';
			echo '<td>' . $row['tvg_name'] . '</td>';
			echo '<td>' . $row['group_title'] . '</td>';
			echo '<td><div class="form-check">
						<input class="form-check-input" type="checkbox" value="' . $row['EntityId'] . '" id="flexCheckDefault">
				</div></td>';
			echo '</tr>';
		}
		
		$resultFilter = true;
	}

	if($_GET['action'] == 'activate'){
		$channelId = $_GET['channelId'];
		$channelStatus = $_GET['status'];
		
		$setActiveChannel = "UPDATE m3u_channels SET active = '" . $channelStatus . "' WHERE EntityId = '" . $channelId . "'";
		echo($setActiveChannel . "<br/>");
		if(mysqli_query($conn, $setActiveChannel)){
			echo "Channel status changed";
		} else {
			echo "Channel status couldn't be changed";
		}
	}
}
?>