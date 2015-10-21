(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note that this assume you're going to use jQuery, so it prepares
	 * the $ function reference to be used within the scope of this
	 * function.
	 *
	 * From here, you're able to define handlers for when the DOM is
	 * ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * Or when the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and so on.
	 *
	 * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
	 * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
	 * be doing this, we should try to minimize doing that in our own work.
	 */
    $( window ).load(function() {

    	// var this_page = window.location.href;

        // run this script at interval
        // crap. delay is in microseconds!
        trigger_counter();
        var interval = parseInt( PARAMS.interval );

        if( interval <= 1 ){
        	interval = 1;
        }

        setInterval( trigger_counter, interval * 1000 );

	    function trigger_counter(){
			var this_page = "http://forum.piwik.org/read.php?2,1011";
			// var this_page = window.location.href;
	    	if( $('div#pwk_feedbar').length  ){
				$.ajax({
				         type : "post",
				         dataType : "json",
				         url : PARAMS.ajaxurl,
				         data : { action: "dx_pvt_ajax_request", local_page : this_page },

				         success: function(response) {
				         	var html_string = PARAMS.html_string.replace( "%count%", parseInt(response) )
				            $('div#pwk_feedbar').html( html_string );
				         },
				         error: function( xhr, status, error ){
							//  alert(  xhr.responseText );
				         }
				      });
			}
	    }

    });
})( jQuery );