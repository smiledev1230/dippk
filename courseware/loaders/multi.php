<?

if( isset($_POST['ajax']) ) {

	include '../../inc/common.php';

	$path = urldecode($_POST['path']);

	$extension = $_POST['extension'];

	$page_num = $_POST['page'];

} else {

	$path = 'courseware/courses/' . $crs->course['ID'] . '/' . $lesson['ID'] . '/' . str_replace( ' ', '_', $chapter['title'] );

	$extension = 'jpg';

	$page_num = 1;

}

$page_prev = ($page_num - 1) > 0 ? $page_num - 1: false;

$next_filename = SITE_ROOT.$path.'('.($page_num+1).').'.$extension;

$page_next = file_exists($next_filename) ? $page_num + 1: false;

?>

<div class="lsn_pagenav">

	<div data-id="<?=$page_prev?>" class="lsn_pagenav_btn tleft fleft"><? if( $page_prev ) { ?>&lt;&lt;&lt;&nbsp;PREV<? } ?></div>

    <div class="lsn_pagenum fleft">page <?=$page_num?></div>

    <div data-id="<?=$page_next?>" class="lsn_pagenav_btn tright fleft"><? if( $page_next ) { ?>NEXT&nbsp;&gt;&gt;&gt;<? } ?></div>

    <div class="fclear hidden">

		<span id="path"><?=urlencode($path)?></span>

        <span id="extension"><?=$extension?></span>

    </div>

</div>

<div class="pancontainer margin_bottom" data-orient="center" data-canzoom="yes">
	<?php if(file_exists($path.'('.$page_num.').'.$extension)):?>
		<img id="img_primary" src="<?=$path.'('.$page_num.').'.$extension?>">
	<?php endif;?>

</div>