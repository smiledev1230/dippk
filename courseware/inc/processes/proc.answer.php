<?php 
    switch ($_POST['proc_type']) {
        case 'answer':
            $upload = new Upload();
            $return = $upload->answer_question();
            header('Location:'.$return);

		    break;
        default:
            break;
    }
?>