<?php
/**
 * The template for displaying search forms in Well Themes
 *
 * @package  WellThemes
 * @file     searchform.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="text" class="searchfield" name="s" id="s" placeholder="<?php esc_attr_e( 'Search', 'wellthemes' ); ?>" />
	<button class="search-submit button main-color-bg"><i class="fa fa-search"></i></button>
</form>
