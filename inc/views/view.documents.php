<div class="acc_head">
	<?
    echo strtoupper( $req['view'] );
	if( $_SESSION['lv_contributor'] ) {
		?><span class="add_form_link"><?=strtoupper($docs->categories[$docs->current_category]['add_text'])?></span><?
	}
	?>
</div>
<?
$docs->get_documents();
$firstfolder = true;
foreach( $docs->docs as $f ) {
	?>
    <div class="folder"><div class="folder_arrow_box arrow_<? echo $firstfolder ? 'down': 'right'; ?> fleft"></div><span><?=strtoupper($f['title'])?></span></div>
	<div class="folder_data<? if( !$firstfolder ) echo ' hidden'; ?>">
		<?
		$firstfolder = false;
		$i = 0; $firstrow = true;
        foreach( $f['files'] as $d ) {
            ?>
            <div class="doc link_content fleft<? echo $firstrow ? '': ' top_15'; ?>">
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
                if( in_array( $d['ID'], $mycontent->dashboard['favorites'] ) ) {
					?><div id="fav<?=$d['ID']?>" class="btn_fav btn_active link_favorites"></div><?
				} else {
					?><div id="fav<?=$d['ID']?>" class="btn_fav"><div class="tt_img hidden"></div></div><?
				}
				?>
                <div class="subhead"><a class="link_content_direct"><?=strtoupper($d['title'])?></a></div>
                <div class="doc-path hidden"><?=$d['path']?></div>
                <?=$d['description']?>
                <div class="doc_contributor"><?=$d['contributor']?></div></div>
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
if( $_SESSION['lv_contributor'] ) {
	?>
	<div id="add_form_holder" class="hidden">
    	<div class="acc_upload"><? include 'inc/modules/form.' . $req['view'] . '.php'; ?></div>
    </div>
    <?
}
?>