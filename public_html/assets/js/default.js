if (typeof(APP) == 'undefined') {
	APP	= {};
}

APP.Default = function() {

	var init = function() {
		anonymous();
		logo();
	}

	var anonymous = function() {
		var val = $('input#name').val();

		$('#remain_anon').click(function() {
			if ($(this).is(':checked')) {
				$('input#name').attr('disabled', 'disabled');
				val = $('input#name').val();
				$('input#name').val('');
			}
			else {
				$('input#name').attr('disabled', false);
				$('input#name').val(val);
			}
		});
	}
	
	var logo = function() {
		$('img#logo').mouseover(function() {
			$(this).attr('src', '/assets/images/logo_hover.png');
		});
		
		$('img#logo').mouseout(function() {
			$(this).attr('src', '/assets/images/logo.png');
		});
	}

	return {
		init: init
	}

}();

jQuery(function($) { APP.Default.init(); });
