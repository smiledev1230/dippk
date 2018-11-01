<?
$reg = new Register();

if( $reg->register_new() ) {
	$_SESSION['success'] = $reg->get_message();
	header('Location:index.php?'.$reg->get_redirect_url());

} else {
	$_SESSION['error'] = $reg->message;
	header('Location:index.php?page=login&view=register');
}
?>