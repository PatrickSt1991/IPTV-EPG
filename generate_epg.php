<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
<link href="css/styles.css" rel="stylesheet" />
<link rel="stylesheet" href="css/epg_iptv.css">
<?php

include '_config.php';

ignore_user_abort(true);
set_time_limit(0);

$messageProgram;
$messageChannel;
$deleteOld = false;
$channelCheck = false;
$programCheck = false;

$epgProgramArray = array();
$epgChannelArray = array();

$continue = true;

$epgM3UChannelResult = mysqli_query($conn, "SELECT count(EntityId) as ChannelCount FROM epg_m3ufile") or die ("Error in query: ".mysqli_error()); 
$m3uTableCheck = mysqli_fetch_assoc($epgM3UChannelResult);

if ($m3uTableCheck['ChannelCount'] == 0){ 
	$error_message = "Channels are not yet ripped from the m3u file!";
	$continue = false;
}

if ($continue == true){

	if (file_exists($customEpgFile)) 
	{
		$deleteOld = true;
		unlink($customEpgFile);
	}

	$fetch_programEpg = "SELECT epg_program.EntityId, programStartTime, programEndTime, programChannel, tvg_name, programTitle, programTitleLang, programIcon FROM epg_program INNER JOIN m3u_channels ON tvg_name LIKE CONCAT('%', programChannel, '%') WHERE active = 1";

	if($result_programEpg = mysqli_query($conn, $fetch_programEpg) or die ("error in query".mysqli_error())){
		while ($programEpg_row = mysqli_fetch_assoc($result_programEpg)){
			array_push($epgProgramArray, $programEpg_row);
			
			if(count($epgProgramArray)){
				$channelCheck = true;
				$messageChannel = 'SUCCES: Succesfully filled Custom EPG XML file with program data.';
			}
		}
		
		mysqli_free_result($result_programEpg);
	}

	$fetch_channelEpg = "SELECT epg_channels.EntityId, channelId, channelName, tvg_name, channelIcon, channelUrl FROM epg_channels INNER JOIN m3u_channels ON tvg_name LIKE CONCAT('%', channelName, '%') WHERE active = 1";


	if($result_channelEpg = mysqli_query($conn, $fetch_channelEpg)){
		while ($channelEpg_row = mysqli_fetch_assoc($result_channelEpg)){
			array_push($epgChannelArray, $channelEpg_row);
			
			if(count($epgChannelArray)){
				$programCheck = true;
				$messageProgram = 'SUCCES: Succesfully filled Custom EPG XML file with channel data.';
			}
		}
		
		mysqli_free_result($result_channelEpg);
	}


	if(($channelCheck == true) && ($programCheck == true)){
		createXMLfile($epgProgramArray, $epgChannelArray, $customEpgFile);
	}else{
		$messageChannel = 'FAIL: Failed to fill Custom EPG XML file with channel data.';
		$messageProgram = 'FAIL: Failed to fill Custom EPG XML file with program data.';
	}

	mysqli_close($conn);
}

function createXMLfile($epgProgramArray, $epgChannelArray, $customEpgFile){
	$filePath = $customEpgFile;
	$dom = new DOMDocument('1.0', 'utf-8');
	$root = $dom->createElement('tv');

	foreach($epgChannelArray as $channel){
		//TV Channels variables
		$channelId = $channel['tvg_name'];
		$channelDisplayName = $channel['tvg_name'];
		$channelId = $channel['channelName'];
		$channelDisplayName = $channel['channelName'];
		$channelIconSrc = $channel['channelIcon'];
		$channelUrl = $channel['channelUrl'];
		
		//Creating TV Channel XML
		$EpgChannel = $dom->createElement('channel');
		$EpgChannel->setAttribute('id', $channelId);
		
		$EpgDisplayName = $dom->createElement('display-name', htmlspecialchars($channelDisplayName));
		$EpgChannel->appendChild($EpgDisplayName);
		
		$EpgIconSrc = $dom->createElement('icon');
		$EpgIconSrc->setAttribute('src', $channelIconSrc);
		$EpgChannel->appendChild($EpgIconSrc);
		
		$EpgUrl = $dom->createElement('url', $channelUrl);
		$EpgChannel->appendChild($EpgUrl);
		
		$root->appendChild($EpgChannel);
	}
	
	foreach($epgProgramArray as $program){
		//TV Program variables
		$programChannel = $program['tvg_name'];
		//$programChannel = $program['programChannel'];
		$programTitle = $program['programTitle'];
		$programTitleLang = $program['programTitleLang'];
		$programStartTime = $program['programStartTime'];
		$programEndTime = $program['programEndTime'];
		$programIcon = $program['programIcon'];
		
		//TV Program XML
		$EpgProgram = $dom->createElement('programme');
		$EpgProgram->setAttribute('start', $programStartTime);
		$EpgProgram->setAttribute('stop', $programEndTime);
		$EpgProgram->setAttribute('channel', $programChannel);
		
		$EpgTitle = $dom->createElement('title', htmlspecialchars($programTitle));
		$EpgTitle->setAttribute('lang', $programTitleLang);
		$EpgProgram->appendChild($EpgTitle);
		
		$EpgIcon = $dom->createElement('icon');
		$EpgIcon->setAttribute('src', $programIcon);
		$EpgProgram->appendChild($EpgIcon);
		
		$root->appendChild($EpgProgram);
	}
	
	$dom->appendChild($root);
	$dom->save($filePath);
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
		<?php
		if($deleteOld == true){
		?>
		<div class="error-message">
		Delete the OLD XML file!
		</div>
		<?php
		}
		?>
		<div class="affected-row">
			<?php  
			if ($continue == true){
			echo $messageChannel ."<br/>". $messageProgram; 
			}?>
		</div>
		<?php 
		if (! empty($error_message)) { ?>
		<div class="error-message">
			<?php echo nl2br($error_message); ?>
		</div>
		<?php } ?>
	</div>
</section>
