<link rel="stylesheet" href="epg_iptv.css">
<?php
	include '_config.php';
	
	$affectedRowM3u = 0;
	$continue = true;
	
	$epgM3UChannelResult = mysqli_query($conn, "SELECT count(EntityId) as ChannelCount FROM epg_m3ufile") or die ("Error in query: ".mysqli_error()); 
	$m3uTableCheck = mysqli_fetch_assoc($epgM3UChannelResult);

	if ($m3uTableCheck['ChannelCount'] > 0){ 
		$error_message = "Channels are already ripped from the m3u file!";
		$continue = false;
	}
	
	if($continue == true){
		$fileContent = file($m3u_file);
		
		foreach($fileContent as $line) {
			if((strpos($line, 'group-title') == true) && (strpos($line, 'tvg-name="#####') == false) 
				&& (strpos($line, '|NL|') == true)) {
				$m3uChannelName = substr($line, strrpos($line, ',') + 1);
				$m3uChannelName = rtrim($m3uChannelName);
				$m3uChannelName = mysqli_real_escape_string($conn, $m3uChannelName);
				$sql_insertFileValue = "INSERT INTO epg_m3ufile (m3uChannelName) VALUES ('" . $m3uChannelName . "')";
				$resultM3U = mysqli_query($conn, $sql_insertFileValue);
				
				if (!empty($resultM3U)) {
					$affectedRowM3u ++;
				}else{
					$error_message = mysqli_error($conn) . "\n";
				}
				
			}
		}
		$part0 = "SUCCES";
		$part1 = $affectedRowM3u . " channels found and inserted";
		
		$message = $part0 . "<br/>" . $part1;
	}
if($continue == true){
?>
<div class="affected-row">
    <?php  echo $message; ?>
</div>
<?php 
}
if (! empty($error_message)) { ?>
<div class="error-message">
    <?php echo nl2br($error_message); ?>
</div>
<?php } ?>