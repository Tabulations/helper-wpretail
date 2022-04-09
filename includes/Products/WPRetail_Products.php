<?php

namespace WPRetail\Products;

use WPRetail;

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

		add_filter( 'wpretail_products_options', [ $this, 'options' ] );
		add_filter( 'wpretail_form_fields_options', [ $this, 'form_fields_option' ] );
		add_action( 'wpretail_view_add_product', [ $this, 'view_add_product' ] );
		add_action( 'wpretail_view_category', [ $this, 'view_category' ] );
		add_action( 'wpretail_view_brand', [ $this, 'view_brand' ] );
		add_action( 'wpretail_view_list', [ $this, 'view_list' ] );
		add_action( 'wpretail_view_warranty', [ $this, 'view_warranty' ] );
	}

	/**
	 * Add Warranty View.
	 *
	 * @return void
	 */
	public function view_warranty() {
		$field_options = apply_filters( 'wpretail_form_fields_options', [] );
		$settings      = $field_options['brand'];
		wpretail()->builder->html(
			'button',
			[
				'id'      => 'add_warranty',
				'content' => __( 'Add Warranty' ),
				'class'   => [ 'mb-3 btn btn-primary' ],
				'closed'  => true,
				'attr'    => [ 'type' => 'button' ],
				'data'    => [
					'bs-toggle' => 'modal',
					'bs-target' => '#wpretail_warranty',
				],
			]
		);

		wpretail()->builder->table(
			[
				'head'  => [
					__( 'Name', 'wpretail' ),
					__( 'Description', 'wpretail' ),
					__( 'Duration', 'wpretail' ),
					__( 'Action', 'wpretail' ),
				],
				'body'  => [
					[
						'test',
						'test',
						'test',
						'test',
					],
				],
				'class' => [ 'wpretail-datatable', 'table table-primary mt-5' ],
				'col'   => 'col-md-12',

			]
		);

		 $args = [
			 'form_args'  => [
				 'id'                => 'wpretail_warranty',
				 'class'             => [ 'wpretail-warranty ' ],
				 'attr'              => [
					 'action' => admin_url(),
					 'method' => 'post',
				 ],
				 'form_title'        => __( 'Add Warranty', 'wpretail' ),
				 'form_submit_id'    => 'wpretail_add_warranty',
				 'form_submit_label' => __( 'Add Warranty', 'wpretail' ),
				 'is_modal'          => true,
				 'modal'             => 'modal-md modal-dialog-centered modal-dialog-scrollable',
			 ],
			 'input_args' => $settings,
		 ];

			wpretail()->builder->form( $args );
	}

		/**
		 * Add List View.
		 *
		 * @return void
		 */
	public function view_list() {
		$field_options = apply_filters( 'wpretail_form_fields_options', [] );
		wpretail()->builder->table(
			[
				'head'  => [
					__( 'Product', 'wpretail' ),
					__( 'Business Location', 'wpretail' ),
					__( 'Unit Purchase Price', 'wpretail' ),
					__( 'Unit Selling Price', 'wpretail' ),
					__( 'Current Stock', 'wpretail' ),
					__( 'Product Type', 'wpretail' ),
					__( 'Category', 'wpretail' ),
					__( 'Brand', 'wpretail' ),
					__( 'SKU', 'wpretail' ),
				],
				'body'  => [
					[
						'test',
						'test',
						'test',
						'test',
						'test',
						'test',
						'test',
						'test',
						'test',
					],
				],
				'class' => [ 'wpretail-datatable', 'table table-primary mt-5' ],
				'col'   => 'col-md-12',
			]
		);

	}

	/**
	 * Add Brand View.
	 *
	 * @return void
	 */
	public function view_brand() {
		$field_options = apply_filters( 'wpretail_form_fields_options', [] );
		$settings      = $field_options['brand'];
		wpretail()->builder->html(
			'button',
			[
				'id'      => 'add_brand',
				'content' => __( 'Add Brand' ),
				'class'   => [ 'mb-3 btn btn-primary' ],
				'closed'  => true,
				'attr'    => [ 'type' => 'button' ],
				'data'    => [
					'bs-toggle' => 'modal',
					'bs-target' => '#wpretail_brand',
				],
			]
		);

		wpretail()->builder->table(
			[
				'head'  => [
					__( 'Brand', 'wpretail' ),
					__( 'Note', 'wpretail' ),
					__( 'Action', 'wpretail' ),
				],
				'body'  => [
					[
						'test',
						'test',
						'test',
					],
				],
				'class' => [ 'wpretail-datatable', 'table table-primary mt-5' ],
				'col'   => 'col-md-12',

			]
		);

		 $args = [
			 'form_args'  => [
				 'id'                => 'wpretail_brand',
				 'class'             => [ 'wpretail-brand ' ],
				 'attr'              => [
					 'action' => admin_url(),
					 'method' => 'post',
				 ],
				 'form_title'        => __( 'Add Brand', 'wpretail' ),
				 'form_submit_id'    => 'wpretail_add_brand',
				 'form_submit_label' => __( 'Add Brand', 'wpretail' ),
				 'is_modal'          => true,
				 'modal'             => 'modal-md modal-dialog-centered modal-dialog-scrollable',
			 ],
			 'input_args' => $settings,
		 ];

			wpretail()->builder->form( $args );
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
				'id'      => 'add_category',
				'content' => __( 'Add Category' ),
				'class'   => [ 'mb-3 btn btn-primary' ],
				'closed'  => true,
				'attr'    => [ 'type' => 'button' ],
				'data'    => [
					'bs-toggle' => 'modal',
					'bs-target' => '#wpretail_category',
				],
			]
		);

		wpretail()->builder->table(
			[
				'head'  => [
					__( 'Category', 'wpretail' ),
					__( 'Category Code', 'wpretail' ),
					__( 'Description', 'wpretail' ),
					__( 'Action', 'wpretail' ),
				],
				'body'  => [
					[
						'test',
						'test',
						'test',
						'test',
					],
				],
				'class' => [ 'wpretail-datatable', 'table table-primary mt-5' ],
				'col'   => 'col-md-12',

			]
		);

		$args = [
			'form_args'  => [
				'id'                => 'wpretail_category',
				'class'             => [ 'wpretail-category ' ],
				'attr'              => [
					'action' => admin_url(),
					'method' => 'post',
				],
				'form_title'        => __( 'Add Category', 'wpreatil' ),
				'form_submit_id'    => 'wpretail_add_category',
				'form_submit_label' => __( 'Add Category', 'wpretail' ),
				'is_modal'          => true,
				'modal'             => 'modal-md modal-dialog-centered modal-dialog-scrollable',
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
				'id'                => 'wpretail_add_product',
				'class'             => [ 'wpretail-add-product' ],
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
			'description'   => [
				'label' => [
					'content' => __( 'Description' ),
				],
				'input' => [
					'type' => 'textarea',
					'name' => 'description',
					'id'   => 'description',
					'attr' => [
						'rows' => '3',
						'cols' => '50',
					],
				],
				'col'   => 'col-md-12',
			],
			'sub_taxonomy'  => [
				'input' => [
					'type'    => 'checkbox',
					'name'    => 'sub_taxonomy',
					'id'      => 'sub_taxonomy',
					'options' => [
						'items'   => [
							'1' => __( 'Add as Sub taxonomy' ),
						],
						'has_key' => true,
					],
				],
				'col'   => 'col-md-12',
			],
			'parent_id'     => [
				'label' => [
					'content' => __( 'Select Parent Category' ),
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'parent_id',
					'id'      => 'parent_id',
					'options' => [
						'mens'   => "Men's",
						'womens' => "Women's",
					],
				],
				'col'   => 'col-md-12 parent_id',
			],

		];
		$brand = [
			'brand_name'        => [
				'label' => [
					'content' => __( 'Brand Name ' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'brand_name',
					'id'   => 'brand_name',
				],
				'col'   => 'col-md-12',
			],
			'brand_description' => [
				'label' => [
					'content' => __( 'Brand Description ' ),
				],
				'input' => [
					'type' => 'text',
					'name' => 'brand_description',
					'id'   => 'brand_description',
				],
				'col'   => 'col-md-12',
			],
		];
		$brand = [
			'warranty_name'        => [
				'label' => [
					'content' => __( 'Warranty Name ' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'warranty_name',
					'id'   => 'warranty_name',
				],
				'col'   => 'col-md-12',
			],
			'warranty_description' => [
				'label' => [
					'content' => __( 'Warranty Description ' ),
				],
				'input' => [
					'type' => 'textarea',
					'name' => 'warranty_description',
					'id'   => 'warranty_description',
				],
				'col'   => 'col-md-12',
			],
			'warranty_duration'        => [
				'label' => [
					'content' => __( 'Warranty Duration','wpretail') ,
				],
				'input' => [
					'type' => 'text',
					'name' => 'warranty_duration',
					'id'   => 'warranty_duration',
				],
				'col'   => 'col-md-6 ',
			],
			'warranty_duration_type'    => [
				'input' => [
					'type'    => 'select',
					'name'    => 'warranty_duration_type',
					'class'	=>['mt-2'],
					'id'      => 'warranty_duration_type',
					'options' => [
						'days'  => 'Days',
						'month' => 'Months',
						'year'  => 'Years',
					],
				],
				'col'   => 'col-md-6',
			],
		];
		return array_filter(
			array_merge(
				$field_options,
				[
					'add_product' => $add_product,
					'category'    => $category,
					'brand'       => $brand,
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
					'list'        => [
						'name' => 'List Product',
						'slug' => 'list',
					],
					'add_product' => [
						'name' => 'Add Product',
						'slug' => 'add_product',
					],
					'category'    => [
						'name' => 'Categories',
						'slug' => 'category',
					],
					'brand'       => [
						'name' => 'Brands',
						'slug' => 'brand',
					],
					'warranty'    => [
						'name' => 'Warranties',
						'slug' => 'warranty',
					],
				]
			)
		);
	}
}
