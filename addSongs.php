<?php
	function addSong($connection, $title, $artist) {
		date_default_timezone_set('America/New_York');
		$date = date('Y-m-d H:i:s', time());
		$query = "INSERT INTO songs (title, artist, songDate) VALUES (?,?,?)";

		$prepared = mysqli_prepare($connection, $query);
		mysqli_stmt_bind_param($prepared, "sss", $title, $artist, $date);
		mysqli_stmt_execute($prepared);
		mysqli_stmt_close($prepared);
	}
	
	require_once('./SongsConnectionVars.php');
	$connection = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database)
		or die('connection error');

	$file = fopen('./data/songs.txt', 'r');

	while ($line = fgets($file)) {
		/*Seperates the artist and title from the file*/
		$tokens = explode(' , ', $line);
		$artist = $tokens[0];
		$title = $tokens[1];
		
		if (strpos($artist, '\r') !== false) {
			$artist = substr($tokens[1], 0, -2);
		}
		
		echo 'title: ' . $title . '<br>';
		echo 'artist: ' . $artist . '<br>';

		addSong($connection, $title, $artist);	
	}
	
	mysqli_close($connection);
	fclose($file);
?>