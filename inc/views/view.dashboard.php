<?
$mycontent = new MyContent();
$albums = $mycontent->build_album_array();

if( $mycontent->get_history() ) {
	?>
    <div class="acc_head">RECENTLY VIEWED</div>
    <?
	$i = 1;
	foreach( $mycontent->history as $history_item ) {
		//if( in_array( $history_item['AlbumID'], $plastipak_albums ) ) {
			?>
			<div id="crsvid-<?=$history_item['AlbumID']?>:<?=$history_item['VideoID']?>" class="vid_preview link_coursevid fleft<? if( $i > 1 ) echo ' margin_left'; ?>">
				<img src="<?=$history_item['video_data']->thumbnails->thumbnail[1]->_content?>">
				<div class="browse_head2"><?=$albums[$history_item['AlbumID']]['title']?></div>
				<div class="browse_head"><?=strtoupper($history_item['video_data']->title)?></div>
				<div class="text_sm">watched on <?=$mycontent->convert_date( $history_item['Date_Watched'] )?></div>     
			</div>
			<?
			if( $i == 3 ) break;
			$i++;
		//}
	}
	?><div class="fclear content_foot link_history">See Full History</div><?
}
?>
<div class="acc_head">WATCHLIST</div>
<?
if( $mycontent->get_watchlist() ) {
	$i = 1;
	foreach( $mycontent->watchlist as $watch_item ) {
		//if( in_array( $watch_item['AlbumID'], $plastipak_albums ) ) {
			?>
			<div id="crsvid-<?=$watch_item['AlbumID']?>:<?=$watch_item['VideoID']?>" class="vid_preview link_coursevid fleft<? if( $i > 1 ) echo ' margin_left'; ?>">
				<img src="<?=$watch_item['video_data']->thumbnails->thumbnail[1]->_content?>">
				<div class="browse_head2"><?=$albums[$watch_item['AlbumID']]['title']?></div>
				<div class="browse_head"><?=strtoupper($watch_item['video_data']->title)?></div>
				<div class="text_sm">added on <?=$mycontent->convert_date( $watch_item['Date_Added'] )?></div>     
			</div>
			<?
			if( $i == 3 ) break;
			$i++;
		//}
	}
	?><div class="fclear content_foot link_playlist">See Full Watchlist</div><?
} else {
	?><img src="img/watchlist_default.jpg"><?
}
?>
<div class="acc_head">FAVORITES</div>
<?
if( $mycontent->get_favorites() ) {
	$i = 1;
	foreach( $mycontent->favorites as $fav_item ) {
		//if( in_array( $fav_item['AlbumID'], $plastipak_albums ) ) {
			?>
			<div id="crsvid-<?=$fav_item['AlbumID']?>:<?=$fav_item['VideoID']?>" class="vid_preview link_coursevid fleft<? if( $i > 1 ) echo ' margin_left'; ?>">
				<img src="<?=$fav_item['video_data']->thumbnails->thumbnail[1]->_content?>">
				<div class="browse_head2"><?=$albums[$fav_item['AlbumID']]['title']?></div>
				<div class="browse_head"><?=strtoupper($fav_item['video_data']->title)?></div>
				<div class="text_sm">added on <?=$mycontent->convert_date( $fav_item['Date_Added'] )?></div>     
			</div>
			<?
			if( $i == 3 ) break;
			$i++;
		//}
	}
	?><div class="fclear content_foot link_favorites">See All Favorites</div><?
} else {
	?><img src="img/favorites_default.jpg"><?
}
?>
