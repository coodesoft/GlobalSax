(function($) {
  /*Brought click function of fileupload button when text field is clicked*/
	$("#uploadtextfield").click(function() {
		$('#file-archivo').click()
	});

  /*To bring the selected file value in text field*/
	$('#file-archivo').change(function() {
    $('#uploadtextfield').val($(this).val());
  });

})(jQuery);
