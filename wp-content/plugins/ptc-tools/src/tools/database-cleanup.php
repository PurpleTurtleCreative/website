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

	<form method="POST" style="margin:1em;">
		<input class="button" type="submit" name="ptc_tool_audit_db" value="Audit Database" />
	</form>

	<?php
elseif ( ! empty( $_POST['ptc_tool_audit_db'] ) ) :

	print_pre( get_present_taxonomies(), 'Present Taxonomies' );
	print_pre( get_registered_taxonomies(), 'Registered Taxonomies' );
	print_pre( get_inactive_taxonomies(), 'Inactive Taxonomies' );
	print_pre( get_inactive_taxonomy_terms(), 'Inactive Taxonomy Terms' );

	echo '<hr />';

	print_pre( get_present_post_types(), 'Present Post Types' );
	print_pre( get_registered_post_types(), 'Registered Post Types' );
	print_pre( get_inactive_post_types(), 'Inactive Post Types' );
	print_pre( get_inactive_post_ids(), 'Inactive Post Type Posts' );
	?>

	<form method="POST">
		<input class="button button-primary" type="submit" name="ptc_tool_clean_db" value="Clean Database" />
	</form>

	<?php
elseif ( ! empty( $_POST['ptc_tool_clean_db'] ) ) :

	/*
	Post data is purged BEFORE taxonomy data by intention.
	This is because the post data purging uses official WordPress
	functions to clean up data references while the taxonomy data
	purging is handled via custom queries. The WordPress functions
	are more thorough, so they should run before custom data
	manipulation to ensure reliability.
	*/

	print_pre( purge_inactive_post_type_data(), 'Purged Inactive Post Type Data: Deleted Posts' );

	print_pre(
		purge_inactive_taxonomy_data(),
		'Purged Inactive Taxonomy Data: Deleted Table Rows'
	);

	echo '<a class="button" href="" target="_self">Done</a>';
endif;

////////////////////////////////////
///////// HELPER FUNCTIONS /////////
////////////////////////////////////

/////////////////////////
// INACTIVE TAXONOMIES //
/////////////////////////

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
	$inactive_taxonomies_sql = array_to_sql_in_clause_values( $inactive_taxonomies );

	$inactive_terms = $wpdb->get_results(
		"
		SELECT term_taxonomy_id, term_id, taxonomy
		FROM {$wpdb->term_taxonomy}
		WHERE taxonomy IN({$inactive_taxonomies_sql})
		",
		\ARRAY_A
	);

	return $inactive_terms;
}

function purge_inactive_taxonomy_data() {
	global $wpdb;

	$inactive_taxonomy_terms = get_inactive_taxonomy_terms();

	$inactive_term_taxonomy_ids_sql = array_to_sql_in_clause_values(
		get_distinct_column_values(
			$inactive_taxonomy_terms,
			'term_taxonomy_id'
		)
	);

	$inactive_term_ids_sql = array_to_sql_in_clause_values(
		get_distinct_column_values(
			$inactive_taxonomy_terms,
			'term_id'
		)
	);

	$inactive_taxonomies_sql = array_to_sql_in_clause_values(
		get_distinct_column_values(
			$inactive_taxonomy_terms,
			'taxonomy'
		)
	);

	$deleted_table_rows = array();

	$deleted_table_rows[ $wpdb->term_relationships ] = $wpdb->query(
		"DELETE FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN({$inactive_term_taxonomy_ids_sql})"
	);

	$deleted_table_rows[ $wpdb->termmeta ] = $wpdb->query(
		"DELETE FROM {$wpdb->termmeta} WHERE term_id IN({$inactive_term_ids_sql})"
	);

	$deleted_table_rows[ $wpdb->terms ] = $wpdb->query(
		"DELETE FROM {$wpdb->terms} WHERE term_id IN({$inactive_term_ids_sql})"
	);

	$deleted_table_rows[ $wpdb->term_taxonomy ] = $wpdb->query(
		"DELETE FROM {$wpdb->term_taxonomy} WHERE taxonomy IN({$inactive_taxonomies_sql})"
	);

	return $deleted_table_rows;
}

/////////////////////////
// INACTIVE POST TYPES //
/////////////////////////

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

function get_inactive_post_ids() {
	global $wpdb;

	$inactive_post_types = get_inactive_post_types();
	$inactive_post_types_sql = array_to_sql_in_clause_values( $inactive_post_types );

	$inactive_post_ids = $wpdb->get_col(
		"
		SELECT ID
		FROM {$wpdb->posts}
		WHERE post_type IN({$inactive_post_types_sql})
		"
	);

	return $inactive_post_ids;
}

function purge_inactive_post_type_data() {

	$deleted_posts_count = 0;

	foreach ( get_inactive_post_ids() as $post_id ) {
		if ( wp_delete_post( $post_id, true ) ) {
			++$deleted_posts_count;
		}
	}

	return $deleted_posts_count;
}

///////////////////////
// GENERIC UTILITIES //
///////////////////////

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
