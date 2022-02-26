<script type='text/javascript' src='https://code.jquery.com/jquery-3.3.1.min.js'></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
<link href="css/styles.css" rel="stylesheet" />
<link rel="stylesheet" href="css/epg_iptv.css">
<!DOCTYPE html>
<html>
	<body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-lg-5">
                <a class="navbar-brand" href="#!">EPG Conversion table</a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php"><i class="bi bi-sliders"></i></a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <section class="pt-4">
            <div class="container px-lg-5">
<?php
	include '_config.php';
	
	$continue = true;
	$searchFilter = false;
		
	$epgGroupTitle = mysqli_query($conn, "SELECT DISTINCT(group_title) as GroupTitle FROM m3u_channels ORDER BY GroupTitle ASC") or die ("Error is query: ".mysqli_error());

	$epgM3UChannelResult = mysqli_query($conn, "SELECT count(EntityId) as ChannelCount FROM epg_m3ufile") or die ("Error in query: ".mysqli_error()); 
	$m3uTableCheck = mysqli_fetch_assoc($epgM3UChannelResult);

	if ($m3uTableCheck['ChannelCount'] == 0){ 
		$error_message = "Channels are not yet ripped from the m3u file!";
		$continue = false;
	}
	
	if($epg_conversion_table == 'off'){
		$error_message = "You haven't activated the EPG Conversion Switch setting";
		$continue = false;
	}
		

	if ($continue == true){
?>
				<select class="form-select" onchange="filterGroup(this.value);">
					<option disabled selected>Choose group</option>
					  <?php
					  while($groups = mysqli_fetch_array($epgGroupTitle))
					  {
						  echo '<option id="' . $groups['GroupTitle'] . '" value="' . $groups['GroupTitle'] . '">' . $groups['GroupTitle'] . '</option>';
					  }
					  ?>
				</select>
				<br/>
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
					<tbody class="unfilterClass">
						<?php
							if($searchFilter == false){
								echo '<tr><td colspan="5" style="width:100%">Choose a filter first (way to much to show in one time!)</td></tr>';
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
					newChannel = encodeURIComponent(newChannel);
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
			
			function filterGroup(isi){
				$.ajax({
					url: "http://<?php echo $_SERVER['SERVER_NAME']; ?>/iptv/epg_actions.php?action=filterConversion",
					type: 'POST',
					data: "groupSearch=" + isi,
					success: function(data){
						$(".unfilterClass").html(data);		
					}
				});
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