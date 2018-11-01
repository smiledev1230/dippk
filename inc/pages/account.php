<div id="main">
	<div id="browse" class="fleft">
    	<?
		$viewname = $_SESSION['view'] ? $_SESSION['view'] : 'account';
		$acc->get_menu_data();
		$dev_views = array('uploads','comments','billing');
//		if( in_array( $viewname, $dev_views ) && $_SESSION['usr_name'] == 'Chris Larkin' && file_exists('inc/views/view.' . $viewname . '_dev.php') ) $viewname .= '_dev';
		foreach( $acc->menu['names'] as $key => $menu_item ) {
			?><div class="<? echo ( $viewname == $key ) ? 'browse_active' : 'browse_nav'; ?> link_<?=$acc->menu['targets'][$key]?>"><?=$menu_item?></div><?
		}
		?>
    </div>
	<div id="content_panel" class="fleft">
			<?php 
				if(isset($req['action']) && $req['action']=='edit'){
						if($req['type']=='topic'){
							$req['type'] = 'course';
						}
						else if($req['type']=='chapter'){
							$req['type'] = 'lesson';
						}
						else if($req['type']=='part'){
							$req['type'] = 'chapter';
						}
						$viewname = "edit.".$req['type'];
				}
			?>
			<? if( !@include 'inc/views/view.' . $viewname . '.php' ) include 'inc/modules/construction.php';
		$_SESSION['debug']['INCLUDE'] = 'inc/views/view.' . $viewname . '.php'; ?>
    </div>
    <div class="fclear">&nbsp;<br>&nbsp;</div>
</div>