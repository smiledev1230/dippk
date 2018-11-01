<?
$docs->get_featured_videos();
$docs->get_albums();
$mycontent = new MyContent();
?>
<div class="acc_head">FEATURED VIDEOS</div>
<?
$i = 0;
foreach( $docs->featured as $video ) {
	?>
	<div id="vid-<?=$video['ID']?>" class="vid_preview link_albumvid fleft">
		<img src="<?=$video['vThumbnail']?>">
        <?
		if( in_array( $video['ID'], $mycontent->dashboard['favorites'] ) ) {
			?><div id="ffav<?=$video['ID']?>" class="btn_fav btn_active link_favorites fright"></div><?
		} else {
			?><div id="ffav<?=$video['ID']?>" class="btn_fav fright"><div class="tt_img hidden"></div></div><?
		}
		if( in_array( $video['ID'], $mycontent->dashboard['watchlist'] ) ) {
			?><div id="fwat<?=$video['ID']?>" class="btn_watch btn_active link_watchlist fright"></div><?
		} else {
			?><div id="fwat<?=$video['ID']?>" class="btn_watch fright"><div class="tt_img hidden"></div></div><?
		}
		?>
		<div class="subhead"><?=strtoupper($video['vTitle'])?></div>
		<?=$video['vDescription']?>
		<div class="course_break"></div>
		<?=$docs->convert_duration( $video['vDuration'] )?>
    </div>
	<?
	if( $i == 2 ) {
		?><div class="fclear"></div><?
	} else {
		?><div class="fspace fleft"></div><?
	}
	$i++;
}
?>
<div class="acc_head">ALBUMS<?
	if( $_SESSION['lv_contributor'] ) {
		?><span class="add_form_link"><?=strtoupper($docs->categories[$docs->current_category]['add_text'])?></span><?
	}
	?></div>
<? 
foreach( $docs->docs as $doc_data ) {
	?>
    <div class="folder"><div class="folder_arrow_box arrow_right fleft"></div><span><?=strtoupper($doc_data['title'])?></span></div>
	<div class="folder_data hidden">
		<?
		$i = 0; $firstrow = true;
		foreach( $doc_data['videos'] as $video ) {
			?>
			<div id="vid-<?=$video['ID']?>" class="vid_preview link_albumvid fleft">
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
				<div class="subhead"><?=strtoupper($video['vTitle'])?></div>
				<?=$video['vDescription']?>
				<div class="course_break"></div>
				<?=$docs->convert_duration( $video['vDuration'] )?></div>
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
	?></div><?
}
if( $_SESSION['lv_contributor'] ) {
	?>
	<div id="add_form_holder" class="hidden">
    	<div class="acc_upload"><? include 'inc/modules/form.' . $req['view'] . '.php'; ?></div>
    </div>
    <?
}
?>