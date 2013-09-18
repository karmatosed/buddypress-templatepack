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

	<ul>
	
	<?php while ( bp_message_threads() ) : bp_message_thread(); ?>		
		
		<li id="m-<?php bp_message_thread_id(); ?>" class="<?php bp_message_css_class(); ?>">
			
			<span><?php bp_message_thread_unread_count(); ?></span>
			
			<span>
			
			<?php  if('sentbox' !== bp_current_action()): ?>
				<a href=""><?php _e( 'From', 'buddypress' ); ?> <?php bp_message_thread_from() ?></a>
			<?php else: ?>
				<a href=""><?php _e( 'To', 'buddypress' ); ?> <?php bp_message_thread_to() ;?></a>
			<?php endif; ?>
			
			</span>
			
			<a href="<?php bp_message_thread_view_link(); ?>"><?php bp_message_thread_subject(); ?></a>
		
		</li>

	<?php endwhile; ?>	
	
	</ul>

</div><!-- / .messages-list -->

<?php endif; ?>
