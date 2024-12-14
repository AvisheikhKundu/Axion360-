(function( $ ) {
    $( function() {
		// Dismissable Admin Notices (from https://www.alexgeorgiou.gr/persistently-dismissible-notices-wordpress/):
        $( '.dce-dismissible-notice' ).on( 'click', '.notice-dismiss', function( event, el ) {
            var $notice = $(this).parent('.notice.is-dismissible');
            var dismiss_url = $notice.attr('data-dismiss-url');
            if ( dismiss_url ) {
                $.get( dismiss_url );
            }
        });
    } );
})( jQuery );
