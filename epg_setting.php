<?php
	include '_config.php';
	
	$switchStatus_m3u = '';
	$switchStatus_epg = '';
	
	$epgConfigSetting = mysqli_query($conn, "SELECT * FROM epg_config") or die ("Error in query: ".mysqli_error()); 
	$i = 0;
	
	while($epgConfigSettingResult = mysqli_fetch_array($epgConfigSetting))
	{
		$i++;
		$epg_setting[$i] = $epgConfigSettingResult['epg_setting'];
		$epg_value[$i] = $epgConfigSettingResult['epg_value'];
	}
	if($epg_value[4] == 'on'){
		$switchStatus_m3u = 'checked';
	}
	if($epg_value[5] == 'on'){
		$switchStatus_epg = 'checked';
	}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Settings - Creating your custom EPG XML</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php"><i class="bi bi-sliders"></i></a></li>
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
                                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-4 mt-n4"><i class="bi bi-file"></i></div>
                                <h2 class="fs-4 fw-bold">Download M3U via URL:</h2>
								<input type="text" id="m3uurlfile" value="<?php echo $epg_value[7]; ?>"><br/><br/>
								<button id="configUpdate_m3uurl_file" type="button" class="button" onclick="updateConfig(document.getElementById('m3uurlfile').value, 'm3uurl_file')">Update</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xxl-4 mb-5">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0">
                                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-4 mt-n4"><i class="bi bi-search"></i></div>
                                <h2 class="fs-4 fw-bold">Local M3U filename:</h2>
								<input type="text" id="m3ufile" value="<?php echo $epg_value[1]; ?>"><br/><br/>
								<button id="configUpdate_m3u_file" type="button" class="button" onclick="updateConfig(document.getElementById('m3ufile').value, 'm3u_file')">Update</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xxl-4 mb-5">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0">
                                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-4 mt-n4"><i class="bi bi-check-circle"></i></div>
                                <h2 class="fs-4 fw-bold">M3U Output filename:</h2>
								<input type="text" id="m3uoutput" value="<?php echo $epg_value[6]; ?>"><br/><br/>
								<button id="configUpdate_m3uoutputfile" type="button" class="button" onclick="updateConfig(document.getElementById('m3uoutput').value, 'm3uoutputfile')">Update</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xxl-4 mb-5">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0">
                                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-4 mt-n4"><i class="bi bi-book"></i></div>
                                <h2 class="fs-4 fw-bold">EPG Conversion Switch:</h2>
								<div class="form-check form-switch">	
								  <input class="form-check-input" id="epgfileswitch" type="checkbox" <?php echo($switchStatus_epg); ?>><br/>
								  <label class="form-check-label" >Set to active for using EPG conversion table</label>
								</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xxl-4 mb-5" hidden>
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0">
                                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-4 mt-n4"><i class="bi bi-tv"></i></div>
                                <h2 class="fs-4 fw-bold">M3U Orginal/Custom Switch:</h2>
								<div class="form-check form-switch">	
								  <input class="form-check-input" id="m3ufileswitch" type="checkbox" <?php echo($switchStatus_m3u); ?>><br/>
								  <label class="form-check-label" >Set to active for using custom m3u file</label>
								</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xxl-4 mb-5">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0">
                                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-4 mt-n4"><i class="bi bi-cloud-download"></i></div>
								<h2 class="fs-4 fw-bold">Custom base XML Url:</h2>
								<input type="text" id="baseurlxml" value="<?php echo $epg_value[2]; ?>"><br/><br/>
								<button id="configUpdate_basexml" type="button" class="button" onclick="updateConfig(document.getElementById('baseurlxml').value, 'basexml')">Update</button>
                            </div>
                        </div>
                    </div> 	
                    <div class="col-lg-6 col-xxl-4 mb-5">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0">
                                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-4 mt-n4"><i class="bi bi-check-circle"></i></div>
                                <h2 class="fs-4 fw-bold">XML Output filename:</h2>
								<input type="text" id="xmloutput" value="<?php echo $epg_value[3]; ?>"><br/><br/>
								<button id="configUpdate_xmloutputfile" type="button" class="button" onclick="updateConfig(document.getElementById('xmloutput').value, 'xmloutputfile')">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
		<script>
			//Switch for EPG Custom On / Off
			$('#epgfileswitch').on('click', function() {
				var switcherStatus = this.checked ? 'on' : 'off';
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {};
					xmlhttp.open("GET", "epg_actions.php?config=" +switcherStatus+"&setting=EPG_Conversion_Table&action=setting", true);
					xmlhttp.send();
			});

			//Switch for M3U Custom file On / Off
			$('#m3ufileswitch').on('click', function() {
				var switcherStatus = this.checked ? 'on' : 'off';
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {};
					xmlhttp.open("GET", "epg_actions.php?config=" +switcherStatus+"&setting=Original_M3U_File&action=setting", true);
					xmlhttp.send();
			});

			function updateConfig(configSetting, settingField)
			{
				if(configSetting != '' && settingField != ''){
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
						{
							document.getElementById("configUpdate_"+settingField).innerText = 'Changed!';
							if(settingField == 'm3uurl_file')
							{
								document.getElementById("m3ufile").value = 'download_iptv.m3u';
							}
							
						}
					};
					xmlhttp.open("GET", "epg_actions.php?config="+configSetting+"&setting=" +settingField+"&action=setting", true);
					xmlhttp.send();
				}else{
					alert('You are supposed to fill something in!');
				}
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
