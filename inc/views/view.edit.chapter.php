
<h2>EDIT PART</h2>

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
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    $upload = new Upload();
    include "classes/class.course.php";
    $crs = new Course();
    $chapter = $crs->get_chapter($req['id']);
?>
<?php 
    $vimeo = new Vimeo();
    $videos = $vimeo->get_album_videos('2711600');
?>
<form enctype="multipart/form-data" method="post" class="mycontent" id="chapter-form">
    <input type="hidden" name="chapter_id" value="<?php echo $chapter['ID'];?>">
    <input type="hidden" name="course" value="<?php echo $chapter['course'];?>">
    <input type="hidden" name="proc_type" value="update part">
    <input type="hidden" name="page" value="account">
    <input type="hidden" name="view" value="uploads">
    <span class="fleft">
        <label>Choose a Section</label>
        <?php $options = $upload->get_sections();?>
        <select name="section" id="section-dropdown" class="input-control" required>
            <option value="">Select Section</option>
            <?
                foreach( $options as $key => $val ) {
                    ?><option value="<?php echo $key;?>" <? if( $key == $chapter['menuID'] ) echo ' selected'; ?> value="<?=$key?>"><?=$val?></option><?
                }
            ?>
        </select>
        <label>Choose a Folder</label>
        <?php $options = $upload->get_folders($chapter['menuID']);?>
        <select name="folder" id="folder-dropdown" class="input-control" required>
            <option value="">Select Folder</option>
            <?
                foreach( $options as $key => $val ) {
                    ?><option value="<?php echo $key;?>" <? if( $key == $chapter['folder'] ) echo ' selected'; ?> value="<?=$key?>"><?=$val?></option><?
                }
            ?>
        </select>
        <label>Choose a Topic</label>
        <?php $options = $upload->get_courses_by_folder($chapter['folder']);?>
        <select name="course" id="course-dropdown" class="input-control" required>
            <option value="">Select Topic</option>
            <?
                foreach( $options as $key => $val ) {
                    ?><option value="<?php echo $key;?>" <? if( $key == $chapter['course'] ) echo ' selected'; ?> value="<?=$key?>"><?=$val?></option><?
                }
            ?>
        </select>
        <label>Choose a Chapter</label>
        <?php $options = $upload->get_lessons_by_course($chapter['course']);?>
        <select name="lesson" id="lesson-dropdown" class="input-control" required>
            <option value="">Select Chapter</option>
            <?
                foreach( $options as $key => $val ) {
                    ?><option value="<?php echo $key;?>" <? if( $key == $chapter['lesson'] ) echo ' selected'; ?> value="<?=$key?>"><?=$val?></option><?
                }
            ?>
        </select>
        <div class="new-lesson" style="display:none;">
            <label>Chapter Title</label><input type="text" name="newlesson" id="new-lesson-input" value="">
        </div>
        <label>Part Title</label><input type="text" name="title" required value="<?php echo $chapter['title'];?>">
        <label>Include a Video <span class="comment">(optional)</span></label>
        <select name="video" id="video-dropdown" class="input-control">
            <option value="">Select Video</option>
            <?
                foreach( $videos as $key => $video ) {
                    ?><option value="<?=$video['id']?>" <?php if($video['id']==$chapter['video']){ echo "selected='true'";}?> ><?=$video['name'];?></option><?
                }
            ?>
        </select>
        <div class="customfile-container">
            <label>Include a Document (PowerPoint or Word Document)  <span class="comment"> (optional)</span></label>
            <input type="file" id="uploadfile" name="uploadfile">
            <?php if(!empty($chapter['asset_name'])):?>
            <span class="comment"><strong>Note: This will replace the old file you have uploaded. So please select a file here only if you want to replace that file.</strong> </span>
            <?php endif;?>
        </div>
    </span>
    <div class="clearfix"></div>
    <label class="top_ad">Content</label>
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
<?php $filepath = 'courseware/courses/' . $crs->course['ID'] . '/' . $chapter['lesson'] . '/' . str_replace( ' ', '_', $chapter['title'] ) . '.txt';?>
<div id="editor" style="min-height:200px;margin-bottom:25px;">
    <?php 
        if( $data = @file_get_contents($filepath) ) {


                $data = str_replace( "\r\n", '<br>', $data );

                $data = str_replace( array("\r","\n"), '<br>', $data );

                echo $data;

        }
    ?>
</div>
<input type="submit" name="process" id="submit-btn" class="button fright submit-btn" value="UPDATE PART">
    
</form>
<div class="clearfix"></div>
<a class="button" style="color:#fff;" href="?page=account&action=edit&type=chapter&id=<?php echo $chapter['lesson'];?>">Back</a>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js" ></script>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
  var quill = new Quill('#editor', {
    modules: {
      syntax: true,
      toolbar: '#toolbar-container'
    },
    placeholder: 'Content....',
    theme: 'snow'
  });

  //add editor content to the form
    $('#chapter-form').submit(function(eventObj) {
        var content = $(".ql-editor").html();
        $(this).append('<textarea style="display:none" name="content">'+content+'</textarea> ');
        return true;
    });
</script>
