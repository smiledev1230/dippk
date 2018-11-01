<form class="top_ad" method="post">
<input type="hidden" name="process" value="ANSWER">
<input type="hidden" name="proc_type" value="answer">
<input type="hidden" name="quiz" value="<?php echo $req['quiz'];?>">
<?php $i=1; foreach($quiz_questions as $question):?>
    <div class="question-section">
        <div class="subhead"><?=$question['question']?></div>
        <?php if($question['question_type']=='TF'):?>
            <label>
                <input type="radio" value="True" name="question[<?php echo $question['ID'];?>]"> TRUE
            </label>
            <label>
                <input type="radio" value="False" name="question[<?php echo $question['ID'];?>]"> FALSE
            </label>
        <?php else:?>
            <?php $answers = $crs->get_question_answers($question['ID']);?>
            <?php foreach($answers as $answer):?>
                <p class="mc-answer">
                    <label>
                        <input type="checkbox" name="question[<?php echo $question['ID'];?>][]" value="<?php echo $answer['ID'];?>"> <?php echo $answer['answer_option'];?>
                    </label>
                </p>
            <?php endforeach;?>
        <?php endif;//check question type?>
    </div>

<?php $i++; endforeach;?>
<input type="submit" class="btn" value="SUBMIT">
</form>