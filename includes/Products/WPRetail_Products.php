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

		// View
		add_action( 'wpretail_view_product', [ $this, 'view_product' ] );
		add_action( 'wpretail_view_category', [ $this, 'view_category' ] );
		add_action( 'wpretail_view_brand', [ $this, 'view_brand' ] );
		add_action( 'wpretail_view_warranty', [ $this, 'view_warranty' ] );
		add_action( 'wpretail_view_unit', [ $this, 'view_unit' ] );

		// Brand Handler.
		add_action( 'wpretail_brand_handler', [ $this, 'brand_handler' ] );
		add_action( 'wpretail_list_brand_handler', [ $this, 'list_brand_handler' ] );

		// category Handler.
		add_action( 'wpretail_category_handler', [ $this, 'category_handler' ] );
		add_action( 'wpretail_list_category_handler', [ $this, 'list_category_handler' ] );

		// Warrenty Handler.
		add_action( 'wpretail_warranty_handler', [ $this, 'warranty_handler' ] );
		add_action( 'wpretail_list_warranty_handler', [ $this, 'list_warranty_handler' ] );

		// Unit Handler.
		add_action( 'wpretail_unit_handler', [ $this, 'unit_handler' ] );
		add_action( 'wpretail_list_unit_handler', [ $this, 'list_unit_handler' ] );

		// Unit Handler.
		add_action( 'wpretail_product_handler', [ $this, 'product_handler' ] );
	}

	/**
	 * Product handler.
	 *
	 * @param mixed $ajax Ajax.
	 * @return void
	 */
	public function product_handler( $ajax ) {
error_log(print_r($ajax,true));
		$fields = [
			'name'            => $ajax->sanitized_fields['product_name'],
			'business_id'     => 1,
			'type'            => 'single',
			'brand_id'        => $ajax->sanitized_fields['brand_id'],
			'category_id'     => $ajax->sanitized_fields['category_id'],
			'sub_category_id' => $ajax->sanitized_fields['sub_category_id'],
			'tax'             => 1,
			'tax_type'        => 'inclusive',
			'product_image'   => $ajax->sanitized_fields['product_image'],
			'enable_stock'    => 1,
			'alert_quantity'  => 2,
			'sku'             => $ajax->sanitized_fields['sku'],
			'barcode_type'    => $ajax->sanitized_fields['barcode_type'],
			'status'          => 1,
			'created_by'      => 1,
		];
		$db     = new WPRetail\Db\WPRetail_Db( 'wpretail_products' );

		try {
			if ( ! empty( $ajax->event['id'] ) ) {
				$where = [ 'id' => $ajax->event['id'] ];
				$id    = $db->update( $fields, $where );
				if ( $id ) {
					$ajax->success['message'] = 'Product updated successfully';
					$ajax->success['id']      = $ajax->event['id'];
					$formatted_fields         = [
						'business_id'     => '1', // Always.
						'status'          => '1', // Always.
						'product_name'    => $fields['name'],
						'type'            => 'single',
						'brand_id'        => $fields['brand_id'],
						'category_id'     => $fields['category_id'],
						'sub_category_id' => $fields['sub_category_id'],
						'tax'             => 'test',
						'tax_type'        => 'inclusive',
						'product_image'   => $fields['product_image'],
						'enable_stock'    => 1,
						'alert_quantity'  => 2,
						'sku'             => $fields['sku'],
						'barcode_type'    => $fields['barcode_type'],
						'created_by'      => 1,
					];
					$ajax->success['updated'] = $formatted_fields;
				} else {
					$ajax->errors['message'] = 'Unit could not be updated';
				}
				return;
			} else {
				$id = $db->insert( $fields );

				if ( $id ) {
					$ajax->success['message']  = __( 'Product added successfully', 'wpretail' );
					$ajax->success['id']       = $db->get_last_insert_id();
					$formatted_fields          = [
						'business_id'     => '1', // Always.
						'status'          => '1', // Always.
						'product_name'    => $fields['name'],
						'type'            => 'single',
						'brand_id'        => $fields['brand_id'],
						'category_id'     => $fields['category_id'],
						'sub_category_id' => $fields['sub_category_id'],
						'tax'             => 'test',
						'tax_type'        => 'inclusive',
						'product_image'   => $fields['product_image'],
						'enable_stock'    => 1,
						'alert_quantity'  => 2,
						'sku'             => $fields['sku'],
						'barcode_type'    => $fields['barcode_type'],
						'created_by'      => 1,
					];
					$ajax->success['inserted'] = $formatted_fields;
				} else {
					$ajax->errors['message'] = __( 'Product Could not be added', 'wpretail' );
				}
			}
		} catch ( \Exception $e ) {
			$ajax->errors['message'] = $e->getMessage();
		}

	}

		/**
		 * List Unit Handler.
		 *
		 * @param mixed $ajax Ajax Object.
		 * @return void
		 */
	public function list_unit_handler( $ajax ) {

		if ( ! empty( $ajax->event ) ) {
			if ( ! empty( $ajax->event['action'] ) ) {
				switch ( $ajax->event['action'] ) {
					case 'delete':
						if ( ! empty( $ajax->event['id'] ) ) {
							$db    = new WPRetail\Db\WPRetail_Db( 'wpretail_units' );
							$where = [ 'id' => $ajax->event['id'] ]; // Business ID is always 1.
							try {
								$id = $db->update( [ 'status' => false ], $where );
								if ( $id ) {
									$ajax->success['message'] = __( 'Unit removed successsfully', 'wpretail' );
								} else {
									$ajax->errors['message'] = __( 'Unit could not be removed, Please repload the page and try again', 'wpretail' );
								}
							} catch ( \Exception $e ) {
								$ajax->errors['message'] = $e->getMessage();
							}
						} else {
							$ajax->errors['message'] = __( 'Id not found, please reload the page and try again', 'wpretail' );
						}
						break;
					case 'edit':
						if ( ! empty( $ajax->event['id'] ) ) {
							$db = new WPRetail\Db\WPRetail_Db( 'wpretail_units' );
							try {
								$warranty                  = $db->get_unit( $ajax->event['id'] );
								$fields                    = [
									'business_id'        => '1', // Always.
									'unit_name'          => $warranty['name'],
									'unit_short_name'    => $warranty['short_name'],
									'unit_allow_decimal' => $warranty['allow_decimal'],
								];
								$ajax->success['message']  = __( 'Update Unit', 'wpretail' );
								$ajax->success['location'] = $fields;
							} catch ( \Exception $e ) {
								$ajax->errors['message'] = $e->getMessage();
							}
						}
						break;
					default:
						$ajax->errors['message'] = __( 'Object not found, Please try again', 'wpretail' );
				}
			} else {
				$ajax->errors['message'] = __( 'Event not found, Please try again', 'wpretail' );
			}
		}
	}

	/**
	 * Warranty handler.
	 *
	 * @param mixed $ajax Ajax.
	 * @return void
	 */
	public function unit_handler( $ajax ) {

		$fields = [
			'name'          => $ajax->sanitized_fields['unit_name'],
			'business_id'   => 1,
			'short_name'    => $ajax->sanitized_fields['unit_short_name'],
			'allow_decimal' => $ajax->sanitized_fields['unit_allow_decimal'],
			'status'        => 1,
		];
		$db     = new WPRetail\Db\WPRetail_Db( 'wpretail_units' );

		try {
			if ( ! empty( $ajax->event['id'] ) ) {
				$where = [ 'id' => $ajax->event['id'] ];
				$id    = $db->update( $fields, $where );
				if ( $id ) {
					$ajax->success['message'] = 'Unit updated successfully';
					$ajax->success['id']      = $ajax->event['id'];
					$formatted_fields         = [
						'business_id'        => '1', // Always.
						'status'             => '1', // Always.
						'unit_name'          => $fields['name'],
						'unit_short_name'    => $fields['short_name'],
						'unit_allow_decimal' => $fields['allow_decimal'],
					];
					$ajax->success['updated'] = $formatted_fields;
				} else {
					$ajax->errors['message'] = 'Unit could not be updated';
				}
				return;
			} else {
				$id = $db->insert( $fields );

				if ( $id ) {
					$ajax->success['message']  = __( 'Unit added successfully', 'wpretail' );
					$ajax->success['id']       = $db->get_last_insert_id();
					$formatted_fields          = [
						'business_id'        => '1', // Always.
						'status'             => '1', // Always.
						'unit_name'          => $fields['name'],
						'unit_short_name'    => $fields['short_name'],
						'unit_allow_decimal' => $fields['allow_decimal'],
					];
					$ajax->success['inserted'] = $formatted_fields;
				} else {
					$ajax->errors['message'] = __( 'Unit Could not be added', 'wpretail' );
				}
			}
		} catch ( \Exception $e ) {
			$ajax->errors['message'] = $e->getMessage();
		}

	}

	/**
	 * Add Unit View.
	 *
	 * @return void
	 */
	public function view_unit() {
		$field_options = apply_filters( 'wpretail_form_fields_options', [] );
		$settings      = $field_options['unit'];
		$db            = new WPRetail\Db\WPRetail_Db( 'wpretail_units' );

		wpretail()->builder->html(
			'button',
			[
				'id'      => 'add_unit',
				'content' => __( 'Add Unit' ),
				'class'   => [ 'mb-3 btn btn-primary' ],
				'closed'  => true,
				'attr'    => [ 'type' => 'button' ],
				'data'    => [
					'bs-toggle' => 'modal',
					'bs-target' => '#wpretail_unit_modal',
				],
			]
		);

		wpretail()->builder->table(
			[
				'head'    => [
					'labels' => [
						'name'       => __( 'Name', 'wpretail' ),
						'short_name' => __( 'Short name', 'wpretail' ),
					],
					'data'   => [
						'name'       => 'unit_name',
						'short_name' => 'unit_short_name',
					],
				],
				'actions' => [
					'options'        => [
						'edit',
						'delete',
					],
					'delete_confirm' => __( 'Are you sure you want to remove?', 'wpretail' ),
					'update_confirm' => __( 'Are you sure you want to update?', 'wpretail' ),
				],
				'id'      => 'wpretail_list_unit',
				'body'    => $db->get_unit(),
				'class'   => [ 'wpretail-datatable', 'table table-primary mt-5' ],
				'col'     => 'col-md-12',

			]
		);

		 $args = [
			 'form_args'  => [
				 'id'                => 'wpretail_unit',
				 'class'             => [ 'wpretail-unit ' ],
				 'attr'              => [
					 'action' => admin_url(),
					 'method' => 'post',
				 ],
				 'form_title'        => __( 'Add Unit', 'wpretail' ),
				 'form_submit_id'    => 'wpretail_add_brand',
				 'form_submit_label' => __( 'Add Unit', 'wpretail' ),
				 'is_modal'          => true,
				 'modal'             => 'modal-md modal-dialog-centered modal-dialog-scrollable',
			 ],
			 'input_args' => $settings,
		 ];

			wpretail()->builder->form( $args );
	}

	/**
	 * List Warranty Handler.
	 *
	 * @param mixed $ajax Ajax Object.
	 * @return void
	 */
	public function list_warranty_handler( $ajax ) {

		if ( ! empty( $ajax->event ) ) {
			if ( ! empty( $ajax->event['action'] ) ) {
				switch ( $ajax->event['action'] ) {
					case 'delete':
						if ( ! empty( $ajax->event['id'] ) ) {
							$db    = new WPRetail\Db\WPRetail_Db( 'wpretail_warranties' );
							$where = [ 'id' => $ajax->event['id'] ]; // Business ID is always 1.
							try {
								$id = $db->update( [ 'status' => false ], $where );
								if ( $id ) {
									$ajax->success['message'] = __( 'Warranty removed successsfully', 'wpretail' );
								} else {
									$ajax->errors['message'] = __( 'Warranty could not be removed, Please repload the page and try again', 'wpretail' );
								}
							} catch ( \Exception $e ) {
								$ajax->errors['message'] = $e->getMessage();
							}
						} else {
							$ajax->errors['message'] = __( 'Id not found, please reload the page and try again', 'wpretail' );
						}
						break;
					case 'edit':
						if ( ! empty( $ajax->event['id'] ) ) {
							$db = new WPRetail\Db\WPRetail_Db( 'wpretail_warranties' );
							try {
								$warranty                  = $db->get_warranty( $ajax->event['id'] );
								$fields                    = [
									'business_id'          => '1', // Always.
									'warranty_name'        => $warranty['name'],
									'warranty_description' => $warranty['description'],
									'warranty_duration'    => $warranty['duration'],
									'warranty_duration_type' => $warranty['duration_type'],
								];
								$ajax->success['message']  = __( 'Update Warranty', 'wpretail' );
								$ajax->success['location'] = $fields;
							} catch ( \Exception $e ) {
								$ajax->errors['message'] = $e->getMessage();
							}
						}
						break;
					default:
						$ajax->errors['message'] = __( 'Object not found, Please try again', 'wpretail' );
				}
			} else {
				$ajax->errors['message'] = __( 'Event not found, Please try again', 'wpretail' );
			}
		}
	}

	/**
	 * Warranty handler.
	 *
	 * @param mixed $ajax Ajax.
	 * @return void
	 */
	public function warranty_handler( $ajax ) {

		$fields = [
			'name'          => $ajax->sanitized_fields['warranty_name'],
			'business_id'   => 1,
			'description'   => $ajax->sanitized_fields['warranty_description'],
			'duration'      => $ajax->sanitized_fields['warranty_duration'],
			'duration_type' => $ajax->sanitized_fields['warranty_duration_type'],
			'status'        => 1,
		];
		$db     = new WPRetail\Db\WPRetail_Db( 'wpretail_warranties' );

		try {
			if ( ! empty( $ajax->event['id'] ) ) {
				$where = [ 'id' => $ajax->event['id'] ];
				$id    = $db->update( $fields, $where );
				if ( $id ) {
					$ajax->success['message'] = 'Warranty updated successfully';
					$ajax->success['id']      = $ajax->event['id'];
					$formatted_fields         = [
						'business_id'            => '1', // Always.
						'status'                 => '1', // Always.
						'warranty_name'          => $fields['name'],
						'warranty_description'   => $fields['description'],
						'warranty_duration'      => $fields['duration'],
						'warranty_duration_type' => $fields['duration_type'],
					];
					$ajax->success['updated'] = $formatted_fields;
				} else {
					$ajax->errors['message'] = 'Warranty could not be updated';
				}
				return;
			} else {
				$id = $db->insert( $fields );
				if ( $id ) {
					$ajax->success['message']  = __( 'Warranty added successfully', 'wpretail' );
					$formatted_fields          = [
						'business_id'            => '1', // Always.
						'status'                 => '1', // Always.
						'warranty_name'          => $fields['name'],
						'warranty_description'   => $fields['description'],
						'warranty_duration'      => $fields['duration'],
						'warranty_duration_type' => $fields['duration_type'],
					];
					$ajax->success['id']       = $db->get_last_insert_id();
					$ajax->success['inserted'] = $formatted_fields;
				} else {
					$ajax->errors['message'] = __( 'Warranty Could not be added', 'wpretail' );
				}
			}
		} catch ( \Exception $e ) {
			$ajax->errors['message'] = $e->getMessage();
		}

	}

	/**
	 * List Category Setting Handler.
	 *
	 * @param mixed $ajax Ajax Object.
	 * @return void
	 */
	public function list_category_handler( $ajax ) {

		if ( ! empty( $ajax->event ) ) {
			if ( ! empty( $ajax->event['action'] ) ) {
				switch ( $ajax->event['action'] ) {
					case 'delete':
						if ( ! empty( $ajax->event['id'] ) ) {
							$db    = new WPRetail\Db\WPRetail_Db( 'wpretail_categories' );
							$where = [ 'id' => $ajax->event['id'] ]; // Business ID is always 1.
							try {
								$id = $db->update( [ 'status' => false ], $where );
								if ( $id ) {
									$ajax->success['message'] = __( 'category removed successsfully', 'wpretail' );
								} else {
									$ajax->errors['message'] = __( 'category could not be removed, Please repload the page and try again', 'wpretail' );
								}
							} catch ( \Exception $e ) {
								$ajax->errors['message'] = $e->getMessage();
							}
						} else {
							$ajax->errors['message'] = __( 'Id not found, please reload the page and try again', 'wpretail' );
						}
						break;
					case 'edit':
						if ( ! empty( $ajax->event['id'] ) ) {
							$db = new WPRetail\Db\WPRetail_Db( 'wpretail_categories' );
							try {
								$category = $db->get_category( $ajax->event['id'] );
								$fields                    = [
									'business_id'          => '1', // Always.
									'category_name'        => $category['name'],
									'category_code'        => $category['short_code'],
									'category_description' => $category['description'],
								];
								$ajax->success['message']  = __( 'Update category', 'wpretail' );
								$ajax->success['location'] = $fields;
							} catch ( \Exception $e ) {
								$ajax->errors['message'] = $e->getMessage();
							}
						}
						break;
					default:
						$ajax->errors['message'] = __( 'Object not found, Please try again', 'wpretail' );
				}
			} else {
				$ajax->errors['message'] = __( 'Event not found, Please try again', 'wpretail' );
			}
		}
	}

	/**
	 * Category handler.
	 *
	 * @param mixed $ajax Ajax.
	 * @return void
	 */
	public function category_handler( $ajax ) {

		$fields = [
			'name'        => $ajax->sanitized_fields['category_name'],
			'business_id' => 1,
			'short_code'  => $ajax->sanitized_fields['category_code'],
			'parent_id'   => 1,
			'description' => $ajax->sanitized_fields['description'],
			'created_by'  => wpretail()->helper->wpretail_get_current_user_id(),
			'status'      => 1,
		];
		$db     = new WPRetail\Db\WPRetail_Db( 'wpretail_categories' );

		try {
			if ( ! empty( $ajax->event['id'] ) ) {
				$where = [ 'id' => $ajax->event['id'] ];
				$id    = $db->update( $fields, $where );
				if ( $id ) {
					$ajax->success['message'] = 'Category updated successfully';
					$ajax->success['id']      = $ajax->event['id'];
					$formatted_fields         = [
						'business_id'          => '1', // Always.
						'status'               => '1', // Always.
						'category_code'        => $fields['short_code'],
						'category_name'        => $fields['name'],
						'category_description' => $fields['description'],
					];
					$ajax->success['updated'] = $formatted_fields;
				} else {
					$ajax->errors['message'] = 'Category could not be updated';
				}
				return;
			} else {
				$id = $db->insert( $fields );
				if ( $id ) {
					$ajax->success['message']  = __( 'Category added successfully', 'wpretail' );
					$ajax->success['id']       = $db->get_last_insert_id();
					$formatted_fields          = [
						'business_id'          => '1', // Always.
						'status'               => '1', // Always.
						'category_code'        => $fields['short_code'],
						'category_name'        => $fields['name'],
						'category_description' => $fields['description'],
					];
					$ajax->success['inserted'] = $formatted_fields;
				} else {
					$ajax->errors['message'] = __( 'Category Could not be added', 'wpretail' );
				}
			}
		} catch ( \Exception $e ) {
			$ajax->errors['message'] = $e->getMessage();
		}

	}

	/**
	 * List Brand Setting Handler.
	 *
	 * @param mixed $ajax Ajax Object.
	 * @return void
	 */
	public function list_brand_handler( $ajax ) {
		if ( ! empty( $ajax->event ) ) {
			if ( ! empty( $ajax->event['action'] ) ) {
				switch ( $ajax->event['action'] ) {
					case 'delete':
						if ( ! empty( $ajax->event['id'] ) ) {
							$db    = new WPRetail\Db\WPRetail_Db( 'wpretail_brands' );
							$where = [ 'id' => $ajax->event['id'] ]; // Business ID is always 1.
							try {
								$id = $db->update( [ 'status' => false ], $where );
								if ( $id ) {
									$ajax->success['message'] = __( 'Brand removed successsfully', 'wpretail' );
								} else {
									$ajax->errors['message'] = __( 'Brand could not be removed, Please repload the page and try again', 'wpretail' );
								}
							} catch ( \Exception $e ) {
								$ajax->errors['message'] = $e->getMessage();
							}
						} else {
							$ajax->errors['message'] = __( 'Id not found, please reload the page and try again', 'wpretail' );
						}
						break;
					case 'edit':
						if ( ! empty( $ajax->event['id'] ) ) {
							$db = new WPRetail\Db\WPRetail_Db( 'wpretail_brands' );
							try {
								$brand                     = $db->get_brand( $ajax->event['id'] );
								$fields                    = [
									'business_id'       => '1', // Always.
									'brand_name'        => $brand['name'],
									'brand_description' => $brand['description'],
								];
								$ajax->success['message']  = __( 'Update brand', 'wpretail' );
								$ajax->success['location'] = $fields;
							} catch ( \Exception $e ) {
								$ajax->errors['message'] = $e->getMessage();
							}
						}
						break;
					default:
						$ajax->errors['message'] = __( 'Object not found, Please try again', 'wpretail' );
				}
			} else {
				$ajax->errors['message'] = __( 'Event not found, Please try again', 'wpretail' );
			}
		}
	}

	/**
	 * Business setting handler.
	 *
	 * @param mixed $ajax Ajax.
	 * @return void
	 */
	public function brand_handler( $ajax ) {

		$fields = [
			'name'        => $ajax->sanitized_fields['brand_name'],
			'business_id' => 1,
			'description' => $ajax->sanitized_fields['brand_description'],
			'created_by'  => wpretail()->helper->wpretail_get_current_user_id(),
			'status'      => 1,
		];
		$db     = new WPRetail\Db\WPRetail_Db( 'wpretail_brands' );

		try {
			if ( ! empty( $ajax->event['id'] ) ) {
				$where = [ 'id' => $ajax->event['id'] ];
				$id    = $db->update( $fields, $where );
				if ( $id ) {
					$ajax->success['message'] = 'Brand updated successfully';
					$ajax->success['id']      = $ajax->event['id'];
					$formatted_fields         = [
						'business_id'       => '1', // Always.
						'status'            => '1', // Always.
						'brand_name'        => $fields['name'],
						'brand_description' => $fields['description'],
					];
					$ajax->success['updated'] = $formatted_fields;
				} else {
					$ajax->errors['message'] = 'Brand could not be updated';
				}
				return;
			} else {
				$id = $db->insert( $fields );
				if ( $id ) {
					$ajax->success['message']  = __( 'Brand added successfully', 'wpretail' );
					$ajax->success['id']       = $db->get_last_insert_id();
					$formatted_fields          = [
						'business_id'       => '1', // Always.
						'status'            => '1', // Always.
						'brand_name'        => $fields['name'],
						'brand_description' => $fields['description'],
					];
					$ajax->success['inserted'] = $formatted_fields;
				} else {
					$ajax->errors['message'] = __( 'Brand Could not be added', 'wpretail' );
				}
			}
		} catch ( \Exception $e ) {
			$ajax->errors['message'] = $e->getMessage();
		}

	}

	/**
	 * Add Warranty View.
	 *
	 * @return void
	 */
	public function view_warranty() {
		$field_options = apply_filters( 'wpretail_form_fields_options', [] );
		$settings      = $field_options['warranty'];
		$db            = new WPRetail\Db\WPRetail_Db( 'wpretail_warranties' );
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
					'bs-target' => '#wpretail_warranty_modal',
				],
			]
		);

		wpretail()->builder->table(
			[
				'head'    => [
					'labels' => [
						'name'        => __( 'Name', 'wpretail' ),
						'description' => __( 'Description', 'wpretail' ),
						'duration'    => __( 'Duration', 'wpretail' ),
					],
					'data'   => [
						'name'          => 'warranty_name',
						'description'   => 'warranty_description',
						'duration'      => 'warranty_duration',
						'duration_type' => 'warranty_duration_type',
					],
				],
				'actions' => [
					'options'        => [
						'edit',
						'delete',
					],
					'delete_confirm' => __( 'Are you sure you want to remove?', 'wpretail' ),
					'update_confirm' => __( 'Are you sure you want to update?', 'wpretail' ),
				],
				'id'      => 'wpretail_list_warranty',
				'body'    => $db->get_warranty(),
				'class'   => [ 'wpretail-datatable', 'table table-primary mt-5' ],
				'col'     => 'col-md-12',

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
	 * Add Brand View.
	 *
	 * @return void
	 */
	public function view_brand() {
		$field_options = apply_filters( 'wpretail_form_fields_options', [] );
		$settings      = $field_options['brand'];
		$db            = new WPRetail\Db\WPRetail_Db( 'wpretail_brands' );

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
					'bs-target' => '#wpretail_brand_modal',
				],
			]
		);

		wpretail()->builder->table(
			[
				'head'    => [
					'labels' => [
						'name'        => __( 'Brand', 'wpretail' ),
						'description' => __( 'Note', 'wpretail' ),
					],
					'data'   => [
						'name'        => 'brand_name',
						'description' => 'brand_description',
					],
				],
				'actions' => [
					'options'        => [
						'edit',
						'delete',
					],
					'delete_confirm' => __( 'Are you sure you want to remove?', 'wpretail' ),
					'update_confirm' => __( 'Are you sure you want to update?', 'wpretail' ),
				],
				'id'      => 'wpretail_list_brand',
				'body'    => $db->get_brand(),
				'class'   => [ 'wpretail-datatable', 'table table-primary mt-5' ],
				'col'     => 'col-md-12',

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
		$db            = new WPRetail\Db\WPRetail_Db( 'wpretail_categories' );
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
					'bs-target' => '#wpretail_category_modal',
				],
			]
		);

		wpretail()->builder->table(
			[
				'head'    => [
					'labels' => [
						'name'        => __( 'Category', 'wpretail' ),
						'short_code'  => __( 'Category Code', 'wpretail' ),
						'description' => __( 'Description', 'wpretail' ),
					],
					'data'   => [
						'name'        => 'category_name',
						'short_code'  => 'category_code',
						'description' => 'category_description',
					],
				],
				'actions' => [
					'options'        => [
						'edit',
						'delete',
					],
					'delete_confirm' => __( 'Are you sure you want to remove?', 'wpretail' ),
					'update_confirm' => __( 'Are you sure you want to update?', 'wpretail' ),
				],
				'id'      => 'wpretail_list_category',
				'body'    => $db->get_category(),
				'class'   => [ 'wpretail-datatable', 'table table-primary mt-5' ],
				'col'     => 'col-md-12',

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
	public function view_product() {

		$field_options = apply_filters( 'wpretail_form_fields_options', [] );
		$settings      = $field_options['product'];
		$db            = new WPRetail\Db\WPRetail_Db( 'wpretail_products' );

		wpretail()->builder->html(
			'button',
			[
				'id'      => 'add_product',
				'content' => __( 'Add Product' ),
				'class'   => [ 'mb-3 btn btn-primary' ],
				'closed'  => true,
				'attr'    => [ 'type' => 'button' ],
				'data'    => [
					'bs-toggle' => 'modal',
					'bs-target' => '#wpretail_product_modal',
				],
			]
		);

		wpretail()->builder->table(
			[
				'head'    => [
					'labels' => [
						'name' => __( 'Product', 'wpretail' ),
					],
					'data'   => [
						'name' => 'product_name',
					],
				],
				'actions' => [
					'options'        => [
						'edit',
						'delete',
					],
					'delete_confirm' => __( 'Are you sure you want to remove?', 'wpretail' ),
					'update_confirm' => __( 'Are you sure you want to update?', 'wpretail' ),
				],
				'id'      => 'wpretail_list_product',
				'body'    => $db->get_product(),
				'class'   => [ 'wpretail-datatable', 'table table-primary mt-5' ],
				'col'     => 'col-md-12',

			]
		);
		$args = [
			'form_args'  => [
				'id'                => 'wpretail_product',
				'class'             => [ 'wpretail-product' ],
				'attr'              => [
					'action'  => admin_url(),
					'method'  => 'post',
					'enctype' => 'multipart/form-data',
				],
				'form_title'        => __( 'Add Product', 'wpreatil' ),
				'form_submit_id'    => 'wpretail_add_product',
				'form_submit_label' => __( 'Add Product', 'wpretail' ),
				'is_modal'          => true,
				'modal'             => 'modal-md modal-dialog-centered modal-dialog-scrollable',
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

		// Brand Name.
		$db         = new WPRetail\Db\WPRetail_Db( 'wpretail_brands' );
		$brands     = $db->get_brand();
		$brand_name = [];
		foreach ( $brands as  $brand ) {
			$brand_name[ $brand->id ] = $brand->name;
		}

		// Category Name
		$db            = new WPRetail\Db\WPRetail_Db( 'wpretail_categories' );
		$categories    = $db->get_category();
		$category_name = [];
		foreach ( $categories as  $category ) {
			$category_name[ $category->id ] = $category->name;
		}
		$product = [
			'product_name'               => [
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
			'sku'                        => [
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
			'barcode_type'               => [
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
			'brand_id'                   => [
				'label' => [
					'content' => __( 'Brand' ),
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'brand_id',
					'id'      => 'brand_id',
					'options' => $brand_name,
					'has_key' => true,
				],
				'col'   => 'col-md-4',
			],
			'category_id'                => [
				'label' => [
					'content' => __( 'Category' ),
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'category_id',
					'id'      => 'category_id',
					'options' => $category_name,
					'has_key' => true,
				],
				'col'   => 'col-md-4',
			],
			'sub_category_id'            => [
				'label' => [
					'content' => __( ' Sub Category' ),
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'sub_category_id',
					'id'      => 'sub_category_id',
					'options' => $category_name,
					'has_key' => true,
				],
				'col'   => 'col-md-4',
			],
			'product_description'        => [
				'label' => [
					'content' => __( 'Product Description' ),
				],
				'input' => [
					'type' => 'textarea',
					'name' => 'product_description',
					'id'   => 'product_description',
				],
				'col'   => 'col-md-4',
			],
			'product_image'              => [
				'label' => [
					'content' => __( 'Product Image' ),
				],
				'input' => [
					'type' => 'file',
					'name' => 'product_image',
					'id'   => 'product_image',
				],
				'col'   => 'col-md-4',
			],
			'enable_product_description' => [
				'input' => [
					'type'    => 'checkbox',
					'name'    => 'enable_product_description',
					'id'      => 'enable_product_description',
					'options' => [
						'items'   => [
							'1' => __( 'Enable Product Description in Pos' ),
						],
					],
				],
				'col'   => 'col-md-4',
			],
			'not_for_selling'            => [
				'input' => [
					'type'    => 'checkbox',
					'name'    => 'not_for_selling',
					'id'      => 'not_for_selling',
					'options' => [
						'items'   => [
							'1' => __( 'Not for selling' ),
						],
						'has_key' => true,
					],
				],
				'col'   => 'col-md-4',
			],

		];
		$category = [
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
		$brand    = [
			'brand_name'        => [
				'label'       => [
					'content' => __( 'Brand Name ' ) . '*',
				],
				'input'       => [
					'type' => 'text',
					'name' => 'brand_name',
					'id'   => 'brand_name',
				],
				'validations' => [ 'required' ],
				'col'         => 'col-md-12',
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
		$warranty = [
			'warranty_name'          => [
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
			'warranty_description'   => [
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
			'warranty_duration'      => [
				'label' => [
					'content' => __( 'Warranty Duration', 'wpretail' ),
				],
				'input' => [
					'type' => 'text',
					'name' => 'warranty_duration',
					'id'   => 'warranty_duration',
				],
				'col'   => 'col-md-6 ',
			],
			'warranty_duration_type' => [
				'input' => [
					'type'    => 'select',
					'name'    => 'warranty_duration_type',
					'class'   => [ 'mt-2' ],
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
		$unit     = [
			'unit_name'          => [
				'label' => [
					'content' => __( ' Unit Name ' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'unit_name',
					'id'   => 'unit_name',
				],
				'col'   => 'col-md-12',
			],
			'unit_short_name'    => [
				'label' => [
					'content' => __( 'Short Name ' ),
				],
				'input' => [
					'type' => 'text',
					'name' => 'unit_short_name',
					'id'   => 'unit_short_name',
				],
				'col'   => 'col-md-12',
			],
			'unit_allow_decimal' => [
				'label' => [
					'content' => __( 'Allow Decimal ' ),
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'unit_allow_decimal',
					'class'   => [ 'mt-2' ],
					'id'      => 'unit_allow_decimal',
					'options' => [
						'yes' => 'Yes',
						'no'  => 'No',
					],
				],
				'col'   => 'col-md-12',
			],
		];
		return array_filter(
			array_merge(
				$field_options,
				[
					'product'  => $product,
					'category' => $category,
					'brand'    => $brand,
					'warranty' => $warranty,
					'unit'     => $unit,
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
					'product'  => [
						'name' => 'Add Product',
						'slug' => 'product',
					],
					'category' => [
						'name' => 'Categories',
						'slug' => 'category',
					],
					'brand'    => [
						'name' => 'Brands',
						'slug' => 'brand',
					],
					'warranty' => [
						'name' => 'Warranties',
						'slug' => 'warranty',
					],
					'unit'     => [
						'name' => 'Units',
						'slug' => 'unit',
					],
				]
			)
		);
	}
}
