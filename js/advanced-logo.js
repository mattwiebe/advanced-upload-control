(function($){
	var api = wp.customize;
	var $logo = null;
	api( 'mw_logo_setting[logo]', function( setting ){
		setting.bind( function( newVal, oldVal ){
			// do we have a cached jQuery object yet?
			if ( ! $logo ) {
				$logo = $( '#mw-logo' );
			}
			// There isn't a logo element on the page yet
			if ( ! $logo.length ) {
				$logo = $( '<img id="mw-logo" />' ).prependTo( '#masthead' );
			}

			if ( newVal && newVal.url ) {
				$logo.attr( 'src', newVal.url ).show();
			} else {
				$logo.hide();
			}
		});
	});
})(jQuery);