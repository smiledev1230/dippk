<?
include '../app_top.php';
switch( $_POST['call'] ) {
	case 'history_add':
		$sql = 'INSERT INTO ' . USER_HISTORY_TABLE . ' ( UserID, AlbumID, VideoID ) VALUES ( "' . $_POST['session_var']['usr_id'] . '", "' . $_POST['session_var']['album_id'] . '", "' . $_POST['session_var']['video_id'] . '" ) ON DUPLICATE KEY UPDATE Date_Watched = "' . time() . '"';
		echo $sql;
}
?>