<div class="acc_head">Quiz History</div>
<?
$mycontent = new MyContent();
$mycontent->get_quiz_history();
$quiz_history = $mycontent->quiz_history;
?>
<?php if(count($quiz_history)):?>
<table class="table">
    <thead>
        <tr>
            <th>Quiz</th>
            <th>Topic</th>
            <th>Result</th>
            <th>Date Taken</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($quiz_history as $row):?>
            <tr>
                <td><?php echo $row['quiz_title'];?></td>
                <td><?php echo $row['course_title'];?></td>
                <td><?php echo ucfirst($row['result']);?></td>
                <td><?php echo date('m/d/Y',strtotime($row['dateTaken'])) ;?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php else:?>
<h3>No quiz taken yet.</h3>
<?php endif;?>
<div class="fclear"></div>