<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
<link href="css/styles.css" rel="stylesheet" />
<link rel="stylesheet" href="css/epg_iptv.css">
<?php
	include '_config.php';
	
	$continue = true;
	$deleteOld = false;


	$epgM3UChannelResult = mysqli_query($conn, "SELECT count(EntityId) as ChannelCount FROM epg_m3ufile") or die ("Error in query: ".mysqli_error()); 
	$m3uTableCheck = mysqli_fetch_assoc($epgM3UChannelResult);

	if ($m3uTableCheck['ChannelCount'] == 0){ 
		$error_message = "Channels are not yet ripped from the m3u file!";
		$continue = false;
	}

	if ($continue == true){

		if (file_exists($customM3uFile)) 
		{
			$deleteOld = true;
			unlink($customM3uFile);
		}
		
		$countCheck = mysqli_query($conn, "SELECT count(EntityId) as ActiveCount FROM m3u_channels") or die ("Error is query: ".mysqli_error());
		$countCheckResult = mysqli_fetch_assoc($countCheck);
		
		if($countCheckResult['ActiveCount'] > 0){
			$fetch_m3uChannels = "SELECT * FROM m3u_channels WHERE active = 1";
		}else{
			$fetch_m3uChannels = "SELET * FROM m3u_channels";
		}
		$m3uChannelsResults = mysqli_query($conn, $fetch_m3uChannels) or die("Error in query: ".mysqli_error());
		$resultSet = "#EXTM3U url-tvg=\"http://192.168.0.125/iptv/".$customEpgFile."\" \r\n";
		while($row = mysqli_fetch_array($m3uChannelsResults))
		{
			$resultSet .= "#EXTINF:-1 tvg-id=\"\" tvg-name=\"".$row['tvg_name']."\" tvg-logo=\"\" group-title=\"".$row['group_title']."\",".$row['tvg_name']." \r\n".$row['stream_url']."\r\n";
		}

		$newM3U_File = fopen($customM3uFile, "w+") or die("Unable to open file!");
		fwrite($newM3U_File,$resultSet);
		fclose($newM3U_File);
	
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
		Deleted the old M3u File.
		</div>
		<?php
		}
		?>
		<div class="affected-row">
			<?php  
			if ($continue == true){
				$downloadUrl = "http://".$_SERVER['SERVER_ADDR'].dirname($_SERVER['PHP_SELF'])."/".$customM3uFile."";
			echo "<a href='$downloadUrl'>Your custom m3u file is ready!</a><br/>";
			echo "URL: ".$downloadUrl;
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
