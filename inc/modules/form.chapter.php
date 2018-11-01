<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/monokai-sublime.min.css" />
<?php 
    $vimeo = new Vimeo();
    $videos = $vimeo->get_db_videos();
?>
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
<div class="message btm_space">This section is for adding a chapter or part to an existing Topic.</div>
<form enctype="multipart/form-data" method="post" class="mycontent" id="chapter-form">
    <input type="hidden" name="ctab" value="course">
    <input type="hidden" name="proc_type" id="proc_type" value="add chapter">
    <input type="hidden" name="page" value="account">
    <input type="hidden" name="view" value="uploads">
    <div  style="float:right;padding-top:10px;/* padding-right:65px; */text-align:center;">
        <img src="img/ShareTopic-Add-Chapter-Part-Flow.jpg" />
    </div>
    
    <span class="fleft">
        <label>Choose a Section</label>
        <?php $options = $upload->get_sections();?>
        <select name="section" id="section-dropdown" class="input-control" required>
            <option value="">Select Section</option>
            <?
                foreach( $options as $key => $val ) {
                    ?><option value="<?php echo $key;?>" <? if( $key == @$_SESSION['usection'] ) echo ' selected'; ?> value="<?=$key?>"><?=$val?></option><?
                }
            ?>
        </select>
        <label>Choose a Folder</label>
        <select name="folder" id="folder-dropdown" class="input-control" required>
            <option value="">Select Section First</option>
        </select>
        <div class="new_folder"  style="display:none;">
            <input type="text" name="newfolder" id="new-folder-input" placeholder="Enter new folder name">
        </div>
        <label>Choose a Topic</label>
        <select name="course" id="course-dropdown" class="input-control" required>
            <option value="">Select Folder First</option>
        </select>
        <label>Choose a Chapter</label>
        <select name="lesson" id="lesson-dropdown" class="input-control" required>
            <option value="">Select Topic First</option>
        </select>
        <div class="new-lesson" style="display:none;">
            <label>Chapter</label><input type="text" name="newlesson" id="new-lesson-input" placeholdr="Enter new chapter name">
        </div>
        <label>Part Title</label><input type="text" name="title" required>
        <label>Include a Video <span class="comment">(optional)</span></label>
        <select name="video" id="video-dropdown" class="input-control">
            <option value="">Select Video</option>
            <?
                foreach( $videos as $key => $video ) {
                    ?><option value="<?=$video['vID']?>"><?=$video['vTitle'];?></option><?
                }
            ?>
        </select>
        <div class="customfile-container">
            <label>Include a Document (PowerPoint or Word Document)  <span class="comment"> (optional)</span></label>
            <input type="file" id="uploadfile" name="uploadfile">
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
<div id="editor" style="min-height:200px;margin-bottom:25px;">
    <p><br></p>
</div>
    <input type="submit" name="process" id="submit-btn" class="button fright submit-btn" value="ADD PART">
</form>


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

  // Enable all tooltips
  $('[data-toggle="tooltip"]').tooltip();
  
  //add editor content to the form
    $('#chapter-form').submit(function(eventObj) {
        var content = $(".ql-editor").html();
        $(this).append('<textarea style="display:none" name="content">'+content+'</textarea> ');
        return true;
    });
</script>

