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
 * Post Data Sanitizer and Validator class.
 */
abstract class WPRetail_Sanitizer {

	protected $errors;

	/**
	 * Get Sanitized Post Data
	 *
	 * @return $sanitized_fields Sanized fields.
	 */
	public function get_sanitized_data() {

		// New Sanitized Field.
		$sanitized_fields = [];

		if ( isset( $_POST['wpretail_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['wpretail_nonce'] ) ), 'wpretail_nonce' ) && isset( $_POST['wpretail_target'] ) ) {
			$target = sanitize_text_field( wp_unslash( $_POST['wpretail_target'] ) );

			if ( empty( $target ) ) {
				return [ 'message' => __( 'Target couldnot be found, Please reload the page and try again', 'wpretail' ) ];
			};

			$all_fields  = apply_filters( 'wpretail_form_fields_options', [] );
			$form_fields = $all_fields[ str_replace( 'wpretail_', '', str_replace( '-', '_', $target ) ) ];

			foreach ( $form_fields as $field_key => $field ) {
				if ( empty( $field['input'] ) || empty( $field['input']['type'] ) ) {
					continue;
				}

				switch ( $field['input']['type'] ) {
					case 'text':
					case 'number':
					case 'select':
					case 'checkbox':
					case 'radio':
						if ( isset( $_POST['wpretail'][ $field['id'] ] ) ) {
							$sanitized_fields[ $field_key ] = sanitize_text_field( wp_unslash( $_POST['wpretail'][ $field['id'] ] ) );
						}
						break;
					case 'textarea':
						if ( isset( $_POST['wpretail'][ $field['id'] ] ) ) {
							$sanitized_fields[ $field_key ] = sanitize_textarea_field( wp_unslash( $_POST['wpretail'][ $field['id'] ] ) );
						}
						break;
					default:
						$sanitized_fields[ $field_key ] = '';
				}

				$this->validate( $sanitized_fields[ $field_key ], $field );
			}

			return [ 'fields' => $sanitized_fields, 'target' => $target ];
		}

		return [ 'message' => __( 'Nonce verification faild, Please reload the page and try again', 'wpretail' ) ];

	}

	public function validate( $value, $field ) {
		if ( empty( $field['validations'] ) ) {
			return;
		}
		$validations = $field['validations'];
		foreach ( $validations as $validation ) {
			switch ( $validation ) {
				case 'required':
					if ( $this->required_validator( $value, $field ) ) {
						$this->errors[ $field['id'] ]['required'] = __( 'This field is required', 'wpretail' );
					}
					break;
				case 'email':
					if ( is_email( $value ) ) {
						$this->errors[ $field['id'] ]['required'] = __( 'Please enter a valid email', 'wpretail' );
					}
					break;
				case ( preg_match( '/min:.*/', $validation ) ? true : false ):
					preg_match_all( '/min:(\d+)/', $validation, $min );
					return strlen( $value ) >= $min;
					break;
				case ( preg_match( '/max:.*/', $validation ) ? true : false ):
					preg_match_all( '/max:(\d+)/', $validation, $max );
					return strlen( $value ) >= $max;
					break;
			}
		}
	}

	/**
	 * Required Field Validator,
	 *
	 * @param mixed $value Value.
	 * @param mixed $field Field.
	 * @return bool
	 */
	public function required_validator( $value, $field ) {
		switch ( $field['type'] ) {
			case 'text':
			case 'number':
			case 'select':
			case 'checkbox':
			case 'textarea':
				return empty( $value );
			case 'radio':
				return empty( $value );
			default:
				return empty( $value );
		}
	}
}
