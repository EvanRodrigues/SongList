
/* Sends an ajax request to the server using all of the input fields that contain text.*/
function ajaxUpdate() {
	var title = $('#title').val();
	var artist = $('#artist').val();
	var setlist = $('#setlist').val();
	var order = "<?php echo $_GET['order'] ?>";
	var dir = "<?php echo $_GET['dir'] ?>";

	if ($('#titleSort').val() != "none" && $('#titleSort').val() != "" && order == "" && dir == "") {
		console.log("titleSort: " + $('#titleSort').val());
		order = 'title';
		dir = $('#titleSort').val();
	}

	if ($('#artistSort').val() != "none" && $('#artistSort').val() != "" && order == "" && dir == "") {
		order = 'artist';
		dir = $('#artistSort').val();
	}

	if ($('#setlistSort').val() != "none" && $('#setlistSort').val() != "" && order == "" && dir == "") {
		order = 'setlist';
		dir = $('#setlistSort').val();
	}

	if ($('#dateSort').val() != "none" && $('#dateSort').val() != "" && order == "" && dir == "") {
		order = 'date';
		dir = $('#dateSort').val();
	}

	$.ajax({
		type: "GET",
		url: "https://doop-songs.000webhostapp.com",
		data: {order: order, title: title, artist: artist, setlist: setlist, dir: dir},
		success: function(data) {
			var newTable = $(data).find('#songTable').html();
			$('#songTable').html(newTable);

			/*Update values so we know what is being sorted for future updates*/
			if (order == 'title') {
				$('#titleSort').val(dir);
			}
			else if (order == 'artist') {
				$('#artistSort').val(dir);
			}
			else if (order == 'setlist') {
				$('#setlistSort').val(dir);
			}
			else if (order == 'date') {
				$('#dateSort').val(dir);
			}
		}
	})
}

/*Changes the sort direction and/or column*/
function sort(mode) {
	var title = $('#title').val();
	var artist = $('#artist').val();
	var setlist = $('#setlist').val();

	var dir = "";
	var titleSort = $('#titleSort').val();
	var artistSort = $('#artistSort').val(); 
	var setlistSort = $('#setlistSort').val();
	var dateSort = $('#dateSort').val();

	/*figure out which column we are sorting and what direction to go*/
	if (mode == "title") {
		if (titleSort == "desc") {
			dir = "asc";
		}
		else {
			dir = "desc";
		}
	}

	if (mode == "artist") {
		if (artistSort == "desc") {
			dir = "asc";
		}
		else {
			dir = "desc";
		}
	}

	if (mode == "setlist") {
		if (setlistSort == "desc") {
			dir = "asc";
		}
		else {
			dir = "desc";
		}
	}

	if (mode == "date") {
		if (dateSort == "desc") {
			dir = "asc";
		}
		else {
			dir = "desc";
		}
	}

	$.ajax({
		type: "GET",
		url: "https://doop-songs.000webhostapp.com",
		data: {order: mode, title: title, artist: artist, setlist: setlist, dir: dir},
		success: function(data) {
			var newTable = $(data).find('#songTable').html();
			$('#songTable').html(newTable);
			
			/*Edit the values of all columns. Apply the value/dir to the correct column. Reset all the rest*/
			if (mode == 'title') {
				var tempHTML = $(data).find('#titleSort').html();
				$('#titleSort').html(tempHTML);
				$('#titleSort').val(dir);
				$('#artistSort').val("none");
				$('#setlistSort').val("none");
				$('#dateSort').val("none");
			}
			else if (mode == 'artist') {
				var tempHTML = $(data).find('#artistSort').html();
				$('#artistSort').html(tempHTML);
				$('#artistSort').val(dir);
				$('#titleSort').val("none");
				$('#setlistSort').val("none");
				$('#dateSort').val("none");
			}
			else if (mode == 'setlist') {
				var tempHTML = $(data).find('#setlistSort').html();
				$('#setlistSort').html(tempHTML);
				$('#setlistSort').val(dir);
				$('#artistSort').val("none");
				$('#titleSort').val("none");
				$('#dateSort').val("none");
			}
			else {
				var tempHTML = $(data).find('#dateSort').html();
				$('#dateSort').html(tempHTML);
				$('#dateSort').val(dir);
				$('#artistSort').val("none");
				$('#setlistSort').val("none");
				$('#titleSort').val("none");
			}	
		}
	})
}

/*sets the value of an html element by finding the correct id*/
function setValue(id, value) {
	if (value != null && id != '#order' && id != '#dir') {
		$(id).val(value); 
	}
}