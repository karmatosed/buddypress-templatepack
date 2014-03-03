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

	<?php 	$i=0; 
			while ( bp_message_threads() ) : bp_message_thread(); 
			$i++;
		if ($i < 2) {
			$class = "show";
			} else {
			$class = "hide";
		}?>

		<div class="message-wrap">
			<div id="m-<?php bp_message_thread_id(); ?>" class="message-header <?php bp_message_css_class(); ?>">
			<!--<?php //if ( bp_message_thread_has_unread() ) : ?>unread<?php //else: ?>read<?php //endif; ?>-->

			<?php bp_message_thread_avatar(); ?>

			<?php if( bp_message_thread_has_unread() ) : ?>
			<span class="unread-num"><?php bp_message_thread_unread_count(); ?> <?php _e( 'Unread', 'buddypress' ); ?></span>
			<?php endif; ?>

			<?php  if('sentbox' !== bp_current_action()): ?>

			<span><?php _e( 'From: ', 'buddypress' ); ?> <?php bp_message_thread_from() ?></span>

			<?php else: ?>

			<span><?php _e( 'To: ', 'buddypress' ); ?> <?php bp_message_thread_to() ;?></span>

			<?php endif; ?>

			<?php bp_message_thread_subject(); ?>
		</div>

		<div class="message-content">

		<div class="show-<?php bp_message_thread_id(); ?>">

		<?php if( 1 == $i ) : ?>

		<h3><a href="<?php bp_message_thread_view_link(); ?>" title="<?php _e('view the full conversation', 'budypress'); ?>"><?php bp_message_thread_subject(); ?></a></h3>

		<?php else: ?>

		<strong><?php bp_message_thread_last_post_date(); ?></strong>

		<?php endif; ?>	

		</div>
		
		<div id="content-<?php bp_message_thread_id(); ?>" class="<?php echo $class;?>">

		<?php if( $i > 1) : ?>

			<p><a href="<?php bp_message_thread_view_link(); ?>" title="<?php _e('view the full conversation', 'budypress'); ?>"><?php bp_message_thread_subject(); ?></a></p>	

		<?php endif; ?>

		<?php bp_message_thread_content();?>

		<?php if( 1 == $i ) : ?>

			<a href="#" class="button show-reply-form"><?php _e('Reply', 'buddypress') ; ?></a>

				<div class="message-reply-form">
					<?php bp_get_template_part('members/single/messages/messages-reply-form'); ?>
					<a href="#" class="button close-reply-form"><?php _e('close', 'buddypress') ; ?></a>
				</div>

		<?php endif; ?>

		</div>

		</div>

</div>

<script>
	jQuery(document).ready(function($) {
  		$(".show-<?php bp_message_thread_id(); ?>").click(function(){
    	$("#content-<?php bp_message_thread_id(); ?>").toggle( 500 );
		});
	});
</script>
<?php endwhile; ?>
</div><!-- / .messages-list -->
<?php endif; ?>