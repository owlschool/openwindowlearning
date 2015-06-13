<?php
/** * The template for displaying content in the single.php template * * @package  WellThemes * @file     content-single.php * @author   WellThemes Team * @link 	 http://wellthemes.com */ ?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
<?php
if (wt_get_option('wt_show_post_img') == 1)
	{
	if (has_post_thumbnail())
		{ ?>
			<div class="thumbnail single-post-thumbnail"><?php the_post_thumbnail('wt720_415'); ?></div>
			<?php
		}
	} ?>
	
<header class="entry-header">
	<div class="entry-meta clearfix">
		<span class="cat"><?php wt_get_cats(); ?></span>
		<span class="date"><?php echo get_the_date(); ?></span>
		<span class="sep">-</span>
		<span class="comments"><?php comments_popup_link(__('No comments', 'wellthemes') , __('1 comment', 'wellthemes') , __('% comments', 'wellthemes')); ?></span>

		<?php if (wt_get_option('wt_enable_rating') == 1) { ?>
			<div class="rating"><?php ec_stars_rating(); ?></div>
		<?php } ?>
	</div>
	<h1><?php the_title(); ?></h1>
</header>
<?php $post_banner1 = get_post_meta($post->ID, 'wt_meta_banner1', true);
if ($post_banner1 == "")
{
	if (wt_get_option('wt_post_banner1') != "")
	{
		$post_banner1 = wt_get_option('wt_post_banner1');
	}
}

if ($post_banner1 != "")
{ ?>
	<div class="entry-ad">
		<div class="ad-inner-wrap"><?php echo $post_banner1; ?></div>
	</div>
<?php } ?>
<div class="entry-content">
	<?php the_content(); ?>
	<?php wp_link_pages(array('before' => '<div class="page-link"><span>' . __('Pages:', 'wellthemes') . '</span>',
								'after' => '</div>')); ?>
</div><!-- /entry-content -->
<?php $post_banner2 = get_post_meta($post->ID, 'wt_meta_banner2', true);
if ($post_banner2 == "")
{
	if (wt_get_option('wt_post_banner2') != "")
	{
		$post_banner2 = wt_get_option('wt_post_banner2');
	}
}

if ($post_banner2 != "")
{ ?>
	<div class="entry-ad">
		<div class="ad-inner-wrap"><?php echo $post_banner2; ?></div>
	</div>
<?php }

the_tags('<div class="entry-tags"><span>Tags:</span>', ', ', '</div>'); ?>

</div>
<?php
if (wt_get_option('wt_show_author_info') == 1) { ?>
	<div class="entry-author section">
		<div class="author-avatar"><?php echo get_avatar(get_the_author_meta('user_email') , 80); ?> </div>
		<div class="author-description">
			<div class="author-name">
				<span class="main-color-bg">
					<a href="<?php	echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author"><?php the_author(); ?></a>
				</span>
			</div>	
			<p><?php the_author_meta('description'); ?></p>
		</div>
	</div><!-- /entry-author -->
<?php }
if (wt_get_option('wt_show_post_social') == 1) { ?>
	<div class="entry-social section">
	<?php
	$full_excerpt = get_the_excerpt();
	$excerpt = mb_substr($full_excerpt, 0, 150);
	if (strlen($full_excerpt) > 150)
	{
		$excerpt = $excerpt . '...';
	}

	$thumbnail = "";
	if (has_post_thumbnail())
	{
		$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID) , 'thumbnail');
		$thumbnail = $image[0];
	} ?>
	<ul class="list">
		<li class="fb">
		<a href="http://facebook.com/share.php?u=<?php echo urlencode(get_the_permalink()); ?>&amp;t=<?php echo urlencode(get_the_title()); ?>" target="_blank"><i class="fa fa-facebook"></i><?php
	_e('Facebook', 'wellthemes'); ?></a>			</li>					<li class="twitter">				<a href="http://twitter.com/home?status=<?php
	echo urlencode(get_the_title()); ?>%20<?php
	echo urlencode(get_the_permalink()); ?>" target="_blank"><i class="fa fa-twitter"></i><?php
	_e('Twitter', 'wellthemes'); ?></a>				</li>					<li class="gplus">							<a href="https://plus.google.com/share?url=<?php
	echo urlencode(get_the_permalink()); ?>&amp;t=<?php
	echo urlencode(get_the_title()); ?>" target="_blank"><i class="fa fa-google-plus"></i><?php
	_e('Google+', 'wellthemes'); ?></a>						</li>					<li class="linkedin">				<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php
	echo urlencode(get_the_permalink()); ?>&amp;title=<?php
	echo urlencode(get_the_title()); ?>&amp;summary=<?php
	echo urlencode($excerpt); ?>" target="_blank"><i class="fa fa-linkedin"></i><?php
	_e('Linkedin', 'wellthemes'); ?></a>			</li>							<li class="pinterest">				<a href="http://pinterest.com/pin/create/button/?url=<?php
	echo urlencode(get_the_permalink()); ?>&amp;media=<?php
	echo $thumbnail; ?>&amp;description=<?php
	echo urlencode(get_the_title()); ?>" target="_blank"><i class="fa fa-pinterest"></i><?php
	_e('Pinterest', 'wellthemes'); ?></a>			</li>				</ul>			</div><!-- /entry-social -->		<?php
	}

if (wt_get_option('wt_show_post_nav') == 1)
	{ ?>	<div class="post-nav section">															<?php
	previous_post_link('<div class="prev-post"><i class="fa fa-angle-left"></i><h4>%link</h4></div>', '%title'); ?>			<?php
	next_post_link('<div class="next-post"><h4>%link</h4><i class="fa fa-angle-right"></i></div>', '%title'); ?>	</div><?php
	}

if (wt_get_option('wt_show_related_posts') == 1)
	{
	get_template_part('includes/related-posts');
	} ?>