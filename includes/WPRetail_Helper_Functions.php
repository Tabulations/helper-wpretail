<?php

namespace WPRetail;

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
class WPRetail_Helper_Functions {

	/**
	 * Get supported currencies.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function wpretail_get_currencies() {
		$currencies = [
			'USD' => [
				'name'                => esc_html__( 'U.S. Dollar', 'wpretail' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'GBP' => [
				'name'                => esc_html__( 'Pound Sterling', 'wpretail' ),
				'symbol'              => '&pound;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'EUR' => [
				'name'                => esc_html__( 'Euro', 'wpretail' ),
				'symbol'              => '&euro;',
				'symbol_pos'          => 'right',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'AUD' => [
				'name'                => esc_html__( 'Australian Dollar', 'wpretail' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'BRL' => [
				'name'                => esc_html__( 'Brazilian Real', 'wpretail' ),
				'symbol'              => 'R$',
				'symbol_pos'          => 'left',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'CAD' => [
				'name'                => esc_html__( 'Canadian Dollar', 'wpretail' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'CZK' => [
				'name'                => esc_html__( 'Czech Koruna', 'wpretail' ),
				'symbol'              => '&#75;&#269;',
				'symbol_pos'          => 'right',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'DKK' => [
				'name'                => esc_html__( 'Danish Krone', 'wpretail' ),
				'symbol'              => 'kr.',
				'symbol_pos'          => 'right',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'HKD' => [
				'name'                => esc_html__( 'Hong Kong Dollar', 'wpretail' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'right',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'HUF' => [
				'name'                => esc_html__( 'Hungarian Forint', 'wpretail' ),
				'symbol'              => 'Ft',
				'symbol_pos'          => 'right',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'ILS' => [
				'name'                => esc_html__( 'Israeli New Sheqel', 'wpretail' ),
				'symbol'              => '&#8362;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'MYR' => [
				'name'                => esc_html__( 'Malaysian Ringgit', 'wpretail' ),
				'symbol'              => '&#82;&#77;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'MXN' => [
				'name'                => esc_html__( 'Mexican Peso', 'wpretail' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'NOK' => [
				'name'                => esc_html__( 'Norwegian Krone', 'wpretail' ),
				'symbol'              => 'Kr',
				'symbol_pos'          => 'left',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'NZD' => [
				'name'                => esc_html__( 'New Zealand Dollar', 'wpretail' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'PHP' => [
				'name'                => esc_html__( 'Philippine Peso', 'wpretail' ),
				'symbol'              => 'Php',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'PLN' => [
				'name'                => esc_html__( 'Polish Zloty', 'wpretail' ),
				'symbol'              => '&#122;&#322;',
				'symbol_pos'          => 'left',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'RUB' => [
				'name'                => esc_html__( 'Russian Ruble', 'wpretail' ),
				'symbol'              => 'pyб',
				'symbol_pos'          => 'right',
				'thousands_separator' => ' ',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'SGD' => [
				'name'                => esc_html__( 'Singapore Dollar', 'wpretail' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'ZAR' => [
				'name'                => esc_html__( 'South African Rand', 'wpretail' ),
				'symbol'              => 'R',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'SEK' => [
				'name'                => esc_html__( 'Swedish Krona', 'wpretail' ),
				'symbol'              => 'Kr',
				'symbol_pos'          => 'right',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'CHF' => [
				'name'                => esc_html__( 'Swiss Franc', 'wpretail' ),
				'symbol'              => 'CHF',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'TWD' => [
				'name'                => esc_html__( 'Taiwan New Dollar', 'wpretail' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'THB' => [
				'name'                => esc_html__( 'Thai Baht', 'wpretail' ),
				'symbol'              => '&#3647;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'JPY' => [
				'name'                => esc_html__( 'Japanese yen', 'wpretail' ),
				'symbol'              => '&yen;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
		];

		return apply_filters( 'everest_forms_currencies', $currencies );
	}

	/**
	 * Get the user id.
	 *
	 * @since 1.0.0
	 */
	function wpretail_get_current_user_id() {
		if ( ! function_exists( 'wp_get_current_user' ) ) {
			return 0;
		}
		$user = wp_get_current_user();
		return ( isset( $user->ID ) ? (int) $user->ID : 0 );

	}

	/**
	 * Serializer.
	 *
	 * @param mixed $array Array to be serailised.
	 * @return string Json.
	 */
	public function encode( $array ) {
		return json_encode( $array );
	}

	/**
	 * Un Serializer.
	 *
	 * @param mixed $array JSON Object to be unserailised.
	 * @return mixed $obj Array.
	 */
	public function decode( $obj ) {
		return json_decode( $obj, true );
	}

/**
	 * Handles the file upload request.
	 */
	function wpretail_handle_file_upload() {



		$file_size = $_FILES['wpretail_product_product_image']['size'];
		$allowed_file_size = 512000; // Here we are setting the file size limit to 500 KB = 500 × 1024

		// Check for file size limit
		if ( $file_size >= $allowed_file_size ) {
			wp_die( sprintf( esc_html__( 'File size limit exceeded, file size should be smaller than %d KB', 'theme-text-domain' ), $allowed_file_size / 1000 ) );
		}

		// These files need to be included as dependencies when on the front end.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		// Let WordPress handle the upload.
		// Remember, 'wpcfu_file' is the name of our file input in our form above.
		// Here post_id is 0 because we are not going to attach the media to any post.
		$attachment_id = media_handle_upload( 'wpcfu_file', 0 );

		if ( is_wp_error( $attachment_id ) ) {
			// There was an error uploading the image.
			wp_die( $attachment_id->get_error_message() );
		} else {
			// We will redirect the user to the attachment page after uploading the file successfully.
			wp_redirect( get_the_permalink( $attachment_id ) );
			exit;
		}
	}
}
