<?php
/*
Plugin Name: BuddyPress Template Pack
Version: 1.0
*/

function templatepack_init() {
	include( plugin_dir_path(__FILE__) . 'bp-custom.php' );
}
add_action( 'bp_include', 'templatepack_init' );
