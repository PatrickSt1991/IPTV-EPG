<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
<link href="css/styles.css" rel="stylesheet" />
<link href="css/epg_iptv.css" rel="stylesheet" >
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<div class="container px-lg-5">
		<a class="navbar-brand" href="#!">Custom EPG XML Generator - Grep M3U file</a>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
				<li class="nav-item"><a class="nav-link active" aria-current="page" href="index.html"><i class="bi bi-sliders"></i></a></li>
			</ul>
		</div>
	</div>
</nav>
<br/>
<section class="pt-4">
	<div class="container px-lg-5">
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
				&& ((strpos($line, '|NL|') == true) || (strpos($line, 'group-title="ADULT"') == true))) {
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
		if($affectedRowM3u != 0){
			$part0 = "SUCCES";
			$part1 = $affectedRowM3u . " channels found and inserted from file: " . $m3u_file;
			
			$message = $part0 . "<br/>" . $part1;
		} else {
			$message = "Something went wrong importing the channels from file: " . $m3u_file;
		}
	}
if($continue == true){
?>
		<div class="affected-row">
			<?php  echo $message; ?>
		</div>
		<?php 
		}
		if (!empty($error_message)) { ?>
		<div class="error-message">
			<?php echo nl2br($error_message); ?>
		</div>
		<?php } ?>
	</div>
</section>
