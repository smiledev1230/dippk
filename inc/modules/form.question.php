<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/monokai-sublime.min.css" />
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
    $quizzes = $crs->get_current_user_quizzes();
?>
<!-- <div class="message">Any images or video intended to be used as part of a question should first be loaded via the Upload Content tab.</div> -->
<form enctype="multipart/form-data" method="post" class="mycontent top_ad" id="question-form">
    <input type="hidden" name="ctab" value="quiz">
    <input type="hidden" name="proc_type" value="add question">
    <input type="hidden" name="page" value="account">
    <input type="hidden" name="view" value="uploads">
    <span>
        <label>Choose a Quiz</label>
        <select name="quiz" id="quiz-dropdown" class="input-control" required>
            <option value="">Select Quiz</option>
            <?php foreach($quizzes as $quiz):?>
                <option value="<?php echo $quiz['ID'];?>" <?php if(isset($_GET['quiz']) && $_GET['quiz']==$quiz['ID']){ echo 'selected';}?> ><?php echo $quiz['title'];?></option>
            <?php endforeach;?>
        </select>


        <label>Question Type</label>
        <select name="question_type" id="question-type-dropdown" class="input-control" required>
            <option value="TF">True/False</option>
            <option value="MC">Multiple Choice</option>
        </select>


        <label>Question</label>
        <div id="toolbar-container">
            <span class="ql-formats">
            <select class="ql-font"></select>
            <select class="ql-size"></select>
            </span>
            
<span class="ql-formats">
<button class="ql-bold" data-toggle="tooltip" data-placement="bottom" title="Bold"></button>
<button class="ql-italic" data-toggle="tooltip" data-placement="bottom" title="Italic"></button>
<button class="ql-underline" data-toggle="tooltip" data-placement="bottom" title="Underline"></button>
<button class="ql-strike" data-toggle="tooltip" data-placement="bottom" title="Strikethrough"></button>
</span>
<span class="ql-formats">
<select class="ql-color" data-toggle="tooltip" data-placement="bottom" title="Font Color"></select>
<select class="ql-background" data-toggle="tooltip" data-placement="bottom" title="Highlight"></select>
</span>
<span class="ql-formats">
<button class="ql-script" value="sub" data-toggle="tooltip" data-placement="bottom" title="Subscript"></button>
<button class="ql-script" value="super" data-toggle="tooltip" data-placement="bottom" title="Superscript"></button>
</span>
<span class="ql-formats">
<button class="ql-header" value="1" data-toggle="tooltip" data-placement="bottom" title="Heading 1"></button>
<button class="ql-header" value="2" data-toggle="tooltip" data-placement="bottom" title="Heading 2"></button>
<button class="ql-blockquote" data-toggle="tooltip" data-placement="bottom" title="Standout Quote"></button>
<button class="ql-code-block" data-toggle="tooltip" data-placement="bottom" title="Reverse Font"></button>
</span>
<span class="ql-formats">
<button class="ql-list" value="ordered" data-toggle="tooltip" data-placement="bottom" title="Numbering"></button>
<button class="ql-list" value="bullet" data-toggle="tooltip" data-placement="bottom" title="Bullet Points"></button>
<button class="ql-indent" value="-1" data-toggle="tooltip" data-placement="bottom" title="Left Indent"></button>
<button class="ql-indent" value="+1" data-toggle="tooltip" data-placement="bottom" title="Right Indent"></button>
</span>
<span class="ql-formats">
<button class="ql-direction" value="rtl" data-toggle="tooltip" data-placement="bottom" title="Paragraph Indentation"></button>
<select class="ql-align" data-toggle="tooltip" data-placement="bottom" title="Alignment"></select>
</span>
<span class="ql-formats">
<button class="ql-link" data-toggle="tooltip" data-placement="bottom" title="Insert Link"></button>
<button class="ql-image" data-toggle="tooltip" data-placement="bottom" title="Insert Image"></button>
<button class="ql-video" data-toggle="tooltip" data-placement="bottom" title="Insert Video"></button>
<button class="ql-formula" data-toggle="tooltip" data-placement="bottom" title="Insert Formula"></button>
</span>
<span class="ql-formats">
<button class="ql-clean" data-toggle="tooltip" data-placement="bottom" title="Remove Formatting"></button>
</span>
        </div>
        <div id="editor" style="min-height:200px;margin-bottom:25px;">
            <p><br></p>
        </div>

        <span id="answer_truefalse">
        	<label>Answer</label>
            <input type="radio" name="answer_truefalse" value="True" checked><span class="radio_text">True</span>
        	<input type="radio" name="answer_truefalse" value="False"><span class="radio_text">False</span>
        </span>
        <span id="answer_multiple" class="hidden">
        	<label>Answers <span class="comment">- check the correct answer(s)</label>
            <input type="checkbox" name="correct_multi_1" value="1"><input type="text" class="multi_answer" name="multi_1">
            <input type="checkbox" class="multi_last" name="correct_multi_2" value="2"><input type="text" class="multi_answer" name="multi_2">
            <div class="comment tright multi_add"><a onClick="add_multi_answer();" class="blue">add another option</a></div>
        </span>
        <label>Explanation <span class="comment">(shown on review after completion)</span></label>
        <textarea name="explanation"></textarea>

        <input type="submit" name="process" class="button fright" value="ADD QUESTION">
        
    </span>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js" ></script>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
  var quill = new Quill('#editor', {
    modules: {
      syntax: true,
      toolbar: '#toolbar-container'
    },
    placeholder: 'Question goes here....',
    theme: 'snow'
  });
  //add editor content to the form
    $('#question-form').submit(function(eventObj) {
        var content = $(".ql-editor").html();
        $(this).append('<textarea style="display:none" name="question">'+content+'</textarea> ');
        return true;
    });
</script>