<?php
/**
 * The template for displaying search forms in P2
 *
 * @package WordPress
 * @subpackage P2
 */
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label for="s" class="assistive-text"><?php _e( 'Search', 'p2' ); ?></label>
		<input type="search" class="field" value="<?php echo $_GET['s'] ?>" name="s" id="s" placeholder="<?php esc_attr_e( 'Search', 'p2' ); ?>" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'p2' ); ?>" />
	</form>
