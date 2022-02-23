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
	$affectedStream = 0;
	
	$continue = true;
	$truncate = false;
	
	$pattern = '/^#EXTINF:.*?\btvg-name="([^"]*)".*?\btvg-logo="([^"]*)".*?\bgroup-title="([^"]*)".*\R(https?:\/\/\S*)/m';

	$epgM3UChannelResult = mysqli_query($conn, "SELECT count(EntityId) as ChannelCount FROM epg_m3ufile") or die ("Error in query: ".mysqli_error()); 
	$m3uTableCheck = mysqli_fetch_assoc($epgM3UChannelResult);

	if ($m3uTableCheck['ChannelCount'] > 0){ 
		$error_message = "Channels and Streams are already ripped from the m3u file! <br/> Truncate the table if you wish to start again.";
		$continue = false;
		$truncate = true;
	}
	
	if($m3u_file == ''){
		$error_message = "No m3u file given in the settings tab!";
		$continue = false;
	}
	
	if($continue == true){
		$fileContent = file_get_contents($m3u_file);
		
		preg_match_all($pattern, $fileContent, $m3uMatch, PREG_SET_ORDER, 0);
		
		//m3uLine[1] = TV Channel 		-> |NL| NPO 1 HD
		//m3uLine[2] = TV Channel logo 	-> URL Logo
		//m3uLine[3] = TV Channel Group -> NL| NEDERLAND
		//m3uLine[4] = TV Channel URL	-> URL Stream
		
		foreach($m3uMatch as $m3uLine) {
			if (strpos($m3uLine[1], '#####') === FALSE) {
				$m3uChannelName = rtrim($m3uLine[1]);
				$m3uChannelName = mysqli_real_escape_string($conn, $m3uChannelName);
				$sql_insertFileValue = "INSERT INTO epg_m3ufile (m3uChannelName) VALUES ('" . $m3uChannelName . "')";
				$resultM3U = mysqli_query($conn, $sql_insertFileValue);
					
				$sql_insertStreamInfo = "INSERT INTO m3u_channels (tvg_name, tvg_logo, group_title, stream_url) VALUES ('" . $m3uChannelName . "', '" . $m3uLine[2] . "','" . $m3uLine[3] . "','" . $m3uLine[4] . "')";
				
				$resultStreamInfo = mysqli_query($conn, $sql_insertStreamInfo);
				
				if((!empty($resultM3U)) && (!empty($resultStreamInfo))){
					$affectedRowM3u ++;
					$affectedStream ++;
				}else{
					$error_message = mysqli_error($conn) . "\n";
				}	
			}
		}

		
		if(($affectedRowM3u != 0) && ($affectedStream != 0)){
			$part0 = "SUCCES";
			$part1 = $affectedStream . " streams and " . $affectedRowM3u . " channels found and inserted from file: " . $m3u_file;
			$message = $part0 . "<br/>" . $part1;
		}elseif(($affectedRowM3u = 0) && ($affectedStream != 0)){
			$message = "Something went wrong importing the channels from file: " . $m3u_file;
		}elseif(($affectedRowM3u != 0) && ($affectedStream = 0)){
			$message = "Something went wrong importing the streams from file: " . $m3u_file;
		}else{
			$message = "Something went totally wrong importing the streams and channels from file: " . $m3u_file;
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
		<div id="truncated" class="error-message">
			<?php echo nl2br($error_message); ?>
		</div>
		<?php } 
		if ($truncate == true){?>
		<button type="button" onclick="truncateTables()" class="btn btn-danger">Truncate epg_m3ufile & m3u_channels tables</button>
		<?php } ?>
		
	</div>
</section>
<script>
	function truncateTables()
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
			{
				document.getElementById("truncated").innerText = 'Tables are truncated, reload this page!';
			}
		};
		xmlhttp.open("GET", "epg_actions.php?action=truncate", true);
		xmlhttp.send();
	}
</script>