<?
$bpan = new Browse();
//$_SESSION['debug']['BPAN_DATA'] = var_export($bpan->data, true);
?>
<table class="bpanel" cellpadding="0" cellspacing="0" border="0">
	<thead><tr>
    	<?
		foreach( $bpan->headings as $heading => $cols ) {
			?><th colspan="<?=($cols*2)-1?>"><?=$heading?></th><?
		}?></tr></thead>
    <tbody><tr>
    	<?
		foreach( $bpan->titles as $col => $btitle ) {
			?><td class="bpanel_title"><?=$btitle?></td><?
            if( $col < count($bpan->titles) ) {
				?><td class="bpanel_space"></td><?
			}
		}
		?></tr><tr><?
		foreach( $bpan->data as $col => $array ) {
			?><td class="bpanel_data"><ul><?
				foreach( $array as $key => $val ) {
					?><li class="fclear"><a href="index.php?sect=<?=$key?>" class="fleft"><?=$val?></a></li><?
					if( $key == $_SESSION['sect'] ) {
						$section_title = $bpan->titles[$col];
						$section_name = $val;
					}
				}
			?></ul></td><?
            	if( $col < count($bpan->data) ) {
					?><td class="bpanel_space"></td><?
				}
		}
		?></tr></tbody>
</table>