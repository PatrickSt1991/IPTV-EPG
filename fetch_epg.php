<link rel="stylesheet" href="epg_iptv.css">
<?php
$affectedRowChannel = 0;
$affectedRowProgram = 0;

$conn = mysqli_connect("localhost", "****", "*****", "*****");
if (!$conn) {
	die('Could not connect: ' . mysqli_error());
}

//Check if tables are empty if not, clean tables.
$epgProgramResult = mysqli_query($conn, "SELECT count(EntityId) as ProgramCount FROM epg_program") or die ("Error in query: ".mysqli_error()); 
$epgChannelResult = mysqli_query($conn, "SELECT count(EntityId) as ChannelCount FROM epg_channels") or die ("Error in query: ".mysqli_error()); 

if (mysqli_num_rows($epgProgramResult) > 0){ 
	mysqli_query($conn, "TRUNCATE TABLE epg_program");
}

if (mysqli_num_rows($epgChannelResult) > 0){
	mysqli_query($conn, "TRUNCATE TABLE epg_channels");
}


$epg_url = 'https://iptv-org.github.io/epg/guides/nl/delta.nl.epg.xml';
$epg = simplexml_load_file($epg_url) or die ("Error: Cannot create object");
	
foreach($epg->channel as $canal) {
	$channel_id = mysqli_real_escape_string($conn, $canal['id']);
	$channel_name = mysqli_real_escape_string($conn, (string)$canal->{'display-name'});
	$channel_icon = mysqli_real_escape_string($conn, (string)$canal->icon['src']);
	$channel_url = mysqli_real_escape_string($conn, (string)$canal->{'url'});

	$sql_insertChannel = "INSERT INTO epg_channels (channelId, channelName, channelIcon, channelUrl, channelName4K, channelNameNormal) VALUES ('" . $channel_id . "', '" . $channel_name . "', '" . $channel_icon . "', '" . $channel_url . "', '|NL|4K| " . $channel_name . " HD', '|NL| " . $channel_name . " HD')";

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

	$sql_insertProgram = "INSERT INTO epg_program (programStartTime, programEndTime, programChannel, programTitle, programTitleLang, programIcon) VALUES ('" . $program_startTime . "', '" . $program_stopTime . "', '" . $program_channel . "', '" . $program_title . "', '" . $program_title_lang . "', '" . $program_icon . "')";
	
	$resultProgram = mysqli_query($conn, $sql_insertProgram);
	
	if (!empty($resultProgram)) {
		$affectedRowProgram ++;
	}else{
		$error_message = mysqli_error($conn) . "\n";
	}
}

mysqli_close($conn);
?>
<h2>Insert XML EPG Data to Database</h2>
<?php

if ($affectedRowChannel > 0 && $affectedRowProgram > 0) {
	$part0 = "SUCCES";
	$part1 = $affectedRowChannel . " channels inserted";
	$part2 = $affectedRowProgram . " programs inserted";
	
	$message = $part0 . "<br/>" . $part1 . " " . $part2;
}elseif($affectedRowChannel > 0 && $affectedRowProgram == 0){
	$part0 = "FAILURE";
	$part1 = $affectedRowChannel . " channels inserted";
	$part2 = "No programs inserted";
	
	$message = $part0 . "<br/>" . $part1 . " " . $part2;
}elseif($affectedRowChannel == 0 && $affectedRowProgram > 0){
	$part0 = "FAILURE";
	$part1 = "No channels inserted";
	$part2 = $affectedRowProgram . " programs inserted";
	
	$message = $part0 . "<br/>" . $part1 . " " . $part2;
}else{
	$part0 = "FAILURE";
	$part1 = "No channels inserted";
	$part2 = "No programs inserted";

	$message = $part0 . "<br/>" . $part1 . " " . $part2;
}
 
?>
<div class="affected-row">
    <?php  echo $message; ?>
</div>
<?php if (! empty($error_message)) { ?>
<div class="error-message">
    <?php echo nl2br($error_message); ?>
</div>
<?php } ?>
