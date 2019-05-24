<head>
	<meta charset="utf-8"/>
	<link type="text/css" rel="stylesheet" href="./css/stylesheet.css"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
	<script type="text/javascript" src="./js/tools.js"></script>
</head>

<body>

<h1>Doopian's Songs</h1>

<div id="inputContainer">
	<form action="songs.php">
		<input type="text" id="title" class="input" name="title" placeholder="Search by title" autocomplete="off">
		<input type="text" id="artist" class="input" name="artist" placeholder="Search by artist" autocomplete="off">
		<input type="text" id="setlist" class="input" name="setlist" placeholder="Search by setlist" autocomplete="off">

		<div id="sortingList">
			<select name="order" id="order" class="input" placeholder="Order by...">
				<option value="title">Order by Title</option>
				<option value="artist">Order by Artist</option>
				<option value="setlist">Order by Setlist</option>
				<option value="date">Order by Date</option>
			</select>

			<select name="dir" class="input" id="dir">
				<option value="asc">Ascending</option>
				<option value="desc">Descending</option>
			</select>

			<input type="submit">
		</div>
	</form>
</div>

<div id="tableContainer">
<table id="songTable">
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

	<tbody>

	<?php
		$title = $_GET['title'];
		$artist = $_GET['artist'];
		$setlist = $_GET['setlist'];
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
		mysqli_stmt_bind_param($prepared, "sss", $title, $artist, $setlist);
		mysqli_stmt_execute($prepared);
		mysqli_stmt_bind_result($prepared, $myTitle, $myArtist, $mySetlist, $myDate);

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
			echo '<th>' . $mySetlist . '</th>';
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


<script>
	var title = "<?php echo $_GET['title']; ?>";
	var artist = "<?php echo $_GET['artist']; ?>";
	var setlist = "<?php echo $_GET['setlist']; ?>";
	var order = "<?php echo $_GET['order']; ?>";
	var dir = "<?php echo $_GET['dir']; ?>";
	
	setValue('#title', title);
	setValue('#artist', artist);
	setValue('#setlist', setlist);
	setValue('#order', order);
	setValue('#dir', dir);
	setScrollListener();

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

	var setlistTimer = null;
	$('#setlist').keydown(function() {
		clearTimeout(setlistTimer);
		setlistTimer = setTimeout(ajaxUpdate, 500);
	});

	$(document).on('click', '#titleSort', function() {
		sort('title');
	});

	$(document).on('click', '#artistSort', function() {
		sort('artist');
	});

	$(document).on('click', '#setlistSort', function() {
		sort('setlist');
	});

	$(document).on('click', '#dateSort', function() {
		sort('date');
	});
</script>
</body>