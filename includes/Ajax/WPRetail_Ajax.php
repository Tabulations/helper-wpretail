<?php

namespace WPRetail\Ajax;

/**
 * Core Functions.
 *
 * Contains a bunch of helper methods.
 *
 * @package WPRetail
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sales Handler class.
 */
class WPRetail_Ajax extends WPRetail_Sanitizer {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'wp_ajax_wpretail_ajax_form_submission', [ $this, 'process_ajax' ] );
	}

	public function process_ajax() {

		$post_fields = $this->get_sanitized_data();
		if ( empty( $post_fields['fields'] ) ) {
			return wp_send_json_error(
				[ 'message' => __( 'Forms fields not found, Please reload the page and try again', 'wpretail' ) ]
			);
		}

		if ( ! empty( $post_fields['message'] ) ) {
			return wp_send_json_error(
				[ $post_fields ]
			);
		}

		if ( ! empty( $this->errors ) ) {
			return wp_send_json_error(
				[
					'message' => __( 'Data couldnot be saved, Please see errors highlighted', 'wpretail' ),
					'errors'  => $this->errors,
				]
			);
		}
		do_action( 'wpretail_' . $post_fields['target'] );
	}
}
