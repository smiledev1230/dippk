<?
if( $upload->message ) {
	?><div class="message"><?=$upload->message?></div><?
}
if( !is_object($upload) ) $upload = new Upload();
$form = $_REQUEST['form'] ? $_REQUEST['form']: 'course';
?>
<!-- <div class="link_tab tab_head top_ad<? if( $form == 'course' ) echo ' blue'; ?>">
	+ ADD NEW TOPIC
    <div class="tab_path hidden">page:account::view:uploads::ctab:course::form:course</div>
</div>
<div class="link_tab tab_head<? if( $form == 'chapter' ) echo ' blue'; ?>">
	+ ADD A PART OR CHAPTER TO A TOPIC
    <div class="tab_path hidden">page:account::view:uploads::ctab:course::form:chapter</div>
</div> -->
<div class="fclear top_ad"></div>
<?
include 'inc/modules/form.' . $form . '.php';
?>