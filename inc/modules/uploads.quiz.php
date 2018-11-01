<?
if( $upload->message ) {
	?><div class="message"><?=$upload->message?></div><?
}
if( !is_object($upload) ) $upload = new Upload();
$form = $_REQUEST['form'] ? $_REQUEST['form']: 'quiz';
?>
<div class="link_tab tab_head top_ad<? if( $form == 'quiz' ) echo ' blue'; ?>">
	+ ADD NEW QUIZ
    <div class="tab_path hidden">page:account::view:uploads::ctab:quiz::form:quiz</div>
</div>
<div class="link_tab tab_head<? if( $form == 'question' ) echo ' blue'; ?>">
	+ ADD A QUESTION TO A QUIZ
    <div class="tab_path hidden">page:account::view:uploads::ctab:quiz::form:question</div>
</div>
<div class="fclear top_ad"></div>
<?
include 'inc/modules/form.' . $form . '.php';
?>