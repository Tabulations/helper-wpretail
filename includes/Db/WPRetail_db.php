<?php

namespace WPRetail\Db;

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
class WPRetail_Db {

	/**
	 * Table.
	 *
	 * @var mixed
	 */
	protected $table;

	/**
	 * DB.
	 *
	 * @var object $wpdb Wpdb.
	 */
	protected $db;

	/**
	 * Constructor.
	 *
	 * @param mixed $table Table.
	 */
	public function __construct( $table ) {
		global $wpdb;
		$this->db    = $wpdb;
		$this->table = $wpdb->prefix . $table;
	}

	/**
	 * Insert Data.
	 *
	 * @param mixed $args Args.
	 * @return mixed $args Args.
	 */
	public function insert( $args ) {
		return $this->db->insert(
			$this->table,
			$args
		);
	}

	/**
	 * Update Data.
	 *
	 * @param mixed $args Args.
	 * @param mixed $where Args.
	 * @return int ID.
	 */
	public function update( $args, $where ) {
		return $this->db->update(
			$this->table,
			$args,
			$where
		);
	}

		/**
		 * Brand Data.
		 *
		 * @param mixed $args Args.
		 * @param mixed $where Args.
		 * @return int ID.
		 */
	public function get_brand( $id = null ) {
		if ( ! empty( $id ) ) {
			$brand = $this->db->get_results(
				$this->db->prepare(
					"SELECT * FROM {$this->db->prefix}wpretail_brands WHERE `id` = %s",
					$id
				)
			);
		}

		return (array) $brand;
	}

		/**
		 * Brand Data.
		 *
		 * @param mixed $args Args.
		 * @param mixed $where Args.
		 * @return int ID.
		 */
	public function get_category() {
		$category_query = $this->db->get_results( 'SELECT name,short_code,description FROM wp_wpretail_categories' );
		return (array) $category_query;
	}

	/**
	 * Get Business.
	 *
	 * @param mixed $id ID.
	 * @return mixed $business Business.
	 */
	public function get_business( $id = null ) {
		if ( ! empty( $id ) ) {
			$business = $this->db->get_results(
				$this->db->prepare(
					"SELECT * FROM {$this->db->prefix}wpretail_business WHERE `id` = %s",
					$id
				)
			);
		} else {
			$business = $this->db->get_results(
				"SELECT * FROM {$this->db->prefix}wpretail_business WHERE status = TRUE"
			);
		}
		return (array) $business;
	}
}
