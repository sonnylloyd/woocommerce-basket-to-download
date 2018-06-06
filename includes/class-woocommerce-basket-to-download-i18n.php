<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.linkedin.com/in/sonnylloyduk/
 * @since      1.0.0
 *
 * @package    Woocommerce_Basket_To_Download
 * @subpackage Woocommerce_Basket_To_Download/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Basket_To_Download
 * @subpackage Woocommerce_Basket_To_Download/includes
 * @author     sonny lloyd <echromlog@gmail.com>
 */
class Woocommerce_Basket_To_Download_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woocommerce-basket-to-download',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
