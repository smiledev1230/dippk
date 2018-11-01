<form enctype="multipart/form-data" method="post" class="mycontent">
	<input type="hidden" name="proc_type" value="add content">
	<span class="fleft">
    	<label>Content Title</label><input type="text" name="title" required>
    	<label>Short Description</label><input type="text" name="description">
        <label>Search Tags <span class="comment">(separate by commas)</span></label><input type="text" name="tags">
        <label>Choose Content Type</label>
        <div id="cat_selector" class="selectBox">
            <div class="selectedBox">
                <span class="selected"></span>
                <div class="selectArrow"></div>
            </div>
            <div class="selectOptions">
                <?
				$options = $upload->get_categories();
				foreach( $options as $key => $val ) {
					?><span class="selectOption" value="<?=$key?>"><?=$val?></span><?
				}
				?>
            </div>
        </div>
        <input type="hidden" name="cat" id="cat_selected">
        <div class="customfile-container">
            <label>File to Upload</label>
            <input type="file" id="uploadfile" name="uploadfile" required>
        </div>
    </span>
    <span class="fright">
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
        <label>Folder</label>
        <select name="folder" id="folder-dropdown" class="input-control" required>
            <option value="">Select Section First</option>
        </select>
        <label>Featured Item?</label>
        <input type="radio" name="featured" value="Y"><span class="radio_text">Yes</span>
        <input type="radio" name="featured" value="N" checked><span class="radio_text">No</span>
        <label>Add to Existing Topic? <span class="comment">(optional)</span></label>
        <select name="course" id="course-dropdown" class="input-control" required>
            <option value="">Select Folder First</option>
        </select>
        
        <label>Topic Asset Title <span class="comment">(optional)</span></label>
        <input type="text" name="asset">
        <input type="submit" name="process" class="button fright" value="ADD CONTENT">
        <div class="message">Larger files may take several minutes for upload and conversion.  Do not stop or refresh your browser until it is complete.</div>
    </span>
</form>