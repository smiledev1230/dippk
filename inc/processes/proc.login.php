<?
$login = new Login();
if ( $login->validate_password() ) {
	if ( $login->login_user() ) {
		/**
		 * auto-login for forum
		 
		define('IN_PHPBB', true);
		$phpbb_root_path = 'forum/';
		$phpEx = substr(strrchr(__FILE__, '.'), 1);
		include 'forum/common.php';
		// Start session management
		$user->session_begin();
		$auth->acl($user->data);
		$user->setup();
		
		$username = request_var('username', $_SESSION['usr_username']);
		$password = request_var('password', trim($_POST['pwd']));
		
		if(isset($username) && isset($password)){
			$result=$auth->login($username, $password, true);
			if ($result['status'] == LOGIN_SUCCESS) {
				//echo "You're logged in";
			} else {
				//echo $user->lang[$result['error_msg']];
			}
		}*/
		header('Location: index.php');
	}
}
$_SESSION['page'] = 'login';
?>