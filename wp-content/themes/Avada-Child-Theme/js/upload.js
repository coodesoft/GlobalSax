(function($) {

	$('.sucursales_div').on('click', '.ciudad', function(){
    	$(this).siblings(".sucursal").toggleClass("mostrar", 1000, "easeOutSine");
  	});

	$('.trescol').on('click', 'span.uploadtextfield', function(){
		$(this).siblings(".file-archivo").children('.file-archivo').click();
	});

	$('.wpcf7-select-parent').on('change', '.menu-prov select', function(){

	});

	var loadUserContentCallback = function(form, action, target, callback){
		var data = {
			'data': $(form).serialize(),
			'action': action,
		}
		$.post(ajaxurl, data, function(data){
				$(target).html(data);
				$('body').removeClass('gbs-progress');

				if (callback != undefined)
					callback(data);
		});
	}

	var sendContent = function(form, action, target, callback){
		var data = {
			'data': $(form).serialize(),
			'action': action,
		}
		$.post(ajaxurl, data, function(data){
			data = JSON.parse(data);

			$(target).html(data['msg']);
			$('body').removeClass('gbs-progress');
			if (callback != undefined)
				callback(data);
		});
	}

	let showPreloadFile = function(input){
		//var input = $('input.file-archivo')[0];
		var output = $(input).closest('p').find('input.uploadtextfield');
		console.log(output);
		output.val(input.files.item(0).name);
		console.log(input.files.item(0).name);
	}
	$('.trescol').on('change', 'input.file-archivo', function(){
		showPreloadFile(this);

	});

})(jQuery);
