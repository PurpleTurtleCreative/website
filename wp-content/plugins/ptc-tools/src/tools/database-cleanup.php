<?php
/**
 * Database Cleanup tool
 *
 * Reports any straggling data that wasn't removed properly
 * from plugin uninstallation processes and the like. Also
 * provides the option to remove the leftover data.
 *
 * @since 1.0.0
 *
 * @package PTC_Tools
 */

namespace PTC_Tools\Database_Cleanup;

defined( 'ABSPATH' ) || die();

if ( empty( $_POST ) ) :
	?>

	<form method="POST">
		<input class="button" type="submit" name="ptc_tool_audit_db" value="Audit Database" />
	</form>

	<?php
elseif ( ! empty( $_POST['ptc_tool_audit_db'] ) ) :

	print_pre( get_present_taxonomies(), 'Present Taxonomies' );
	print_pre( get_registered_taxonomies(), 'Registered Taxonomies' );
	print_pre( get_inactive_taxonomies(), 'Inactive Taxonomies' );
	print_pre( get_inactive_taxonomy_terms(), 'Inactive Taxonomy Terms' );

	print_hr();

	print_pre( get_present_post_types(), 'Present Post Types' );
	print_pre( get_registered_post_types(), 'Registered Post Types' );
	print_pre( get_inactive_post_types(), 'Inactive Post Types' );
	print_pre( get_inactive_posts(), 'Inactive Post Type Posts' );
	?>

	<form method="POST">
		<input class="button button-primary" type="submit" name="ptc_tool_clean_db" value="Clean Database" />
	</form>

<?php elseif ( ! empty( $_POST['ptc_tool_clean_db'] ) ) : ?>

	<pre>
		Cleanup information
		will go here
		to display the general situation

		You can then leave this page
	</pre>

	<?php
endif;

////////////////////////////////////
///////// HELPER FUNCTIONS /////////
////////////////////////////////////

function print_hr() {
	echo '<hr />';
}

function print_pre( $data, $heading_text = '' ) {
	if ( $heading_text ) {
		echo '<h2>' . esc_html( $heading_text ) . '</h2>';
	}
	echo '<pre>' . esc_html( print_r( $data, true ) ) . '</pre>';
}

function get_present_taxonomies() {
	global $wpdb;
	return $wpdb->get_col(
		"SELECT DISTINCT(taxonomy) FROM {$wpdb->term_taxonomy}"
	);
}

function get_registered_taxonomies() {
	return array_keys( $GLOBALS['wp_taxonomies'] );
}

function get_inactive_taxonomies() {
	return array_values(
		array_diff(
			get_present_taxonomies(),
			get_registered_taxonomies()
		)
	);
}

function get_inactive_taxonomy_terms() {
	global $wpdb;

	$inactive_taxonomies = get_inactive_taxonomies();
	$inactive_taxonomies_sqlcsv = '\'' . implode( '\',\'', $inactive_taxonomies ) . '\'';

	$inactive_terms = $wpdb->get_results(
		"
		SELECT term_taxonomy_id, term_id, taxonomy
		FROM {$wpdb->term_taxonomy}
		WHERE taxonomy IN({$inactive_taxonomies_sqlcsv})
		"
	);

	return $inactive_terms;
}

function get_present_post_types() {
	global $wpdb;
	return $wpdb->get_col(
		"SELECT DISTINCT(post_type) FROM {$wpdb->posts}"
	);
}

function get_registered_post_types() {
	return array_keys( $GLOBALS['wp_post_types'] );
}

function get_inactive_post_types() {
	return array_values(
		array_diff(
			get_present_post_types(),
			get_registered_post_types()
		)
	);
}

function get_inactive_posts() {
	global $wpdb;

	$inactive_post_types = get_inactive_post_types();
	$inactive_post_types_sqlcsv = '\'' . implode( '\',\'', $inactive_post_types ) . '\'';

	$inactive_posts = $wpdb->get_col(
		"
		SELECT ID
		FROM {$wpdb->posts}
		WHERE post_type IN({$inactive_post_types_sqlcsv})
		"
	);

	return $inactive_posts;
}

function delete_inactive_post_types() {}
