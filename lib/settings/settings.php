<?php
namespace Podlove\Settings;

class Settings {

	static $pagehook;
	
	public function __construct( $handle ) {
		
		Settings::$pagehook = add_submenu_page(
			/* $parent_slug*/ $handle,
			/* $page_title */ 'Settings',
			/* $menu_title */ 'Settings',
			/* $capability */ 'administrator',
			/* $menu_slug  */ 'podlove_settings_settings_handle',
			/* $function   */ array( $this, 'page' )
		);

		add_settings_section(
			/* $id 		 */ 'podlove_settings_general',
			/* $title 	 */ \Podlove\t( 'General Settings' ),	
			/* $callback */ function () { /* section head html */ }, 		
			/* $page	 */ Settings::$pagehook	
		);
		
		add_settings_field(
			/* $id       */ 'podlove_setting_merge_episodes',
			/* $title    */ sprintf(
				'<label for="merge_episodes">%s</label>',
				\Podlove\t( 'Display episodes on front page together with blog posts' )
			),
			/* $callback */ function () {
				?>
				<input name="podlove[merge_episodes]" id="merge_episodes" type="checkbox" <?php checked( \Podlove\get_setting( 'merge_episodes' ), 'on' ) ?>>
				<?php
			},
			/* $page     */ Settings::$pagehook,  
			/* $section  */ 'podlove_settings_general'
		);
		
		add_settings_section(
			/* $id 		 */ 'podlove_settings_modules',
			/* $title 	 */ \Podlove\t( 'Modules' ),	
			/* $callback */ function () { /* section head html */ }, 		
			/* $page	 */ Settings::$pagehook	
		);

		$modules = \Podlove\Modules\Base::get_all_module_names();
		foreach ( $modules as $module_name ) {
			$class = \Podlove\Modules\Base::get_class_by_module_name( $module_name );
			$module = new $class;

			add_settings_field(
				/* $id       */ 'podlove_setting_module_' . $module_name,
				/* $title    */ sprintf(
					'<label for="' . $module_name . '">%s</label>',
					$module->get_module_name()
				),
				/* $callback */ function () use ( $module, $module_name ) {
					?>
					<label for="<?php echo $module_name ?>">
						<input name="podlove_active_modules[<?php echo $module_name ?>]" id="<?php echo $module_name ?>" type="checkbox" <?php checked( \Podlove\Modules\Base::is_active( $module_name ), true ) ?>>
						<?php echo $module->get_module_description() ?>
					</label>
					
					<?php
				},
				/* $page     */ Settings::$pagehook,  
				/* $section  */ 'podlove_settings_modules'
			);

		}

		register_setting( Settings::$pagehook, 'podlove' );
		register_setting( Settings::$pagehook, 'podlove_active_modules' );
	}
	
	function page() {
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2><?php echo __( 'Settings' ) ?></h2>

			<form method="post" action="options.php">
				<?php settings_fields( Settings::$pagehook ); ?>
				<?php do_settings_sections( Settings::$pagehook ); ?>
				
				<?php submit_button( __( 'Save Changes' ), 'button-primary', 'submit', TRUE ); ?>
			</form>
		</div>	
		<?php
	}
	
}