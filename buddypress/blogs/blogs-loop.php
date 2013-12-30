<?php
/**
 * Blogs loop
 *
 * @package BuddyPress
 * @subpackage Templatepack
 */
?>

<?php do_action( 'bp_before_blogs_loop' ); ?>

<?php if ( bp_has_blogs( bp_ajax_querystring( 'blogs' ) ) ) : ?>

	<div id="pagination-top" class="pagination">

		<div class="pagination-count">
			<?php bp_blogs_pagination_count(); ?>
		</div>

		<div class="pagination-links">
			<?php bp_blogs_pagination_links(); ?>
		</div>

	</div>

	<?php do_action( 'bp_before_directory_blogs_list' ); ?>

	<ul id="blogs-list"  class="directory-list">
		<?php while ( bp_blogs() ) : bp_the_blog(); ?>

			<li>
				<div class="blog-avatar">
					<a href="<?php bp_blog_permalink(); ?>"><?php bp_blog_avatar( 'type=thumb' ); ?></a>
				</div>
				<div class="blog">
					<div class="blog-title"><a href="<?php bp_blog_permalink(); ?>"><?php bp_blog_name(); ?></a></div>
					<div class="blog-meta"><span class="activity"><?php bp_blog_last_active(); ?></span></div>
					<?php do_action( 'bp_directory_blogs_item' ); ?>
				</div>

				<div class="blog-action">
					<?php do_action( 'bp_directory_blogs_actions' ); ?>
					<div class="blog-meta">
					<?php bp_blog_latest_post(); ?>
					</div>
				</div>
			</li>

		<?php endwhile; ?>
	</ul>

	<?php do_action( 'bp_after_directory_blogs_list' ); ?>

	<?php bp_blog_hidden_fields(); ?>

	<div id="pagination-bottom" class="pagination">
		<div class="pagination-count">
			<?php bp_blogs_pagination_count(); ?>
		</div>

		<div class="pagination-links">
			<?php bp_blogs_pagination_links(); ?>
		</div>
	</div>

<?php else: ?>

	<div id="message" class="message-info">
		<p><?php _e( 'Sorry, there were no sites found.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_blogs_loop' ); ?>
