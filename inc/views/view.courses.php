<?
$docs->get_featured_courses();
$docs->get_courses();
//$mycontent = new MyContent();
?>
<div class="acc_head padten" style="margin-top:0px!important">TOPICS</div>
<?
$firstrow = true;
$i = 0;
foreach( $docs->featured as $c ) {
	?>
	<div id="crs-<?=$c['ID']?>" class="link_course vid_preview fleft<? if( !$firstrow ) echo ' top_ad'; ?>">
		<?php if( !empty($c['image']) && file_exists('img/previews/courses/'.$c['image'])):?>
			<img src="img/previews/courses/<?=$c['image']?>">
		<?php endif;?>
        <div id="ffeat<?=$c['ID']?>" class="btn_feature btn_active link_featured fright"></div>
    	<div class="browse_head2"><?=$c['title']?></div>
		<?=$c['tagline']?>
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
$firstfolder = true;
foreach( $docs->docs as $doc_data ) {
	?>
    <div class="folder<? if( $firstfolder ) { echo ' top_ad'; $firstfolder = false; } ?>"><div class="folder_arrow_box arrow_right fleft"></div><span><?=strtoupper($doc_data['title'])?></span></div>
	<div class="folder_data hidden">
		<?
		$i = 0; $firstrow = true;
		foreach( $doc_data['courses'] as $c ) {
			?>
			<div id="crs-<?=$c['ID']?>" class="link_course vid_preview fleft" style="margin-bottom:15px;">
				<?php if( !empty($c['image']) && file_exists('img/previews/courses/'.$c['image'])):?>
					<img src="img/previews/courses/<?=$c['image']?>">
				<?php endif;?>
                
                <?
				if( in_array( $c['ID'], array_keys( $docs->featured ) ) ) {
					?><div id="ffeat<?=$c['ID']?>" class="btn_feature btn_active link_featured fright"></div><?
				} elseif( $c['oID'] == $_SESSION['usr_id'] ) {
					?><div id="ffeat<?=$c['ID']?>" class="btn_feature fright"><div class="tt_img hidden"></div></div><?
				}
				?>
                <div class="browse_head2"><?=$c['title']?></div>
				<?=$c['tagline']?>
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