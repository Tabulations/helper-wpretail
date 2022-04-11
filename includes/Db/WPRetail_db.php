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
	public function get_brand() {
		$brand_query = $this->db->get_results( 'SELECT name,description FROM wp_wpretail_brands' );
		return (array) $brand_query;
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
}
