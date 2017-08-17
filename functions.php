<?php
	/*
	 * Edits the table header based on the variabls 'dir' and 'order'
	 * This is extremely hard coded, so I could probably improve this with a seperate function.
	 */
	function setHeader($order, $dir) {
		if ($order == 'title') {
			if ($dir == "asc") {
				echo '<th class="topRow"><div id="titleSort" value="asc">TITLE▲</div></th>';
			}
			else {
				echo '<th class="topRow"><div id="titleSort" value="desc">TITLE▼</div></th>';
			}

			echo '<th class="topRow"><div id="artistSort" value="none">ARTIST</div></th>';
			echo '<th class="topRow"><div id="setlistSort" value="none" >SETLIST</div></th>';
			echo '<th class="topRow"><div id="dateSort" value="none">DATE ADDED</div></th>';
		}
		else if ($order == 'artist') {
			echo '<th class="topRow"><div id="titleSort" value="none">TITLE</div></th>';

			if ($dir == 'asc') {
				echo '<th class="topRow"><div id="artistSort" value="asc">ARTIST▲</div></th>';
			}
			else {
				echo '<th class="topRow"><div id="artistSort" value="asc">ARTIST▼</div></th>';
			}

			echo '<th class="topRow"><div id="setlistSort" value="none">SETLIST</div></th>
				  <th class="topRow"><div id="dateSort" value="none">DATE ADDED</div></th>';
		}
		else if ($order == 'setlist') {
			echo '<th class="topRow"><div id="titleSort" value="none">TITLE</div></th>';
			echo '<th class="topRow"><div id="artistSort" value="none">ARTIST</div></th>';

			if ($dir == 'asc') {
				echo '<th class="topRow"><div id="setlistSort" value="none">SETLIST▲</div></th>';
			}
			else {
				echo '<th class="topRow"><div id="setlistSort" value="none">SETLIST▼</div></th>';
			}

			echo '<th class="topRow"><div id="dateSort" value="none">DATE ADDED</div></th>';
		}
		else if ($order == 'date') {
			echo '<th class="topRow"><div id="titleSort" value="none">TITLE</div></th>';
			echo '<th class="topRow"><div id="artistSort" value="none">ARTIST</div></th>';
			echo '<th class="topRow"><div id="setlistSort" value="none">SETLIST</div></th>';

			if ($dir == 'asc') {
				echo '<th class="topRow"><div id="dateSort" value="asc">DATE ADDED▲</div></th>';
			}
			else {
				echo '<th class="topRow"><div id="dateSort" value="asc">DATE ADDED▼</div></th>';
			}
		}
		else {
			echo '<th class="topRow"><div id="titleSort" value="none">TITLE</div></th>';
			echo '<th class="topRow"><div id="artistSort" value="none">ARTIST</div></th>';
			echo '<th class="topRow"><div id="setlistSort" value="none">SETLIST</div></th>';
			echo '<th class="topRow"><div id="dateSort" value="none">DATE ADDED</div></th>';
		}
	}


	/* Edits the mySQL query based on the variables 'dir' and 'order' */
	function setQuery($order, $dir) {
		$query;

		/* Must check if proper values are used */
		if ($order != NULL) {
			if ($order == "artist"){
				$query = "SELECT title, artist, setlist, songDate FROM songs WHERE ? AND ? AND ? ORDER BY artist LIMIT 50";
			} 
			else if ($order == "title") {
				$query = "SELECT title, artist, setlist, songDate FROM songs WHERE ? AND ? AND ? ORDER BY title LIMIT 50";
			} 
			else if ($order == "date") {
				$query = "SELECT title, artist, setlist, songDate FROM songs WHERE ? AND ? AND ? ORDER BY songDate LIMIT 50";
			}
			else if ($order == "setlist") {
				$query = "SELECT title, artist, setlist, songDate FROM songs WHERE ? AND ? AND ? ORDER BY setlist LIMIT 50";
			}
			else {
				$query = "SELECT title, artist, setlist, songDate FROM songs WHERE ? AND ? AND ? ORDER BY artist LIMIT 50";
			}
			
		}
		else {
			$query = "SELECT title, artist, setlist, songDate FROM songs WHERE ? AND ? AND ? ORDER BY artist LIMIT 50";
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
		global $query, $title, $artist, $setlist;	
		$alwaysTrue = "1=1";

		/* Not the default state */
		if ($title != NULL || $artist != NULL || $order != NULL || $setlist != NULL) {
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

			if ($setlist == NULL) {
				$setlist = $alwaysTrue;
			}
			else {
				//artist variable set in URL
				if (strpos($query, "artist LIKE ?") == false) {
					$query = str_replace("AND ? AND ?", "AND ? AND setlist LIKE ?", $query);
				}
				//no artist variable set in URL
				else { 
					$query = str_replace("AND ?", "AND setlist LIKE ?" , $query);
				}

				$setlist = '%' . $setlist . '%';
			}
		}
		/* The default state */
		else {
			$title = $alwaysTrue;
			$artist = $alwaysTrue;
			$setlist = $alwaysTrue;
		}

		return;
	}
?>