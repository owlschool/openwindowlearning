<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package  WellThemes
 * @file     content-page.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<header class="entry-header">
		<h1><?php the_title(); ?></h1>
	</header><!-- /entry-header -->

	<?php		
		$post_banner1 = get_post_meta($post->ID, 'wt_meta_banner1', true);		
		if ($post_banner1 != ""){ ?>
			<div class="entry-ad">
				<div class="ad-inner-wrap">
					<?php echo $post_banner1; ?>
				</div>			
			</div><?php 
		}	
	?>
	
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'wellthemes' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- /entry-content -->
	
	<?php		
		$post_banner2 = get_post_meta($post->ID, 'wt_meta_banner2', true);		
		if ($post_banner2 != ""){ ?>
			<div class="entry-ad">
				<div class="ad-inner-wrap">
					<?php echo $post_banner1; ?>
				</div>			
			</div><?php 
		}	
	?>

</article><!-- /post-<?php the_ID(); ?> -->