<?php
/**
 * Member plugins
 *
 * @package BuddyPress
 * @subpackage Templatepack
 */
?>

		<?php do_action( 'bp_before_member_plugin_template' ); ?>


		<h3><?php do_action( 'bp_template_title' ); ?></h3>

		<?php do_action( 'bp_template_content' ); ?>

		<?php do_action( 'bp_after_member_plugin_template' ); ?>
