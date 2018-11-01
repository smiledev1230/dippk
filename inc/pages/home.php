<div id="main">
	
	<?
	$force_view = '';
	switch( $req['view'] ) {
		case 'contributors':
			$force_view = '&view=' . $req['view'];
			break;
		case 'courses':
			$force_view = '&view=' . $req['view'];
			break;
		default:
			?>
            <div id="demos">
                <table cellspacing="0" cellpadding="0" border="0"><tr><td>
                  <div id="s1">
                    <div class="slides"><img src="img/ads/ShareTopic-Home-Banner-960x320.jpg" /></div>
                  </div>
                </td></tr></table>
            </div>
            <?
	}
	?>
	<?php if (isset($_SESSION['logged_in_message'])):?>
		<div class="success-message" style="width:94%;margin:0 auto;margin-top:10px;">
			<?php echo $_SESSION['logged_in_message']; unset($_SESSION['logged_in_message']); ?>
		</div>
	<?php endif;?>
<!--<div id="feature"><img src="img/ads/welcome-banner_960x320.jpg"></div>-->
	<div id="browse" class="fleft">
    	<?
		//if( count( $bpan->titles ) == 1 ) {
			?><div class="browse_head redtab">BROWSE SECTIONS</div><div class="scrolldiv"><?
		//}
		//foreach( $bpan->titles as $col => $btitle ) {
			/*if( count( $bpan->titles ) > 1 ) {
				?><div class="browse_head"><?=$btitle?></div><?
			}*/
			foreach( $bpan->data as $col => $array ) {
				foreach( $array as $key => $val ) {
					?>
					<div class="browse_link">
						<?
						//for demo purposes only
							//keep only this part when active site
							?><a href="index.php?page=section&sect=<?=$key.$force_view?>&view=courses"><?=$val?></a><?
						
						?>
					</div>
					<?
				}
			}
		//}
        ?></div>
    </div>
	<div id="content_panel" class="fleft">
    	<?
		$viewname = $req['view'] ? $req['view']: 'home';
		include 'inc/views/view.main.' . $viewname . '.php';
		?>
    </div>
    <div class="fclear"></div>
</div>