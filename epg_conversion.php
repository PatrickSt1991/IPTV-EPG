<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
<link href="css/styles.css" rel="stylesheet" />
<link rel="stylesheet" href="css/epg_iptv.css">
<?php
	include '_config.php';
	
	$continue = true;
	$showResult = mysqli_query($conn, "SELECT * FROM epg_m3ufile");

	$epgM3UChannelResult = mysqli_query($conn, "SELECT count(EntityId) as ChannelCount FROM epg_m3ufile") or die ("Error in query: ".mysqli_error()); 
	$m3uTableCheck = mysqli_fetch_assoc($epgM3UChannelResult);

	if ($m3uTableCheck['ChannelCount'] == 0){ 
		$error_message = "Channels are not yet ripped from the m3u file!";
		$continue = false;
	}

	if ($continue == true){
?>
<!DOCTYPE html>
<html>
	<body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-lg-5">
                <a class="navbar-brand" href="#!">EPG Conversion table</a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.html"><i class="bi bi-sliders"></i></a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <section class="pt-4">
            <div class="container px-lg-5">
				<table class="styled-table">
					<thead>
						<tr>
							<th>Id</th>
							<th>m3u Name (imported)</th>
							<th>Corrected Name</th>
							<th>Update</th>
							<th>Clear</th>
						</tr>
					</thead>
					<tbody>
						<?php
							while($row = mysqli_fetch_array($showResult))
							{
								echo '<tr>';
								echo '<td>' . $row['EntityId'] . '</td>';
								echo '<td>' . $row['m3uChannelName'] . '</td>';
								echo '<td><input type="text" id="guidChannelName+' . $row['EntityId'] . '" value="' . $row['guidChannelName'] . '"></td>';
								echo '<td><button id="updater' . $row['EntityId'] . '" type="button" class="button" onclick="updateChannel(document.getElementById(\'guidChannelName+' . $row['EntityId'] . '\').value,'.$row['EntityId'].')">Update</button></td>';
								echo '<td><button id="cleaner' . $row['EntityId'] . '" type="button" class="button" onclick="clearChannel('.$row['EntityId'].')">Clear</button></td>';
								echo '</tr>';
							}
						?>
					</tbody>
				</table>
			</div>
		</section>
		<script>
			function updateChannel(newChannel, channelId)
			{
				if(newChannel != ''){
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
						{
							document.getElementById("updater"+channelId).innerText = 'Changed!';
						}
					};
					xmlhttp.open("GET", "epg_actions.php?channelId="+channelId+"&channelName=" +newChannel+"&action=update", true);
					xmlhttp.send();
				}else{
					alert('You are supposed to fill something in as a channel name!');
				}
			}
			
			function clearChannel(channelId)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
					{
						document.getElementById("cleaner"+channelId).innerText = 'Cleared!';
					}
				};
				xmlhttp.open("GET", "epg_actions.php?channelId="+channelId+"&action=clear", true);
				xmlhttp.send();
			}
		</script>
	</body>
</html>
<?php
	}else{
?>
<div class="error-message">
    <?php echo nl2br($error_message); ?>
</div>
<?php } ?>