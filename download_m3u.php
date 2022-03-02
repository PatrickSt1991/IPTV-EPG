<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
<link href="css/styles.css" rel="stylesheet" />
<link href="css/epg_iptv.css" rel="stylesheet" >
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
		include '_config.php';
		
		if (file_exists($customM3uFile))
		{
			$deleteOld = true;
			unlink($customM3uFile);
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $m3u_url);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		
		if (substr($info["http_code"], 0, 2) != 20) {
			die("Could not connect to server. Check username and password");
			$error_message = "Could not connect to server. Check username and password";
		}

		$fp = fopen($m3u_file, 'w');
		fwrite($fp, $response);
		fclose($fp);
		$message = "Downloaded the M3U file!";
		
	?>
		<div class="affected-row">
			<?php  echo $message; ?>
		</div>
		<?php 
		if (!empty($error_message)) { ?>
		<div class="error-message">
			<?php echo nl2br($error_message); ?>
		</div>
		<?php } ?>
	</div>
</section>
