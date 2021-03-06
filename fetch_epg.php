<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
<link href="css/styles.css" rel="stylesheet" />
<link rel="stylesheet" href="css/epg_iptv.css">
<?php
include '_config.php';

$affectedRowChannel = 0;
$affectedRowProgram = 0;
$continue = true;

$epgM3UChannelResult = mysqli_query($conn, "SELECT count(EntityId) as ChannelCount FROM epg_m3ufile") or die ("Error in query: ".mysqli_error()); 
$m3uTableCheck = mysqli_fetch_assoc($epgM3UChannelResult);

if ($m3uTableCheck['ChannelCount'] == 0){ 
	$error_message = "Channels are not yet ripped from the m3u file!";
	$continue = false;
}

if ($continue == true){
	$epgProgramResult = mysqli_query($conn, "SELECT count(EntityId) as ProgramCount FROM epg_program") or die ("Error in query: ".mysqli_error()); 
	$epgChannelResult = mysqli_query($conn, "SELECT count(EntityId) as ChannelCount FROM epg_channels") or die ("Error in query: ".mysqli_error()); 

	if (mysqli_num_rows($epgProgramResult) > 0){ 
		mysqli_query($conn, "TRUNCATE TABLE epg_program");
	}

	if (mysqli_num_rows($epgChannelResult) > 0){
		mysqli_query($conn, "TRUNCATE TABLE epg_channels");
	}

	$updateCheck = mysqli_query($conn, "SELECT COUNT(epg_conversion.EntityId) AS UpdateNumber FROM epg_m3ufile INNER JOIN epg_conversion ON epg_m3ufile.m3uChannelName = epg_conversion.m3uChannelName WHERE epg_m3ufile.guidChannelName IS NULL");
	$updateCheckResult = mysqli_fetch_assoc($updateCheck);
	
	if($updateCheckResult['UpdateNumber'] >= 1){
		mysqli_query($conn, "UPDATE epg_m3ufile INNER JOIN epg_conversion ON epg_m3ufile.m3uChannelName = epg_conversion.m3uChannelName SET epg_m3ufile.guidChannelName = epg_conversion.customName");
	}
	

	$epg = simplexml_load_file($epg_url) or die ("Error: Cannot create object");
		
	foreach($epg->channel as $canal) {
		$channel_id = mysqli_real_escape_string($conn, $canal['id']);
		$channel_name = mysqli_real_escape_string($conn, (string)$canal->{'display-name'});
		$channel_icon = mysqli_real_escape_string($conn, (string)$canal->icon['src']);
		$channel_url = mysqli_real_escape_string($conn, (string)$canal->{'url'});

		$sqlGhostChannel = "SELECT customName FROM epg_conversion WHERE m3uChannelName = '" . $channel_name . "'";

		$sqlGhostChannel_result = mysqli_query($conn, $sqlGhostChannel);
		$GhostCustomName = mysqli_fetch_assoc($sqlGhostChannel_result);
		if($GhostCustomName == false){
			if($epg_conversion_table === 'on'){
				$sqlUpdateChannel  = "SELECT epg_m3ufile.EntityId, epg_m3ufile.m3uChannelName, epg_m3ufile.guidChannelName FROM epg_m3ufile 
											INNER JOIN m3u_channels ON m3uChannelName = tvg_name
											WHERE m3uChannelName NOT LIKE '%S0%E0%'
											AND active = 1
											AND m3uChannelName LIKE '%" . $channel_name ."%' 
											OR guidChannelName LIKE '%" . $channel_name ."%' 
											ORDER BY EntityId ASC LIMIT 1";
			}else{
				$sqlUpdateChannel = "SELECT epg_m3ufile.EntityId, epg_m3ufile.m3uChannelName, epg_m3ufile.guidChannelName FROM epg_m3ufile 
														INNER JOIN m3u_channels ON m3uChannelName = tvg_name
														WHERE m3uChannelName NOT LIKE '%S0%E0%'
														AND m3uChannelName LIKE '%" . $channel_name ."%' 
														OR guidChannelName LIKE '%" . $channel_name ."%' 
														ORDER BY EntityId ASC LIMIT 1";
			}
			
			$sqlUpdateChannel_result = mysqli_query($conn, $sqlUpdateChannel);
			$channel_name_m3u = mysqli_fetch_array($sqlUpdateChannel_result);
			if(isset($channel_name_m3u['m3uChannelName'])){
				$channel_name_m3u = $channel_name_m3u['m3uChannelName'];
				if(($channel_name_m3u != '') || ($channel_name_m3u != null)){
					$channel_name = $channel_name_m3u;
				}
			}
		}else{
			$channel_name = $GhostCustomName['m3uChannelName'];
		}

		$sql_insertChannel = "INSERT INTO epg_channels (channelId, channelName, channelIcon, channelUrl) VALUES ('" . $channel_id . "', '" . $channel_name . "', '" . $channel_icon . "', '" . $channel_url . "')";

		$resultChannel = mysqli_query($conn, $sql_insertChannel);
		
		if (!empty($resultChannel)) {
			$affectedRowChannel ++;
		}else{
			$error_message = mysqli_error($conn) . "\n";
		}
	}
		
	foreach($epg->programme as $program) {
		$program_startTime = mysqli_real_escape_string($conn, $program['start']);
		$program_stopTime = mysqli_real_escape_string($conn, $program['stop']);
		$program_channel = mysqli_real_escape_string($conn, $program['channel']);
		$program_title = mysqli_real_escape_string($conn, (string)$program->title);
		$program_title_lang = mysqli_real_escape_string($conn, (string)$program->title['lang']);
		$program_icon = mysqli_real_escape_string($conn, (string)$program->icon['src']);

		$sqlUpdateProgram = "SELECT channelName FROM epg_channels WHERE channelId like '%" . $program_channel . "%'";
		$sqlUpdateProgram_result = mysqli_query($conn, $sqlUpdateProgram);
		$program_name_m3u = mysqli_fetch_array($sqlUpdateProgram_result);
		$program_name_m3u = $program_name_m3u['channelName'];
		
		if(($program_name_m3u != '') || ($program_name_m3u != null)){
			$program_channel = $program_name_m3u;
		}

		$sql_insertProgram = "INSERT INTO epg_program (programStartTime, programEndTime, programChannel, programTitle, programTitleLang, programIcon) VALUES ('" . $program_startTime . "', '" . $program_stopTime . "', '" . $program_channel . "', '" . $program_title . "', '" . $program_title_lang . "', '" . $program_icon . "')";
		$resultProgram = mysqli_query($conn, $sql_insertProgram);
		
		if (!empty($resultProgram)) {
			$affectedRowProgram ++;
		}else{
			$error_message = mysqli_error($conn) . "\n";
		}
	}
	
	mysqli_close($conn);
}

if ($affectedRowChannel > 0 && $affectedRowProgram > 0) {
	$part0 = "SUCCES";
	$part1 = $affectedRowChannel . " channels inserted";
	$part2 = $affectedRowProgram . " programs inserted";
	
	$message = $part0 . "<br/>" . $part1 . " <br/> " . $part2;
}elseif($affectedRowChannel > 0 && $affectedRowProgram == 0){
	$part0 = "FAILURE";
	$part1 = $affectedRowChannel . " channels inserted";
	$part2 = "No programs inserted";
	
	$message = $part0 . "<br/>" . $part1 . " <br/> " . $part2;
}elseif($affectedRowChannel == 0 && $affectedRowProgram > 0){
	$part0 = "FAILURE";
	$part1 = "No channels inserted";
	$part2 = $affectedRowProgram . " programs inserted";
	
	$message = $part0 . "<br/>" . $part1 . " <br/> " . $part2;
}else{
	$part0 = "FAILURE";
	$part1 = "No channels inserted";
	$part2 = "No programs inserted";

	$message = $part0 . "<br/>" . $part1 . " <br/> " . $part2;
}
 
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<div class="container px-lg-5">
		<a class="navbar-brand" href="#!">Custom EPG & M3U Generator</a>								
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse"><span class="bi bi-sliders" onclick="location.href='./index.php';"></span></button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
				<li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php"><i class="bi bi-sliders"></i></a></li>
			</ul>
		</div>
	</div>
</nav>
<br/>
<section class="pt-4">
	<div class="container px-lg-5">
		<div class="affected-row">
			<?php  echo $message; ?>
			<br/>
		</div>
		<?php if (!empty($error_message)) { ?>
		<div class="error-message">
			<?php echo nl2br($error_message); ?>
		</div>
		<?php } ?>
	</div>
</section>
<div class="modal"><!-- Place at bottom of page --></div>