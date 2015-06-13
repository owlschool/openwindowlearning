<?php
/**
 * The template for displaying category content in the archive and search results template
 *
 * @package  WellThemes
 * @file     content-category.php
 * @author   WellThemes Team
 * @link 	 http://wellthemes.com
 */
?>

<article>
	<div class="blog_template classical">
		    <div class="blog_header">
			<?php the_post_thumbnail( 'full' );?>
			<h1><a href="<?php the_permalink();?>"><?php the_title();?></a></h1>
			<div class="metadatabox">
					<div class="icon-date"></div>Posted by <span><?php the_author();?></span> on <?php the_time( __( 'F d, Y' ) );?>
					<!--<?php
					$categories_list = get_the_category_list( __( ', ', 'twentyeleven' ) );
					if ( $categories_list ):
						printf( __( ' %2$s', 'twentyeleven' ), 'entry-utility-prep entry-utility-prep-tag-links', $categories_list );
						$show_sep = true;
					endif; ?>
				<div class="metacomments">
					<div class="icon-comment"></div><?php comments_popup_link( '0', '1', '%' ); ?>
				</div> -->
			</div>
			<?php
            $tags_list = get_the_tag_list( '', __( ', ', 'twentyeleven' ) );
            if ( $tags_list ):?>
                <div class="tags">
                    <div class="icon-tags"></div>
                    <?php
                    printf( __( '%2$s', 'twentyeleven' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
                    $show_sep = true;
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="post_content">
            <?php if( get_option( 'rss_use_excerpt' ) == 0 ):?>
                <?php the_content(); ?>
            <?php else:?>
                <?php the_excerpt( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) );?>
            <?php endif;?>
        </div>
	</div>
	
</article><!-- /post-<?php the_ID(); ?> -->