<?
/* TO DO LIST
	Course author value
	Course progress dynamics
	Activate 'Add to' buttons
*/
$vimeo = new Vimeo();
$course = $vimeo->get_course($req['id']);
//$_SESSION['debug']['COURSE'] = var_export($course['videos'],true);
if( $req['v'] ) {
	$video_id = $req['v'];
	foreach( $course['videos'] as $video ) {
		if( $video->id == $req['v'] ) {
			$iframe_id = $video->width . 'x' . $video->height . 'x' . $video_id;
			$video_title = $video->title;
			break;
		}
	}
} else {
	$video_id = $course['videos'][0]->id;
	$iframe_id = $course['videos'][0]->width . 'x' . $course['videos'][0]->height . 'x' . $video_id;
	$video_title = $course['videos'][0]->title;
}
$video_url = $vimeo->player_url( $video_id, true, $iframe_id );
$_SESSION['album_id'] = $req['id'];
$_SESSION['video_id'] = $video_id;
$mycontent = new MyContent();
$mycontent->get_dashboard( $video_id );
?>
</div>
<div class="contain">
<div id="active_vim">
	<div class="vim_title"><?=$course['title'].': '.$video_title?></div>
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
    if( in_array( $video_id, $mycontent->dashboard['favorites']['videos'] ) ) {
		?><div class="btn_fav_main btn_main_active link_favorites fright"><div class="vim_text">FAVORITES</div></div><?
	} else {
		?><div class="btn_fav_main fright"><div class="vim_text">FAVORITES</div><div class="tt_img hidden"></div></div><?
	}
	?>
    <div class="fspace_vim fright"></div>
    <?
    if( in_array( $video_id, $mycontent->dashboard['watchlist']['videos'] ) ) {
		?><div class="btn_watch_main btn_main_active link_watchlist fright"><div class="vim_text">WATCHLIST</div></div><?
	} else {
		?><div class="btn_watch_main fright"><div class="vim_text">WATCHLIST</div><div class="tt_img hidden"></div></div><?
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
	<div id="browse" class="fleft">
    	<div class="course_btn">
        	<div class="icon_box fleft"><img src="img/icons/crs_download.png"></div>
            <div class="label_box fleft">DOWNLOAD<br>PROJECT FILES</div>
        </div><div class="fclear"></div>
    	<div class="course_btn top_15">
        	<div class="icon_box fleft"><img src="img/icons/crs_playlist.png"></div>
            <div class="label_box fleft">ADD TOPIC<br>TO WATCHLIST</div>
        </div><div class="fclear"></div>
    	<div class="course_btn top_15">
        	<div class="icon_box fleft"><img src="img/icons/crs_favorites.png"></div>
            <div class="label_box fleft">ADD TOPIC<br>TO FAVORITES</div>
        </div><div class="fclear"></div>
    	<div class="browse_head2 top_ad">TOPIC PROGRESS</div>
        <div class="progress_bar"></div><div class="progress_fill">25%</div>
        <div class="browse_head2">TOPIC DETAILS</div>
        <b>Run Time:&nbsp;</b><?=$vimeo->convert_duration($course['runtime'])?><br>
        <b>Release Date:<br></b><?=$vimeo->convert_date($course['release_date'])?><br>
        <b>Updated:<br></b><?=$vimeo->convert_date($course['updated'])?><br>
        <b>Contributor:&nbsp;</b>Diego Bianco<br><br>
        <?=$course['description']?>
    </div>
	<div id="content_panel" class="fleft">
    	<?
        include 'inc/modules/comments-demo.php';
		include 'inc/modules/share-demo.php';
		?>
        <div class="acc_head"><?=strtoupper($course['title'])?></div>
        <?
		$i = 0; $firstrow = true;
        foreach( $course['videos'] as $video ) {
			?>
            <div id="crsvid-<?=$req['id']?>:<?=$video->id?>" class="vid_preview link_coursevid fleft<? echo $firstrow ? '': ' top_ad'; ?>">
                <img src="<?=$video->thumbnails->thumbnail[1]->_content?>">
                <?
				if( in_array( $video->id, $mycontent->dashboard['favorites']['videos'] ) ) {
					?><div class="btn_fav btn_active link_favorites fright"></div><?
				} else {
					?><div class="btn_fav fright"><div class="tt_img hidden"></div></div><?
				}
				if( in_array( $video->id, $mycontent->dashboard['watchlist']['videos'] ) ) {
					?><div class="btn_watch btn_active link_watchlist fright"></div><?
				} else {
					?><div class="btn_watch fright"><div class="tt_img hidden"></div></div><?
				}
				?>
				<div class="subhead fclear up"><?=strtoupper($video->title)?></div>
                <div class="up"><?=$video->description?></div>
                <div class="course_break up"></div>
                <div class="up"><?=$vimeo->convert_duration( $video->duration )?></div></div>
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