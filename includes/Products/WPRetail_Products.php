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
		add_action( 'admin_enqueue_scripts', [ $this, 'product_enqueue_script' ] );
		add_action( 'wp_ajax_add_product', [ $this, 'add_product' ] );
		add_action( 'wp_ajax_nopriv_add_product', [ $this, 'add_product' ] );
		add_filter( 'wpretail_products_options', [ $this, 'options' ] );
		add_filter( 'wpretail_form_fields_options', [ $this, 'form_fields_option' ] );
		add_action( 'wpretail_view_add_product', [ $this, 'view_add_product' ] );
		add_action( 'wpretail_view_category', [ $this, 'view_category' ] );
	}

	/**
	 * Add Category View.
	 *
	 * @return void
	 */
	public function view_category() {
		$field_options = apply_filters( 'wpretail_form_fields_options', [] );
		$settings      = $field_options['category'];
		wpretail()->builder->html(
			'button',
			[
				'id'      => 'add_location',
				'content' => __( 'Add Category' ),
				'class'   => [ 'mb-3 btn btn-primary' ],
				'closed'  => true,
				'attr'    => [ 'type' => 'button' ],
				'data'    => [
					'bs-toggle' => 'modal',
					'bs-target' => '#wpretail-category',
				],
			]
		);

		$args = [
			'form_args'  => [
				'id'                => 'wpretail-category',
				'class'             => [ 'wpretail-category' ],
				'attr'              => [
					'action' => admin_url(),
					'method' => 'post',
				],
				'form_title'        => __( 'Add Category', 'wpreatil' ),
				'form_submit_id'    => 'wpretail_add_category',
				'form_submit_label' => __( 'Add Category', 'wpretail' ),
				'is_modal'          => true,
				'modal'             => 'modal-lg modal-dialog-centered modal-dialog-scrollable',
			],
			'input_args' => $settings,
		];

		wpretail()->builder->form( $args );
	}

		/**
		 * Add Product View.
		 *
		 * @return void
		 */
	public function view_add_product() {
		$field_options = apply_filters( 'wpretail_form_fields_options', [] );
		$settings      = $field_options['add_product'];

		$args = [
			'form_args'  => [
				'id'                => 'wpretail-add_product',
				'class'             => [ 'wpretail-add_product' ],
				'attr'              => [
					'action' => admin_url(),
					'method' => 'post',
				],
				'form_title'        => __( 'Add New Product', 'wpretail' ),
				'form_submit_id'    => 'wpretail_add_product',
				'form_submit_label' => __( 'Add Product', 'wpretail' ),
			],
			'input_args' => $settings,
		];

		wpretail()->builder->form( $args );
	}

	/**
	 * Fields Options
	 *
	 * @param mixed $field_options
	 * @return void
	 */
	public function form_fields_option( $field_options ) {
		$brands      = [ 'apple', 'samsung', 'nokia', 'micromax', 'realme', 'redme' ];
		$add_product = [
			'product_name' => [
				'label' => [
					'content' => __( 'Product Name' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'product_name',
					'id'   => 'product_name',
				],
				'col'   => 'col-md-4',
			],
			'sku'          => [
				'label' => [
					'content' => __( 'SKU' ),
				],
				'input' => [
					'type' => 'text',
					'name' => 'sku',
					'id'   => 'sku',
				],
				'col'   => 'col-md-4',
			],
			'barcode_type' => [
				'label' => [
					'content' => __( 'Barcode Type' ) . '*',
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'barcode_type',
					'id'      => 'barcode_type',
					'options' => [
						'c128'  => __( 'Code 128 (C128)' ),
						'c39'   => __( 'Code 39 (C39)' ),
						'ean13' => __( 'EAN-13' ),
						'ean8'  => __( 'EAN-8' ),
						'upca'  => __( 'UPC-A' ),
						'upce'  => __( 'UPC-E' ),
					],
				],
				'col'   => 'col-md-4',
			],
			'brand_id'     => [
				'label' => [
					'content' => __( 'Brand' ),
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'brand_id',
					'id'      => 'brand_id',
					'options' => $brands,
				],
				'col'   => 'col-md-4',
			],
			'category_id'  => [
				'label' => [
					'content' => __( 'Category' ),
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'category_id',
					'id'      => 'category_id',
					'options' => [
						'1' => 'Accesories',
						'2' => 'Electronics',
					],
				],
				'col'   => 'col-md-4',
			],
		];
		$category    = [
			'category_name' => [
				'label' => [
					'content' => __( 'Category Name ' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'category_name',
					'id'   => 'category_name',
				],
				'col'   => 'col-md-12',
			],
			'category_code' => [
				'label' => [
					'content' => __( 'Category Code(HSN Code) ' ),
				],
				'input' => [
					'type' => 'text',
					'name' => 'category_code',
					'id'   => 'category_code',
				],
				'col'   => 'col-md-12',
			],
			'description' => [
				'label' => [
					'content' => __( 'Description' ),
				],
				'input' => [
					'type' => 'textarea',
					'name' => 'description',
					'id'   => 'description',
					'attr'	=>  [
						'rows' => '3',
						'cols'=> '50',
					],
				],
				'col'   => 'col-md-12',
			],
			'sub_taxonomy' => [
				'label' => [
					'content' => __( 'Add as Sub taxonomy' ),
				],
				'input' => [
					'type' => 'checkbox',
					'name' => 'sub_taxonomy',
					'id'   => 'sub_taxonomy',
				],
				'col'   => 'col-md-12',
			],

		];

		return array_filter(
			array_merge(
				$field_options,
				[
					'add_product' => $add_product,
					'category'    => $category,
				]
			)
		);
	}

	/**
	 * Menu.
	 *
	 * @param mixed $menus Menu.
	 *
	 * @return mixed
	 */
	public function options( $options ) {
		return array_filter(
			array_merge(
				$options,
				[
					'list_product' => [
						'name' => 'List Product',
						'slug' => 'list_product',
					],
					'add_product'  => [
						'name' => 'Add Product',
						'slug' => 'add_product',
					],
					'category'     => [
						'name' => 'Category',
						'slug' => 'category',
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
				'name'         => sanitize_text_field( $_POST['product_name'] ),
				'sku'          => sanitize_text_field( $_POST['product_sku'] ),
				'barcode_type' => sanitize_text_field( $_POST['barcode_type'] ),
				'brand_id'     => sanitize_text_field( $_POST['brand_id'] ),
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
