<?php
	/*
	 * Edits the table header based on the variabls 'dir' and 'order'
	 * This is extremely hard coded, so I could probably improve this with a seperate function.
	 */
	function setHeader($order, $dir) {
		$categories = array('title', 'artist', 'date');
		$count = 0;

		while($count < sizeof($categories)) {
			if ($categories[$count] == 'date') {
				$display = 'DATE ADDED';
			}
			else {
				$display = strtoupper($categories[$count]);
			}

			if($order == $categories[$count]) {
				if($dir == 'asc') {
					$display .= '▲';
				}
				else {
					$display .= '▼';
				}

				echo '<th class="topRow"><div id="' . $categories[$count] . 'Sort" value="' . $display . '">' . $display . '</div></th>';
			}	
			else {
				echo '<th class="topRow"><div id="' . $categories[$count] . 'Sort" value="none">' . $display . '</div></th>';
			}

			$count++;
		}
	}


	/* Edits the mySQL query based on the variables 'dir' and 'order' */
	function setQuery($order, $dir) {
		$query;

		/* Must check if proper values are used */
		if ($order != NULL) {
			if ($order == "artist"){
				$query = "SELECT title, artist, songDate FROM songs WHERE ? AND ? ORDER BY artist LIMIT 50";
			} 
			else if ($order == "title") {
				$query = "SELECT title, artist, songDate FROM songs WHERE ? AND ? ORDER BY title LIMIT 50";
			} 
			else if ($order == "date") {
				$query = "SELECT title, artist, songDate FROM songs WHERE ? AND ? ORDER BY songDate LIMIT 50";
			}
			else {
				$query = "SELECT title, artist, songDate FROM songs WHERE ? AND ? ORDER BY artist LIMIT 50";
			}
			
		}
		else {
			$query = "SELECT title, artist, songDate FROM songs WHERE ? AND ? ORDER BY artist LIMIT 50";
		}

		/*default direction is 'ASC' so 'DESC' is the only case where appending to the end of the query is necessary.*/
		if ($dir == 'desc') {
			$query = trim($query, "LIMIT 50");
			$query = $query . " DESC LIMIT 50";
		}
		
		return $query;
	}



	/*
	 * Updates the query based on what information was sent in the URL.
	 * Creates a statement that is always true if the variable has a NULL value.
	 */
	function setVariables() {	
		global $query, $title, $artist;	
		$alwaysTrue = "1=1";

		/* Not the default state */
		if ($title != NULL || $artist != NULL || $order != NULL) {
			if ($title == NULL) {
				$title = $alwaysTrue;
			}
			else {
				$query = str_replace("WHERE ?", "WHERE title LIKE ?", $query);
				$title = '%' . $title . '%';
			}

			if ($artist == NULL) {
				$artist = $alwaysTrue;
			}
			else {
				$query = preg_replace("/AND \?/", "AND artist LIKE ?", $query, 1);
				$artist = '%' . $artist . '%';
			}	
		}
		/* The default state */
		else {
			$title = $alwaysTrue;
			$artist = $alwaysTrue;
		}

		return;
	}
?>