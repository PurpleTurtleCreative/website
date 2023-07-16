<?php
/**
 * Database Cleanup tool
 *
 * Reports any straggling data that wasn't removed properly
 * from plugin uninstallation processes and the like. Also
 * provides the option to remove the leftover data.
 *
 * @since 1.1.0
 *
 * @package PTC_Tools
 */

namespace PTC_Tools\Cron_Audit;

defined( 'ABSPATH' ) || die();

if ( empty( $_POST ) ) :
	?>

	<form method="POST" style="margin:1em;">
		<input class="button" type="submit" name="ptc_tool_audit_cron" value="Audit WP Cron" />
	</form>

	<?php
elseif ( ! empty( $_POST['ptc_tool_audit_cron'] ) ) :

	$scheduled_hooks = get_scheduled_hooks();

	print_pre( $scheduled_hooks, 'WP Cron Hooks' );
	print_pre( get_no_callback( $scheduled_hooks ), 'No Callback' );
	?>
	<hr />
	<h2>Pro Tip</h2>
	<p>You can use WP CLI to easily remove cron events. (<a href="https://developer.wordpress.org/cli/commands/cron/event/" target="_blank">Documentation</a>)</p>
	<pre style="
		background: white;
		width: fit-content;
		padding: 0.5em 1.5em;
		border-radius: 0.3em;
		border: 1px solid #ccc;
	">wp cron event delete hook_name</pre>
	<pre style="
		background: white;
		width: fit-content;
		padding: 0.5em 1.5em;
		border-radius: 0.3em;
		border: 1px solid #ccc;
	">wp cron event unschedule hook_name</pre>
	<?php
endif;

// //////////////////////////////// //
// /////// HELPER FUNCTIONS /////// //
// //////////////////////////////// //

function get_cron_array() {
	return get_option( 'cron', array() );
}

function get_scheduled_hooks() {
	$hooks = array();
	foreach ( get_cron_array() as $due => &$cron ) {
		if ( is_array( $cron ) ) {
			foreach ( $cron as $hook => &$data ) {
				$hooks[] = $hook;
			}
		}
	}
	return $hooks;
}

function get_no_callback( array $hooks ) {
	$no_callback = array();
	foreach ( $hooks as &$hook ) {
		if ( false === has_action( $hook ) ) {
			$no_callback[] = $hook;
		}
	}
	return $no_callback;
}

// ///////////////// //
// GENERIC UTILITIES //
// ///////////////// //

function print_pre( $data, $heading_text = '' ) {
	if ( $heading_text ) {
		echo '<h2>' . esc_html( $heading_text ) . '</h2>';
	}
	echo '<pre>' . esc_html( print_r( $data, true ) ) . '</pre>';
}

function array_to_sql_in_clause_values( array $values ) {
	return '\'' . implode( '\',\'', array_map( 'esc_sql', $values ) ) . '\'';
}

function get_distinct_column_values( array $rows, string $column_key ) {
	return array_values(
		array_unique(
			array_column( $rows, $column_key )
		)
	);
}
