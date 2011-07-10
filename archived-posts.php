<?php
/*
 Template Name: Archived Projets
 */

$arguments = array(
  'meta_key' => 'status', 
  'meta_value' => 'archived', 
  'meta_compare' => '=='
);

query_posts($arguments);


?>
<?php get_header(); ?>

<div class="sleeve_main">

	<div id="main">
		<h2>Projets archivés</h2>
		<ul id="postlist">
		<?php if ( have_posts() ) : ?>
			
			<?php while ( have_posts() ) : the_post(); ?>
				<?php p2_load_entry(); // loads entry.php ?>
			<?php endwhile; ?>
		
		<?php endif; ?>
		</ul>
		
	</div> <!-- main -->

</div> <!-- sleeve -->

<?php get_footer(); ?>