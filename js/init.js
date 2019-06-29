setValue("#title", title);
setValue("#artist", artist);
setValue("#order", order);
setValue("#dir", dir);

/* Setting timers to send ajax calls when a user has finished typing in each search box. */

var titleTimer = null;
$("#title").keydown(function() {
    clearTimeout(titleTimer);
    titleTimer = setTimeout(ajaxUpdate, 500);
});

var artistTimer = null;
$("#artist").keydown(function() {
    clearTimeout(artistTimer);
    artistTimer = setTimeout(ajaxUpdate, 500);
});

$(document).on("click", "#titleSort", function() {
    sort("title");
});

$(document).on("click", "#artistSort", function() {
    sort("artist");
});

$(document).on("click", "#dateSort", function() {
    sort("date");
});
