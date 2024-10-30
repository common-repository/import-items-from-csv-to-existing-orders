<?php
/**
 * Import CSV Items
 *
 * @package import-order-items-from-csv
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Class.
 *
 * @class Import_Items_From_Csv
 */
class Import_Items_From_Csv {

	public $version = '1.0';

	public function __construct() {

		$this->define_constants();
		$this->includes();
	}

	private function define_constants() {

		$upload_dir = wp_upload_dir( null, false );

		$this->define( 'Importcsv_ABSPATH', dirname(__FILE__) . '/' );
		$this->define( 'Importcsv_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'Importcsv_VERSION', $this->version );
		
	}

	public function includes() {

		/**
		 * Class autoloader.
		 */
		include_once Importcsv_ABSPATH . '/class-importcsv-helper-functions.php';
		include_once Importcsv_ABSPATH . '/class-importcsv-frontend.php';
	}


	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}


	
}