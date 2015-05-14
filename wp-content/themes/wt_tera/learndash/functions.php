<?php

/** Print out lesson in course **/
function print_lesson_html($lesson, $lesson_topics, $color_header_class, $max_topics)
{ ?>
    <div class="course-lesson-container">
        <?php $topics = @$lesson_topics[$lesson["post"]->ID]; ?>
        <div class="course-lesson-overview">
            <a class="course-lesson-title" href="<?php echo get_permalink($lesson["post"]->ID) . '?color=' . $color_header_class; ?>">
              <span class="<?php echo $color_header_class; ?>"><?php echo $lesson["post"]->post_title; ?></span>
            </a>
        </div>
        <div class="course-lesson-topics">
            <div class="topics-box">
                <?php if (!empty($topics)) { ?>
                    <ul class="course-lesson-topics-list">
                        <?php $topic_number = 0; ?>
                        <?php foreach($topics as $key => $topic) { ?>
                            <?php $topic_number++; ?>
                            <li class="course-lesson-topic">
                                <a href="<?php echo get_permalink($topic->ID) . '?color=' . $color_header_class; ?>"><span class="topic-name"><?php echo $topic->post_title; ?></span></a>
                            </li>
                            <?php if ($topic_number >= $max_topics) { break; } ?>
                        <?php } ?>
                        <li class="course-lesson-more">
                            <a href="<?php echo get_permalink($lesson["post"]->ID); ?>">
                                <span class="topic-name">Learn more &raquo;</span>
                            </a>
                        </li>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </div>
<?php }

/** Print out lessons **/
function print_lessons($lessons, $lesson_topics, $max_lessons_per_row, $max_topics)
{ 
    /** Colors for course headers **/
    $header_color_array = array("blue-text", "orange-text", "purple-text",
        "sea-green-text", "deep-blue-text", "light-blue-text", "green-brown-text",
        "medium-blue-text", "pink-text", "light-purple-text", "red-text", "light-green-text");
?>
    <div class="course-lessons-list">
        <div class="course-lessons-list-table">
            <?php $lesson_number = 0;
            $width = floor(100.0/$max_lessons_per_row);
            $width = "width: " . $width . "%;";
            foreach($lessons as $lesson) {
                if ($lesson_number % $max_lessons_per_row == 0) { ?>
                    <div class="course-lessons-list-table-row">
                <?php } ?>
                <div class="course-lessons-list-table-cell course-lessons-lesson-index-<?php echo ($lesson_number % $max_lessons_per_row); ?>" style="<?php echo $width; ?>">
                    <?php $h_color = $header_color_array[$lesson_number % count($header_color_array)]; ?>
                    <?php print_lesson_html($lesson, $lesson_topics, $h_color, $max_topics); ?>
                </div>
                <?php $lesson_number++;
                if ($lesson_number % $max_lessons_per_row == 0) { ?>
                    </div>
                <?php }
            }
            if ($lesson_number %max_lessons_per_row != 0) { ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php }

/** Print out side course menu **/
function print_menu($course_id)
{
    $selected_meta = get_post_meta( $course_id, '_sfwd-courses', true );
    $posts = ld_course_list(array('array' => true)); ?>

    <ul style="margin: 0px;">
        <li style="font-size: 28px; color: gray; font-family: 'Trebuchet MS'; list-style:none;"><?php echo strtoupper($selected_meta['sfwd-courses_course_test_assignment']); ?></li>
        <?php foreach ($posts as $post) {
            $course_meta = get_post_meta( $post->ID, '_sfwd-courses', true );
            if ($course_meta['sfwd-courses_course_test_assignment'] == $selected_meta['sfwd-courses_course_test_assignment']) { 
                $additional_class = $post->ID == $course_id ? "course-menu-item-current" : ""; ?>
                <li class="course-menu-item">
                    <a href="<?php echo get_permalink($post->ID); ?>" class="course-menu-item-bubble <?php echo $additional_class; ?>">
                        <span class="course-menu-item-text"><?php echo $post->post_title; ?></span>
                    </a>
                </li>
            <?php }
        } ?>
    </ul>
<?php }
?>