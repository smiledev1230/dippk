<div class="pull-left">
	<div class="crs_head_lg"><?=$crs->course['title']?></div>
</div>
<div class="pull-right">
	<?php if($crs->course['contributor_id']==$_SESSION['usr_id']):?>
		<a  class="link-btn" href="?page=account&action=edit&type=topic&id=<?php echo $crs->course['ID'] ?>">EDIT TOPIC</a> 
	<?php endif;?>
</div>
<div class="clearfix"></div>



<div class="crs_text_lg crs_text_wrap">
	<?php if(!empty($crs->course['image'])):?>
		<img src="courseware/courses/<?=$crs->course['ID'].'/'.$crs->course['image']?>" class="fleft" style="max-width:100%;">
	<?php endif;?>
	
    Welcome to the topic: <b><?=$crs->course['title']?></b>.<br><br> <?=nl2br($crs->course['description'])?>

</div>

<div class="panel_divider thick"></div>

<div class="crs_head_md">ASSETS BELONGING TO THIS TOPIC</div>

<div class="crs_text_lg">Below is a list of the assets belonging to this topic. If an icon is greyed out then this topic does not contain any of that asset type.</div>

<table class="crs_asset_tbl" border="0" cellspacing="0" cellpadding="0">

<?

$firstrow = true; $c = 1;

foreach( $crs->course['assets'] as $asset_type => $asset_data ) {
	if($asset_type=='Lessons'){
		$asset_type = 'Chapters';
	}
	if( $c == 1 ) echo '<tr>';

	?>

    <td><div class="crs_icon_asset crs_icon_<? echo $asset_data['icon']; if( $asset_data['num'] > 0 ) echo ' found'; ?> fleft"></div><? echo $asset_type; if( $asset_data['num'] > 0 ) echo ' ('.$asset_data['num'].')'; ?></td>

	<td class="crs_spacer"></td>

	<?

	if( $c == 3 ) {

		$c = 1;

		if( $firstrow ) {

			?><td rowspan="<?=ceil( count( $crs->course['assets'] ) / 3 )?>"><div class="crs_begin_big link_crs_lesson"></div></td><?

			$firstrow = false;

		}

	} else {

		$c++;

	}

}

?>

</table>
<?

if( $crs->other ) {

	?>

    <div class="panel_divider thick"></div>

    <div class="crs_head_lg">Other Topics</div>

    <?

	$i = 0;

	foreach( $crs->other as $other ) {

		?>
		<a href="?page=topic&id=<?php echo $other['ID'];?>">
        <div class="crs_box pointer<? if( $i > 0 ) echo ' top_ad'; ?>">

            <div class="crs_icon_course fleft"></div>

            <div class="crs_coursebox fleft">
				
					<div class="crs_head_sm"><?=$other['title']?></div>

					<div><?=nl2br($other['description'])?></div>
				

            </div>

            <div class="fclear"></div>

        </div>
		</a>

        <?

		$i++;

	}

}

?>