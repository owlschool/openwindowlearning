<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to wellthemes_comment() which is
 * located in the functions.php file.
 *
 * @package  WellThemes
 * @file     comments.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<div id="comments">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'wellthemes' ); ?></p>
	</div><!-- /comments -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php // if has comments ?>

	<?php if ( have_comments() ) : ?>
		
		<div class="comments-title">
			
			<div class="title-wrap">				
				<i class="fa fa-comments"></i>
				<?php if (get_post_type() == 'ticket') { ?>
				  <h3><?php echo get_comments_number(); ?> Replies</h3>
				<?php } else { ?>
				  <h3><?php printf(_n('1 comment', '%1$s comments', get_comments_number()), number_format_i18n( get_comments_number() ), 'wellthemes' ); ?></h3>
				<?php } ?>
			</div>
			
			
			<div class="add-comment">
				<?php if (get_post_type() == 'ticket') { ?>
				  <h6><a href="#respond">Reply</a></h6>
				<?php } else { ?>
				  <h6><a href="#respond"><?php _e('Leave Comment', 'wellthemes');?></a></h6>
				<?php } ?>
			</div>
		</div>
		
		<ol class="commentlist ticket-reply">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use wellthemes_comment() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define wellthemes_comment() and that will be used instead.
				 * See wellthemes_comment() in functions.php for more.
				 */
				wp_list_comments( array( 'callback' => 'wellthemes_comment' ) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav class="comment-nav">
			<h4><?php _e( 'Comment navigation', 'wellthemes' ); ?></h4>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'wellthemes' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'wellthemes' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		
	<?php endif; ?>

	<?php if (get_post_type() == 'ticket') { ?>
	    <?php comment_form( array ('label_submit' => 'Post Reply') ); ?>
	<?php } else { ?>
	    <?php comment_form(); ?>
	<?php } ?>

</div><!-- /comments -->
