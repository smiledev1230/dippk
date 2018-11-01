<?
class Navigation {
	public $alphabet = array();
	public $top = array();
	public $submenu = array();
	public $submenu_alpha = array();
	public $submenu_count = array();
	
	public function __construct() {
		$this->build_alphabet();
		$this->build_top_menu();
		if( $_SESSION['view'] ) $this->get_menu_list( $_SESSION['view'] );
	}
	
	private function build_alphabet() {
		$this->alphabet = array_merge( array( 0 => '#' ), range( 'A', 'Z' ) );
	}
	
	private function build_top_menu() {
		$result = mysql_query("SELECT * FROM " . NAV_MENU_TABLE . " ORDER BY priority DESC");
		while( $row = mysql_fetch_assoc($result) ) {
			$this->top[$row['menu']] = $row;
		}
	}
	
	public function get_menu_list( $view ) {
		$result = mysql_query("SELECT ID, submenu FROM " . NAV_SUBMENU_TABLE . " WHERE menuID = " . $this->top[$view]['ID']);
		while( $row = mysql_fetch_assoc($result) ) {
			 $initial = strtoupper( substr( $row['submenu'], 0, 1 ) );
			 $alpha_key = in_array( $initial, $this->alphabet ) ? $initial : '#';
			 $this->submenu_alpha[] = $alpha_key;
			 $this->submenu[$alpha_key][] = $row;
			 $result_count = mysql_query( "SELECT ID FROM " . VIDEO_TABLE . " WHERE Submenus LIKE '%." . $row['ID'] . ".%'" );
			 $this->submenu_count[$row['ID']] = mysql_num_rows( $result_count );
		}
	}
	
}
?>