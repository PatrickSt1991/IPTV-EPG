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

		if (filter_var($configSetting, FILTER_VALIDATE_URL)) { 
		  mysqli_query($conn, "UPDATE epg_config SET epg_value = 'download_iptv.m3u' WHERE epg_setting = 'm3u_file'");
		}
		
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
		
		$channelGroupTotal = 1;
		$channelGroupActive = 0;

		if($searchFilter == 'ALL'){
			$showResult = mysqli_query($conn, "SELECT * FROM m3u_channels");
		}else{
			$showResult = mysqli_query($conn, "SELECT * FROM m3u_channels WHERE group_title = '" . $searchFilter . "'");
			$countAllSql = mysqli_query($conn, "SELECT count(tvg_name) as CountAll FROM m3u_channels where group_title = '" . $searchFilter . "'");
			$countActiveSql = mysqli_query($conn, "SELECT count(tvg_name) as countActive FROM m3u_channels where group_title = '" . $searchFilter . "' AND active = 1");
			
			$countAllResult = mysqli_fetch_assoc($countAllSql);
			$countActiveResult = mysqli_fetch_assoc($countActiveSql);
			
			$channelGroupTotal = $countAllResult['CountAll'];
			$channelGroupActive = $countActiveResult['countActive'];
		}
		
		while($row = mysqli_fetch_array($showResult))
		{
			echo '<tr>';
			echo '<td>' . $row['EntityId'] . '</td>';
			echo '<td>' . $row['tvg_name'] . '</td>';
			echo '<td>' . $row['group_title'] . '</td>';
			if($row['active'] == 1)
			{
				echo '<td><div class="form-check">
							<input class="form-check-input" type="checkbox" checked value="' . $row['EntityId'] . '" id="flexCheckDefault">
					</div></td>';
			} else {
				echo '<td><div class="form-check">
							<input class="form-check-input" type="checkbox" value="' . $row['EntityId'] . '" id="flexCheckDefault">
					</div></td>';
			}
			echo '</tr>';
		}

		if($channelGroupTotal === $channelGroupActive)
		{
			echo "<script>$('#AllSelect').prop('checked', true);</script>";
		}

		$resultFilter = true;
	}

	if($_GET['action'] == 'activate'){
		$channelId = $_GET['channelId'];
		$channelStatus = $_GET['status'];
		
		$setActiveChannel = "UPDATE m3u_channels SET active = '" . $channelStatus . "' WHERE EntityId = '" . $channelId . "'";

		if(mysqli_query($conn, $setActiveChannel)){
			echo "Channel status changed";
		} else {
			echo "Channel status couldn't be changed";
		}
	}
	
	if($_GET['action'] == 'groupactivate'){
		$groupname = $_GET['groupId'];
		
		$setActiveChannel = "UPDATE m3u_channels SET active = 1 WHERE group_title = '" . $groupname . "'";

		if(mysqli_query($conn, $setActiveChannel)){
			echo "Group Channel status changed";
		} else {
			echo "Group Channel status couldn't be changed";
		}
	}
	
if($_GET['action'] == 'filterConversion'){

		$searchFilter = $_POST['groupSearch'];

		$showResult = mysqli_query($conn, "SELECT * FROM m3u_channels INNER JOIN epg_m3ufile ON m3uChannelName = tvg_name WHERE group_title = '" . $searchFilter . "'");

		while($row = mysqli_fetch_array($showResult))
		{
			echo '<tr>';
			echo '<td>' . $row['EntityId'] . '</td>';
			echo '<td>' . $row['m3uChannelName'] . '</td>';
			echo '<td><input type="text" id="guidChannelName+' . $row['EntityId'] . '" value="' . $row['guidChannelName'] . '"></td>';
			echo '<td><button id="updater' . $row['EntityId'] . '" type="button" class="button" onclick="updateChannel(document.getElementById(\'guidChannelName+' . $row['EntityId'] . '\').value,'.$row['EntityId'].')">Update</button></td>';
			echo '<td><button id="cleaner' . $row['EntityId'] . '" type="button" class="button" onclick="clearChannel('.$row['EntityId'].')">Clear</button></td>';
			echo '</tr>';
		}
		
		$resultFilter = true;
	}
	
}
?>