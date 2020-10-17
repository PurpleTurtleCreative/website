<?php

/**
 * The plugin bootstrap file
 *
 * @since             1.0.0
 * @package           Ch_Th_Gen
 *
 * @wordpress-plugin
 * Plugin Name:       Child Theme Generator
 * Plugin URI:        http://serafinocorriero.it/child-theme-generator
 * Description:       This plugin allow to generate a child theme, and all related files
 * Version:           2.2.6
 * Author:            Serafino Corriero
 * Author URI:        http://serafinocorriero.it
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       child-theme-generator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Used for referring to the plugin file or basename
if ( ! defined( 'CH_TH_GEN_FILE' ) ) {
	define( 'CH_TH_GEN_FILE', plugin_basename( __FILE__ ) );
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-child-theme-generator-activator.php
 */
function activate_ch_th_gen() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-child-theme-generator-activator.php';
	Ch_Th_Gen_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-child-theme-generator-deactivator.php
 */
function deactivate_ch_th_gen() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-child-theme-generator-deactivator.php';
	Ch_Th_Gen_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ch_th_gen' );
register_deactivation_hook( __FILE__, 'deactivate_ch_th_gen' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-child-theme-generator.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ch_th_gen() {

	$plugin = new Ch_Th_Gen();
	$plugin->run();

}
run_ch_th_gen();
