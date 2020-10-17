<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://serafinocorriero.it
 * @since      1.0.0
 *
 * @package    Ch_Th_Gen
 * @subpackage Ch_Th_Gen/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ch_Th_Gen
 * @subpackage Ch_Th_Gen/admin
 * @author     Serafino Corriero <sercorrie@gmail.com>
 */

class Ch_Th_Gen_Admin {

	/**
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'ch_th_gen';

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    	1.0.0
	 * @param      string    $plugin_name   The name of this plugin.
	 * @param      string    $version    	The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ch_Th_Gen_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ch_Th_Gen_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/child-theme-generator-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ch_Th_Gen_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ch_Th_Gen_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/child-theme-generator-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Add a menu page settings
	 *
	 * @since  1.0.0
	 */
	public function create_menu() {
		add_submenu_page( 
			'options-general.php', // parent-menu
			esc_html__('Child Themes Generator', 'child-theme-generator'), // $page_title:(string) (Required) The text to be displayed in the title tags of the page when the menu is selected.
			'Child-Theme Gen', // $menu_title (string) (Required) The text to be used for the menu.
			'manage_options', // $capability (string) (Required) The capability required for this menu to be displayed to the user.
			$this->plugin_name, // $menu_slug (string) (Required) The slug name to refer to this menu by (should be unique for this menu).
			array($this, 'display_options_page'), // $function (callable) (Optional) The function to be called to output the content for this page Default value: ''
			60 // $position (int) (Optional) The position in the menu order this one should appear. Default value: null
			);
	}


	/**
	* Render the options page for plugin
	*
	* @since  1.0.0
	*/
	public function display_options_page() {
		include_once 'partials/child-theme-generator-admin-display.php';
	}


	// Add a General Section and Settings Fields
	public function register_setting() {
		add_settings_section(
			$this->option_name . '_setting_section', //$id (string) (Required) Slug-name to identify the section. Used in the 'id' attribute of tags.
			'', // $title (string) (Required) Formatted title of the section. Shown as the heading for the section.
			array( $this, $this->option_name . '_section_text_intro' ), // $callback (callable) (Required) Function that echos out any content at the top of the section (between heading and fields).
			$this->plugin_name //$page name (string) (Required) The slug-name of the settings page on which to show the section. Create your own using add_options_page() or add_menu_page();
			);
	}

	/**
	 * Render the text for the general section
	 *
	 * @since  1.0.0
	 */
	public function ch_th_gen_section_text_intro() {
		// write here about this plugin

	}
	/**
	 * Display section create
	 *
	 * @since  1.0.0
	 */
	public function section_create() {
		if( isset( $_POST[ 'hidden_switch' ] ) && $_POST['activate']=='yes' ) {
			switch_theme( $_POST[ 'hidden_switch' ] );
		}

		if( isset($_POST[ 'hidden_field' ]) ) {
			$new_child_theme = array(
				'parent'      => $_POST['parent'],
				'title'       => sanitize_text_field($_POST['title']),
				'theme-url'   => sanitize_text_field($_POST['description']),
				'author'      => sanitize_text_field($_POST['author']),
				'author-url'  => sanitize_text_field($_POST['author-url']),
				'description' => sanitize_text_field($_POST['description']),
				'template' 	  => sanitize_text_field($_POST['parent']),
				'version'     => sanitize_text_field($_POST['version']),
				'include-gpl' => $_POST['include-gpl'],
				'text-domain' => sanitize_title($_POST['title']),
				);

			if ( !empty( $new_child_theme['title'] ) ) {
				// then remove all chars except numbers
				$new_child_theme['title'] =  preg_replace("/[^A-Za-z0-9]/", "", $new_child_theme['title']);
				// remove number at the begginnig on string
				$new_child_theme['title'] = ltrim($new_child_theme['title'], "0..9");
				$new_child_theme['text-domain'] = $new_child_theme['title'];
				echo "<p>ltrim: " . $new_child_theme['title'] . "</p>";
			} else {
				$new_child_theme['text-domain']  = $new_child_theme['parent'] . "-child";
				$new_child_theme['title'] = $new_child_theme['parent'] . " child theme";		
			}

			$new_child_theme['description'] = empty( $new_child_theme['description'] ) ? __('Write here a brief description about your child-theme', 'child-theme-generator') : $new_child_theme['description'];
			$new_child_theme['author'] = empty( $new_child_theme['author'] ) ? __('Write here the author\'s name', 'child-theme-generator') : $new_child_theme['author'];
			$new_child_theme['author-url'] = empty( $new_child_theme['author-url'] ) ? __('Write here the author\'s blog or website url', "child-theme-generator") : $new_child_theme['author-url'];
			$new_child_theme['version'] = empty( $new_child_theme['version'] ) ? '1.0' : $new_child_theme['version'];

			$results = Ch_Th_Gen_Functions::files_generation( $new_child_theme, $results );

			// show errors
			if ( $results['alert'] == -1 ) {
				?>
				<div class="pad1">
					<?php
					$err_count = 0;
					foreach ($results as $steps => $message) {
						if ($steps != 'alert') {
							echo $results[$steps];
						}

					}
					esc_html_e('Please correct before continue', 'child-theme-generator' );
					?>
					<p><a class = 'button-primary' href="<?php echo get_admin_url() . 'admin.php?page=child-theme-generator&tab=create'; ?>"><?php esc_html_e('Try again?', 'child-theme-generator'); ?></a></p>
				</div>
				<?php
			} else {
				// all fine
				?>
				<div class="pad1">
					<?php
					foreach ($results as $steps => $message) {
						if ($steps != 'alert')
							echo $results[$steps];
					}
					?>
				</div>
				<div class="pad1">
					<?php
					printf( esc_html__( 'Child Theme %1$s %2$s %3$s has been created successfully!', 'child-theme-generator'), "<b>", $new_child_theme['title'], "</b>");
					?>
					<form method="post" action="" >
						<input type="hidden" name="hidden_switch" value="<?php echo $new_child_theme['text-domain']; ?>">
						<p><input type="checkbox" name="activate" value="yes"><?php esc_html_e("Activate", "child-theme-generator") . " <b>" . $new_child_theme['title'] . "</b>"?> child-theme? </p>
						<p class="submit">
							<input id="activate" type="submit" name="" class="button-primary" value="<?php esc_html_e('Finished', 'child-theme-generator'); ?>" /></p>
					</form>
				</div>				
				<?php
			}
		} else {
			Ch_Th_Gen_Functions::child_themes_setup();
		}
	}


	/**
	 * Display section remove
	 *
	 * @since  1.0.0
	 */
	public function section_remove() {
		if( isset( $_POST['btn-confirm-remove'] ) ) {
			$response = Ch_Th_Gen_Functions::delete_theme_folder( $_POST[ 'folder_to_remove' ], $_POST[ 'parent_to_restore' ] );
			// show errors
			if ( $response['alert'] == -1 ) {
				echo "<div class='pad1'>";
				$err_count = 0;
				foreach ($response as $steps => $message) {
					if ($steps != 'alert') {
						echo $response[$steps];
						esc_html_e('Please correct before continue', 'child-theme-generator');
						?>
						<p><a class = 'button-primary' href="<?php echo get_admin_url() . 'admin.php?page=child-theme-generator&tab=remove'; ?>"><?php esc_html_e("Try again?", "child-theme-generator"); ?></a></p>
						<?php
					}
				}
				echo "</div>";
				} else {
				// all fine
					?>
					<div class="pad1">
						<?php
						foreach ($response as $steps => $message) {
							if ($steps != 'alert')
								echo $response[$steps];
						}
						printf( esc_html__( 'Child Theme %1$s %2$s %3$s has been removed successfully!', 'child-theme-generator'), "<b>", $_POST[ 'folder_to_remove' ], "</b>");

						?>
						<p><a class='button-primary' href="<?php echo get_admin_url() . 'admin.php?page=child-theme-generator&tab=remove'; ?>" ><?php esc_html_e(" Finished", "child-theme-generator"); ?> </a></p>
					</div>
					<?php
				}
			} else {
			// display remove tab
				Ch_Th_Gen_Functions::remove_child_theme();
			}
		}
	/**
	 * Renders Settings Tabs
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function ch_th_gen_render_tabs() {
		$plugin_settings_tabs['create'] = esc_html__('Create New', 'child-theme-generator');
		$plugin_settings_tabs['remove'] = esc_html__('Remove Child Theme', 'child-theme-generator');
		
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'create';
		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' .  $active . '" href="?page=' . $this->plugin_name . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
		}
		echo '</h2>';
	}

	/**
	 * Adds a 'create' link at the plugin admin page
	 *
	 * @since 		1.0.0
	 * @param 		array 		$links 		The current array of links
	 * @return 		array 					The modified array of links
	 */
	public function add_create_link( $links ) {
		$create_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'admin.php?page=' . $this->plugin_name . '&tab=create' ) ),
			esc_html__('Create', 'child-theme-generator')
			);
		array_unshift( $links, $create_link );
		return $links;
	}

}
