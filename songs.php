<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Custom Songs</title>
	
	<link rel="stylesheet" href="./css/bootstrap.min.css">
	<link rel="stylesheet" href="./css/stylesheet.css">
	<link rel="stylesheet" href="./css/mobile.css"/>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
	<script src="./js/tools.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</head>

<body>
<div class="container">
	<div class="row header">
		<div class="col header-container"> 
			<h1 class="page-header">Custom Songs</h1>
		</div>
	</div>

	
	<div class="row inputs">
		<div class="col input-container"> 	
			<input type="text" id="title" name="title" class="form-control" placeholder="Search by Title" autocomplete="off">
		</div>
		<div class="col input-container">
			<input type="text" id="artist" name="artist" class="form-control" placeholder="Search by Artist"  autocomplete="off">
		</div>

		<div class="col-6">
	
		</div>
	</div>
	

	<div class="row table-container">
		<div class="col">
			<div class="table_header">
				<table class="table table-striped table-dark">
					<thead>
						<tr id="topRowContainer">
							<?php
								error_reporting(0); //turning off notices and php errors in case any of these are NULL.
								$order = $_GET['order'];
								$dir = $_GET['dir'];

								include('./functions.php');
								setHeader($order, $dir);//uses $order and $dir to keep the sorting links in the same state before the server call.
							?>
						</tr>
					</thead>
				</table>
			</div>

			<div class="table-scroll">
				<table class="table table-striped table-dark" id="songTable">
						

					<tbody id="table_body">

					<?php
						$title = $_GET['title'];
						$artist = $_GET['artist'];
						$offset = $_GET['off'];

						require_once('./SongsConnectionVars.php');
						$connection = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database)
							or die('connection error');

						$query = setQuery($order, $dir);
						if ($offset != NULL) {
							$query = $query . " OFFSET " . $offset;
						}
						setVariables();

						$prepared = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($prepared, "ss", $title, $artist);
						mysqli_stmt_execute($prepared);
						mysqli_stmt_bind_result($prepared, $myTitle, $myArtist, $myDate);

						echo $myTitle;

						$count = 0;
						while (mysqli_stmt_fetch($prepared) != NULL) {
							$tempDate = date_create($myDate);
							
							if ($count == 0 && $offset == NULL) {
								echo '<tr id="topRow">';
							}
							else {
								echo '<tr>';
							}
							
							echo '<th>' . $myTitle . '</th>';
							echo '<th>' . $myArtist . '</th>';
							echo '<th>' . date_format($tempDate, 'm-d-Y') . '</th>';
							echo '</tr>';

							$count = $count + 1;
						}

						mysqli_stmt_close($prepared);
						mysqli_close($connection);
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>



<script>
	var title = "<?php echo $_GET['title']; ?>";
	var artist = "<?php echo $_GET['artist']; ?>";
	var order = "<?php echo $_GET['order']; ?>";
	var dir = "<?php echo $_GET['dir']; ?>";
	
	setValue('#title', title);
	setValue('#artist', artist);
	setValue('#order', order);
	setValue('#dir', dir);

	/* Setting timers to send ajax calls when a user has finished typing in each search box. */	
	var titleTimer = null;
	$('#title').keydown(function() {
		clearTimeout(titleTimer);
		titleTimer = setTimeout(ajaxUpdate, 500);
	});

	var artistTimer = null;
	$('#artist').keydown(function() {
		clearTimeout(artistTimer);
		artistTimer = setTimeout(ajaxUpdate, 500);
	});

	$(document).on('click', '#titleSort', function() {
		sort('title');
	});

	$(document).on('click', '#artistSort', function() {
		sort('artist');
	});

	$(document).on('click', '#dateSort', function() {
		sort('date');
	});
</script>
</body>
</html>