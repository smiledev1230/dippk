<div class="vid_default top_ad fleft"></div>
<div class="browse_head fright top_15 vid_side">Welcome to Plastipak</div>
<div class="fright vid_side"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sodales convallis felis vitae gravida. Proin sodales laoreet luctus. Vestibulum convallis mi quis vulputate fringilla. Praesent blandit, est sit amet faucibus rhoncus, purus quam tempor ante, at blandit sem arcu eget enim.</p></div>
<div class="fclear"></div>
<?
$docs->get_featured_courses();
if( count( $docs->featured ) > 0 ) {
	?><div class="acc_head">FEATURED COURSES</div><?
	$i = 0; $firstrow = true;
	foreach( $docs->featured as $crsID => $crsData ) {
		?>
		<div class="vid_preview fleft<? echo $firstrow ? '': ' top_15'; ?>">
		<a id="crs-<?=$crsID?>" class="link_course"><img src="img/previews/courses/<?=$crsData['image']?>">
		<div class="browse_head2"><?=strtoupper($crsData['title'])?></div><?=$crsData['tagline']?></div></a>
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
	if( $i > 0 ) {
		?><div class="fclear"></div><?
	}
}
?>
<div class="acc_head">FEATURED ITEMS</div>
<?
$docs->get_featured();
$firstfolder = true;
foreach( $docs->featured as $catID => $doc_array ) {
	?>
    <div class="folder"><div class="folder_arrow_box arrow_<? echo $firstfolder ? 'down': 'right'; ?> fleft"></div><span><?=strtoupper($docs->categories[$catID]['title'])?></span></div>
	<div class="folder_data<? if( !$firstfolder ) echo ' hidden'; ?>">
		<?
		$firstfolder = false;
		$i = 0; $firstrow = true;
        foreach( $doc_array as $d ) {
            ?>
            <div class="doc link_content fleft<? echo $firstrow ? '': ' top_15'; ?>">
                <?
                if( $d['thumb'] ) {
                    ?>
                    <div class="doc_image">
                        <img src="<?=$d['thumb']?>">
                        <div class="<?=$d['type']?> small"></div>
                    </div>
                    <?
                } else {
                    ?><div class="<?=$d['type']?>"></div><?
                }
                if( in_array( $d['ID'], $mycontent->dashboard['favorites'] ) ) {
					?><div id="ffav<?=$d['ID']?>" class="btn_fav btn_active link_favorites"></div><?
				} else {
					?><div id="ffav<?=$d['ID']?>" class="btn_fav"><div class="tt_img hidden"></div></div><?
				}
				?>
                <div class="subhead"><a class="link_content_direct"><?=strtoupper($d['title'])?></a></div>
                <div class="doc-path hidden"><?=$d['path']?></div>
                <?=$d['description']?></div>
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
        if( $i < 4 ) {
            ?><div class="fclear"></div><?
        }
		?>
    </div>
	<?
}
$docs->get_featured_videos();
?>
<div class="folder"><div class="folder_arrow_box arrow_<? echo $firstfolder ? 'down': 'right'; ?> fleft"></div><span>VIDEOS</span></div>
<div class="folder_data<? if( !$firstfolder ) echo ' hidden'; ?>">
	<?
	$firstfolder = false;
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
			<?=$docs->convert_duration( $video['vDuration'] )?></div>
		<?
		if( $i == 2 ) {
			?><div class="fclear"></div><?
		} else {
			?><div class="fspace fleft"></div><?
		}
		$i++;
	}
    ?>
</div>