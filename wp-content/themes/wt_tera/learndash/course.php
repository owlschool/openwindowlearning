<style type="text/css">
.course-lessons-list-table {
    display:table;
    width: 100%;
}

.course-lessons-list-table-row {
   display:table-row;
   width: 100%;
}

.course-lessons-list-table-cell {
    display:table-cell;
    padding: 10px 20px 20px 0px;
}

.course-lesson-title {
    font-family: 'Trebuchet MS';
    font-size: 20px;
    font-style: normal;
    font-variant: normal;
    font-weight: bold;
    letter-spacing: normal;
    line-height: 22px;
}

ul.course-lesson-topics-list {
    list-style: none;
    margin-left: 0px;
}

.course-lesson-topic span.topic-name {
    color: #3b6715;
    font-family: Verdana, arial, helvetica, clean, sans-serif;
    font-size: 13px;
    font-style: normal;
    font-variant: normal;
    font-weight: normal;
    letter-spacing: normal;
    line-height: 15px;
    height: 15px;
}

.course-lesson-more span.topic-name {
    color: #489405;
    font-family: Verdana, arial, helvetica, clean, sans-serif;
    font-size: 13px;
    font-style: normal;
    font-variant: normal;
    font-weight: normal;
    letter-spacing: normal;
    line-height: 15px;
    height: 15px;
    text-decoration: underline;
}

.single-post-course .entry-content.course-landing h1 {
   display: none;
   visibility: none;
}

.single-course-header-name {
    font-family: 'Trebuchet MS';
    font-size: 48px;
    font-style: normal;
    font-variant: normal;
    letter-spacing: normal;
    line-height: normal;
    padding-bottom: 25px;
    color: #00ADEF;
}

</style>

<div style="width:100%; overflow:hidden; display:table;">
<div style="width:100%; display:table-row;">
<div class="course-menu">
    <?php print_menu($course_id); ?>
</div>
<div class="course-details">
    <div class="single-course-header">
        <div class="single-course-header-name"><?php echo get_the_title( $course_id ); ?></div>
    </div>
    <div class="single-course-lesson-list">
        <?php if (!empty($lessons)) {
            print_lessons($lessons, $lesson_topics, 3, 3);
        } ?>
    </div>
</div>
</div>
</div>