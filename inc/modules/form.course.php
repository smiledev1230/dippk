
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
<form enctype="multipart/form-data" method="post" class="mycontent">
        <input type="hidden" name="ctab" value="course">
        <input type="hidden" name="proc_type" id="proc_type" value="add course">
        <input type="hidden" name="process" id="process" value="ADD COURSE">
        <input type="hidden" name="page" value="account">
        <input type="hidden" name="view" value="uploads">

        <label>Topic Title</label><input type="text" name="title">
        <label>Topic Tagline</label><input type="text" name="tagline">
        <label>Topic Description</label><textarea name="description" placeholder="begin typing description here"></textarea>
        <label>Choose a Section</label>
        <?php $options = $upload->get_sections();?>
        <select name="section" class="input-control"  id="section-dropdown" required>
            <option value="">Select Section</option>
            <?
                foreach( $options as $key => $val ) {
                    ?><option value="<?php echo $key;?>" <? if( $key == @$_SESSION['usection'] ) echo ' selected'; ?> value="<?=$key?>"><?=$val?></option><?
                }
            ?>
            <option value="new_section">ADD NEW SECTION</option>
        </select>
        <div class="new_section"  style="display:none;">
            <input type="text" name="newsection" id="new-section-input" placeholder="Enter new section name">
        </div>
        <label>Folder</label>
        <?php $options = $upload->get_folders();?>
        <select name="folder" class="input-control" id="folder-dropdown" required>
            <option value="">Select Section First</option>
            <option value="new_folder">ADD NEW FOLDER</option>
        </select>
        <div class="new_folder"  style="display:none;">
            <input type="text" name="newfolder" id="new-folder-input" placeholder="Enter new folder name">
        </div>
        <div class="customfile-container">
            <label>Thumbnail (recommend 226x170, max 50kB)</label>
            <input type="file" id="thumbnail" name="thumbnail">
        </div>
        <div class="customfile-container">
            <label>Image for My Results page</label>
            <input type="file" id="imagefile" name="imagefile">
        </div>
        <div class="featurelabel">
        <label>Make This a Featured Item?</label>
        <input type="radio" name="featured" value="Y"><span class="radio_text">Yes</span>
        <input type="radio" name="featured" value="N" checked><span class="radio_text">No</span>
        </div>
        <div class="clearfix"></div>
        <input type="submit" name="action" class="button" value="Save As Draft">
        <input type="submit" name="action" class="button" value="Publish">
        <input type="submit" name="action" class="button" value="Unpublish">
</form>