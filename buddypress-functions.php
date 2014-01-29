<?php
/**
 * The new template pack for BuddyPress.
 *
 * @package BuddyPress
 * @subpackage BuddyPress Templates
 * @since BuddyPress (1.7)
 *
 * Code and format borrowed from Turtleshell : props @djPaul, @r-a-y
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Temporary: add a version number to footer so people can find out what release they're using
function templatepack_version_number() {
	echo "\n\n<!-- templates: alpha 1 -->\n\n";
}
add_action( 'wp_footer', 'templatepack_version_number' );

if ( ! class_exists( 'BP_Templates' ) ) :

/**
 * Loads the BuddyPress template pack
 * See @link BP_Theme_Compat() for more.
 *
 * @since buddypress  (1.7)
 */
class BP_Templates extends BP_Theme_Compat {

	/**
	 * Constructor
	 *
	 * @since BuddyPress templates (1.0)
	 */
	public function __construct() {

		$this->setup_globals();
		$this->setup_actions();
	}

	/**
	 * Component global variables
	 *
	 * You'll want to customize the values in here, so they match whatever your
	 * needs are.
	 *
	 * @since BuddyPress (1.7)
	 */
	protected function setup_globals() {
		$bp            = buddypress();
		$this->id      = 'templatepack';
		$this->name    = __( 'BuddyPress Templates', 'buddypress' );
		$this->version = bp_get_version();
		$this->dir     = plugin_dir_path( __FILE__ );
		$this->url     = plugin_dir_url( __FILE__ );
	}

	/**
	 * Hooks into required actions and filters to set up the template pack
	 *
	 * @since BuddyPress (1.7)
	 */
	protected function setup_actions() {
		add_action( 'bp_enqueue_scripts',   array( $this, 'enqueue_styles'         ) ); // Enqueue theme CSS
		add_action( 'bp_enqueue_scripts',   array( $this, 'enqueue_scripts'        ) ); // Enqueue theme JS
		add_action( 'widgets_init',         array( $this, 'widgets_init'           ) ); // Widgets		
		add_filter( 'body_class',           array( $this, 'add_nojs_body_class'    ), 20, 1 );
		// Run an action for for third-party plugins to affect the template pack
		do_action_ref_array( 'bp_theme_compat_actions', array( &$this ) );
	}

	/**
	 * Enqueue template pack CSS
	 *
	 * @since BuddyPress (1.7)
	 */
	public function enqueue_styles() {
		// LTR or RTL
		$file = is_rtl() ? 'css/buddypress-rtl.css' : 'css/buddypress.css';
		$shamefile = 'css/shame.css';
		$shamehandle = 'shame-css';

		// Check child theme
		if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $file ) ) {
			$location = trailingslashit( get_stylesheet_directory_uri() );
			$handle   = 'bp-child-css';

		// Check parent theme
		} elseif ( file_exists( trailingslashit( get_template_directory() ) . $file ) ) {
			$location = trailingslashit( get_template_directory_uri() );
			$handle   = 'bp-parent-css';

		// BuddyPress Theme Compatibility
		} else {
			$location = trailingslashit( $this->url );
			$handle   = 'bp-templatepack-css';
		}

		wp_enqueue_style( $handle, $location . $file, array(), $this->version, 'screen' );
		// add in shame.css
		wp_enqueue_style( $shamehandle, $location . $shamefile, array(), $this->version, 'screen');

	}

	/**
	 * Enqueue template pack javascript
	 *
	 * @since BuddyPress (1.7)
	 */
	public function enqueue_scripts() {
		// LTR or RTL
		$file = 'js/buddypress.js';

		// Check child theme
		if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $file ) ) {
			$location = trailingslashit( get_stylesheet_directory_uri() );
			$handle   = 'bp-child-js';

		// Check parent theme
		} elseif ( file_exists( trailingslashit( get_template_directory() ) . $file ) ) {
			$location = trailingslashit( get_template_directory_uri() );
			$handle   = 'bp-parent-js';

		// BuddyPress Theme Compatibility
		} else {
			$location = trailingslashit( $this->url );
			$handle   = 'bp-templatepack-js';
		}

		wp_enqueue_script( $handle, $location . $file, array( 'jquery', 'hoverIntent', ), $this->version );
	}

	/**
	 * Registers widget areas
	 *
	 * @since BuddyPress (1.7)
	 */
	public function widgets_init() {
		register_sidebar( array(
			'description' => __( 'Appears on member profiles pages', 'buddypress' ),
			'id'          => 'bp-member-profile-widgets',
			'name'        => __( '(BuddyPress) Member Profile', 'buddypress' ),
		) );
	}



	/**
	 * Adds the no-js class to the body tag.
	 *
	 * This function ensures that the <body> element will have the 'no-js' class by default. If you're
	 * using JavaScript for some visual functionality in your theme, and you want to provide noscript
	 * support, apply those styles to body.no-js.
	 *
	 * The no-js class is removed by the JavaScript created in buddypress.js.
	 *
	 * @since BuddyPress (1.7)
	 */
	public function add_nojs_body_class( $classes ) {
		if ( ! in_array( 'no-js', $classes ) )
			$classes[] = 'no-js';

		return array_unique( $classes );
	}

}
new BP_Templates();
endif;

include( plugin_dir_path(__FILE__) . 'buddypress-ajax.php' );