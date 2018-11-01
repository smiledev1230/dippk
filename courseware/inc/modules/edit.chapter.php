<?
$lesson = $req['lesson'] ? $crs->lessons[$req['lesson']] : $crs->lessons[$crs->progress['lesson']['num']];
$chapter_num = $req['chapter'] ? $req['chapter'] : 1;
$chapter = $lesson['chapters'][$chapter_num];
$_SESSION['debug']['LESSON'] = var_export( $lesson, true );
$_SESSION['debug']['CHAPTER'] = var_export( $chapter, true );
if( $editor->message ) {
	?><div class="message"><?=$editor->message?></div><?
}
?>
<div class="crs_head_sm">
	<span id="crs_actv_lesson"><?=$lesson['title']?></span>
    <form id="crs_edit_lesson" method="post" class="hidden">
    	<input type="hidden" name="process" value="edit">
        <input type="hidden" name="lessonid" value="<?=$lesson['ID']?>">
    	<input type="text" name="title" value="<?=$lesson['title']?>">
        <input type="submit" class="crs_button top_15" name="proc_type" value="CHANGE LESSON TITLE">
    </form>
    <?
	if( $chapter['type'] == 'multi' ) {
		?><a class="expand crs_text_sm blue fright">expand >>></a><?
	}
	?>
</div>
<div class="crs_head_lg">
	<span id="crs_actv_title"><?=$chapter['title']?></span>
    <form id="crs_edit_title" method="post" class="hidden">
    	<input type="hidden" name="process" value="edit">
        <input type="hidden" name="chapterid" value="<?=$chapter['ID']?>">
    	<input type="text" name="title" value="<?=$chapter['title']?>">
        <input type="submit" class="crs_button" name="proc_type" value="CHANGE TITLE">
    </form>
    <?
	if( $chapter['type'] == 'multi' ) {
		?><a class="expand crs_text_sm blue fright">expand >>></a><?
	}
	?>
</div>
<div id="lsn_container">
<?
$video_options = $editor->get_videos();
?>
<form id="crs_edit_video" method="post" class="half_content hidden">
	<div class="message margin_bottom">This form is for working with existing site content only.  To upload a new video, use the ADD VIDEO tool under the Upload Content tab on the My Content page.</div>
	<input type="hidden" name="process" value="edit">
	<input type="hidden" name="chapterid" value="<?=$chapter['ID']?>">
	<?
    if( $chapter['video'] ) {
		?><label>Change Video</label><?
	} else {
		?><label>Add a Video</label><?
	}
	?>
	<div id="video_selector" class="selectBox">
		<div class="selectedBox">
			<span class="selected"></span>
			<div class="selectArrow"></div>
		</div>
		<div class="selectOptions">
			<?
			if( $chapter['video'] ) {
				?><span class="selectOption" value="remove">REMOVE VIDEO</span><?
			}
			foreach( $video_options as $key => $val ) {
				if( $key != $chapter['video'] ) {
					?><span class="selectOption" value="<?=$key?>"><?=$val?></span><?
				}
			}
			?>
		</div>
	</div>
	<input type="text" name="video" id="video_selected" class="hidden" required>
	<input type="submit" class="crs_button" name="proc_type" value="<? echo $chapter['video'] ? 'CHANGE': 'ADD'; ?> VIDEO">
</form>
<?
if( $chapter['video'] ) {
	$modules[] = 'video';
	$vimeo = new Vimeo();
	$active_video = $vimeo->get_video($chapter['video']);
	$iframe_id = $active_video->width . 'x' . $active_video->height . 'x' . $chapter['video'];
	$video_title = $active_video->title;
	$video_url = $vimeo->player_url( $chapter['video'], true, $iframe_id );
	?><div class="chp_vim"><iframe id="<?=$iframe_id?>" src="<?=$video_url?>" frameborder="0" allowfullscreen></iframe></div><?
}
?>
<form id="crs_edit_doc" method="post" class="half_content hidden">
	<div class="message margin_bottom">This form is for working with existing site content only.  To upload a new content, use the ADD OTHER CONTENT tool under the Upload Content tab on the My Content page.</div>
	<input type="hidden" name="process" value="edit">
	<input type="hidden" name="chapterid" value="<?=$chapter['ID']?>">
    <?
	if( $chapter['type'] != 'text' ) {
    	?>
        <input type="submit" class="crs_button" name="proc_type" value="REMOVE CURRENT DOCUMENT">
    	<div class="margin_bottom">OR</div>
        <?
	}
	$options = $editor->get_assets($req['id']);
	if( count( $options ) > 0 ) {
		?>
		<label>Use an Existing Course Asset</label>
		<div id="asset_selector" class="selectBox">
			<div class="selectedBox">
				<span class="selected"></span>
				<div class="selectArrow"></div>
			</div>
			<div class="selectOptions">
				<?
				foreach( $options as $key => $val ) {
					?><span class="selectOption" value="<?=$key?>"><?=$val?></span><?
				}
				?>
			</div>
			<div class="comment fright">OR <a onClick="$('#choose_asset').removeClass('hidden');" class="blue">import existing site content</a></div>
		</div>
		<input type="hidden" name="asset" id="asset_selected">
        <?
	}
	?>
    <span id="choose_asset"<? if( count( $options ) > 0 ) echo ' class="hidden"'; ?>>
        <label>Section</label>
        <div class="selectBox">
            <div class="selectedBox">
                <span class="selected trigger" data-id="get_folder"></span>
                <div class="selectArrow"></div>
            </div>
            <div class="selectOptions">
                <?
                $options = $editor->get_sections();
                foreach( $options as $key => $val ) {
                    ?><span class="selectOption<? if( $key == $_SESSION['usection'] ) echo ' selectedOption'; ?>" value="<?=$key?>"><?=$val?></span><?
                }
                ?>
            </div>
        </div>
        <label>Folder</label>
        <div id="folder_selector" class="selectBox no_add">
            <div class="selectedBox">
                <span class="selected trigger" data-id="get_folderfile"></span>
                <div class="selectArrow"></div>
            </div>
            <div class="selectOptions"></div>
        </div>
        <label>File</label>
        <div id="folderfile_selector" class="selectBox">
            <div class="selectedBox">
                <span class="selected"></span>
                <div class="selectArrow"></div>
            </div>
            <div class="selectOptions"></div>
        </div>
        <input type="hidden" name="document" id="folderfile_selected">
        <div class="message">Converting a file to a course asset may take several minutes.  Please do not refresh your browser.</div>
    </span>
    <input type="submit" class="crs_button top_ad" name="proc_type" value="<? echo ( $chapter['type'] != 'text' ) ? 'CHANGE': 'ADD'; ?> DOCUMENT">
</form>
<?
if( $chapter['type'] != 'text' ) {
	include 'courseware/loaders/' . $chapter['type'] . '.php';
	$modules[] = 'multi';
}
$filepath = 'courseware/courses/' . $crs->course['ID'] . '/' . $lesson['ID'] . '/' . str_replace( ' ', '_', $chapter['title'] ) . '.txt';
if( $data = @file_get_contents($filepath) ) {
	$modules[] = 'text';
	?>
    <span id="crs_actv_text">
        <div id="lsn_scroll" class="crs_text_lg"><?=nl2br($data)?></div>
	</span>
	<form id="crs_edit_text" method="post" class="hidden">
    	<input type="hidden" name="process" value="edit">
        <input type="hidden" name="chapterid" value="<?=$chapter['ID']?>">
        <textarea name="text"><?=$data?></textarea>
    	<input type="submit" class="crs_button fright top_ad" name="proc_type" value="CHANGE TEXT">
    </form>
	<?
} else {
	?>
    <form id="crs_edit_text" method="post" class="hidden">
    	<input type="hidden" name="process" value="edit">
        <input type="hidden" name="chapterid" value="<?=$chapter['ID']?>">
        <textarea name="text"><?=$data?></textarea>
    	<input type="submit" class="crs_button fright top_ad" name="proc_type" value="ADD TEXT">
    </form>
    <?
}
?>
</div>