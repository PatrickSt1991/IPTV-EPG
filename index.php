<?php
	include '_config.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Creating your custom EPG / M3U files</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-lg-5">
                <a class="navbar-brand" href="#!">Custom EPG & M3U Generator</a>								
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse"><span class="bi bi-sliders" onclick="location.href='./epg_setting.php';"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
						<li class="nav-item"><a class="nav-link active" aria-current="page" href="epg_setting.php"><i class="bi bi-sliders"></i></a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Header-->
        <header class="py-5">
            <div class="container px-lg-5">
                <div class="p-4 p-lg-5 bg-light rounded-3 text-center">
                    <div class="m-4 m-lg-5">
                        <h1 class="display-5 fw-bold">Make your own M3U / EPG</h1>
                        <p class="fs-4"><u>In a few easy steps you can create you own M3U and EPG file!<br/></u>
						1. Grep the TV Channels from the m3u file.<br/>
						2. Fetch the TV Channels and programs from the base XML EPG.<br/>
						3. Generate the modified EPG XML file.<br/>
						4. Choose channels for your modified M3U file.</p><br/>
						<div class="row">
							<div class="col-lg-6">
								<a class="btn btn-primary btn-lg" id="download_m3u" onclick="startSpinningButton(this.id)" href="download_m3u.php">Download the M3U File!</a>
							</div>
							<div class="col-lg-6">
								<a class="btn btn-primary btn-lg" id="grep_m3u" onclick="startSpinningButton(this.id)" href="grep_m3u.php">Read the M3U File!</a>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Page Content-->
        <section class="pt-4">
            <div class="container px-lg-5">
                <!-- Page Features-->
                <div class="row gx-lg-5">
                    <div class="col-lg-6 col-xxl-4 mb-5">
                        <div class="card bg-light border-0 h-100">
							<a href="generate_m3u.php" onclick="startSpinning(this.id)" id="bi-card-text" style="text-decoration: none;">
								<div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0" style="color: #000;">
									<div class="feature bg-primary bg-gradient text-white rounded-3 mb-4 mt-n4"><i id="bi bi-card-text" class="bi bi-card-text"></i></div>
									<h2 class="fs-4 fw-bold">Choose channels for M3U file</h2>
									<p class="mb-0">Create your own M3U file without crap in it.</p>
								</div>
							</a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xxl-4 mb-5">
                        <div class="card bg-light border-0 h-100">
							<a href="fetch_epg.php" onclick="startSpinning(this.id)" id="bi-cloud-download" style="text-decoration: none;">
								<div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0" style="color: #000;">
									<div class="feature bg-primary bg-gradient text-white rounded-3 mb-4 mt-n4"><i id="bi bi-cloud-download" class="bi bi-cloud-download"></i></div>
									<h2 class="fs-4 fw-bold">Fetch data from public EPG</h2>
									<p class="mb-0">Fetch the channels and programmes.</p>
								</div>
							</a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xxl-4 mb-5">
                        <div class="card bg-light border-0 h-100">
							<a href="epg_conversion.php" onclick="startSpinning(this.id)" id="bi-pencil" style="text-decoration: none;">
								<div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0" style="color: #000;">
									<div class="feature bg-primary bg-gradient text-white rounded-3 mb-4 mt-n4"><i id="bi bi-pencil" class="bi bi-pencil"></i></div>
									<h2 class="fs-4 fw-bold">Fix mismatch in channel names</h2>
									<p class="mb-0">Example: Fox Sport -> ESPN Sports!</p>
								</div>
							</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container px-lg-5">
				<div class="row justify-content-center">
					<div class="col-4">
						<div class="p-4 p-lg-5 bg-light rounded-3 text-center">
							<div class="m-4 m-lg-5">
								<h1 class="display-5 fw-bold">Generate Custom EPG</h1>
								<p class="fs-4">Start generating custom EPG</p><br/>
								<a class="btn btn-primary btn-lg" id="create_epg" onclick="startSpinningButton(this.id)" href="generate_epg.php">Create Electronic Program Guide</a>
							</div>
						</div>
					</div>
					<div class="col-4">
						<div class="p-4 p-lg-5 bg-light rounded-3 text-center">
							<div class="m-4 m-lg-5">
								<h1 class="display-5 fw-bold">Generate Custom M3U</h1>
								<p class="fs-4">Start generating custom M3U.</p><br/>
								<a class="btn btn-primary btn-lg" id="create_m3u" onclick="startSpinningButton(this.id)" href="create_m3u.php">Create custom M3U file</a>
							</div>
						</div>
					</div>
			  </div>
            </div>
        </section>
		<script>
		function startSpinning(id)
		{
			var loadingName = document.getElementById("bi "+id)
			loadingName.className="fa fa-circle-o-notch fa-spin" 
		}
		
		function startSpinningButton(id)
		{
			var loadingName = document.getElementById(id)
			loadingName.innerText = 'Working it!';
		}
		</script>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Feel free to use and modify to your needs<br/><a href="https://github.com/PatrickSt1991/IPTV-EPG" style="color: #FFF; text-decoration: none;">https://github.com/PatrickSt1991/IPTV-EPG</a></p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
