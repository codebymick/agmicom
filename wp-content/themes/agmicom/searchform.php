<?php
/**
 * Template for displaying search form
 * @package Themehunk
 * @subpackage gogo
 * @since 1.0 
 */
?>
<form role="search" method="get" id="searchform" action="<?php echo esc_url(home_url( '/' )); ?>">
	<div class="form-content">
		<input type="text" placeholder="<?php esc_html_e( 'Search..', 'gogo' ); ?>" name="s" id="s" value="<?php echo get_search_query(); ?>"/>
		<input type="submit" value="<?php echo _x('Search', 'submit button', 'gogo' ); ?>" />
	</div>
</form>