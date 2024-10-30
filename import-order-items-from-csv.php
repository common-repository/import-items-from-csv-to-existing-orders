<?php
/**
 * Plugin Name: Import items from csv to Existing Orders for WooCommerce
 * Plugin URI: https://jcodex.com/import-items-from-csv-to-existing-orders-for-woocommerce/
 * Description: A tool for easily import items from csv into existing WooCommerce orders. 
 * Version: 1.0
 * Author: Jcodex
 * Author URI: https://jcodex.com
 * Text Domain: itemswcorders
 * Domain Path: /languages/
  * WC requires at least: 2.3
 * WC tested up to: 3.6.3
**/

defined( 'ABSPATH' ) || exit;


// Include the main WooCommerce class.
if ( ! class_exists( 'Import_Items_From_Csv' ) ) {
  include_once dirname( __FILE__ ) . '/inc/class-import-items-from-csv.php';
}

return new Import_Items_From_Csv;







