<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://serafinocorriero.it
 * @since      1.0.0
 *
 * @package    Ch_Th_Gen
 * @subpackage Ch_Th_Gen/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ch_Th_Gen
 * @subpackage Ch_Th_Gen/includes
 * @author     Serafino Corriero <sercorrie@gmail.com>
 */
class Ch_Th_Gen_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'child-theme-generator',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}
