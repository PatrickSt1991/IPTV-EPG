<script type='text/javascript' src='https://code.jquery.com/jquery-3.3.1.min.js'></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
<link href="css/styles.css" rel="stylesheet" />
<link rel="stylesheet" href="css/epg_iptv.css">
<?php
	include '_config.php';

	$continue = true;
	$resultFilter = false;

	$epgM3UChannelResult = mysqli_query($conn, "SELECT count(EntityId) as ChannelCount FROM epg_m3ufile") or die ("Error in query: ".mysqli_error()); 
	$m3uTableCheck = mysqli_fetch_assoc($epgM3UChannelResult);

	if ($m3uTableCheck['ChannelCount'] == 0){ 
		$error_message = "Channels are not yet ripped from the m3u file!";
		$continue = false;
	}

	if ($continue == true){
		$epgGroupTitle = mysqli_query($conn, "SELECT DISTINCT(group_title) as GroupTitle FROM m3u_channels ORDER BY GroupTitle ASC") or die ("Error is query: ".mysqli_error());
?>
<!DOCTYPE html>
<html>
	<body>
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
        <section class="pt-4">
            <div class="container px-lg-5">
			<div id="successBox" class="alert-box success"></div>
				<table class="styled-table">
					<thead>
						<tr>
							<th>Id</th>
							<th>TV Channel Name</th>
							<th>
								<select class="form-select" onchange="filterGroup(this.value);">
									<option disabled selected>Country Filter</option>
									<option value="ALL">ALL</option>
									  <?php
									  while($groups = mysqli_fetch_array($epgGroupTitle))
									  {
										  echo '<option id="' . $groups['GroupTitle'] . '" value="' . $groups['GroupTitle'] . '">' . $groups['GroupTitle'] . '</option>';
									  }
									  ?>
								</select>
							</th>
							<th>(De)Select All&nbsp;&nbsp;<input class="form-check-input" id="AllSelect" type="checkbox" onchange="setMultiChannelStatus(this.value)" value="groupAll"></th>
							
						</tr>
					</thead>
					<tbody class="unfilterClass">
					<?php
						if($resultFilter == false){
							$showResult = mysqli_query($conn, "SELECT * FROM m3u_channels");
							while($row = mysqli_fetch_array($showResult))
							{
								echo '<tr>';
								echo '<td>' . $row['EntityId'] . '</td>';
								echo '<td>' . $row['tvg_name'] . '</td>';
								echo '<td>' . $row['group_title'] . '</td>';
								if($row['active'] == 1)
								{
									echo '<td><div class="form-check">
												<input class="form-check-input" type="checkbox" onchange="setChannelStatus('.$row['EntityId'].',0)" value="' . $row['EntityId'] . '" id="flexCheckDefault" checked>
										</div></td>';
								} else {
									echo '<td><div class="form-check">
												<input class="form-check-input" type="checkbox" onchange="setChannelStatus('.$row['EntityId'].',1)" value="' . $row['EntityId'] . '" id="flexCheckDefault">
										</div></td>';
								}
								echo '</tr>';
							}
						}
					?>
					</tbody>
				</table>
			</div>
		</section>
		<script>

			function setMultiChannelStatus(groupName)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {};
				xmlhttp.open("GET", "epg_actions.php?groupId="+groupName+"&action=groupactivate", true);
				xmlhttp.send();
				$( "div.success" ).fadeIn( 500 ).delay( 2000 ).fadeOut( 400 );
				$("#successBox").html("TV Channels for group: " + groupName + " are activated!");
				$("#AllSelect").prop('checked', false);
			}
		
			function setChannelStatus(channelId, checkStatus)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {};
				xmlhttp.open("GET", "epg_actions.php?channelId="+channelId+"&action=activate&status="+checkStatus, true);
				xmlhttp.send();
			}

			function filterGroup(isi){
				$.ajax({
					url: "http://<?php echo $_SERVER['SERVER_NAME']; ?>/iptv/epg_actions.php?action=filter",
					type: 'POST',
					data: "groupSearch=" + isi,
					success: function(data){
						$(".unfilterClass").html(data);		
						$("#AllSelect").val(isi);
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