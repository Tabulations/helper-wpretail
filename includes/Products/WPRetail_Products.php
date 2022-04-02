<?php

namespace WPRetail\Products;

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
class WPRetail_Products {

	/**
	 * Test Function.
	 */
	public function __construct() {
		add_filter( 'wpretail_menus', [ $this, 'menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'product_enqueue_script' ] );
		add_action( 'wp_ajax_add_product', [ $this, 'add_product' ] );
		add_action( 'wp_ajax_nopriv_add_product', [ $this, 'add_product' ] );
	}

	/**
	 * Menu.
	 *
	 * @param mixed $menus Menu.
	 *
	 * @return mixed
	 */
	public function menu( $menus ) {
		return array_filter(
			array_merge(
				$menus,
				[
					'Products' => [
						[
							'label'  => 'List Products',
							'slug'   => 'list-products',
							'icon'   => 'list-product-icon',
							'class'  => [],
							'is_pro' => false,
						],
						[
							'label'  => 'List Products',
							'slug'   => 'add-products',
							'icon'   => 'list-product-icon',
							'class'  => [],
							'is_pro' => false,
						],
					],
				]
			)
		);
	}

	/**
	 * Register and Enqueue the Product Style and Script.
	 *
	 * @since 1.0.0
	 */
	public function product_enqueue_script() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script( 'wp_retail_product_script', plugins_url( '/assets/js/product/product.js', WPRETAIL_PLUGIN_FILE ), [ 'jquery' ], WPRETAIL_VERSION, true );
		wp_localize_script(
			'wp_retail_product_script',
			'productAjax',
			[
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'product_nonce' => wp_create_nonce( 'product_script_nonce' ),
			]
		);
		wp_enqueue_script( 'wp_retail_product_script' );
	}

		/**
		 * Add the Product.
		 *
		 * @since 1.0.0
		 */
	public function add_product() {
		global $wpdb;
		if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'product_script_nonce' ) ) {
			return;
		}
		$product = $wpdb->insert(
			'wp_wpretail_products',
			[
				'name'           => sanitize_text_field( $_POST['product_name'] ),
				// 'sku'            => sanitize_text_field( $_POST['product_sku'] ),
				// 'barcode_type'   => sanitize_text_field( $_POST['barcode_type'] ),
				// 'brand_id'       => sanitize_text_field( $_POST['brand_id'] ),
				// 'category_id'    => sanitize_text_field( $_POST['category_id'] ),
				// 'sub_category_id'=> sanitize_text_field( $_POST['sub_category_id'] ),
				// 'business_id'    => sanitize_text_field( $_POST['business_location'] ),
			]
		);

		if ( ! is_wp_error( $product ) ) {
			wp_send_json_success( [ 'msg' => __( 'Product Added Successfully', 'wp-retail' ) ] );
		}

	}
}
