<? $req['id'] = '2453704'; ?>
<div class="acc_head">UPLOAD NEW CONTENT</div>
<?
$vimeo = new Vimeo();
$courses = $vimeo->get_course_previews();
$titles = array();
foreach( $courses as $course ) {
	if( substr( $course['title'], 0, strlen( $vimeo->site_prefix ) ) == $vimeo->site_prefix ) {
		$titles[] = str_replace( $vimeo->site_prefix, '', $course['title'] );
	} else {
		$titles[] = $course['title'];
	}
}
natsort($titles);
$sections = array();
foreach( $bpan->data as $col => $array ) {
	foreach( $array as $key => $val ) {
		$sections[] = $val;
	}
}
natsort($sections);
?>
<div class="acc_upload fleft">
	<div class="browse_head2">ADD VIDEO</div>
    <form class="acc">
    	<label>Course</label><select name="album">
        	<?
			foreach( $titles as $title ) {
				?><option><?=$title?></option><?
			}
			?>
        </select>
        <label>Title</label><input type="text" name="title" required>
        <label>Description</label><textarea name="desc"></textarea>
        <label>Search Tags</label><input type="text" name="tags">
        <input type="file" name="uploadfile" required>
        <input type="button" onClick="alert('Sorry. For security reasons, uploads cannot be performed on this demo.');" name="process" id="btn_media_big" class="fright" value="Add Video">
    </form>
</div>
<div class="acc_upload fright">
	<div class="browse_head2">ADD OTHER CONTENT</div>
    <form class="acc">
    	<label>Title</label><input type="text" name="title" required>
        <label>Section</label><select name="section">
        	<?
			foreach( $sections as $section ) {
				?><option><?=$section?></option><?
			}
			?>
        </select>
        <label>Type</label><select name="type">
        	<option>Documents</option>
            <option>Pictures</option>
            <option>Presentations</option>
		</select>
        <input type="file" name="uploadfile" required>
        <input type="button" onClick="alert('Sorry. For security reasons, uploads cannot be performed on this demo.');" name="process" id="btn_media_big" class="fright" value="Add Content">
    </form>
</div>
<div class="fclear"></div>
<div class="acc_head">UPLOAD HISTORY</div>
<?
$videos = $vimeo->get_course($req['id']);
$sorted_videos = $vimeo->sort_by_upload($videos['videos']);
$i = 0; $firstrow = true;
foreach( $sorted_videos as $video ) {
	?>
    <div id="crsvid-<?=$req['id']?>:<?=$video->id?>" class="watched upload fleft top_space link_coursevid">
        <img src="<?=$video->thumbnails->thumbnail[1]->_content?>" class="fleft">
        <div class="text_sm fleft">Uploaded on: <?=$video->upload_date?></div><div class="browse_head fleft"><?=strtoupper($video->title)?></div>
        <div class="fclear"></div>     
    </div>
	<?
	if( $i == 1 ) {
		?><div class="fclear"></div><?
	} else {
		?><div class="fspace fleft"></div><?
	}
	$i++;
	if( $i == 2 ) {
		$i = 0;
		$firstrow = false;
	}
}
if( $i < 2 ) {
	?><div class="fclear"></div><?
}
?>