<style type="text/css">
.lesson-topics-list ul {
    list-style: none;
    margin-left: 0px;
}

.lesson-topic, .lesson-quiz {
    height: 30px;
    padding-bottom: 10px;
}

.lesson-topic span.topic-name, .lesson-quiz span.quiz-name {
    color: #3b6715;
    font-family: 'Open sans';
    font-size: 20px;
    font-style: normal;
    font-variant: normal;
    font-weight: normal;
    letter-spacing: normal;
    line-height: 30px;
}

.lesson-topics-title, .lesson-quizzes-title {
    font-family: Verdana, arial, helvetica, clean, sans-serif;
    font-size: 24px;
    font-style: normal;
    font-variant: normal;
    font-weight: normal;
    letter-spacing: normal;
    line-height: normal;
    height: 20px;
    padding-bottom: 20px;
    padding-top: 10px;
    color: #d30000;
}

.lesson-quizzes-list ul {
    list-style: none;
    margin-left: 0px;
}

.single-post-course .entry-content.course-landing h1 {
    display: none;
}

.lesson-previous-next-link {
    width: 100%;
    font-family: 'Open sans';
    font-size: 15px;
    font-style: normal;
    font-variant: normal;
    font-weight: normal;
    letter-spacing: normal;
    line-height: 30px;
}

.lesson-previous-link a{
    padding-top: 15px;
    padding-bottom: 20px;
    width: 40%;
    float: left;
    text-align: left;
    color: #ed1568 !important;
}    

.lesson-next-link a{
    padding-top: 15px;
    padding-bottom: 20px;
    width: 40%;
    float: right;
    text-align: right;
    color: #ed1568 !important;
}

.lesson-description {
    font-family: Verdana, arial, helvetica, clean, sans-serif;
    font-size: 13px;
    color: black;
}
</style>
<?php
    /*
        Available Variables:
        $course_id         : (int) ID of the course
        $course         : (object) Post object of the course
        $course_settings : (array) Settings specific to current course
        $course_status     : Course Status
        $has_access     : User has access to course or is enrolled.

        $courses_options : Options/Settings as configured on Course Options page
        $lessons_options : Options/Settings as configured on Lessons Options page
        $quizzes_options : Options/Settings as configured on Quiz Options page

        $user_id         : (object) Current User ID
        $logged_in         : (true/false) User is logged in
        $current_user     : (object) Currently logged in user object

        $quizzes         : (array) Quizzes Array
        $post             : (object) The lesson post object
        $topics         : (array) Array of Topics in the current lesson
        $all_quizzes_completed : (true/false) User has completed all quizzes on the lesson Or, there are no quizzes.
        $lesson_progression_enabled     : (true/false)
        $show_content    : (true/false) true if lesson progression is disabled or if previous lesson is completed. 
        $previous_lesson_completed     : (true/false) true if previous lesson is completed
        $lesson_settings : Settings sepecific to the current lesson.

    */
            
        /* Lesson Topics */
        /*
        Topics Array Format
            (
                [0] => WP_Post Object
                    (
                        [ID] => 584
                        [post_author] => 1
                        [post_date] => 2014-02-05 22:24:06
                        [post_date_gmt] => 2014-02-05 22:24:06
                        [post_content] => 
                        [post_title] => Lesson Topic 
                        [post_excerpt] => 
                        [post_status] => publish
                        [comment_status] => open
                        [ping_status] => open
                        [post_password] => 
                        [post_name] => lesson-topic
                        [to_ping] => 
                        [pinged] => 
                        [post_modified] => 2014-02-05 22:24:06
                        [post_modified_gmt] => 2014-02-05 22:24:06
                        [post_content_filtered] => 
                        [post_parent] => 0
                        [guid] => http://domain.com/?post_type=sfwd-topic&p=584
                        [menu_order] => 0
                        [post_type] => sfwd-topic
                        [post_mime_type] => 
                        [comment_count] => 0
                        [filter] => raw
                        [completed] => 0
                    )

            )
        */
    ?>
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
                <span class="topic-header-breadcrumbs-course-name">Course: <?php echo get_the_title($course_id); ?></span>
            </a>
        </div>
    </div>

    <div class='lesson-description'>
        <?php echo $content; ?>
    </div>
    <?php if(!empty($topics)) { ?>
        <div class='lesson-topics-list'>
            <div class='lesson-topics-title red-text'>Learn</div>
            <ul>
                <?php $topic_number = 1; ?>
                <?php foreach ($topics as $key => $topic) { ?>
                    <li class="lesson-topic">
                        <a href="<?php echo get_permalink($topic->ID) . '?color=' . $_REQUEST['color']; ?>" title="<?php echo $topic->post_title; ?>">
                            <span class="topic-name"><?php echo $topic_number . ".     " . $topic->post_title; ?></span>
                        </a>
                    </li>
                    <?php $topic_number++; ?>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>

    <?php if ( !empty( $quizzes ) ) { ?>
        <div class='lesson-quizzes-list'>
            <div class='lesson-quizzes-title red-text'>Practice</div>
            <ul>
                <?php $quiz_number = 1; ?>
                <?php foreach($quizzes as $quiz) { ?>
                    <li class="lesson-quiz">
                        <a href="<?php echo $quiz["permalink"]?>">
                            <span class="quiz-name"><?php echo $quiz_number . ".     " . $quiz["post"]->post_title; ?></span>
                        </a>
                    </li>
                    <?php $quiz_number++; ?>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
    <br>
    <div class='lesson-previous-next-link'>
        <div class='lesson-previous-link'><?php echo learndash_previous_post_link(); ?></div>
        <div class='lesson-next-link'><?php echo learndash_next_post_link(); ?></div>
    </div>
</div>
</div>
</div>