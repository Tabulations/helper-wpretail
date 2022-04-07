/* eslint-disable max-len */
/* global wpretailSettingsParams */
jQuery( function ( $ ) {
	'use strict';

	// wpretailSettings_params is required to continue, ensure the object exists.
	if ( typeof wpretailSettingsParams === 'undefined' ) {
		return false;
	}

	var wpretailSettings = {
		init: function () {
			var form = $( 'form.wpretail-form' );
			form.each( function ( i, v ) {
				$( document ).ready( function () {
					var formTuple = $( v ),
						btn = formTuple.find( '.wpretail-submit' );

					btn.on( 'click', function ( e ) {
						e.preventDefault();
						var data = formTuple.serializeArray();
						// Change the text to user defined property.
						$( this ).html(
							undefined !== formTuple.data( 'process-text' )
								? formTuple.data( 'process-text' )
								: 'Processing'
						);

						// Add action intend for ajax_form_submission endpoint.
						data.push( {
							name: 'action',
							value: 'wpretail_ajax_form_submission',
						} );

						data.push( {
							name: 'wpretail_nonce',
							value: wpretailSettingsParams.nonce,
						} );

						data.push( {
							name: 'wpretail_target',
							value: formTuple.data( 'target' ),
						} );

						// Fire the ajax request.
						$.ajax( {
							url: wpretailSettingsParams.ajax_url,
							type: 'POST',
							data: data,
						} )
							.done( function ( xhr, textStatus, errorThrown ) {
								if (
									'success' === xhr.data.response ||
									true === xhr.success
								) {
								} else {
									var err = JSON.parse(
										errorThrown.responseText
									);
									console.log( err );
								}
							} )
							.fail( function () {
								btn.attr( 'disabled', false ).html( 'Submit' );
								formTuple
									.trigger( 'focusout' )
									.trigger( 'change' );
							} )
							.always( function ( xhr ) {} );
					} );
				} );
			} );
		},
	};
	wpretailSettings.init();
} );
