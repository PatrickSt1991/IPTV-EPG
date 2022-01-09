<link rel="stylesheet" href="epg_iptv.css">
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

	$fetch_programEpg = "SELECT * FROM epg_program";

	if($result_programEpg = mysqli_query($conn, $fetch_programEpg)){
		while ($programEpg_row = mysqli_fetch_assoc($result_programEpg)){
			array_push($epgProgramArray, $programEpg_row);
			
			if(count($epgProgramArray)){
				$channelCheck = true;
				$messageChannel = 'SUCCES: Succesfully filled Custom EPG XML file with program data.';
			}
		}
		
		mysqli_free_result($result_programEpg);
	}

	$fetch_channelEpg = "SELECT * FROM epg_channels";

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
		$channelId = $channel['channelId'];
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
		$programChannel = $program['programChannel'];
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
	<br/><br/>
	Enjoy the Power of Patrick EPG!
</div>
<?php 
if (! empty($error_message)) { ?>
<div class="error-message">
    <?php echo nl2br($error_message); ?>
</div>
<?php } ?>