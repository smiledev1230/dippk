<div class="content_head">OUR PRESENTATIONS</div>
<?
$docs = new Documents();
$i = 0;
foreach( $docs->docs as $d ) {
	?>
	<div class="doc fleft top_ad">
    	<div class="<?=$d['type']?>"></div>
        <div class="subhead"><?=strtoupper($d['title'])?></div>
        <?=$d['description']?></div>
	<?
	if( $i == 3 ) {
		?><div class="fclear"></div><?
	} else {
		?><div class="fspace fleft"></div><?
	}
	$i++;
	if( $i == 4 ) $i = 0;
}
if( $i < 4 ) {
	?><div class="fclear"></div><?
}
?>