<div id="main">
		<?
		if ( (is_object($login) && $login->change_password) || ($req['process'] == 'CHANGE PASSWORD') ) {
			$module = 'change_pwd.php';
		} else {
        	$module = $req['view'] ? $req['view'] . '.php' : 'login.php';
		}
		include 'inc/modules/' . $module;
		?>
    </div></div>
</div>