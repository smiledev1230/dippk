<?

class MyContent extends Vimeo {

	public $lastwatch;

	public $history = array();

	public $favorites = array();

	public $watchlist = array();

	public $quiz_history = array();

	public $dashboard = array();

	public $folders = array();

	

	public function __construct() {

		parent::__construct();

		$this->build_folder_array();

		$this->get_dashboard();

	}

	

	public function build_folder_array() {

		$sql = "SELECT f.*, c.title AS category, m.Name AS section FROM " . FILES_FOLDERS_TABLE . " f JOIN (" . FILES_CATEGORIES_TABLE . " c, " . PANEL_MENU_TABLE . " m) ON f.menuID = m.ID AND f.catID = c.ID";

		$result = mysql_query($sql);

		while( $row = @mysql_fetch_assoc($result) ) {

			$this->folders['ID'] = $row;

		}

	}

	

	public function get_counts() {

		$sql = "SELECT (SELECT COUNT(UserID) FROM " . USER_HISTORY_TABLE . " WHERE UserID = " . $_SESSION['usr_id'] . ") AS history, (SELECT COUNT(UserID) FROM " . USER_FAVORITES_TABLE . " WHERE UserID = " . $_SESSION['usr_id'] . ") AS favorites, (SELECT COUNT(UserID) FROM " . USER_WATCHLIST_TABLE . " WHERE UserID = " . $_SESSION['usr_id'] . ") AS watchlist";

		$_SESSION['debug']['COUNTS'] = $sql;

		$result = mysql_query($sql);

		$arr = @mysql_fetch_assoc($result);

		return $arr;

	}

	

	public function get_last_watched() {

		$sql = "SELECT h.DocumentID, d.title, v.vThumbnail FROM " . USER_HISTORY_TABLE . " h JOIN (" . FILES_DOCUMENTS_TABLE . " d, " . SEARCH_VIDEO_TABLE . " v) ON h.DocumentID = d.ID AND d.filename IN (SELECT vID FROM " . SEARCH_VIDEO_TABLE . ") WHERE h.UserID = '" . $_SESSION['usr_id'] . "' ORDER BY h.Date_Viewed DESC LIMIT 1";

		//$_SESSION['debug']['GETLAST'] = $sql;

		$result = mysql_query($sql);

		if( mysql_num_rows( $result ) != 1 ) return false;

		$this->lastwatch = mysql_fetch_assoc($result);

		return true;

	}

	

	/* public function get_history() {

		$sql = "SELECT a.DocumentID AS `dID`, a.Date_Viewed, m.name AS `sTitle`, c.title AS `cTitle`, c.destination AS `cPath`, f.destination AS `fPath`, d.title AS `dTitle`, d.filename AS `dPath` FROM " . USER_HISTORY_TABLE . " a JOIN (" . PANEL_MENU_TABLE . " m, " . FILES_CATEGORIES_TABLE . " c, " . FILES_FOLDERS_TABLE . " f, " . FILES_DOCUMENTS_TABLE . " d) ON a.DocumentID = d.ID AND d.folder = f.ID AND f.catID = c.ID AND f.menuID = m.ID WHERE a.UserID = '" . $_SESSION['usr_id'] . "<br>

' ORDER BY a.Date_Viewed DESC";

		$_SESSION['debug']['SQL'] = $sql;

		$result = mysql_query($sql);

		if( mysql_num_rows( $result ) < 1 ) return false;

		while( $row = mysql_fetch_assoc($result) ) {

			$this->history[] = $row;

		}

		if( count( $this->history ) == 0 ) return false;

		return true;

	} */
	public function get_history() {
		$user_id = $_SESSION['usr_id'];
		$sql = "SELECT sv.*, uh.Date_Watched FROM usr_history uh INNER JOIN search_video sv ON uh.VideoID=sv.vID WHERE uh.UserID='$user_id'";

		$_SESSION['debug']['SQL'] = $sql;

		$result = mysql_query($sql);

		if( mysql_num_rows( $result ) < 1 ) return false;

		while( $row = mysql_fetch_assoc($result) ) {

			$this->history[] = $row;

		}

		if( count( $this->history ) == 0 ) return false;

		return true;

	}

	

	/* public function get_favorites() {

		$sql = "SELECT a.DocumentID AS `dID`, a.Date_Added, m.name AS `sTitle`, c.title AS `cTitle`, c.destination AS `cPath`, f.destination AS `fPath`, d.title AS `dTitle`, d.filename AS `dPath` FROM " . USER_FAVORITES_TABLE . " a JOIN (" . PANEL_MENU_TABLE . " m, " . FILES_CATEGORIES_TABLE . " c, " . FILES_FOLDERS_TABLE . " f, " . FILES_DOCUMENTS_TABLE . " d) ON a.DocumentID = d.ID AND d.folder = f.ID AND f.catID = c.ID AND f.menuID = m.ID WHERE a.UserID = '" . $_SESSION['usr_id'] . "<br>

' ORDER BY sTitle, cTitle, dTitle";

		$result = mysql_query($sql);

		if( mysql_num_rows( $result ) < 1 ) return false;

		while( $row = mysql_fetch_assoc($result) ) {

			$this->favorites[] = $row;

		}

		if( count( $this->favorites ) == 0 ) return false;

		return true;

	} */
	public function get_favorites() {

		$user_id = $_SESSION['usr_id'];
		$sql = "SELECT sv.* FROM usr_favorites f INNER JOIN search_video sv ON f.VideoID=sv.vID WHERE f.UserID='$user_id'";

		$result = mysql_query($sql);

		if( mysql_num_rows( $result ) < 1 ) return false;

		while( $row = mysql_fetch_assoc($result) ) {

			$this->favorites[] = $row;

		}

		if( count( $this->favorites ) == 0 ) return false;

		return true;

	}

	

	public function get_watchlist( $album_array = null ) {

		$user_id = $_SESSION['usr_id'];
		$sql = "SELECT sv.* FROM usr_watchlist  w INNER JOIN search_video sv ON w.VideoID=sv.vID WHERE w.UserID='$user_id'";

		$result = mysql_query($sql);

		if( mysql_num_rows( $result ) < 1 ) return false;

		while( $row = @mysql_fetch_assoc($result) ) {

			$this->watchlist[] = $row;

		}

		if( count( $this->watchlist ) == 0 ) return false;

		return true;

	}
	/* public function get_watchlist( $album_array = null ) {

		$sql = "SELECT w.DocumentID, w.Date_Added, a.aID, a.aTitle, v.vID, v.vTitle, v.vThumbnail FROM " . USER_WATCHLIST_TABLE . " w JOIN (" . FILES_DOCUMENTS_TABLE . " d, " . FILES_FOLDERS_TABLE . " f, " . SEARCH_ALBUM_TABLE . " a, " . SEARCH_VIDEO_TABLE . " v) ON w.DocumentID = d.ID AND d.folder = f.ID AND f.destination = a.aID AND d.filename = v.vID WHERE UserID = '" . $_SESSION['usr_id'] . "' ORDER BY Date_Added DESC";

		$result = mysql_query($sql);

		if( mysql_num_rows( $result ) < 1 ) return false;

		while( $row = @mysql_fetch_assoc($result) ) {

			$this->watchlist[] = $row;

		}

		if( count( $this->watchlist ) == 0 ) return false;

		return true;

	} */

	

	public function get_dashboard() {

		$result = mysql_query("SELECT * FROM " . USER_HISTORY_TABLE . " WHERE UserID = '" . $_SESSION['usr_id'] . "'");

		$this->dashboard['history'] = ( mysql_num_rows( $result ) > 0 ) ? true : false;

		if( $this->dashboard['history'] ) {

			$this->dashboard['history'] = mysql_fetch_array( $result );

		}

		$result = mysql_query("SELECT DocumentID FROM " . USER_FAVORITES_TABLE . " WHERE UserID = '" . $_SESSION['usr_id'] . "' ORDER BY DocumentID");

		$this->dashboard['favorites'] = ( mysql_num_rows( $result ) > 0 ) ? array() : false;

		if( is_array($this->dashboard['favorites']) ) {

			while( $row = mysql_fetch_assoc( $result ) ) {

				$this->dashboard['favorites'][] = $row['DocumentID'];

			}

		}

		$result = mysql_query("SELECT DocumentID FROM " . USER_WATCHLIST_TABLE . " WHERE UserID = '" . $_SESSION['usr_id'] . "' ORDER BY DocumentID");

		$this->dashboard['watchlist'] = ( mysql_num_rows( $result ) > 0 ) ? array() : false;

		if( is_array($this->dashboard['watchlist']) ) {

			while( $row = mysql_fetch_assoc( $result ) ) {

				$this->dashboard['watchlist'][] = $row['DocumentID'];

			}

		}

		$_SESSION['debug']['DASHBOARD'] = var_export($this->dashboard, true);

	}

	public function get_quiz_history() {

		$user_id = $_SESSION['usr_id'];
		$sql = "SELECT qr.*,qq.title AS quiz_title, cc.title AS course_title FROM quiz_result qr INNER JOIN quiz_quiz qq ON qr.quizID=qq.ID INNER JOIN crs_course cc ON cc.ID=qq.course WHERE qr.oID='$user_id' ORDER BY dateTaken DESC";

		$result = mysql_query($sql);
		
		if( mysql_num_rows( $result ) < 1 ) return false;

		while( $row = @mysql_fetch_assoc($result) ) {

			$this->quiz_history[] = $row;

		}

		if( count( $this->quiz_history ) == 0 ) return false;

		return true;

	}	

}

?>