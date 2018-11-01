<?
//need to grab section info on direct links
$vimeo = new Vimeo();
$docs = new Documents();

$video_info = $docs->get_albums($req['id']);
$album_title = $docs->docs[0]['title'];
$album_videos = $docs->docs[0]['videos'];
$_SESSION['debug']['ALBUM_VIDS'] = var_export( $album_videos, true );
if( $video_info ) {
	$video_id = $video_info['vid'];
	$active_doc = $video_info['doc'];
} else {
	$video_id = $docs->docs[0]['videos'][0]['vID'];
	$active_doc = $docs->docs[0]['videos'][0]['ID'];
}
$active_video = $vimeo->get_video($video_id);
$iframe_id = $active_video->width . 'x' . $active_video->height . 'x' . $video_id;
$video_title = $active_video->title;
$video_url = $vimeo->player_url( $video_id, true, $iframe_id );
?>
</div>
<div class="contain">
<div id="active_vim">
	<div id="active_doc" class="hidden"><?=$active_doc?></div>
	<div class="vim_title"><?=$album_title.': '.$video_title?></div>
	<iframe id="<?=$iframe_id?>" src="<?=$video_url?>" frameborder="0" allowfullscreen></iframe>
    <div id="vim_data" class="hidden">
		<?
		if( $mycontent->dashboard['history'] ) echo 'seek:' . $mycontent->dashboard['history']['Progress'];
        ?>
    </div>
</div>
<div class="vim_control">
    <div class="fspace fright"></div>
    <div class="btn_collapse fright"><div class="vim_text">COLLAPSE VIDEO</div></div>
    <?
    if( in_array( $active_doc, $mycontent->dashboard['favorites'] ) ) {
		?><div id="afav<?=$active_doc?>" class="btn_fav_main btn_main_active link_favorites fright"><div class="vim_text">FAVORITES</div></div><?
	} else {
		?><div id="afav<?=$active_doc?>" class="btn_fav_main fright"><div class="vim_text">FAVORITES</div><div class="tt_img hidden"></div></div><?
	}
	?>
    <div class="fspace_vim fright"></div>
    <?
    if( in_array( $active_doc, $mycontent->dashboard['watchlist'] ) ) {
		?><div id="awat<?=$active_doc?>" class="btn_watch_main btn_main_active link_watchlist fright"><div class="vim_text">WATCHLIST</div></div><?
	} else {
		?><div id="awat<?=$active_doc?>" class="btn_watch_main fright"><div class="vim_text">WATCHLIST</div><div class="tt_img hidden"></div></div><?
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
        <div class="sect_title fleft"><?=$section_name?></div>
        <div class="sect_name fright"></div>
        <div class="fclear"></div>
    </div>
	<div id="browse" class="fleft">
    	<? include 'inc/modules/categories.php'; ?>
    </div>
	<div id="content_panel" class="fleft">
    	<?
        include 'inc/modules/comments-demo.php';
		include 'inc/modules/share-demo.php';
		?>
        <div class="acc_head"><?=strtoupper($album_title)?></div>
        <?
		$i = 0; $firstrow = true;
		foreach( $album_videos as $video ) {
			?>
            <div id="vid-<?=$video['ID']?>" class="vid_preview link_albumvid fleft<? echo $firstrow ? '': ' top_ad'; ?>">
                <img src="<?=$video['vThumbnail']?>">
                <?
				if( in_array( $video['ID'], $mycontent->dashboard['favorites'] ) ) {
					?><div id="fav<?=$video['ID']?>" class="btn_fav btn_active link_favorites fright"></div><?
				} else {
					?><div id="fav<?=$video['ID']?>" class="btn_fav fright"><div class="tt_img hidden"></div></div><?
				}
				if( in_array( $video['ID'], $mycontent->dashboard['watchlist'] ) ) {
					?><div id="wat<?=$video['ID']?>" class="btn_watch btn_active link_watchlist fright"></div><?
				} else {
					?><div id="wat<?=$video['ID']?>" class="btn_watch fright"><div class="tt_img hidden"></div></div><?
				}
				?>
				<div class="subhead fclear up"><?=strtoupper($video['vTitle'])?></div>
                <div class="up"><?=$video['vDescription']?></div>
                <div class="course_break up"></div>
                <div class="up"><?=$docs->convert_duration( $video['vDuration'] )?></div></div>
            <?
			if( $i == 2 ) {
				?><div class="fclear"></div><?
			} else {
				?><div class="fspace fleft"></div><?
			}
			$i++;
			if( $i == 3 ) {
				$i = 0;
				$firstrow = false;
			}
		}
		if( $i < 3 ) {
			?><div class="fclear"></div><?
		}
		?>
    </div>
    <div class="fclear"></div>
</div>