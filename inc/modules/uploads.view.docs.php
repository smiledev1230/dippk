<?
$i = 0; $firstrow = true;
if( $_POST['pagenum'] ) {
	$pagenum = $_POST['pagenum'];
	$doc_type = $_POST['doctype'];
	$limit = 8;
	$start = ($doc_type == 'Videos') ? ($limit/4*3) * ($pagenum-1): $limit * ($pagenum-1);
	$docs = new Documents();
	$docs->get_uploads( $limit, $start, $doc_type );
} else {
	$pagenum = 1;
}
switch( $doc_type ) {
	case 'Videos':
		$pagecount = ceil($docs->uploads['counts'][$doc_type]/6);
		foreach( $docs->uploads[$doc_type] as $video ) {
			?>
			<div id="vid-<?=$video['ID']?>" class="vid_preview link_albumvid js_delete_target fleft top_ad">
				<img src="<?=$video['vThumbnail']?>">
				<div class="subhead js_rename_target">
                	<a><?=strtoupper($video['vTitle'])?></a>
                    <input type="text" class="hidden" value="<?=$video['vTitle']?>">
                </div>
                <div class="line_links"><a class="js_rename blue">rename</a>&nbsp;|&nbsp;<a class="blue">replace</a>&nbsp;|&nbsp;<a class="js_delete blue">delete</a></div>
			</div>
			<?
			if( $i == 2 ) {
				$firstrow = false;
				?><div class="fclear"></div><?
			} else {
				?><div class="fspace fleft"></div><?
			}
			$i++;
		}
		break;
	default:
		$pagecount = ceil($docs->uploads['counts'][$doc_type]/8);
		foreach( $docs->uploads[$doc_type] as $key => $d ) {
			if( $key == ($pagenum*$limit) ) break;
			?>
			<div class="doc link_content js_delete_target fleft top_ad">
				<?
				if( $d['thumb'] ) {
					?>
					<img src="<?=$d['thumb']?>">
					<?
					if( $d['type'] == 'unknown' || $d['type'] == 'img' || $d['type'] == 'audio' ) {
						?><div class="extension small"><?=$d['ext']?></div><?
					} else {
						?><div class="<?=$d['type']?> small"></div><?
					}
				} elseif( $d['type'] == 'img' ) {
					?>
					<img src="<?=$d['path']?>">
					<div class="extension small"><?=$d['ext']?></div>
					<?
				} else {
					?>
					<div class="<?=$d['type']?>"></div>
					<?
					if( $d['type'] == 'unknown' || $d['type'] == 'audio' ) {
						?><div class="extension small"><?=$d['ext']?></div><?
					}
				}
				?>
				<div class="subhead fclear js_rename_target">
                	<a class="link_content_direct"><?=strtoupper($d['title'])?></a>
                    <input type="text" class="hidden" value="<?=$d['title']?>">
                </div>
				<div class="doc-path hidden"><?=$d['path']?></div>
				<div class="line_links"><a class="js_rename blue">rename</a>&nbsp;|&nbsp;<a class="blue">replace</a>&nbsp;|&nbsp;<a class="js_delete blue">delete</a></div>
			</div>
			<?
			if( $i == 3 ) {
				?><div class="fclear"></div><?
			} else {
				?><div class="fspace_wide fleft"></div><?
			}
			$i++;
			if( $i == 4 ) {
				$i = 0;
				$firstrow = false;
			}
		}
}
if( $i > 0 ) {
	?><div class="fclear"></div><?
}
if( $pagecount > 1 ) {
	$pagestart = $pagenum - 4;
	if( $pagestart < 1 ) $pagestart = 1;
	if( $pagecount > ($pagenum + 5) ) {
		$pageend = $pagenum + 4;
		$pagelast = false;
	} else {
		$pageend = $pagecount;
		$pagelast = true;
	}
	?>
    <div class="pagebar top_ad">
		<?
		if( $pagenum < $pagecount ) {
			?><div data-id="<?=($pagenum+1)?>" class="pagenext left_space fright"></div><?
		}
		?><div class="fright pagelinks"><?
			for( $i=$pagestart; $i<=$pageend; $i++ ) {
				?><a data-id="<?=$i?>" class="blue left_space<? if( $i == $pagenum ) echo ' active'; ?>"><?=$i?></a><?
			}
			if( !$pagelast ) {
				?><a data-id="<?=$pagecount?>" class="blue">...<?=$pagecount?></a><?
			}
		?></div><?
		if( $pagenum > 1 ) {
			?><div data-id="<?=($pagenum-1)?>" class="pageprev fright"></div><?
		}
		?>
        <div class="fclear"></div>
    </div>
	<?
}
?>