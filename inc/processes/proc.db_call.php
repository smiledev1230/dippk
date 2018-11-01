<?
switch( $_POST['call'] ) {
	case 'history_add':
		$sql = 'INSERT INTO ' . USER_HISTORY_TABLE . ' ( UserID, DocumentID ) VALUES ( "' . $_SESSION['usr_id'] . '", "' . $_POST['doc'] . '") ON DUPLICATE KEY UPDATE Date_Viewed = CURRENT_TIMESTAMP';
		break;
	case 'history_update':
		$sql = 'UPDATE ' . USER_HISTORY_TABLE . ' SET Progress = ' . $_POST['progress'] . ' WHERE UserID = "' . $_SESSION['usr_id'] . '" AND DocumentID = "' . $_POST['doc'] . '"';
		break;
	case 'favorite_add':
		$sql = 'INSERT INTO ' . USER_FAVORITES_TABLE . ' ( UserID, VideoID ) VALUES ( "' . $_SESSION['usr_id'] . '", "' . $_POST['doc'] . '" )';
		break;
	case 'favorite_delete':
		$sql = 'DELETE FROM ' . USER_FAVORITES_TABLE . ' WHERE UserID = "' . $_SESSION['usr_id'] . '" AND VideoID = "' . $_POST['doc'] . '"';
		break;
	case 'watchlist_add':
		$sql = 'INSERT INTO ' . USER_WATCHLIST_TABLE . ' ( UserID, VideoID ) VALUES ( "' . $_SESSION['usr_id'] . '", "' . $_POST['doc'] . '" )';
		break;
	case 'watchlist_delete':
		$sql = 'DELETE FROM ' . USER_WATCHLIST_TABLE . ' WHERE UserID = "' . $_SESSION['usr_id'] . '" AND VideoID = "' . $_POST['doc'] . '"';
		break;
	case 'featured_add':
		$sql = 'UPDATE ' . COURSE_COURSE_TABLE . ' SET featured = "Y" WHERE ID = ' . $_POST['doc'];
		break;
	case 'featured_remove':
		$sql = 'UPDATE ' . COURSE_COURSE_TABLE . ' SET featured = "N" WHERE ID = ' . $_POST['doc'];
		break;
	default:
		//echo var_export($_POST,true);
}
echo $sql;
mysql_query($sql);
$bypass = true;
?>