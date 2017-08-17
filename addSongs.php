<?php
	require_once('./SongsConnectionVars.php');
	$connection = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database)
		or die('connection error');

	$file = fopen('newSongs.txt', 'r');
	$setlist = '';

	while ($line = fgets($file)) {
		if (strpos(substr($line, -3), ':') !== false) {
			$setlist = substr($line, 0, -3);
			echo 'setlist: ' . $setlist . '<br>';
		}
		else {
			$tokens = explode(' - ', $line);
			$title = $tokens[0];
			$artist = $tokens[1];

			if (strpos($artist, '\r') !== false) {
				$artist = substr($tokens[1], 0, -2);
			}
			
			echo 'title: ' . $title . '<br>';
			echo 'artist: ' . $artist . '<br>';

			addSong($connection, $title, $artist, $setlist);	
		}
	}

	mysqli_close($connection);
	fclose($file);



	function addSong($connection, $title, $artist, $setlist) {
		date_default_timezone_set('America/New_York');
		$date = date('Y-m-d H:i:s', time());

		
		$query = "INSERT INTO songs (title, artist, setlist, songDate) VALUES (?,?,?,?)";

		$prepared = mysqli_prepare($connection, $query);
		mysqli_stmt_bind_param($prepared, "ssss", $title, $artist, $setlist, $date);
		mysqli_stmt_execute($prepared);
		mysqli_stmt_close($prepared);	
	}
?>