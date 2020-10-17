<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://serafinocorriero.it
 * @since      1.0.0
 *
 * @package    Ch_Th_Gen
 * @subpackage Ch_Th_Gen/admin/partials
 */
$plugin_admin = new Ch_Th_Gen_Admin( $plugin_name, $version );
$plugin_functions = new Ch_Th_Gen_Functions();
//flush rewrite rules when we load this page!
flush_rewrite_rules();
?>
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php
	$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'create';
	$this->ch_th_gen_render_tabs();
	?>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="postbox-container-2" class="postbox-container">
				<?php settings_fields(  $this->plugin_name ); ?>
				<?php do_settings_sections(  $this->plugin_name ); ?>
				<?php
				// Detect if root theme folder is writable
				$theme_path=get_theme_root() . '/';
				clearstatcache();
				$perms_path=substr( sprintf( '%o', fileperms( $theme_path ) ), -4);
				if ( !is_writable( $theme_path ) ) {
					echo '<div class="error"><br /><span class="dashicons dashicons-dismiss"></span>';
					printf( esc_html__(' Fatal error: cannot continue because the theme root folder is write-protected - permissions detected: %1$s', 'child-theme-generator'), $perms_path );
					echo "<br /><br />";
					printf( esc_html__('Tips: change your folder\'s permissions to writing on %1$s %2$s %3$s before continue.', 'child-theme-generator'),'<em><b>', $theme_path, '</b></em>' );
					echo '<br /><br /></div>';
					die();
				}
				switch ($tab):
				case 'remove': ?>
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox ">
						<div class="inside">
							<?php $plugin_admin->section_remove(); ?>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				<?php
				break;
				// If no tab or create
				default: 
				?>
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox ">
						<div class="inside">
							<?php $plugin_admin->section_create(); ?>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				<?php
				break;
				endswitch ?>

			</div>
			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox ">
						<div class="inside">
							<?php
							printf( esc_html__('%1$s How to use this plugin %2$s', 'child-theme-generator'), '<h3>', '</h3>');
							$image_url= plugins_url() . '/child-theme-generator/admin/img/page1.png';
							$post_link='http://www.serafinocorriero.it/child-theme-generator/';
							echo "<a href=$post_link target='blank'><img src=$image_url alt='page-link' width='100%'></a>";
							?>
						</div>
					</div>
				</div>
				<div class="meta-box-sortables ui-sortable">
					<div class="meta-box-sortables ui-sortable">
						<div class="postbox ">
							<div class="inside">
								<div class="ctg-btn">
									<?php
									printf( esc_html__('%1$s Follow me & Review %2$s', 'child-theme-generator'), '<h3>', '</h3>'); ?>
									<!-- Facebook button -->
									<div id="fb-root"></div>
									<div class="fb-share-button" data-href="http://www.serafinocorriero.it/child-theme-generator/" data-layout="button_count" data-size="small" data-mobile-iframe="false"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.serafinocorriero.it%2Fchild-theme-generator%2F&amp;src=sdkpreparse">Share</a></div>
								</div>
								<div class="ctg-btn">
									<!-- Twitter button -->
									<a href="https://twitter.com/SerafinoCorrier" class="twitter-follow-button" data-show-count="false" data-size="small">Follow @SerafinoCorrier</a>
									<br />
								</div>
								<div class="ctg-btn">
									<!-- WordPress Rating -->
									<div style="float:left;">
									<?php 
									$star_url= plugins_url() . '/child-theme-generator/admin/img/star.png';
									$review_link='https://wordpress.org/support/plugin/child-theme-generator/reviews/#plugin-info';									
									echo "<a href=$review_link target='blank'><img src=$star_url alt='WordPress Reviews'></a><span class='chThGenStar'>";
									?>
									</div>
									<div>
									<?php
									printf( esc_html__('Your feedback and review are both important, %1$s Rate this plugin! %2$s', 'child-theme-generator'), '<a href=' . $review_link .' target="blank">', '</a></span>' );
									 ?>
									</div>
									<div class="clear">
								</div>
							</div>
						</div>
					</div>

				</div>

				<div class="meta-box-sortables ui-sortable">
					<div class="postbox ">
						<div class="inside">
							<p>
								<?php esc_html_e( 'Thank you for using Child Theme Generator. This plugin is free and you can use it whenever you wish. If you have appreciated my work, consider a small donation to show your appreciation. Thanks! ', 'child-theme-generator');?>
								<br />
								<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
									<input type="hidden" name="cmd" value="_s-xclick">
									<input type="hidden" name="hosted_button_id" value="UNQC9QFV64XH6">
									<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
									<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
								</form>
							</p>
						</div>
					</div>
				</div>
				<div class="meta-box-sortables ui-sortable" style="display:none;">
					<div id="itsec_security_updates" class="postbox ">
						<div class="inside">
							<!-- Begin MailChimp Signup Form -->
							<link href="//cdn-images.mailchimp.com/embedcode/slim-10_7.css" rel="stylesheet" type="text/css">
							<div id="mc_embed_signup">
								<?php
								printf( esc_html__('%1$s Keep in touch %2$s', 'child-theme-generator'), '<h3>', '</h3>');
								printf( esc_html__( 'This plugins has been coded with the best practices, it will not slow down your website or spam your database.', 'child-theme-generator'), '<br /><br />');
								printf( esc_html__('Subscribe to get notificed once new plugin is out. No spam, just one mail per one new plugin.%1$s', 'child-theme-generator'), '<br />') ;
								?>
								<form action="//serafinocorriero.us14.list-manage.com/subscribe/post?u=36743ae007e3020c68fe9b2e1&amp;id=af9acf8548" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
									<div id="mc_embed_signup_scroll">
										<label for="mce-EMAIL"><?php esc_html_e('Subscribe to my mailing list', 'child-theme-generator'); ?></label>
										<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="<?php esc_html_e('Email address', 'child-theme-generator'); ?>" required>
										<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
										<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_36743ae007e3020c68fe9b2e1_af9acf8548" tabindex="-1" value=""></div>
										<div class="clear"><input type="submit" value="<?php esc_html_e('Subscribe', 'child-theme-generator'); ?>" name="subscribe" id="mc-embedded-subscribe" class="button-primary"></div>
									</div>
								</form>
							</div>
							<!--End mc_embed_signup-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
