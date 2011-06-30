$(document).ready(function() {
	$("#uploadprogressbar").progressBar();
});
			
function showUpload() {
	$.get("/ajax_uploadprogress.php?id=" + progress_key, function(data) {
		if (!data)
			return;

		var response;
		eval ("response = " + data);

		if (!response)
			return;

		var percentage = Math.floor(100 * parseInt(response['bytes_uploaded']) / parseInt(response['bytes_total']));
		$("#uploadprogressbar").progressBar(percentage);

	});
	setTimeout("showUpload()", 1500);
}