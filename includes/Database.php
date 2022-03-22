<?php

namespace WPRetail;

/**
 * Class Database.
 *
 * @since 1.0.0
 */

class Database {

	/**
	 * The name of the database connection to use.
	 *
	 * @since 1.0.0
	 *
	 * @var wpdb
	 */
	protected $connection;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		global $wpdb;
		$this->connection = $wpdb;
	}

	/**
	 * Get table name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $table Table Name.
	 *
	 * @return string
	 */
	public function get_table_name( $table ) {
		return $this->connection->prefix . $table;
	}

	/**
	 *  To Show the data.
	 *
	 * @since 1.0.0
	 */
	public function show( $field = '*', $value, $table, $where, $like, $orderby, $order, $limit ) {
		$table = $this->get_table_name( $table );
		$query = [];
		$query = $this->connection()->prepare( "SELECT {$field} FROM {$table}" );
		$where = [
			[
				'field'     => $field,
				'value'     => $value,
				'condition' => 'LIKE',
			],
		];

		if ( ! empty( $limit ) ) {
			$query[] = $this->connection()->prepare( 'LIMIT %d', absint( $limit ) );
		}

		$order       = 'DESC' === strtoupper( $order ) ? 'DESC' : 'ASC';
		$orderby_sql = sanitize_sql_orderby( "{$orderby} {$order}" );
		$query[]     = "ORDER BY {$orderby_sql}";

		$sql = $this->connection()->get_results( implode( ' ', $query ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $sql;
	}

	/**
	 *  To insert the data.
	 *
	 * @since 1.0.0
	 */
	public function insert( $table, $value ) {
		$table = $this->get_table_name( $table );
		$sql   = $this->connection->insert(
			$table,
			[
				'value' => $value,
			],
			[ '%s' ]
		);
		return $sql;
	}

	/**
	 *  To update the data.
	 *
	 * @since 1.0.0
	 */
	public function update( $table, $value ) {
		$table = $this->get_table_name( $table );
		$sql   = $this->connection->update(
			$table,
			[
				'value' => $value,
			],
			[ '%s' ]
		);
		return $sql;
	}

	/**
	 *  To delete the data.
	 *
	 * @since 1.0.0
	 */
	public function delete( $table, $value ) {
		$table = $this->get_table_name( $table );
		$sql   = $this->connection->delete( $table, [ 'value' => $value ], [ '%d' ] );
		return $sql;
	}
}
