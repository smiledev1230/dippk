<?
$editor = new CourseEditor();
$bypass = false;
switch( $_POST['proc_type'] ) {
	case 'ADD TEXT':
	case 'CHANGE TEXT':
		$editor->change_text();
		break;
	case 'CHANGE TITLE':
		$editor->change_title();
		break;
	case 'ADD VIDEO':
	case 'CHANGE VIDEO':
		$editor->change_video();
		break;
	case 'ADD DOCUMENT':
	case 'CHANGE DOCUMENT':
		$editor->change_doc();
		break;
	case 'REMOVE CURRENT DOCUMENT':
		$editor->remove_doc();
		break;
	case 'CHANGE LESSON TITLE':
		$editor->change_lesson();
		break;
	case 'CHANGE COURSE TITLE':
	case 'CHANGE TAGLINE':
	case 'CHANGE DESCRIPTION':
		$editor->change_course();
		break;
	case 'CHANGE THUMBNAIL':
		$editor->change_thumbnail();
		break;
}
?>