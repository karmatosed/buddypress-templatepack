<?php
/**
 * Member messages loop
 *
 * @package BuddyPress
 * @subpackage Templatepack
 */
?>
<?php do_action( 'bp_before_member_messages_loop' ); ?>

<?php if ( bp_has_message_threads( bp_ajax_querystring( 'messages' ) ) ) : ?>

<div class="messages-list">


	<?php while ( bp_message_threads() ) : bp_message_thread(); ?>

		<div id="m-<?php bp_message_thread_id(); ?>" class="message-header <?php bp_message_css_class(); ?><?php if ( bp_message_thread_has_unread() ) : ?>unread<?php else: ?>read<?php endif; ?>">

			<?php bp_message_thread_avatar(); ?>

			<?php if( bp_message_thread_has_unread() ) : ?>
			<!--<span class="unread-num">

				<?php bp_message_thread_unread_count(); ?> <?php _e( 'Unread', 'buddypress' ); ?>

			</span>-->
			<?php endif; ?>

			<?php  if('sentbox' !== bp_current_action()): ?>

				<span><?php _e( 'From', 'buddypress' ); ?> <?php bp_message_thread_from() ?></span>

			<?php else: ?>

				<span><?php _e( 'To', 'buddypress' ); ?> <?php bp_message_thread_to() ;?></span>

			<?php endif; ?>

			<a href="<?php bp_message_thread_view_link(); ?>"><?php bp_message_thread_subject(); ?></a>

		</div>

		<div class="message-content">

		<span><a href="<?php bp_message_thread_view_link(); ?>"><?php bp_message_thread_subject(); ?></a></span>

		<?php bp_message_thread_content();?>

		</div>


	<?php endwhile; ?>



</div><!-- / .messages-list -->

<?php endif; ?>
