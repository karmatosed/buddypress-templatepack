<?php
function templatepack_work() {
	bp_register_theme_package( array(
		'id'      => 'templates',
		'name'    => __( 'BuddyPress Templates', 'buddypress' ),
		'version' => bp_get_version(),

		// Adjust these to point to your dev server if necessary
		'dir'     => plugin_dir_path( __FILE__ ),
		'url'     => plugin_dir_url( __FILE__ ),
	) );
}
add_action( 'bp_register_theme_packages', 'templatepack_work' );

function templatepack_package_id( $package_id ) {
	return 'templates';
}
add_filter( 'pre_option__bp_theme_package_id', 'templatepack_package_id' );

// Proposed BP core change: see http://buddypress.trac.wordpress.org/ticket/3741#comment:43
function templatepack_kill_legacy_js_and_css() {
	wp_dequeue_script( 'groups_widget_groups_list-js' );
	wp_dequeue_script( 'bp_core_widget_members-js' );
}
add_action( 'wp', 'templatepack_kill_legacy_js_and_css', 999 );

// Defines profile as default component, but not in global scope
function templatepack_define_profile_as_default() {
	/**
	 * We need to check BP_DEFAULT_COMPONENT is not defined to something else
	 * and most important that xprofile is not deactivated by Admin.
	 */ 
	if ( ! defined( 'BP_DEFAULT_COMPONENT' ) && bp_is_active( 'xprofile' ) ) {
		define( 'BP_DEFAULT_COMPONENT', 'profile' );

		// Now let's edit BP nav so that Profile is the first tab of the member's nav
		add_action( 'bp_xprofile_setup_nav', 'templatepack_maybe_change_nav_position' );
	}
}
add_action( 'bp_loaded', 'templatepack_define_profile_as_default' );

/**
 * Profile is default component so let's make it the first tab of xprofile nav
 */
function templatepack_maybe_change_nav_position() {
	$bp = buddypress();

	if ( defined( 'BP_DEFAULT_COMPONENT' ) && 'profile' == BP_DEFAULT_COMPONENT )
		$bp->bp_nav['profile']['position'] = 1;
}


