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
    $courses = $crs->get_current_user_courses();
?>
<form method="post" class="mycontent">
    <input type="hidden" name="ctab" value="quiz">
    <input type="hidden" name="proc_type" value="add quiz">
    <input type="hidden" name="page" value="account">
    <input type="hidden" name="view" value="uploads">
    <span>
        <label>Quiz Purpose</label>
        <select name="purpose" id="purpose" required class="input-control" >
            <option value="">Select</option>
            <option value="course">Topic Completion</option>
            <option value="lesson">Chapter Completion</option>
            <option value="chapter">Part Completion</option>
        </select>

        <label>Choose a Topic</label>
        <select name="course" id="course-dropdown" class="input-control" required>
            <option value="">Select Topic</option>
            <?php foreach($courses as $course):?>
                <option value="<?php echo $course['ID'];?>"><?php echo $course['title'];?></option>
            <?php endforeach;?>
        </select>
        

        <label>Choose a Chapter <span class="comment">(for Chapter Completion only)</span></label>
        <select name="lesson" id="lesson-dropdown" class="input-control" >
            <option value="">Select Topic First</option>
        </select>

        <label>Choose a Part <span class="comment">(for Part Completion only)</span></label>
        <select name="chapter" id="chapter-dropdown" class="input-control">
            <option value="">Select Chapter First</option>
        </select>

        <label>Quiz Title</label><input type="text" name="title">

        <label>Percentage Required to Pass <span class="comment">(use numbers only)</span></label>
        <input type="number" min="1" name="passing" max="100" value="100" required>
    </span>
    <div class="fclear"></div>
    <input type="submit" name="process" class="button fright" value="ADD QUIZ">
</form>