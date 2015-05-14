<?php
/**
 * The template for displaying image attachments.
 *
 * @package  WellThemes
 * @file     image.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<?php get_header(); ?>
<div id="content" class="image-content">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
			
			<header class="entry-header">
			<h1><?php the_title(); ?></h1>			
			<div class="entry-meta clearfix">
				<span class="cat"><?php wt_get_cats(); ?></span>
				<span class="date"><?php echo get_the_date(); ?></span>
				<span class="sep">-</span>
				<span class="comments"><?php comments_popup_link( __('No comments', 'wellthemes'), __( '1 comment', 'wellthemes'), __('% comments', 'wellthemes')); ?></span>			
				<?php 
					if ( wt_get_option( 'wt_enable_rating' ) == 1 ){ ?>
					<span class="rating"><?php ec_stars_rating(); ?></span>
				<?php } ?>
			</div>	
				
			
		</header>
		
						
			<?php		
				$post_banner1 = get_post_meta($post->ID, 'wt_meta_post_banner1', true);			
				if ($post_banner1 == "") {		
					if ( wt_get_option( 'wt_post_banner1' ) != "" ){
						$post_banner1 = wt_get_option( 'wt_post_banner1' );
					}				
				}
				
				if ($post_banner1 != ""){ ?>
					<div class="entry-ad">
						<div class="inner-wrap">
							<?php echo $post_banner1; ?>
						</div>			
					</div><?php 
				}	
			?>

			<div class="entry-content">
				<div class="entry-attachment">
					<div class="attachment">
						<?php
							/**
							* Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
							* or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
							*/
							$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
							foreach ( $attachments as $k => $attachment ) {
								if ( $attachment->ID == $post->ID )
									break;
							}
							$k++;

							// If there is more than 1 attachment in a gallery
							if ( count( $attachments ) > 1 ) {
								if ( isset( $attachments[ $k ] ) )
									// get the URL of the next image attachment
									$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
								else
									// or get the URL of the first image attachment
									$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
							} else {
									// or, if there's only 1 image, get the URL of the image
									$next_attachment_url = wp_get_attachment_url();
							}
						?>
						
						<a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php the_title_attribute(); ?>" rel="attachment">
						<?php echo wp_get_attachment_image( $post->ID, 'full' ); ?></a>

						<?php if ( ! empty( $post->post_excerpt ) ) : ?>
						<div class="entry-caption">
							<?php the_excerpt(); ?>
						</div>
						<?php endif; ?>
					</div><!-- /attachment -->

				</div><!-- /entry-attachment -->

				<div class="entry-description">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'wellthemes' ) . '</span>', 'after' => '</div>' ) ); ?>
				</div><!-- /entry-description -->							

			</div><!-- /entry-content -->
						
			<nav class="img-nav">
				<span class="nav-previous"><?php previous_image_link( false, __( '&larr; Previous' , 'wellthemes' ) ); ?></span>
				<span class="nav-next"><?php next_image_link( false, __( 'Next &rarr;' , 'wellthemes' ) ); ?></span>
			</nav><!-- /nav-single -->
						
			<div class="image-post-link">
				<a href="<?php echo get_permalink($post->post_parent) ?>" title="<?php printf( __( 'Return to %s', 'wellthemes' ), esc_html( get_the_title($post->post_parent), 1 ) ) ?>" rev="attachment"><?php echo get_the_title($post->post_parent) ?></a>
			</div>
			
			<?php
			
				if ( wt_get_option( 'wt_show_post_nav' ) == 1 ){ ?>
					<div class="post-nav section">													
						<?php previous_post_link('<div class="prev-post"><i class="fa fa-angle-left"></i><h4>%link</h4></div>', '%title'); ?>	
						<?php next_post_link('<div class="next-post"><h4>%link</h4><i class="fa fa-angle-right"></i></div>', '%title'); ?>
					</div><?php
				}			
				
				$post_banner2 = get_post_meta($post->ID, 'wt_meta_post_banner2', true);			
				if ($post_banner2 == "") {		
					if ( wt_get_option( 'wt_post_banner2' ) != "" ){
						$post_banner2 = wt_get_option( 'wt_post_banner2' );
					}	
				}
				
				if ($post_banner2 != ""){ ?>
					<div class="entry-ad">
						<div class="inner-wrap">
							<?php echo $post_banner2; ?>
						</div>
					</div><?php 
				}	
			?>					
						
		</article><!-- /post-<?php the_ID(); ?> -->
		
		
		<?php comments_template( '', true ); ?>	
		
	<?php endwhile; // end of the loop. ?>

	</div><!-- /content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>