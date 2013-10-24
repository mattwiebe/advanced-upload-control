(function($){
	var api = wp.customize;
	var $logo = null;
	api( 'mwvip_logo_control[url]', function( value ){
		value.bind( function( newVal, oldVal ){
			// do we have a cached jQuery object yet?
			if ( ! $logo )
				$logo = $( '#mw-logo' );
			// There isn't a logo element on the page yet
			if ( ! $logo.length )
				$logo = $( '<img id="mw-logo" />' ).prependTo( '#masthead' );

			if ( newVal )
				$logo.attr( 'src', newVal ).show();
			else
				$logo.hide();
		});
	})
})(jQuery);