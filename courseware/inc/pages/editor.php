<?
$crs = new Course();
if( $_SESSION['view'] ) {
	$viewname = $_SESSION['view'];
} else {
	$viewname = ( $_GET['type'] == 'Quizzes' ) ? 'quiz': 'course';
}
$crs->set_course( $viewname );
$crs->build_course_nav();
$crs->get_course();
if( !is_object( $editor ) ) $editor = new CourseEditor();
$modules = array();
?>
<div class="crs_head">TRAINING COURSE: <?=strtoupper($crs->course['title'])?></div>
<div id="main">
<div class="crs_left">
	<? include 'courseware/inc/views/edit_' . $viewname . '.php'; ?>
</div>
<div class="crs_right">
	<? include 'courseware/inc/modules/editnav.php'; ?>
</div>
<div class="fclear"></div>
</div>