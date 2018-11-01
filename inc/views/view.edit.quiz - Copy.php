<h2>Edit Quiz</h2>
<hr>
<?php if(isset($_SESSION['error'])):?>
    <div class="error-message">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif;?>
<?php if(isset($_SESSION['success'])):?>
    <div class="success-message">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif;?>
<?php 
    require 'classes/class.course.php';
    $crs = new Course();
    $upload = new Upload();
    $courses = $crs->get_current_user_courses();
    $crs->get_quiz($req['id']);
    $quiz = $crs->quiz;

    //get lessons
    $lessons = $upload->get_lessons_by_course($quiz['course']);

    //get chapters
    $chapters = $upload->get_chapters_by_lesson($quiz['lesson']);

    //get this quiz's question
    $questions = $crs->get_quiz_questions($req['id']);

    
?>

<form method="post" class="mycontent">
    <input type="hidden" name="ctab" value="quiz">
    <input type="hidden" name="proc_type" value="update quiz">
    <input type="hidden" name="page" value="account">
    <input type="hidden" name="view" value="uploads">
    <input type="hidden" name="ID" value="<?php echo $quiz['ID'];?>">
    <span>
        <label>Quiz Purpose</label>
        <select name="purpose" id="purpose" required class="input-control" >
            <option value="">Select</option>
            <option value="course" <?php if($quiz['purpose']=='course'){ echo 'selected';};?>>Topic Completion</option>
            <option value="lesson" <?php if($quiz['purpose']=='lesson'){ echo 'selected';};?>>Chapter Completion</option>
            <!--<option value="chapter" <?php if($quiz['purpose']=='chapter'){ echo 'selected';};?>>Part Completion</option>-->
        </select>

        <label>Choose a Topic</label>
        <select name="course" id="course-dropdown" class="input-control" required>
            <option value="">Select Topic</option>
            <?php foreach($courses as $course):?>
                <option value="<?php echo $course['ID'];?>"  <?php if($quiz['course']==$course['ID']){ echo 'selected';};?>><?php echo $course['title'];?></option>
            <?php endforeach;?>
        </select>
        

        <label>Choose a Chapter <span class="comment">(for Chapter Completion only)</span></label>
        <select name="lesson" id="lesson-dropdown" class="input-control" required>
            <option value="">Select Chapter</option>
            <?php foreach($lessons as $key_l => $lesson):?>
                <option value="<?php echo $key_l;?>"  <?php if($quiz['lesson']==$key_l){ echo 'selected';};?>><?php echo $lesson;?></option>
            <?php endforeach;?>
        </select>

        <label>Choose a Part <span class="comment">(for Part Completion only)</span></label>
        <select name="chapter" id="chapter-dropdown" class="input-control">
            <option value="">Select a Part</option>
            <?php foreach($chapters as $key_c => $chapter):?>
                <option value="<?php echo $key_c;?>"  <?php if($quiz['chapter']==$key_c){ echo 'selected';};?>><?php echo $chapter;?></option>
            <?php endforeach;?>
        </select>

        <label>Quiz Title</label><input type="text" name="title" value="<?=$quiz['title'];?>">

        <label>Percentage Required to Pass <span class="comment">(use numbers only)</span></label>
        <input type="number" min="1" name="passing" max="100" value="<?=$quiz['passing'];?>" required>
    </span>
    <div class="fclear"></div>
    <input type="submit" name="process" class="button fright" value="UPDATE QUIZ">
</form>
<div class="clearfix"></div>
<hr>
<h2>Questions</h2>
<table class="c-table">
    <tr>
        <th>Question </th>
        <th>Question Order</th>
        <th style="width:150px;" class="text-center">Action</th>
    </tr>
    <?php $i=1; foreach($questions as $question): ?>
    <tr>
        <td><?php echo $question['question'];?></td>
        <td><input type="text" class="display_order" tabindex="<?php echo $i;?>" data-id="<?php echo $question['ID']; ?>" value="<?php echo $question['display_order'];?>"></td>
        <td class="text-center">
            <a href="?page=account&action=edit&type=question&id=<?php echo $question['ID']; ?>">Edit</a> |
            <span class="delete-btn delete-question" data-id="<?php echo $question['ID']; ?>">Delete</span>
        </td>
    </tr>
    <?php $i++; endforeach;?>
</table>
<script>
    $(".delete-question").on("click",function(){
        var confirm = window.confirm("Do you really want to delete this?");
        if(confirm){
            var id = $(this).data('id');
            $this = $(this);
            $.ajax({
                url:'index.php',
                method:'post',
                dataType:'json',
                data:{
                    process:'DELETE QUESTION',
                    proc_type:'delete question',
                    id:id
                },
                success:function(data){
                    if(data.success){
                        $(".message-box").html('<div class="success-message">'+data.message+'</div>');
                        $this.parent().parent().remove();
                    }
                    else{
                        $(".message-box").html('<div class="error-message">' + data.message + '</div>');
                    }
                    
                },
                error:function(err){
                    console.log(err);
                }

            })
        }
    })

    $(".display_order").on("blur",function(){
        var display_order = $(this).val();
        var id = $(this).data('id');
        $.ajax({
                url:'index.php',
                method:'post',
                dataType:'json',
                data:{
                    process:'UPDATE QUESTION ORDER',
                    proc_type:'update question order',
                    id:id,
                    display_order:display_order
                },
                error:function(err){
                    console.log(err);
                }

            })
    })


</script>