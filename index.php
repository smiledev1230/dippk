<?
date_default_timezone_set('America/Sao_Paulo');
include 'inc/app_top.php';
if($_SESSION['page']=='topics'){
	$_SESSION['page']='courses';
}
if($_SESSION['view']=='topics'){
	$_SESSION['view']='courses';
}
if($req['view']=='topics'){
	$req['view']='courses';
}
if($req['page']=='topics'){
	$req['page']='courses';
}
if($_SESSION['page']=='topic'){
	$_SESSION['page']='course';
}
if($_SESSION['view']=='topic'){
	$_SESSION['view']='course';
}
if($req['view']=='topic'){
	$req['view']='course';
}
if($req['page']=='topic'){
	$req['page']='course';
}
if($req['view']=='chapter'){
	$req['view']='lesson';
}

$course_pages = array( 'course', 'editor' );
$component = ( in_array( $_SESSION['page'], $course_pages ) ) ? 'courseware/' : '';

if( $_POST['process'] ) {
	if( $component != '' ) {
		require $component . 'inc/processes/proc.' . strtolower( $_POST['process'] ) . '.php';
	} else {
		switch( $_POST['process'] ) {
			case 'UPDATE':
			case 'UPLOAD NEW':
			case 'RESET PASSWORD':
			case 'CHANGE PASSWORD':
				require 'inc/processes/proc.account.php';
				break;
			case 'REPLY':
			case 'SHARE':
			case 'SEND MESSAGE':
				require 'inc/processes/proc.messaging.php';
				$bypass = true;
				break;
			case 'POST COMMENT':
			case 'POST REPLY':
			case 'ADD REPLY':
				require 'inc/processes/proc.comments.php';
				break;
			case 'ADD CONTENT':
			case 'ADD COURSE':
			case 'DELETE FOLDER':
			case 'UPDATE TOPIC':
			case 'DELETE COURSE':
			case 'UPDATE CHAPTER':
			case 'DELETE CHAPTER':
			case 'DELETE LESSON':
			case 'DELETE QUESTION':
			case 'DELETE QUIZ':
			case 'ADD PART':
			case 'UPDATE PART':
			case 'ADD QUIZ':
			case 'UPDATE QUIZ':
			case 'ADD QUESTION':
			case 'UPDATE QUESTION':
			case 'COMPLETE COURSE':
			case 'UPDATE LESSON ORDER':
			case 'UPDATE CHAPTER ORDER':
			case 'UPDATE QUESTION ORDER':
				require 'inc/processes/proc.upload.php';
				break;
			case 'ADD VIDEO':
				require 'inc/processes/proc.video.php';
				break;
			default:
				require 'inc/processes/proc.' . strtolower( $_POST['process'] ) . '.php';
		}
	}
}

if( !$bypass ) {
	$_POST['process'] = FALSE;
	/* echo "<pre>";
	print_r($_SESSION);
	echo "</pre>"; */
	include 'inc/html_head.php';
	
	if( $_SESSION['page'] != 'login' ) include $component . 'inc/site_head.php';
	
	if( !@include $component . 'inc/pages/' . $_SESSION['page'] . '.php' ) include 'inc/modules/construction.php';
	
	if( $_SESSION['page'] != 'login' ) include $component . 'inc/site_foot.php';
	
	include 'inc/html_foot.php';
}
?>