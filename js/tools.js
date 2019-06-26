var scroll_div;
var tableHeight;
var offset;

$(document).ready(function() {
    scroll_div = ".table-scroll";
    tableHeight = $(scroll_div)[0].scrollHeight - $(scroll_div).outerHeight();
    offset = 0;

    $("#topRowContainer").css("width", $("#songTable").outerWidth());

    setScrollListener();
});

function getDataFields() {
    var title = $("#title").val();
    var artist = $("#artist").val();

    var order = "";
    var dir = "";

    if ($("#titleSort").val() != "none" && $("#titleSort").val() != "") {
        order = "title";
        dir = $("#titleSort").val();
    }

    if ($("#artistSort").val() != "none" && $("#artistSort").val() != "") {
        order = "artist";
        dir = $("#artistSort").val();
    }

    if ($("#dateSort").val() != "none" && $("#dateSort").val() != "") {
        order = "date";
        dir = $("#dateSort").val();
    }

    var output = {};
    output["title"] = title;
    output["artist"] = artist;
    output["order"] = order;
    output["dir"] = dir;

    return output;
}

/*
 * Recalculates the height of the table for the scroll bar event.
 * Sets the new width of the topRowContainer.
 */
function recalculate_height() {
    $("#topRowContainer").css("width", $("#songTable").outerWidth());

    tableHeight = $(scroll_div)[0].scrollHeight - $(scroll_div).outerHeight();
    return tableHeight;
}

/*
 * Scrolls to top of table.
 * Used when the table is updated from an ajax request.
 */
function scroll_to_top() {
    $(scroll_div)[0].scrollTop = 0;
}

$(window).resize(recalculate_height);

function setScrollListener() {
    var delay = false;
    var sqlOffset = 50 * offset;

    /* Loads more song data when the user scrolls near the bottom. */
    $(scroll_div).scroll(function() {
        var height = $(scroll_div).scrollTop();

        if (tableHeight - height < 75) {
            if (delay == true) {
                return;
            }

            /*prevents multiple ajax calls to happen at once*/
            delay = true;
            setTimeout(function() {
                delay = false;
            }, 300);

            dataFields = getDataFields();
            offset = offset + 1;
            sqlOffset = 50 * offset;

            $.ajax({
                type: "GET",
                url: "./songs.php",
                data: {
                    order: dataFields["order"],
                    title: dataFields["title"],
                    artist: dataFields["artist"],
                    dir: dataFields["dir"],
                    off: sqlOffset
                },
                success: function(data) {
                    var tableContent = $(data)
                        .find("tbody")
                        .html();
                    $("tbody").append(tableContent);
                    tableHeight =
                        $(scroll_div)[0].scrollHeight -
                        $(scroll_div).outerHeight();
                }
            });
        }
    });
}

/* Sends an ajax request to the server using all of the input fields that contain text.*/
function ajaxUpdate() {
    dataFields = getDataFields();

    $.ajax({
        type: "GET",
        url: "./songs.php",
        data: {
            order: dataFields["order"],
            title: dataFields["title"],
            artist: dataFields["artist"],
            dir: dataFields["dir"]
        },
        success: function(data) {
            var newTable = $(data)
                .find("#songTable")
                .html();
            $("#songTable").html(newTable);

            /*Update values so we know what is being sorted for future updates*/
            if (dataFields["order"] == "title") {
                $("#titleSort").val(dataFields["dir"]);
            } else if (dataFields["order"] == "artist") {
                $("#artistSort").val(dataFields["dir"]);
            } else if (dataFields["order"] == "date") {
                $("#dateSort").val(dataFields["dir"]);
            }

            scroll_to_top();
            recalculate_height();
            offset = 0;
        }
    });
}

/*Changes the sort direction and/or column*/
function sort(mode) {
    var title = $("#title").val();
    var artist = $("#artist").val();

    var dir = "";
    var titleSort = $("#titleSort").val();
    var artistSort = $("#artistSort").val();
    var dateSort = $("#dateSort").val();

    /*figure out which column we are sorting and what direction to go*/
    if (mode == "title") {
        if (titleSort == "desc") {
            dir = "asc";
        } else {
            dir = "desc";
        }
    }

    if (mode == "artist") {
        if (artistSort == "desc") {
            dir = "asc";
        } else {
            dir = "desc";
        }
    }

    if (mode == "date") {
        if (dateSort == "desc") {
            dir = "asc";
        } else {
            dir = "desc";
        }
    }

    $.ajax({
        type: "GET",
        url: "./songs.php",
        data: { order: mode, title: title, artist: artist, dir: dir },
        success: function(data) {
            var newTable = $(data)
                .find("#songTable")
                .html();
            $("#songTable").html(newTable);

            /*Edit the values of all columns. Apply the value/dir to the correct column. Reset all the rest*/
            if (mode == "title") {
                var tempHTML = $(data)
                    .find("#titleSort")
                    .html();
                $("#titleSort").html(tempHTML);
                $("#titleSort").val(dir);
                $("#artistSort").val("none");
                $("#dateSort").val("none");
            } else if (mode == "artist") {
                var tempHTML = $(data)
                    .find("#artistSort")
                    .html();
                $("#artistSort").html(tempHTML);
                $("#artistSort").val(dir);
                $("#titleSort").val("none");
                $("#dateSort").val("none");
            } else {
                var tempHTML = $(data)
                    .find("#dateSort")
                    .html();
                $("#dateSort").html(tempHTML);
                $("#dateSort").val(dir);
                $("#artistSort").val("none");
                $("#titleSort").val("none");
            }

            scroll_to_top();
            recalculate_height();
            offset = 0;
        }
    });
}

/*sets the value of an html element by finding the correct id*/
function setValue(id, value) {
    if (value != null && id != "#order" && id != "#dir") {
        $(id).val(value);
    }
}
