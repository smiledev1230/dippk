<div class="acc_head">WATCHLIST</div>
<?
$mycontent = new MyContent();
if( $mycontent->get_watchlist() ) {
	$firstrow = true;
	foreach( $mycontent->watchlist as $watch_item ) {
		?>
		<div class="watchlist_item line">
            <div id="vid-<?=$watch_item['aID']?>:<?=$watch_item['vID']?>" class="watched link_albumvid<? if( !$firstrow ) echo ' top_ad'; ?>">
                <a id="del<?=$watch_item['DocumentID']?>" class="watch_delete fright" href="#">X</a>
                <img src="img/icons/playlist_sm.png" class="fleft noborder">
                <img src="<?=$watch_item['vThumbnail']?>" class="fleft">
                <div class="browse_head2"><?=str_replace( VIMEO_PREFIX, '', $watch_item['aTitle'])?></div>
                <div class="browse_head"><?=strtoupper($watch_item['vTitle'])?></div>
                <div class="text_sm">added on <?=$mycontent->convert_date( $watch_item['Date_Added'] )?></div>
            	
            </div>
            <div class="panel_break"></div>
		</div>
		<?
		$firstrow = false;
	}
} else {
	?><img src="img/watchlist_default.jpg"><?	
}
?>