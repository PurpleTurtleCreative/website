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

namespace PTC_Tools;

defined( 'ABSPATH' ) || die();

if ( empty( $_POST ) ) :
	?>

	<form method="POST">
		<input type="submit" name="ptc_tool_audit_db" value="Audit Database" />
	</form>

<?php elseif ( ! empty( $_POST['ptc_tool_audit_db'] ) ) : ?>

	<pre>
		Audit information
		will go here
		to display the general situation

		You can then confirm by clicking
		the "Clean Database" button below
	</pre>

	<form method="POST">
		<input type="submit" name="ptc_tool_clean_db" value="Clean Database" />
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
