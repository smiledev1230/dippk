<?
$bypass = true;

switch( $_POST['proc_type'] ) {
	
	case 'change tab':
		include 'inc/modules/uploads.' . $_POST['tabview'] . '.php';
		break;
	case 'change page':
		include 'inc/modules/uploads.view.docs.php';
		break;
	case 'get_folder':
	case 'get_folderc':
	case 'get_course':
	case 'get_lesson':
	case 'get_asset':
	case 'get_folderfile':
	case 'get_quiz':
		include 'inc/modules/uploads.select.php';
		break;
	case 'Published':
	case 'Unpublished':
		$upload = new Upload();
		$upload->change_publish_state($_POST['proc_type']);
	case 'add content':
		$upload = new Upload();
		$upload->add_content();
		header('Location:index.php?page=account&view=uploads');
		$bypass = false;
		break;
	case 'add course':

		$upload = new Upload();
		if( $upload->add_course() ){
			$_SESSION['success'] = $upload->message;
			header('Location:index.php?page=account&view=uploads&ctab=course&form=chapter');
		}
		else{
			$_SESSION['error'] = $upload->message;
			header('Location:index.php?page=account&view=uploads&ctab=course&form=course');
		}
		break;
	case 'delete course':
		$upload = new Upload();
		$id = $_POST['id'];
		$upload->deleteCourse($id);
		echo json_encode(array('success'=>true,'message'=>'Successfully deleted the topic.'));
		exit;
		break;
	case 'delete quiz':
		$upload = new Upload();
		$id = $_POST['id'];
		$upload->deleteQuiz($id);
		echo json_encode(array('success'=>true,'message'=>'Successfully deleted the quiz.'));
		exit;
		break;
	case 'complete course':
		sleep(2);
		$crs = new Course();
		$course = $_POST['course'];
		$lesson = $_POST['lesson'];
		$chapter = $_POST['chapter'];
		$crs->completeCourse($course,$lesson,$chapter);
		$crs->completeLesson($course,$lesson);
		$crs->completeChapter($course,$lesson,$chapter);
		echo json_encode(array('success'=>true,'message'=>'Successfully completed the topic.'));
		exit;
		break;
	case 'update topic':
		$upload = new Upload();
		$upload->update_course();
		if( $upload->update_course() ) {
			$_SESSION['success'] = $upload->message;
		}
		else{
			$_SESSION['error'] = $upload->message;
		}
		header('Location:'.$_SERVER['HTTP_REFERER']);
		break;
	case 'update chapter':
		$upload = new Upload();
		if( $upload->update_lesson() ) {
			$_SESSION['success'] = $upload->message;
		}
		else{
			$_SESSION['error'] = $upload->message;
		}
		$bypass = false;
		header('Location:'.$_SERVER['HTTP_REFERER']);
		break;
	case 'add chapter':
		$upload = new Upload();
		if( $upload->add_chapter() ) {
			$_SESSION['success'] = $upload->message;
			header('Location:index.php?page=account&view=uploads&ctab=course&form=chapter');
		}
		else{
			$_SESSION['error'] = $upload->message;
			header('Location:index.php?page=account&view=uploads&ctab=course&form=topic');
		}
		
		
		break;
	case 'delete course':
		$upload = new Upload();
		$id = $_POST['id'];
		$upload->deleteCourse($id);
		echo json_encode(array('success'=>true,'message'=>'Successfully deleted the topic.'));
		exit;
		break;
	case 'update part':
		$upload = new Upload();
		if( $upload->update_chapter() ) {
			$_SESSION['success'] = $upload->message;
		}
		else{
			$_SESSION['error'] = $upload->message;
		}
		header('Location:'.$_SERVER['HTTP_REFERER']);
		break;
	case 'delete chapter':
		$upload = new Upload();
		$id = $_POST['id'];
		$upload->deleteChapter($id);
		echo json_encode(array('success'=>true,'message'=>'Successfully deleted the part.'));
		exit;
		break;
	case 'delete lesson':
		$upload = new Upload();
		$id = $_POST['id'];
		$upload->deleteLesson($id);
		echo json_encode(array('success'=>true,'message'=>'Successfully deleted the chapter.'));
		exit;
		break;
	case 'delete question':
		$upload = new Upload();
		$id = $_POST['id'];
		$upload->deleteQuestion($id);
		echo json_encode(array('success'=>true,'message'=>'Successfully deleted the question.'));
		exit;
		break;
	case 'add quiz':
		$upload = new Upload();
		if( $upload->add_quiz() ) {
			$_SESSION['success'] = $upload->message;
			header('Location:index.php?page=account&view=uploads&ctab=quiz&form=question');
		}
		else{
			$_SESSION['error'] = $upload->message;
			header('Location:index.php?page=account&view=uploads&ctab=quiz&form=quiz');
		}
		
		break;
	case 'update quiz':
		$upload = new Upload();
		if( $upload->update_quiz() ) {
			$_SESSION['success'] = $upload->message;
		}
		else{
			$_SESSION['error'] = $upload->message;
		}
		header('Location:'.$_SERVER['HTTP_REFERER']);
		break;
	case 'add question':
		$upload = new Upload();
		if( $upload->add_question() ) {
			$_SESSION['success'] = $upload->message;
		}
		else{
			$_SESSION['error'] = $upload->message;
		}
		header('Location:'.$_SERVER['HTTP_REFERER']);
		$bypass = false;
		break;
	case 'update question':
		$upload = new Upload();
		if( $upload->update_question() ) {
			$_SESSION['success'] = $upload->message;
		}
		else{
			$_SESSION['error'] = $upload->message;
		}
		header('Location:'.$_SERVER['HTTP_REFERER']);
		$bypass = false;
		break;
	case 'update lesson order':
		$upload = new Upload();
		$upload->update_lesson_order();
		break;
	case 'update chapter order':
		$upload = new Upload();
		$upload->update_chapter_order();
		break;
	case 'update question order':
		$upload = new Upload();
		$upload->update_question_order();
		break;
	case 'answer question':
	
		$upload = new Upload();
		$upload->answer_question();
		break;
	case 'delete folder':
		$upload = new Upload();
		$id = $_POST['id'];
		$return = $upload->deleteFolder($id);
		echo json_encode($return);
		exit;
		break;
	
}
?>