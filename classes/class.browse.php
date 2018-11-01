<?
class Browse {
	public $headings = array();
	public $titles = array();
	public $data = array();
	public $images = array();
	public $sections = array();
	public $icons = array();
	
	public function __construct($limit) {
		$this->get_headings();
		$this->get_titles();
		$this->get_data();
		$this->arrange_data($limit);
		//$this->get_icons();
	}
	
	private function get_headings() {
		$this->headings = array( 'BROWSE BY SECTION' => 3 );
	}
	
	private function get_titles() {
		$result = mysql_query( "SELECT ID, name FROM " . PANEL_TITLES_TABLE );
		while( $row = mysql_fetch_assoc( $result ) ) {
			$this->titles[$row['ID']] = $row['name'];
		}
	}
	
	private function get_data() {
		$result = mysql_query( "SELECT * FROM " . PANEL_MENU_TABLE );
		while( $row = mysql_fetch_assoc( $result ) ) {
			$this->data[$row['titleID']][$row['ID']] = $row['name'];
			$this->images[$row['ID']] = $row['image'];
			/*$result_sub = mysql_query( "SELECT ID, name FROM " . PANEL_SUBMENU_TABLE . " WHERE menuID = " . $row['ID'] );
			while( $row_sub = mysql_fetch_assoc( $result_sub ) ) {
				$this->data[$row['titleID']][$row['ID']]['submenus'][$row_sub['ID']] = $row_sub['name'];
			}*/
		}
	}
	
	private function arrange_data( $list_max = null ) {
		$counts = array();
		foreach( $this->titles as $key => $val ) {
			$counts[$key] = count( $this->data[$key] );
		}
		switch( count( $this->titles ) ) {
			case 1:
				if( !$list_max ) $list_max = ceil( $counts[1] / 3 );
				$arr = array();
				$list_count = 1;
				$column = 1;
				foreach( $this->data[1] as $key => $val ) {
					if( $list_count > $list_max ){
						$column++;
						if( $column > 3 ) break;
						$list_count = 1;
					}
					$this->sections[$column][$key] = $val;
					$list_count++;
				}
				break;
			case 2:
				
			default:
				$this->sections = $this->data;
		}
	}
	
	private function get_icons() {
		$this->icons = array();
	}
}
?>