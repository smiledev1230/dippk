<?php 
    //see if we are showing the result page or questions page
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */

?>
<?php
    $crs->get_quiz($req['quiz']);
    $quiz = $crs->quiz;
    $quiz_questions = $crs->get_quiz_questions($req['quiz']);
?>
<div class="crs_head_lg">QUIZ: <?=$crs->quiz['title']?></div>
<hr>
<?php if(isset($req['quiz_page']) && $req['quiz_page']=='result'):?>
    <?php 
        //show result page
        include "courseware/inc/modules/quiz-result.php";
    ?>
<?php else:?>
    <?php 
        //show questions page
        include "courseware/inc/modules/quiz.questions.php";
    ?>
<?php endif;?>
