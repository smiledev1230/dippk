<?
$upload = new Upload();

$values = $upload->get_select_options( $_POST['proc_type'] );
foreach( $values as $key => $val ) {
	?><span class="selectOption" value="<?=$key?>"><?=$val?></span><?
}
//echo $values;
?>