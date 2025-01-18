<?php
declare(strict_types=1);

namespace WP_Rocket\Engine\Media\Fonts\Admin;

use WP_Rocket\Engine\Admin\Settings\Settings as AdminSettings;

class Settings {
	/**
	 * Adds the host fonts locally option to WP Rocket options array
	 *
	 * @param array $options WP Rocket options array.
	 *
	 * @return array
	 */
	public function add_option( array $options ): array {
		$options['host_fonts_locally'] = 0;

		return $options;
	}

	/**
	 * Sanitizes the option value when saving from the settings page
	 *
	 * @param array         $input    Array of sanitized values after being submitted by the form.
	 * @param AdminSettings $settings Settings class instance.
	 *
	 * @return array
	 */
	public function sanitize_option_value( array $input, AdminSettings $settings ): array {
		$input['host_fonts_locally'] = $settings->sanitize_checkbox( $input, 'host_fonts_locally' );

		return $input;
	}
}
