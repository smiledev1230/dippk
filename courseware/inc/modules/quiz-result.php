<?php 
    $quiz_result = $crs->get_quiz_result($req['result_id']);
?>
<?php if($quiz_result['result']=='pass'):?>
    <div class="success-message">
        Congratulations! You have passed the quiz for <span style="text-transform:uppercase;"> <?php echo $crs->course['title']; ?></span>. Confirmation of the passed quiz will appear in your profile. You scored <?php echo $quiz_result['percentage'].'%';?>
    </div>
    <!-- <p>
        <a style="width: 550px;" href="?page=topic&id=<?php echo $crs->course['ID']; ?>" class="link-btn">Return to topic | <span style="text-transform:uppercase;"> <?php echo $crs->course['title']; ?></span></a>
    </p>
    <p>
        <a style="width: 550px;" href="?sect=false&view=topics" class="link-btn">View Other Topics</span></a>
    </p>
    <p>
        <a style="width: 550px;" href="?sect=false" class="link-btn">Return to ShareTopic Home</span></a>
    </p> -->

    <?php if($quiz['purpose']=='lesson'):?>
            <?php 
                //we need to check if this is the last lesson of this topic or not. If not show go to the next lesson button else show nothing 
                $is_last_lesson_of_course = $crs->is_last_lesson_of_course($crs->course['ID'],$quiz['lesson']);
            ?>
            <?php if(!$is_last_lesson_of_course): $next_lesson_number = $crs->get_next_lesson_number($crs->course['ID'],$quiz['lesson']); ?>
                <p>
                    <a href="?page=topic&view=chapter&chapter=<?php echo $next_lesson_number;?>" class="link-btn">Next Chapter</span></a>
                </p>
            <?php endif;?>
    <?php endif;?>

    <?php if ($quiz['purpose']=='chapter'):?>
            <?php 
                //we need to check if this is the last chapter of this lesson or not. If not show go to the next chpater button else show nothing
                // if this is the last part of this lesson then check if is this lesson the last of this topic or not. If not show go to the next lesson button else show nothing
                $is_last_chapter_of_lesson = $crs->is_last_chapter_of_lesson($quiz['lesson'],$quiz['chapter']);
                if(!$is_last_chapter_of_lesson): $next_chapter_number = $crs->get_next_chapter_number($quiz['lesson'],$quiz['chapter']); $next_lesson_number = $crs->get_next_lesson_number($crs->course['ID'], $quiz['lesson']);?>
                    <p>
                        <a href="?page=topic&view=chapter&chapter=<?php echo $next_lesson_number-1;?>&part=<?php echo $next_chapter_number;?>" class="link-btn">Next Part</span></a>
                    </p>
                <?php else:?>
                    <?php
                        //we need to check if this is the last lesson of this topic or not. If not show go to the next lesson button else show nothing
                        $is_last_lesson_of_course = $crs->is_last_lesson_of_course($crs->course['ID'], $quiz['lesson']);
                    ?>
                    <?php if (!$is_last_lesson_of_course): $next_lesson_number = $crs->get_next_lesson_number($crs->course['ID'], $quiz['lesson']); ?>
                        <p>
                            <a href="?page=topic&view=chapter&chapter=<?php echo $next_lesson_number-1;?>" class="link-btn">Next Chapter</span></a>
                        </p>
                    <?php endif;?>
                <?php endif;?>
    <?php endif;?>

<?php else:?>
    <div class="error-message" style="margin-top:15px;">
        You have failed. You scored <?php echo round($quiz_result['percentage']).'%';?>
    </div>
    <button class="btn" id="review-incorrect-answers">Review Incorrect Answers</button>

    <div id="answers-for-review" style="display:none;">
    <h2>Review Questions</h2>
    <h4><strong>Note: The correct answers are in <span class="correct-answer">GREEN</span> and your wrong answers are in <span class="wrong-answer"> RED</span>.</strong></h4>
    
        <?php 
            $wrong_answers = array();
            if(isset($_SESSION['wrong_answers'])){
                $wrong_answers = $_SESSION['wrong_answers'];
            }
            $user_answers = $_SESSION['questions'];
        ?>
        <?php $i=1; foreach ($quiz_questions as $question):?>
            <?php if(in_array($question['ID'],$wrong_answers)):?>
                <div class="question-section">
                    <div class="subhead"><?=$question['question']?></div>
                    <?php if ($question['question_type']=='TF'):?>
                        <?php $answers = $crs->get_question_answers($question['ID']);?>
                        <div class="mc-answer <?php if($user_answers[$question['ID']]=='True'){ echo 'wrong-answer';}?> <?php if($answers[0]['answer_option']=='True'){ echo 'correct-answer';}?> ">
                            True
                        </div>
                        <div class="mc-answer  <?php if($user_answers[$question['ID']]=='False'){ echo 'wrong-answer';}?> <?php if($answers[0]['answer_option']=='False'){ echo 'correct-answer';}?> ">
                            False
                        </div>
                    <?php else:?>
                        <?php $answers = $crs->get_question_answers($question['ID']);?>
                        <?php foreach ($answers as $answer):?>
                            <p class="mc-answer <?php if($answer['correct']=='Y'){ echo "correct-answer";}?> <?php if(in_array($answer['ID'],$user_answers[$question['ID']])){ echo 'wrong-answer';}?>">
                                <?php echo $answer['answer_option'];?>
                            </p>
                        <?php endforeach;?>
                    <?php endif;//check question type?>
                    <?php if(!empty($question['explanation'])):?>
                        <div class="q-explanation">
                            <?php echo $question['explanation'];?>
                        </div>
                    <?php endif;?>
                </div>
            <?php endif;?>
        <?php $i++; endforeach;?>

        <p>
            <a href="?page=topic&view=lesson&quiz=<?php echo $req['quiz'];?>" class="link-btn">Retake the Quiz</a>
            <a href="?page=topic&view=chapter" class="link-btn" target="_blank">View this Topic</a>
        </p>
    </div>
<?php endif;?>
<script>
    $("#review-incorrect-answers").on("click",function(){
        $(this).hide();
        $("#answers-for-review").show();
    })
</script>
<style>
#message-contributor-btn{
    width: 550px;    
}
</style>
