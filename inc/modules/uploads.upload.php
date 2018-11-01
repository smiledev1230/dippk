<?
if( $upload->message ) {
	?><div class="message"><?=$upload->message?></div><?
}
if( !is_object($upload) ) $upload = new Upload();
$form = $_REQUEST['form'] ? $_REQUEST['form']: 'content';
?>
<!-- <div class="link_tab tab_head top_ad<? if( $form == 'video' ) echo ' blue'; ?>">
	+ ADD VIDEO (add a new or uploaded video)
    <div class="tab_path hidden">page:account::view:uploads::ctab:upload::form:video</div>
</div>
<div class="link_tab tab_head<? if( $form == 'content' ) echo ' blue'; ?>">
	+ ADD OTHER CONTENT (add a new or uploaded PDF, Word, Powerpoint, or image file)
    <div class="tab_path hidden">page:account::view:uploads::ctab:upload::form:content</div>
</div> -->
<div class="fclear top_ad"></div>
<?
include 'inc/modules/form.upload.' . $form . '.php';
?>