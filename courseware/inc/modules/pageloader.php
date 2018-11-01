<?
$path = 'courseware/courses/' . $crs->course['id'] . '/' . $lesson['id'] . '/' . $chapter['filename'];
?>
<div class="pancontainer" data-orient="center" data-canzoom="yes">
	<img id="img_primary" src="<?=$path.'(1).'.$chapter['extension']?>">
</div>