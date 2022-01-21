<?php
	include '_config.php';
	
	$epgConfigSetting = mysqli_query($conn, "SELECT * FROM epg_config") or die ("Error in query: ".mysqli_error()); 
	$i = 0;
	
	while($epgConfigSettingResult = mysqli_fetch_array($epgConfigSetting))
	{
		$i++;
		$epg_setting[$i] = $epgConfigSettingResult['epg_setting'];
		$epg_value[$i] = $epgConfigSettingResult['epg_value'];
	}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Settings - Creating your custom EPG XML</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
		<link href="css/epg_iptv.css" rel="stylesheet">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-lg-5">
                <a class="navbar-brand" href="#!">Custom EPG XML Generator</a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.html"><i class="bi bi-sliders"></i></a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Header-->
        <header class="py-5">
            <div class="container px-lg-5">
                <div class="p-4 p-lg-5 bg-light rounded-3 text-center">
                    <div class="m-4 m-lg-5">
                        <h1 class="display-5 fw-bold">Settings</h1>
                        <p class="fs-4"><u>Fill in the required settings!<br/></u>
						1. Fill in the exact name of the m3u file.<br/>
						2. Fill in the url of the base xml url. Example: <a href="https://github.com/iptv-org/epg" style="color: #000;">IPTV-ORG</a><br/>
						3. Fill in the the name you want as output file.</p>
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
                            <div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0">
                                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-4 mt-n4"><i class="bi bi-search"></i></div>
                                <h2 class="fs-4 fw-bold">Your m3u filename:</h2>
								<input type="text" id="m3ufile" value="<?php echo $epg_value[1]; ?>"><br/><br/>
								<button id="m3ufile_update" type="button" class="button" onclick="updateChannel(document.getElementById(m3ufile).value">Update</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xxl-4 mb-5">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0">
                                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-4 mt-n4"><i class="bi bi-cloud-download"></i></div>
								<h2 class="fs-4 fw-bold">Base XML Url:</h2>
								<input type="text" class="w3-input" value="<?php echo $epg_value[3]; ?>"><br/><br/>
								<button id="m3ufile_update" type="button" class="button" onclick="updateChannel(document.getElementById(baseurlxml).value">Update</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xxl-4 mb-5">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0">
                                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-4 mt-n4"><i class="bi bi-check-circle"></i></div>
                                <h2 class="fs-4 fw-bold">XML Output filename:</h2>
								<input type="text" id="m3ufile" value="<?php echo $epg_value[2]; ?>"><br/><br/>
								<button id="m3ufile_update" type="button" class="button" onclick="updateChannel(document.getElementById(baseurlxml).value">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Feel free to use and modify to your needs<br/><a href="https://github.com/PatrickSt1991/IPTV-EPG" style="color: #FFF; text-decoration: none;">https://github.com/PatrickSt1991/IPTV-EPG</a></p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
