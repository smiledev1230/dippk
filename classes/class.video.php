<?
class Video {
	public $detail = array();
	public $routes = array();
	public $options = array();
	
	public function __construct() {
		$this->build_routes();
		$this->build_options();
	}
	
	private function build_routes() {
		$this->routes = array(
					'YouTube'	=>	'http://www.youtube.com/embed/',
					'Vimeo'		=>	'http://player.vimeo.com/video/'
					);
	}
	
	private function build_options() {
		$this->options = array(
					'YouTube'	=>	'?rel=0',
					'Vimeo'		=>	''
					);
	}
	
	public function get_video_detail( $ID ) {
		$result = mysql_query("SELECT * FROM " . VIDEO_TABLE . " WHERE ID = " . $ID);
		$this->detail = mysql_fetch_assoc($result);
	}
	
	public function get_thumbnail( $SourceID, $SourceType ) {
		switch( $SourceType ) {
			case 'YouTube':
				$array = explode("&", $image_url['query']);
				return 'http://img.youtube.com/vi/' . $SourceID . '/default.jpg';
				break;
			case 'Vimeo':
				$hash = unserialize(file_get_contents('http://vimeo.com/api/v2/video/' . $SourceID . '.php'));
				return $hash[0]['thumbnail_small'];
				break;
			case 'local':
				return 'img/thumbs/' . $SourceID . '.jpg';
				break;
		}
	}
	
	public function get_recent() {
		$result = mysql_query("SELECT v.*, u.Date_Watched, u.Progress FROM " . USER_HISTORY_TABLE . " u JOIN " . VIDEO_TABLE . " v ON u.VideoID = v.ID WHERE u.UserID = " . $_SESSION['db_userid']);
		if( mysql_num_rows( $result ) == 0 ) return FALSE;
		while( $row = mysql_fetch_assoc($result) ) {
			$recent[] = $row;
		}
		return $recent;
	}
	
	public function get_saved() {
		$result = mysql_query("SELECT v.*, u.Date_Added, u.Progress FROM " . USER_SAVED_TABLE . " u JOIN " . VIDEO_TABLE . " v ON u.VideoID = v.ID WHERE u.UserID = " . $_SESSION['db_userid']);
		if( mysql_num_rows( $result ) == 0 ) return FALSE;
		while( $row = mysql_fetch_assoc($result) ) {
			$saved[] = $row;
		}
		return $saved;
	}
	
	public function get_recommend() {
		
	}
}
?>