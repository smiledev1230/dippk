<?
$valid = array(
			'admin'		=>	'e76a6bd8daafd1c8d3fcbd1787ad73bb',
			'clarkin'	=>	'c9f17a10193db48b31212574e47f0224'
		);
if( md5(trim($_POST['pwd'])) == $valid[$_POST['user']] ) {
	@session_start();
	$_SESSION['portal'] = true;
	header('Location: index.php');
} else {
	header('Location: http://zerodropmedia.com/demo/index.php?error=true');
}
?>