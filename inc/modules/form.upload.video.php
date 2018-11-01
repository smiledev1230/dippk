<form enctype="multipart/form-data" method="post" class="mycontent">
	<input type="hidden" name="proc_type" value="add video">
	<span class="fleft">
    	<label>Video Title</label><input type="text" name="title" required>
    	<label>Search Tags</label><input type="text" name="tags">
        <label>Video Description</label><textarea name="description">begin typing description here</textarea>
    </span>
    <span class="fright">
        <label>Choose a Section</label>
        <div class="selectBox">
            <div class="selectedBox">
                <span class="selected trigger" data-id="get_folder"></span>
                <div class="selectArrow"></div>
            </div>
            <div class="selectOptions">
                <?
				$options = $upload->get_sections();
				foreach( $options as $key => $val ) {
					?><span class="selectOption<? if( $key == $_SESSION['usection'] ) echo ' selectedOption'; ?>" value="<?=$key?>"><?=$val?></span><?
				}
				?>
            </div>
        </div>
        <label>Folder</label>
        <div id="folder_selector" class="selectBox">
            <div class="selectedBox">
                <span class="selected trigger" data-id="get_course"></span>
                <div class="selectArrow"></div>
            </div>
            <div class="selectOptions"></div>
        </div>
        <input type="hidden" name="folder" id="folder_selected">
        <label>Add to Existing Course? <span class="comment">(optional)</span></label>
        <div id="course_selector" class="selectBox">
            <div class="selectedBox">
                <span class="selected"></span>
                <div class="selectArrow"></div>
            </div>
            <div class="selectOptions"></div>
        </div>
        <input type="hidden" name="course" id="course_selected">
        <div class="customfile-container">
            <label>File to Upload</label>
            <input type="file" id="uploadfile" name="uploadfile" required>
        </div>
        <input type="submit" name="process" class="button fright" value="ADD VIDEO">
    </span>
</form>