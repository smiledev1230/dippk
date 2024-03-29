<?
//need to grab section info on direct links
$vimeo = new Vimeo();
$docs = new Documents();

/* $video_info = $docs->get_albums($req['id']);
$album_title = $docs->docs[0]['title'];
$album_videos = $docs->docs[0]['videos'];
$_SESSION['debug']['ALBUM_VIDS'] = var_export( $album_videos, true );
if( $video_info ) {
	$video_id = $video_info['vid'];
	$active_doc = $video_info['doc'];
} else {
	$video_id = $docs->docs[0]['videos'][0]['vID'];
	$active_doc = $docs->docs[0]['videos'][0]['ID'];
} */
$video_id = $req['id'];
$video = $vimeo->get_video($req['id']);
$iframe_id = $video['width'] . 'x' . $video['height'] . 'x' . $req['id'];
$video_title = $video['name'];
$video_url = $vimeo->player_url( $video_id, true, $iframe_id );
?>
</div>
<div class="contain">
<div id="active_vim">
	<div class="vim_title"><?=$video_title?></div>
	<iframe id="<?=$iframe_id?>" src="<?=$video_url?>" frameborder="0" allowfullscreen></iframe>
    <div id="vim_data" class="hidden">
		<?
		if( $mycontent->dashboard['history'] ) echo 'seek:' . $mycontent->dashboard['history']['Progress'];
        ?>
    </div>
</div>
<div class="vim_control">
    <div class="fspace fright"></div>
    <?
    if( in_array( @$active_doc, $mycontent->dashboard['favorites'] ) ) {
		?><div id="afav<?=@$active_doc?>" class="btn_fav_main btn_main_active link_favorites fright"><div class="vim_text">FAVORITES</div></div><?
	} else {
		?><div id="afav<?=@$active_doc?>" class="btn_fav_main fright"><div class="vim_text">FAVORITES</div><div class="tt_img hidden"></div></div><?
	}
	?>
    <div class="fspace_vim fright"></div>
    <?
    if( in_array( @$active_doc, $mycontent->dashboard['watchlist'] ) ) {
		?><div id="awat<?=@$active_doc?>" class="btn_watch_main btn_main_active link_watchlist fright"><div class="vim_text">WATCHLIST</div></div><?
	} else {
		?><div id="awat<?=@$active_doc?>" class="btn_watch_main fright"><div class="vim_text">WATCHLIST</div><div class="tt_img hidden"></div></div><?
	}
	
	?>
	<div class="fspace_vim fright"></div>
    <div class="btn_messages fright"><div class="vim_text">SHARE</div></div>
    <div class="fspace_vim fright"></div>
    <div class="btn_comments fright"><div class="vim_text">COMMENTS</div></div>
</div>
</div>
<div class="contain">
<div id="main">
	<div id="sect_header">
        <div class="sect_title fleft"><?=@$section_name?></div>
        <div class="sect_name fright"></div>
        <div class="fclear"></div>
    </div>
	<div id="content_panel" class="fleft">
    	<?
        include 'inc/modules/comments-demo.php';
		include 'inc/modules/share-demo.php';
		?>
    </div>
    <div class="fclear"></div>
</div>