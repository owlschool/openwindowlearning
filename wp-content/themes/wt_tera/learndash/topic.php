<div style="width:100%; overflow:hidden; display:table;">
<div style="width:100%; display:table-row;">
<div class="course-menu">
    <?php print_menu($course_id); ?>
</div>
<div class="course-details">
<div class="topic-header">
    <div class="topic-header-title"><div class="topic-header-name <?php echo $_REQUEST['color']; ?>"><?php the_title(); ?></div></div>
    <div class="topic-header-breadcrumbs">
        <a class="topic-header-breadcrumbs-course" href="<?php echo get_permalink( $course_id ); ?>">
            <span class="topic-header-breadcrumbs-course-name">Course: <?php echo get_the_title($course_id); ?> &raquo;</span>
        </a>
        <a class="topic-header-breadcrumbs-lesson" href="<?php echo get_permalink( $lesson_post->ID ) . '?color=' . $_REQUEST['color']; ?>">
            <span class="topic-header-breadcrumbs-lesson-name">Lesson: <?php echo $lesson_post->post_title; ?></span>
        </a>
    </div>
</div>
<div class="topic-content">
    <?php $video_url = get_post_meta( $post->ID, '_learndash_topic_video_key', true );
    if (isset($video_url) && $video_url != "") { ?>
        <iframe class="sproutvideo-player" src="<?php echo $video_url; ?>?type=hd&amp;hoverColorTop=f5f3f2" width="640" height="360" frameborder="0" allowfullscreen="allowfullscreen" style="margin:auto;"></iframe>
    <?php } ?>
    <?php $question_available = get_post_meta( $post->ID, '_learndash_topic_question_1_text', true ) ?>
    <?php if ($question_available != "") { ?>
        <h3>Questions Covered In This Video</h3>
	<table style="border-collapse: collapse;">
            <?php $curr_index = 1;
            while (true) {
                $question_text = get_post_meta( $post->ID, '_learndash_topic_question_' . $curr_index . '_text', true );
                $question_image = get_post_meta( $post->ID, '_learndash_topic_question_' . $curr_index . '_image', true );
	
                if (!isset($question_text) || $question_text == "") {
                    break;
                }
	
                echo '<tr style="border: 0px 0px 0px 0px;">';
                if (isset($question_image) && $question_image != "") {
                    echo '<td style="width:70%; border-top: 0px; border-bottom: 0px; padding: 0px 0px 0px 0px;">' . do_shortcode($question_text) . '</td>';
                    echo '<td style="width:30%; border-top: 0px; border-bottom: 0px; padding: 0px 0px 0px 0px;">' . $question_image . '</td>';
                } else {
                    echo '<td style="width:100%; border-top: 0px; border-bottom: 0px; padding: 0px 0px 0px 0px;"><ul><li>' . apply_filters('generate_latex_in_page', $question_text) . '</li></ul></td>';
                }
                echo '</tr>';
                $curr_index++;
            } ?>
        </table>
    <?php } ?>				
    <?php echo $content; ?>
</div>
</div>
</div>
</div>