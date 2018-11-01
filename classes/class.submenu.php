<?
class Submenu {
	public $detail = array();
	public $vid_list = array();
	
	public function __construct() {
		$this->get_details();
		$this->build_video_list();
	}
	
	public function get_details() {
		$result = mysql_query("SELECT * FROM " . NAV_SUBMENU_TABLE . " WHERE ID = " . $_SESSION['submenu']);
		$this->detail = mysql_fetch_assoc($result);
	}
	
	public function build_video_list() {
		$result = mysql_query("SELECT * FROM " . VIDEO_TABLE . " WHERE Submenus LIKE '%." . $_SESSION['submenu'] . ".%'");
		while( $row = mysql_fetch_assoc($result) ) {
			$this->vid_list[] = $row;
		}
	}
}
?>