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
class WPRetail_install {

	/**
	 * Install EVF
	 *
	 * @since 1.0.0
	 */
	public static function install() {
		if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'wpretail_installing' ) ) {
			return;
		}

		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient( 'wpretail_installing', 'yes', MINUTE_IN_SECONDS * 10 );
		evf_maybe_define_constant( 'WPRETAIL_INSTALLING', true );
		self::remove_admin_notices();
		self::create_options();
		self::create_tables();

		delete_transient( 'wpretail_installing' );

		do_action( 'wpretail_flush_rewrite_rules' );
		do_action( 'wpretail_installed' );
		die();
	}

		/**
		 * Reset any notices added to admin.
		 */
	public function remove_admin_notices() {

	}

	/**
	 * Default options.
	 *
	 * Sets up the default options used on the settings page.
	 */
	public function create_options() {

	}

		/**
		 * Set up the database tables which the plugin needs to function.
		 */
	public function create_tables() {
		global $wpdb;
		$wpdb->hide_errors();

		/**
		 * Change wp_wpretail_sessions schema to use a bigint auto increment field
		 * instead of char(32) field as the primary key. Doing this change primarily
		 * as it should reduce the occurrence of deadlocks, but also because it is
		 * not a good practice to use a char(32) field as the primary key of a table.
		 */
		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}wpretail_sessions'" ) ) {
			if ( ! $wpdb->get_var( "SHOW KEYS FROM {$wpdb->prefix}wpretail_sessions WHERE Key_name = 'PRIMARY' AND Column_name = 'session_id'" ) ) {
				$wpdb->query(
					"ALTER TABLE `{$wpdb->prefix}wpretail_sessions` DROP PRIMARY KEY, DROP KEY `session_id`, ADD PRIMARY KEY(`session_id`), ADD UNIQUE KEY(`session_key`)"
				);
			}
		}
		dbDelta( self::get_schema() );
	}

	private static function get_schema() {
		global $wpdb;
		$charset_collate = '';

		$charset_collate = $wpdb->get_charset_collate();
	}

	$tables = "
		CREATE TABLE {$wpdb->prefix}evf_entries (
			entry_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			form_id BIGINT UNSIGNED NOT NULL,
			user_id BIGINT UNSIGNED NOT NULL,
			user_device varchar(100) NOT NULL,
			user_ip_address VARCHAR(100) NULL DEFAULT '',
			referer text NOT NULL,
			fields longtext NULL,
			status varchar(20) NOT NULL,
			viewed tinyint(1) NOT NULL DEFAULT '0',
			starred tinyint(1) NOT NULL DEFAULT '0',
			date_created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY  (entry_id),
			KEY form_id (form_id)
		) $charset_collate;
		CREATE TABLE {$wpdb->prefix}evf_entrymeta (
			meta_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			entry_id BIGINT UNSIGNED NOT NULL,
			meta_key varchar(255) default NULL,
			meta_value longtext NULL,
			PRIMARY KEY  (meta_id),
			KEY entry_id (entry_id),
			KEY meta_key (meta_key(32))
		) $charset_collate;
		CREATE TABLE {$wpdb->prefix}wpretail_sessions (
			session_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			session_key char(32) NOT NULL,
			session_value longtext NOT NULL,
			session_expiry BIGINT UNSIGNED NOT NULL,
			PRIMARY KEY  (session_id),
			UNIQUE KEY session_key (session_key)
		) $charset_collate;
	";

	return $tables;
}

/**
 * Return a list of WPREATAIL tables. Used to make sure all UM tables are dropped when uninstalling the plugin
 * in a single site or multi site environment.
 *
 * @return array UM tables.
 */
public static function get_tables() {
	$charset_collate = '';

	if ( $wpdb->has_cap( 'collation' ) ) {
		$charset_collate = $wpdb->get_charset_collate();
	}

		$tables = "
			CREATE TABLE {$wpdb->prefix}business (
				id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
				name text NOT NULL,
				currency_id BIGINT UNSIGNED NOT NULL,
				start_date NULL,
				tax_number_1 VARCHAR(100) NOT NULL ,
				tax_label_1 VARCHAR(10) NOT NULL,
				tax_number_1 VARCHAR(100) NULL ,
				tax_label_1 VARCHAR(10) NULL,
				default_profit_percent FLOAT(5,2) DEFAULT '0',
				owner_id BIGINT UNSIGNED,
				FOREIGN_KEY(owner_id) REFERENCES users(id)  ON DELETE CASCADE,
				time_zone text DEFAULT 'Asia/Kathmandu',
				fiscal_year_start_month TINYINT DEFAULT '1',
				accounting_method ENUM('fifo','lifo','avco') DEFAULT 'fifo',
				default_sale_discount DECIMAL(5,2) NULL,
				sell_price_tax ENUM('includes','excludes') DEFAULT 'includes';
				FOREIGN_KEY(currency_id) REFERENCES currencies(id);
				logo text NULL,
				sku_prefix text NULL,
				enable_tooltip BOOLEAN DEFAULT '1',
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
				updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			) $charset_collate;
			CREATE TABLE {$wpdb->prefix}business_location(
				id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
				business_id BIGINT UNSIGNED NOT NULL,
				FOREIGN_KEY('business_id') REFERENCES business(id) ON DELETE CASCADE,
				name VARCHAR(256) NOT NULL,
				landmark text NULL,
				country VARCHAR(100) NOT NULL,
				state VARCHAR(100) NOT NULL,
				city VARCHAR(100) NOT NULL,
				zip_code VARCHAR NOT NULL,
				mobile VARCHAR NUll,
				alternate_number VARCHAR(10) NUll,
				email VARCHAR NUll,
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
				updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			) $charset_collate;
		";

		return $tables;
}
