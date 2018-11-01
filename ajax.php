<?php 
include 'inc/app_top.php';

//see what we have to return
$get = $_POST['get'];

switch ($get) {
    case 'folders':
        $upload = new Upload();
        $section = $_POST['section'];
        $options = $upload->get_folders($section);
        echo json_encode($options);
        break;
    case 'courses':
        $upload = new Upload();
        $folder = $_POST['folder'];
        $options = $upload->get_courses_by_folder($folder);
        echo json_encode($options);
        break;
    case 'lessons':
        $upload = new Upload();
        $course = $_POST['course'];
        $lessons = $upload->get_lessons_by_course($course);
        $assets = $upload->get_assets($course);
        $data = array('lessons'=>$lessons,'assets'=>$assets);
        echo json_encode($data);
        break;
    case 'chapters':
        $upload = new Upload();
        $lesson = $_POST['lesson'];
        $lessons = $upload->get_chapters_by_lesson($lesson);
        $data = array('chapters'=>$lessons);
        echo json_encode($data);
        break;
    case 'files':
        $upload = new Upload();
        $folder = $_POST['folder'];
        $options = $upload->get_folder_files($folder);
        echo json_encode($options);
        break;
    case 'send message':
        $message = new Message();
        $message->send_message();
        echo json_encode(array('success'=>true,'message'=>'Your message has been sent to the Content Contributor.'));
        break;
    case 'approve-user':
        $admin = new Admin();
        $admin->approve_user();
        echo json_encode(array('success'=>true,'message'=>'The user has been approved.'));
        break;
    case 'block-user':
        $admin = new Admin();
        $admin->block_user();
        echo json_encode(array('success'=>true,'message'=>'The user has been blocked.'));
        break;
    case 'unblock-user':
        $admin = new Admin();
        $admin->unblock_user();
        echo json_encode(array('success'=>true,'message'=>'The user has been unblocked.'));
        break;
    default:
        # code...
        break;
}



?>