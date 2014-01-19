<?php
/**
 * Member panel
 *
 * @package BuddyPress
 * @subpackage Templatepack
 */
?>
<div id="member-panel" class="secondary-column">

	<nav id="member-navigation" class="nav-list">

			<?php bp_nav_menu(); ?>

	</nav><!-- end #member-navigation -->

	<?php bp_get_template_part('members/single/nav-search-filters'); ?>

	<?php bp_get_template_part( 'members/single/member-header' ) ?>

</div><!-- end #member-panel -->