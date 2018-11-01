<style>
#browse{display:none}
#content_panel{width:920px;}
.padleft{width:920px;margin-left:20px;margin-right:20px}
</style>
<?
$docs = new Documents();
$docs->get_featured_courses();
$docs->get_courses_full();
?>


<div class="padleft">
<div class="acc_head">FEATURED TOPICS</div>
<?
$firstrow = true;
$i = 0;
foreach( $docs->featured as $c ) {
	?>
	<div id="crs-<?=$c['ID']?>" class="link_course vid_preview fleft<? if( !$firstrow ) echo ' top_ad'; ?>">
		<?php if(!empty($c['image']) && file_exists('img/previews/courses/'.$c['image'])):?>
		<img src="img/previews/courses/<?=$c['image']?>">
		<?php endif;?>
        <div id="ffeat<?=$c['ID']?>" class="btn_feature btn_active link_featured fright c-tooltip"><span class="c-tooltiptext">Featured Topic</span></div>
    	<div class="browse_head2"><?=$c['title']?></div>
		<!--<?=$c['tagline']?>-->
    </div>
	<?
	if( $i == 2 ) {
		?><div class="fclear"></div><?
		$firstrow = false;
		$i = 0;
	} else {
		?><div class="fspace fleft"></div><?
		$i++;
	}
}
if( $i > 0 ) { ?><div class="fclear"></div><? }
?>
<div class="acc_head padten">OTHER TOPICS</div>
<?
$firstrow = true;
$firstfolder = true;

foreach( $docs->docs as $skey => $sdata ) {
	?>
    <div class="section<? if( !$firstrow ) echo ' top_ad'; ?>">
        <div class="big_text" style="padding-bottom:15px"><?=strtoupper($sdata['title'])?></div>
        <?
        $i = 1;
        foreach( $sdata['folders'] as $fkey => $fdata ) {?>
			<div class="folder<? if( $firstfolder ) { $firstfolder = false; } ?>"><div class="folder_arrow_box arrow_right fleft"></div><span><?=strtoupper($fdata['title'])?></span></div>
			<div class="folder_data hidden">
				<?
				$i = 0; $firstrow = true;
				foreach( $fdata['courses'] as $ckey => $cdata ) {
					?>
					<div  class=" line subsection<? if( $i&1 ) echo ' zebra'; ?>">
						<?
						if( in_array( $ckey, array_keys( $docs->featured ) ) ) {
							?><div id="ffeat<?=$ckey?>" class="btn_feature inline btn_active c-tooltip"><span class="c-tooltiptext">Featured Topic</span></div><?
						} elseif( $cdata['oID'] == $_SESSION['usr_id'] ) {
							?><div id="ffeat<?=$ckey?>" class="btn_feature inline c-tooltip "><span class="c-tooltiptext">Make Featured</span></div><?
						}
						?>
						<span id="crs-<?=$ckey?>" class="title link_course">
						<?=$cdata['title']?>
						</span>
						<span id="crs-<?=$ckey?>" class="description link_course"><?=$cdata['description']?>: </span>
					</div>
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
			?></div>
        <?php }
        ?>
    </div>
	<?
	$firstrow = false;
}
?>
</div>
