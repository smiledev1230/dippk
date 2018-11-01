<?
switch( $_POST['update_type'] ) {
	case 'account info':
		$ch_profile = new Account();
		if( $ch_profile->update_profile() ) {
			$_SESSION['profile_success'] = $ch_profile->message;
		}
		else{
			$_SESSION['profile_error'] = $ch_profile->message;
			
		}
		$_SESSION['profile_update_set_time']  = time();
		header('Location: '.$_SERVER['HTTP_REFERER']);
		break;
	case 'change pwd':
		$ch_pwd = new Account();
		if( $ch_pwd->change_password() ) {
			$_SESSION['profile_success'] = $ch_pwd->message;
		}
		else{
			$_SESSION['profile_error'] = $ch_pwd->message;
		}
		$_SESSION['profile_update_set_time']  = time();

		header('Location: '.$_SERVER['HTTP_REFERER']);
		break;
	case 'reset pwd':
		$recover = new Account();
		if( $recover->reset_password() ) {
			?>
			<script>
				alert('A new password has been sent to the email address provided.\nPlease allow several minutes for it to arrive.');
            	window.location.assign('index.php');
            </script>
			<?
		}
		
		break;
	case 'upload img':
		$ch_img = new Account();
		
		if( $ch_img->update_image() ) {
			$_SESSION['profile_success'] = $ch_img->message;
		}
		else{
			$_SESSION['profile_error'] = $ch_img->message;
		}
		$_SESSION['profile_update_set_time']  = time();
		$random = rand(1,999999);
		header('Location: index.php?page=account&random='.$random);
		break;
}
?>