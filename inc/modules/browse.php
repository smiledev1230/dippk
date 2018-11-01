<?
$bpan = new Browse(10);
$_SESSION['debug']['BPAN_SECTIONS'] = var_export($bpan->sections, true);
?>
<table class="bpanel" cellpadding="0" cellspacing="0" border="0">
	<thead><tr>
    	<?
		foreach( $bpan->headings as $heading => $cols ) {
			?><th colspan="<?=($cols*2)-1?>"><?=$heading?></th><?
		}?></tr></thead>
    <tbody>
    	<?
		if( count( $bpan->titles ) > 1 ) {
			?><tr><?
			foreach( $bpan->titles as $col => $btitle ) {
				?><td class="bpanel_title"><?=$btitle?></td><?
				if( $col < count($bpan->titles) ) {
					?><td class="bpanel_space"></td><?
				}
			}
			?></tr><?
		}
		?><tr><?
		foreach( $bpan->sections as $col => $array ) {
			?><td class="bpanel_data"><ul><?
				foreach( $array as $key => $val ) {
					?>
                    <li class="fclear">
                        <a href="index.php?page=section&sect=<?=$key?>" class="fleft"><?=$val?></a>
                    </li>
                    <?
					if( $key == $_SESSION['sect'] ) {
						$section_title = $bpan->titles[$col];
						$section_name = $val;
					}
				}
			?></ul></td><?
            	if( $col < count($bpan->sections) ) {
					?><td class="bpanel_space"></td><?
				}
		}
		?></tr></tbody>
</table>