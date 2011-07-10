<?php
/**
 * @package WordPress
 * @subpackage P2
 */
?>
<?php get_header(); ?>

<div class="sleeve_main">
	
	<div id="main">
		
		<?php if ( have_posts() ) : ?>
			
			<?php while ( have_posts() ) : the_post(); ?>
			
				<ul id="postlist">
		    		<?php p2_load_entry(); // loads entry.php ?>
				</ul>
			
			<?php endwhile; ?>
			
		<?php else : ?>
			
			<ul id="postlist">
				<li class="no-posts">
			    	<h3><?php _e( 'No posts yet!', 'p2' ); ?></h3>
				</li>
			</ul>
			
		<?php endif; ?>

	</div> <!-- main -->

</div> <!-- sleeve -->

<?php get_footer(); ?>