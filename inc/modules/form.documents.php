<div class="title">Add Document Form</div>
<form enctype="multipart/form-data" class="add_form">
    <label>Title</label><input type="text" name="title" required>
    <label>Description</label><textarea name="description">begin typing description here</textarea>
    <label>Section</label>
    <div class="selectBox">
    	<div class="selectedBox">
            <span class="selected"></span>
            <div class="selectArrow"></div>
        </div>
        <div class="selectOptions">
        	<?
			foreach( $bpan->data as $col => $array ) {
				foreach( $array as $key => $val ) {
					?><span class="selectOption<? if( $key == $_SESSION['sect'] ) echo ' selectedOption'; ?>" value="<?=$key?>"><?=$val?></span><?
				}
			}
			?>
        </div>
    </div>
	<label>Folder</label>
    <div class="selectBox">
    	<div class="selectedBox">
            <span class="selected"></span>
            <div class="selectArrow"></div>
        </div>
        <div class="selectOptions">
        	<?
			foreach( $docs->docs as $key => $array ) {
				?><span class="selectOption" value="<?=$array['title']?>"><?=$array['title']?></span><?
			}
			?>
        </div>
    </div>
    <div class="customfile-container">
    	<label>File to Upload</label>
    	<input type="file" id="uploadfile" name="uploadfile" required>
    </div>
    <input type="submit" onClick="alert('Sorry. For security reasons, uploads cannot be performed on this demo.');" name="process" class="fright" value="Add Document">
</form>