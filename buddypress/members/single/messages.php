<?php

/**
 * BuddyPress - Users Messages
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
	<ul class="messages-nav">

		<?php bp_get_options_nav(); ?>

	</ul>
	
	<!--<?php if ( bp_is_messages_inbox() || bp_is_messages_sentbox() ) : ?>

		<div class="message-search"><?php bp_message_search_form(); ?></div>

	<?php endif; ?>-->

</div><!-- .item-list-tabs -->

<div class="messages-sidebar">
	<?php if ( bp_has_message_threads( bp_ajax_querystring( 'messages' ) ) ) : ?>
		<?php while ( bp_message_threads() ) : bp_message_thread(); ?>
		<div class="message-wrap">
			<div id="m-<?php bp_message_thread_id(); ?>" class="message-header <?php bp_message_css_class(); ?>">
				<?php bp_message_thread_avatar() ?>

				<?php if ( bp_message_thread_has_unread() ) : ?>
					<span class="unread-num"><?php printf( __( '%s Unread', 'buddypress' ), bp_get_message_thread_unread_count() ); ?></span>
				<?php endif; ?>

				<?php if ( bp_is_current_action( 'sentbox' ) ) : ?>
					<span><?php _e( 'To: ', 'buddypress' ); ?> <?php bp_message_thread_to(); ?></span>
				<?php else : ?>
					<span><?php _e( 'From: ', 'buddypress' ); ?> <?php bp_message_thread_from(); ?></span>
				<?php endif; ?>

				<p><a href="<?php bp_message_thread_view_link(); ?>" title="<?php esc_attr_e( "View Message", "buddypress" ); ?>"><?php bp_message_thread_subject(); ?></a></p>
			</div>
		</div>
		<?php endwhile; ?>
	<?php else : ?>
		<?php _e( 'No messages', 'buddypress' ); ?>
	<?php endif; ?>
</div>

<div class="messages-main">
	<?php
	switch ( bp_current_action() ) :

		// Inbox/Sentbox
		case 'inbox'   :
		case 'sentbox' :
			// THIS IS A HACK - will require a BuddyPress patch
			global $messages_template;
			buddypress()->current_action = 'view';
			buddypress()->action_variables[0] = intval( $messages_template->threads[0]->thread_id );

			do_action( 'bp_before_member_messages_content' );
			bp_get_template_part( 'members/single/messages/single' );
			do_action( 'bp_after_member_messages_content' );
			break;

		// Single Message View
		case 'view' :
			bp_get_template_part( 'members/single/messages/single' );
			break;

		// Compose
		case 'compose' :
			bp_get_template_part( 'members/single/messages/compose' );
			break;

		// Sitewide Notices
		case 'notices' :
			do_action( 'bp_before_member_messages_content' ); ?>


				<?php bp_get_template_part( 'members/single/messages/notices-loop' );; ?>

			<?php do_action( 'bp_after_member_messages_content' );
			break;

		// Any other
		default :
			bp_get_template_part( 'members/single/plugins' );
			break;
	endswitch; ?>
</div>
