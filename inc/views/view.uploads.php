<style>
.subsection span.name { padding-top:5px!important }
.btn_feature.inline {
    top: 1px!important;
    right: 5px!important;
}
</style>
<div class="acc_head">MANAGE MY CONTENT</div>
<div class="ctab_bar">
	<?
	$cnav_arr = array('course','chapter','quiz','view');
	$ctab_active = $_REQUEST['ctab'] ? $_REQUEST['ctab']: 'view';
	if($ctab_active=='course' && isset($_REQUEST['form']) && $_REQUEST['form']=='chapter'){
		$active_tab = 'chapter';
	}
	else{
		$active_tab = $ctab_active;
	}
	foreach( $cnav_arr as $cnav ) {
		?>
        <div id="cnav_<?=$cnav?>" class="ctab<? if( $cnav == $active_tab ) echo ' active'; ?>"></div>
        <div class="ctab_spacer"></div>
        <?
	}
	?>
    <div class="fclear"></div>
</div>
<div id="ctab_content">
    <? include 'inc/modules/uploads.'.$ctab_active.'.php'; ?>
</div>