/**
 * EverestFormsRepeaterFieldsFrontEnd JS
 */
jQuery( function ( $ ) {
	var WPRetail = {
		init: function () {
			WPRetail.bindUIActions();
			WPRetail.bindScrollTop();
		},

		/**
		 * Element bindings
		 */
		bindUIActions: function () {
			// Functions to write to control add and remove buttons.
			$( '#wpretail-wrapper #sidebarToggleTop' ).click( function () {
				if ( $( '#wpretail-wrapper .sidebar' ).is( ':visible' ) ) {
					$( '#wpretail-wrapper .sidebar' ).css( 'display', 'none' );
				} else {
					$( '#wpretail-wrapper .sidebar' ).css( 'display', 'block' );
				}
			} );
		},
		bindScrollTop: function () {
			// Scroll to top button appear
			$( document ).on( 'scroll', function () {
				var scrollDistance = $( this ).scrollTop();
				if ( scrollDistance > 100 ) {
					$( '.scroll-to-top' ).fadeIn();
				} else {
					$( '.scroll-to-top' ).fadeOut();
				}
			} );

			// Smooth scrolling using jQuery easing
			$( document ).on( 'click', 'a.scroll-to-top', function ( e ) {
				var $anchor = $( this );
				$( 'html, body' )
					.stop()
					.animate(
						{
							scrollTop: $( $anchor.attr( 'href' ) ).offset().top,
						},
						1000,
						'easeInOutExpo'
					);
				e.preventDefault();
			} );
		},
	};
	WPRetail.init( jQuery );
} );
