<?php
/**
 * Member single message
 *
 * @package BuddyPress
 * @subpackage Templatepack
 */

/**
* Messages single view or top level inbox
*
* Template re-worked to use conditionals to check whether 'inbox' or 'view'
* & present a single cut down  message view for for message default screen
* or full message thread view for 'view'
*
* This is an interim approach to move the templates toward the wireframe impression
* requiring adjustments as & when we determine the exact screen flow we want. ~hnla
*/

/** test case stuff only! */


?>

<?php do_action( 'bp_before_message_thread_content' ); ?>

<?php if ( bp_thread_has_messages() ) : ?>

<div class="messages-content-wrap">

		<?php // h#? h1, h2, h3 ?>

		<h3><?php bp_the_thread_subject(); ?></h3>

		<?php /**  message thread details ************/ ?>

			<p>
				<span>
					<?php if ( !bp_get_the_thread_recipients() ) : ?>

						<?php _e( 'You are alone in this conversation.', 'buddypress' ); ?>

					<?php else : ?>

						<?php printf( __( 'Conversation between %s and you.', 'buddypress' ), bp_get_the_thread_recipients() ); ?>

					<?php endif; ?>
				</span>

			<span>
				<a class="button confirm" href="<?php bp_the_thread_delete_link(); ?>" title="<?php _e( 'Delete Message', 'buddypress' ); ?>"><?php _e( 'Delete', 'buddypress' ); ?></a>
			</span>
		</p>

		<?php /**  end message details ************/ ?>


	<?php do_action( 'bp_before_message_thread_list' ); ?>

	<?php while ( bp_thread_messages() ) : bp_thread_the_message(); ?>

	<article class="<?php bp_the_thread_message_alt_class(); ?>">

		<?php /** message metadata ****************/ ?>

		<header>

			<?php do_action( 'bp_before_message_meta' ); ?>

				<p>

					<span>
					<?php bp_the_thread_message_sender_avatar( 'type=thumb&width=30&height=30' ); ?>
					</span>

					<span>

						<?php if ( bp_get_the_thread_message_sender_link() ) : ?>

							<a href="<?php bp_the_thread_message_sender_link(); ?>" title="<?php bp_the_thread_message_sender_name(); ?>"><?php bp_the_thread_message_sender_name(); ?></a>

						<?php else : ?>

							<?php bp_the_thread_message_sender_name(); ?>

						<?php endif; ?>

					</span>

					<span>

						<?php bp_the_thread_message_time_since(); ?>

					</span>

				</p>

				<?php do_action( 'bp_after_message_meta' ); ?>

			</header>

		<?php /** end message metadata ************/ ?>

		<?php do_action( 'bp_before_message_content' ); ?>

		<div class="message-content">

			<?php bp_the_thread_message_content(); ?>

		</div>

		<?php do_action( 'bp_after_message_content' ); ?>

	</article>

<?php endwhile; ?>

	<?php bp_get_template_part('members/single/messages/messages-reply-form'); ?>

</div><!-- / .messages-content-wrap -->

<?php endif; ?>
