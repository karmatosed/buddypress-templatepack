<?php
/**
 * The new template pack for BuddyPress.
 *
 * @package BuddyPress
 * @subpackage BuddyPress Templates
 * @since BuddyPress Templates (1.0)
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'BP_Templates' ) ) :

/**
 * Loads the BuddyPress template pack
 * See @link BP_Theme_Compat() for more.
 *
 * @since BuddyPress Templates (1.0)
 */
class BP_Templates extends BP_Theme_Compat {

	/**
	 * Constructor.
	 *
	 * @since BuddyPress Templates (1.0)
	 *
	 * @uses BP_Templates::setup_globals()
	 * @uses BP_Templates::setup_actions()
	 */
	public function __construct() {
		parent::start();
	}

	/**
	 * Component global variables
	 *
	 * You'll want to customize the values in here, so they match whatever your
	 * needs are.
	 *
	 * @since BuddyPress Templates (1.0)
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
	 * @since BuddyPress Templates (1.0)
	 */
	protected function setup_actions() {
		// Template Output
		add_filter( 'bp_get_activity_action_pre_meta', array( $this, 'secondary_avatars' ), 10, 2 );

		add_action( 'bp_enqueue_scripts',     array( $this, 'enqueue_styles'         ) ); // Enqueue theme CSS
		add_action( 'bp_enqueue_scripts',     array( $this, 'enqueue_scripts'        ) ); // Enqueue theme JS
		add_action( 'widgets_init',           array( $this, 'widgets_init'           ) ); // Widgets
		add_filter( 'body_class',             array( $this, 'add_nojs_body_class'    ), 20, 1 );
		add_action( 'bp_head',                array( $this, 'head_scripts'     ) ); // Output some extra JS in the <head>

		// Run an action for third-party plugins to affect the template pack
		do_action_ref_array( 'bp_theme_compat_actions', array( &$this ) );

	/** Buttons ***********************************************************/

	if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		// Register buttons for the relevant component templates

		// Friends button
		if ( bp_is_active( 'friends' ) )
			add_action( 'bp_member_header_actions',    'bp_add_friend_button',           5 );

			// Activity button
		if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() )
			add_action( 'bp_member_header_actions',    'bp_send_public_message_button',  20 );

			// Messages button
		if ( bp_is_active( 'messages' ) )
			add_action( 'bp_member_header_actions',    'bp_send_private_message_button', 20 );

			// Group buttons
		if ( bp_is_active( 'groups' ) ) {
			add_action( 'bp_before_directory_groups',  'bp_group_create_button' );
			add_action( 'bp_group_header_actions',     'bp_group_join_button',           5 );
			add_action( 'bp_group_header_actions',     'bp_group_new_topic_button',      20 );
			add_action( 'bp_directory_groups_actions', 'bp_group_join_button' );
		}

			// Blog button
		if ( bp_is_active( 'blogs' ) ) {
			add_action( 'bp_before_directory_blogs_content', 'bp_blog_create_button' );
			add_action( 'bp_directory_blogs_actions',   'bp_blogs_visit_blog_button' );
		}

			// Specific Script to include in JS dependencies
		add_filter( 'bp_core_get_js_dependencies', array( $this, 'js_dependencies' ), 10, 1 );

	}

	/** Notices ***********************************************************/

	// Only hook the 'sitewide_notices' overlay if the Sitewide
	// Notices widget is not in use (to avoid duplicate content).
	if ( bp_is_active( 'messages' ) && ! is_active_widget( false, false, 'bp_messages_sitewide_notices_widget', true ) ) {
		add_action( 'bp_after_member_header', array( $this, 'sitewide_notices' ), 9999 );

		// If uesr is admin add a message to the message compose screen to inform about the widget
		if( current_user_can( 'manage_options' ) )
			add_action('bp_before_messages_compose_content', array($this, 'notices_admin_message') );
	}

	/** Ajax ************************************************************* */

	$actions = array(

		// Directory filters
		'blogs_filter'    => 'bp_template_pack_object_template_loader',
		'forums_filter'   => 'bp_template_pack_object_template_loader',
		'groups_filter'   => 'bp_template_pack_object_template_loader',
		'members_filter'  => 'bp_template_pack_object_template_loader',
		'messages_filter' => 'bp_template_pack_messages_template_loader,
		'invite_filter'   => 'bp_template_pack_invite_template_loader',
		'requests_filter' => 'bp_template_pack_requests_template_loader',

		// Friends
		'accept_friendship' => 'bp_template_pack_ajax_accept_friendship',
		'addremove_friend'  => 'bp_template_pack_ajax_addremove_friend',
		'reject_friendship' => 'bp_template_pack_ajax_reject_friendship',

		// Activity
		'activity_get_older_updates'  => 'bp_template_pack_activity_template_loader',
		'activity_mark_fav'           => 'bp_template_pack_mark_activity_favorite',
		'activity_mark_unfav'         => 'bp_template_pack_unmark_activity_favorite',
		'activity_widget_filter'      => 'bp_template_pack_activity_template_loader',
		'delete_activity'             => 'bp_template_pack_delete_activity',
		'delete_activity_comment'     => 'bp_template_pack_delete_activity_comment',
		'get_single_activity_content' => 'bp_template_pack_get_single_activity_content',
		'new_activity_comment'        => 'bp_template_pack_new_activity_comment',
		'post_update'                 => 'bp_template_pack_post_update',
		'bp_spam_activity'            => 'bp_template_pack_spam_activity',
		'bp_spam_activity_comment'    => 'bp_template_pack_spam_activity',

		// Groups
		'groups_invite_user' => 'bp_template_pack_ajax_invite_user',
		'joinleave_group'    => 'bp_template_pack_ajax_joinleave_group',

		// Messages
		'messages_autocomplete_results' => 'bp_template_pack_ajax_messages_autocomplete_results',
		'messages_close_notice'         => 'bp_template_pack_ajax_close_notice',
		'messages_delete'               => 'bp_template_pack_ajax_messages_delete',
		'messages_markread'             => 'bp_template_pack_ajax_message_markread',
		'messages_markunread'           => 'bp_template_pack_ajax_message_markunread',
		'messages_send_reply'           => 'bp_template_pack_ajax_messages_send_reply',
		);

		/**
		 * Register all of these AJAX handlers
		 *
		 * The "wp_ajax_" action is used for logged in users, and "wp_ajax_nopriv_"
		 * executes for users that aren't logged in. This is for backpat with BP <1.6.
		 */
		foreach( $actions as $name => $function ) {
			add_action( 'wp_ajax_'        . $name, $function );
			add_action( 'wp_ajax_nopriv_' . $name, $function );
		}

		add_filter( 'bp_ajax_querystring', 'bp_template_pack_ajax_querystring', 10, 2 );

	}

	/**
	 * Enqueue template pack CSS
	 *
	 * @since BuddyPress Templates (1.0)
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
	 * @since BuddyPress Templates (1.0)
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

		wp_enqueue_script( $handle, $location . $file, bp_core_get_js_dependencies(), $this->version );

		// Add words that we need to use in JS to the end of the page
		// so they can be translated and still used.
		$params = apply_filters( 'bp_core_get_js_strings', array(
			'accepted'            => __( 'Accepted', 'buddypress' ),
			'close'               => __( 'Close', 'buddypress' ),
			'comments'            => __( 'comments', 'buddypress' ),
			'leave_group_confirm' => __( 'Are you sure you want to leave this group?', 'buddypress' ),
			'mark_as_fav'	      => __( 'Favorite', 'buddypress' ),
			'my_favs'             => __( 'My Favorites', 'buddypress' ),
			'rejected'            => __( 'Rejected', 'buddypress' ),
			'remove_fav'	      => __( 'Remove Favorite', 'buddypress' ),
			'show_all'            => __( 'Show all', 'buddypress' ),
			'show_all_comments'   => __( 'Show all comments for this thread', 'buddypress' ),
			'show_x_comments'     => __( 'Show all %d comments', 'buddypress' ),
			'unsaved_changes'     => __( 'Your profile has unsaved changes. If you leave the page, the changes will be lost.', 'buddypress' ),
			'view'                => __( 'View', 'buddypress' ),
		) );
		wp_localize_script( $handle, 'BP_DTheme', $params );

		// Maybe enqueue comment reply JS
		if ( is_singular() && bp_is_blog_page() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

	}

	/**
	 * Since 2.0 it's possible to filter bp_core_get_js_dependencies
	 * to add specific js depedencies 
	 */
	public function js_dependencies( $deps = array() ) {
		$deps[] = 'hoverIntent';

		return $deps;
	}

	/**
	 * Registers widget areas
	 *
	 * @since BuddyPress Templates (1.0)
	 */
	public function widgets_init() {
		register_sidebar( array(
			'description' => __( 'Appears on member profiles pages', 'buddypress' ),
			'id'          => 'bp-member-profile-widgets',
			'name'        => __( '(BuddyPress) Member Profile', 'buddypress' ),
		) );
	}

	/**
	 * Put some scripts in the header, like AJAX url for wp-lists
	 *
	 * @since BuddyPress (1.7)
	 */
	public function head_scripts() {
	?>

		<script type="text/javascript">
			/* <![CDATA[ */
			var ajaxurl = '<?php echo bp_core_ajax_url(); ?>';
			/* ]]> */
		</script>

	<?php
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
	 * @since BuddyPress Templates (1.0)
	 */
	public function add_nojs_body_class( $classes ) {
		if ( ! in_array( 'no-js', $classes ) )
			$classes[] = 'no-js';
		return array_unique( $classes );
	}

	/**
	 * Outputs sitewide notices markup in the members header & adds admin message to compose screen.
	 *
	 * @since BuddyPress (1.7)
	 *
	 * @see https://buddypress.trac.wordpress.org/ticket/4802
	 */
	public function sitewide_notices() {
		// Do not show notices if user is not logged in
		if ( ! is_user_logged_in() )
			return;

		// add a class to determine if the admin bar is on or not
		$class = did_action( 'admin_bar_menu' ) ? 'admin-bar-on' : 'admin-bar-off';

		echo '<div id="sitewide-notice" class="' . $class . '">';
		bp_message_get_notices();
		echo '</div>';
	}

	public function notices_admin_message() {
		echo	'<p class="notice info">' . __('Admin currently sitewide notices are displaying in your members account screens only, you can use the sitewide widget to show notices in your themes sidebars if you prefer.', 'buddypress') . '</p>';
		return;
	}

	/**
	 * Add secondary avatar image to this activity stream's record, if supported.
	 *
	 * @since BuddyPress (1.7)
	 *
	 * @param string $action The text of this activity
	 * @param BP_Activity_Activity $activity Activity object
	 * @package BuddyPress Theme
	 * @return string
	 */
	function secondary_avatars( $action, $activity ) {
		switch ( $activity->component ) {
			case 'groups' :
			case 'friends' :
				// Only insert avatar if one exists
				if ( $secondary_avatar = bp_get_activity_secondary_avatar() ) {
					$reverse_content = strrev( $action );
					$position        = strpos( $reverse_content, 'a<' );
					$action          = substr_replace( $action, $secondary_avatar, -$position - 2, 0 );
				}
				break;
		}

		return $action;
	}
}
new BP_Templates();
endif;

// Include our Ajax functions
include( plugin_dir_path(__FILE__) . 'buddypress-ajax.php' );