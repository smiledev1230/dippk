<div class="acc_head">ONLINE RESOURCES<?
	if( $_SESSION['lv_contributor'] ) {
		?><span class="add_form_link"><?=strtoupper($docs->categories[$docs->current_category]['add_text'])?></span><?
	}
	?></div>
<?
$docs->get_documents(true);
$firstfolder = true;
$folder_count = count($docs->docs);
$f_number = 0;
foreach( $docs->docs as $f ) {
	$f_number++;
	?>
    <div class="folder"><div class="folder_arrow_box arrow_<? echo $firstfolder ? 'down': 'right'; ?> fleft"></div><span><?=strtoupper($f['title'])?></span></div>
	<div class="folder_data narrow<? if( !$firstfolder ) echo ' hidden'; ?>">
    	<div class="resource_edit">
        	<form class="acc edit">
            	<input type="hidden" name="docID" value="" />
                <label>Name</label><input type="text" name="name" class="maintain" required />
                <label>Link</label><input type="text" name="link" class="maintain" required />
                <a class="js_cancel fright">Cancel</a>
                <input type="submit" name="process" class="btn fright" value="Submit Changes">
            </form>
            <div class="fclear"></div>
        </div>
		<?
		$firstfolder = false;
		$i = 0; $firstrow = true;
        foreach( $f['files'] as $d ) {
            ?>
            <div id="doc<?=$d['ID']?>" class="resource fleft<? if( !$firstrow ) echo ' top_ad'; ?>">
            	<div class="subhead"><?=strtoupper($d['title'])?></div>
                <a href="<?=$d['description']?>"><? echo ( strlen($d['description']) > 33 ) ? substr($d['description'],0,30) . '...': $d['description']; ?></a>
                <div class="fright tools">
                	<a class="js_edit">EDIT</a> | <a class="js_delete">DELETE</a>
                </div>
            </div>
            <?
            $i++;
            if( $i == 2 ) {
                $i = 0;
                $firstrow = false;
            }
        }
		if( $f_number == $folder_count ) {
			?><div class="panel_break"></div><?
		}
        ?>
        <div class="fclear"></div>
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