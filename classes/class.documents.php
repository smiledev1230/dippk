<?
class Documents {
	private $view;
	private $content_directory;
	public $current_category;
	public $categories = array();
	public $docs = array();
	public $featured;
	public $uploads = array();
	
	public function __construct() {
		$this->view = $_SESSION['view'] ? $_SESSION['view'] : 'documents';
		$this->get_categories();
	}
	
	public function get_categories() {
		$sql = "SELECT * FROM " . FILES_CATEGORIES_TABLE . " WHERE section_menu = 'Y' ORDER BY priority, ID ASC";
		$result = mysql_query( $sql );
		while( $row = mysql_fetch_assoc( $result ) ) {
			$this->categories[$row['ID']] = $row;
			if( $this->view == $row['link_type'] ) {
				$this->current_category = $row['ID'];
				$this->content_directory = 'content/' . $row['link_type'];
			}
		}
	}
	
	public function get_counts($menu) {
		$arr = array();
		$sql = "SELECT c.title, COUNT(d.ID) as files FROM " . FILES_DOCUMENTS_TABLE . " d JOIN (" . FILES_FOLDERS_TABLE . " f, " . FILES_CATEGORIES_TABLE . " c) ON d.folder = f.ID AND f.catID = c.ID WHERE f.menuID = " . $menu . " GROUP BY c.title";
		$result = mysql_query( $sql );
		while( $row = mysql_fetch_assoc( $result ) ) {
			$arr[$row['title']] = $row['files'];
		}
		return $arr;
	}
	
	public function get_section_number($doc_id) {
		$sql = "SELECT f.menuID FROM " . FILES_FOLDERS_TABLE . " f JOIN " . FILES_DOCUMENTS_TABLE . " d ON f.ID = d.folder WHERE d.ID = " . $doc_id . " LIMIT 1";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		return $row['menuID'];
	}
	
	public function get_documents($resource=false) {
		$types = $this->get_file_types();
		$sql = "SELECT * FROM " . FILES_FOLDERS_TABLE . " WHERE catID = '" . $this->current_category . "' AND menuID = '" . $_SESSION['sect'] . "'";
		$result = @mysql_query( $sql );
		while( $row = @mysql_fetch_assoc( $result ) ) {
			$this->docs[$row['ID']] = $row;
			$folder_path = $this->content_directory . '/' . $row['destination'] . '/';
			$docsql = "SELECT d.*, CONCAT(p.First_Name,' ',p.Last_Name) AS contributor FROM " . FILES_DOCUMENTS_TABLE . " d JOIN " . USER_PROFILE_TABLE . " p ON d.cID = p.ID WHERE d.folder = " . $row['ID'];
			$docresult = @mysql_query( $docsql );
			while( $docrow = @mysql_fetch_assoc( $docresult ) ) {
				$path = $folder_path . $docrow['filename'];
				if( $resource ) {
					$this->docs[$row['ID']]['files'][$docrow['ID']] = $docrow;
				} elseif( is_file( $path ) ) {
					$this->docs[$row['ID']]['files'][$docrow['ID']] = $docrow;
					$file_info = pathinfo( $path );
					$ext = substr( $file_info['extension'], 0, 3 );
					$this->docs[$row['ID']]['files'][$docrow['ID']]['ext'] = $ext;
					$this->docs[$row['ID']]['files'][$docrow['ID']]['path'] = $path;
					$this->docs[$row['ID']]['files'][$docrow['ID']]['type'] = $types[$ext] ? $types[$ext] : false;
					if( $this->view == 'pictures' ) {	
						$this->docs[$row['ID']]['files'][$docrow['ID']]['thumb'] = $path;
					} else {
						$thumb_path = $this->content_directory . '/' . $row['destination'] . '/thumbs/' . $file_info['filename'] . '.png';
						$this->docs[$row['ID']]['files'][$docrow['ID']]['thumb'] = file_exists( $thumb_path ) ? $thumb_path : false;
					}
				}
			}
		}
		$_SESSION['debug']['DOCS'] = var_export($this->docs,true);
	}
	
	public function get_albums( $video = false ) {
		$active_video = false;
		if( $video ) {
			$sql .= "SELECT f.* FROM " . FILES_FOLDERS_TABLE . " f JOIN " . FILES_DOCUMENTS_TABLE . " d ON d.folder = f.ID WHERE d.ID = " . $video;
		} else {
			$sql = "SELECT * FROM " . FILES_FOLDERS_TABLE . " WHERE catID = '" . $this->current_category . "' AND menuID = '" . $_SESSION['sect'] . "'";
		}
		$_SESSION['debug']['SQL'] = $sql;
		$result = @mysql_query( $sql );
		$key = 0;
		while( $row = @mysql_fetch_assoc( $result ) ) {
			$this->docs[$key] = $row;
			$docsql = "SELECT d.ID, v.*, CONCAT(p.First_Name,' ',p.Last_Name) AS contributor FROM " . FILES_DOCUMENTS_TABLE . " d JOIN (" . SEARCH_VIDEO_TABLE . " v, " . USER_PROFILE_TABLE . " p) ON d.filename = v.vID AND d.cID = p.ID WHERE d.folder = " . $row['ID'];
			$_SESSION['debug']['DOCSQL'] = $docsql;
			$docresult = @mysql_query( $docsql );
			while( $docrow = @mysql_fetch_assoc( $docresult ) ) {
				$this->docs[$key]['videos'][] = $docrow;
				if( $docrow['ID'] == $video ) $active_video = array( 'doc' => $docrow['ID'], 'vid' => $docrow['vID'] );
			}
			$key++;
		}
		$_SESSION['debug']['DOC_ALBUMS'] = var_export($this->docs,true);
		return $active_video;
	}
	
	public function get_courses() {
		if(!empty($this->current_category)){
			$sql = "SELECT * FROM " . FILES_FOLDERS_TABLE . " WHERE catID = '" . $this->current_category . "' AND menuID = '" . $_SESSION['sect'] . "'";
		}
		else{
			$sql = "SELECT * FROM " . FILES_FOLDERS_TABLE . " WHERE  menuID = '" . $_SESSION['sect'] . "'";
		}
		
		//$_SESSION['debug']['SQL'] = $sql;
		$result = @mysql_query( $sql );
		$key = 0;
		while( $row = @mysql_fetch_assoc( $result ) ) {
			$this->docs[$key] = $row;
			$docsql = "SELECT c.*, CONCAT(p.First_Name,' ',p.Last_Name) AS contributor FROM " . COURSE_COURSE_TABLE . " c JOIN " . USER_PROFILE_TABLE . " p ON c.oID = p.ID WHERE c.folder = " . $row['ID'];
			$_SESSION['debug']['SQL'] = $docsql;
			$docresult = @mysql_query( $docsql );
			while( $docrow = @mysql_fetch_assoc( $docresult ) ) {
				$this->docs[$key]['courses'][] = $docrow;
			}
			$key++;
		}
	}
	
	public function get_courses_full() {
		$sql = "SELECT c.description, c.ID, c.title, c.oID, CONCAT(p.First_Name,' ',p.Last_Name) AS contributor, c.folder, f.title AS fTitle, f.menuID, s.name FROM " . COURSE_COURSE_TABLE . " c JOIN (" . USER_PROFILE_TABLE . " p, " . FILES_FOLDERS_TABLE . " f, " . PANEL_MENU_TABLE . " s) ON c.oID = p.ID AND c.folder = f.ID AND f.menuID = s.ID WHERE c.publish='Y' ORDER BY s.name, f.title, c.title";
		$_SESSION['debug']['SQL'] = $sql;
		$result = @mysql_query( $sql );
		while( $row = @mysql_fetch_assoc( $result ) ) {
			$this->docs[$row['menuID']]['title'] = $row['name'];
			$this->docs[$row['menuID']]['folders'][$row['folder']]['title'] = $row['fTitle'];
			$this->docs[$row['menuID']]['folders'][$row['folder']]['courses'][$row['ID']] = array( 'title' => $row['title'], 'oID' => $row['oID'], 'description' => $row['description'] );
		}
	}
	
	public function get_resources() {
		$types = $this->get_file_types();
		$sql = "SELECT * FROM " . FILES_FOLDERS_TABLE . " WHERE catID = '" . $this->current_category . "' AND menuID = '" . $_SESSION['sect'] . "'";
		$result = mysql_query( $sql );
		while( $row = mysql_fetch_assoc( $result ) ) {
			$this->docs[$row['ID']] = $row;
			$folder_path = $this->content_directory . '/' . $row['destination'] . '/';
			$docsql = "SELECT * FROM " . FILES_DOCUMENTS_TABLE . ' WHERE folder = ' . $row['ID'];
			$docresult = mysql_query( $docsql );
			while( $docrow = mysql_fetch_assoc( $docresult ) ) {
					$this->docs[$row['ID']]['files'][$docrow['ID']] = $docrow;
					$file_info = pathinfo( $path );
					$ext = substr( $file_info['extension'], 0, 3 );
					$this->docs[$row['ID']]['files'][$docrow['ID']]['ext'] = $ext;
					$this->docs[$row['ID']]['files'][$docrow['ID']]['path'] = $path;
					$this->docs[$row['ID']]['files'][$docrow['ID']]['type'] = $types[$ext] ? $types[$ext] : false;
					if( $this->view == 'pictures' ) {	
						$this->docs[$row['ID']]['files'][$docrow['ID']]['thumb'] = $path;
					} else {
						$thumb_path = $this->content_directory . '/' . $row['destination'] . '/thumbs/' . $file_info['filename'] . '.png';
						$this->docs[$row['ID']]['files'][$docrow['ID']]['thumb'] = file_exists( $thumb_path ) ? $thumb_path : false;
					}
			}
		}
		$_SESSION['debug']['DOCS'] = var_export($this->docs,true);
	}
	
	public function get_featured( $view = false ) {
		$this->featured = array();
		$types = $this->get_file_types();
		if( $view ) {
			$sql = "SELECT f.catID, f.destination, d.* FROM " . FILES_DOCUMENTS_TABLE . " d JOIN (" . FILES_FOLDERS_TABLE . " f, " . FILES_CATEGORIES_TABLE . " c) ON d.folder = f.ID AND f.catID = c.ID WHERE d.featured = 'Y' AND c.link_type = '" . $view . "' AND f.menuID = '" . $_SESSION['sect'] . "'";
		} else {
			$sql = "SELECT f.catID, f.destination, d.* FROM " . FILES_DOCUMENTS_TABLE . " d JOIN " . FILES_FOLDERS_TABLE . " f ON d.folder = f.ID WHERE d.featured = 'Y' AND f.menuID = '" . $_SESSION['sect'] . "'";
		}
		//$_SESSION['debug']['SQL'] = $sql;
		$result = mysql_query( $sql );
		//$_SESSION['debug']['PATH'] = '';
		while( $row = mysql_fetch_assoc( $result ) ) {
			$path = 'content/' . $this->categories[$row['catID']]['destination'] . '/' . $row['destination'] . '/' . $row['filename'];
			$_SESSION['debug']['PATH'] .= $path;
			if( is_file( $path ) ) {
				$_SESSION['debug']['PATH'] .= ':FOUND';
				$this->featured[$row['catID']][$row['ID']] = $row;
				$file_info = pathinfo( $path );
				$ext = substr( $file_info['extension'], 0, 3 );
				$this->featured[$row['catID']][$row['ID']]['ext'] = $ext;
				$this->featured[$row['catID']][$row['ID']]['path'] = $path;
				$this->featured[$row['catID']][$row['ID']]['type'] = $types[$ext] ? $types[$ext] : false;
				if( $this->view == 'pictures' ) {	
					$this->featured[$row['catID']][$row['ID']]['thumb'] = $path;
				} else {
					$thumb_path = 'content/' . $this->categories[$row['catID']]['destination'] . '/' . $row['destination'] . '/thumbs/' . $file_info['filename'] . '.png';
					$this->featured[$row['catID']][$row['ID']]['thumb'] = file_exists( $thumb_path ) ? $thumb_path : false;
				}
			}
			$_SESSION['debug']['PATH'] .= '<br>';
		}
		//$_SESSION['debug']['FEATURED'] = var_export($this->featured,true);
	}
	
	public function get_featured_videos() {
		$this->featured = array();
		$sql = "SELECT d.ID, v.* FROM " . FILES_DOCUMENTS_TABLE . " d JOIN (" . FILES_FOLDERS_TABLE . " f, " . FILES_CATEGORIES_TABLE . " c, " . SEARCH_VIDEO_TABLE . " v) ON d.folder = f.ID AND f.catID = c.ID AND d.filename = v.vID WHERE d.featured = 'Y' AND c.link_type = 'videos' AND f.menuID = '" . $_SESSION['sect'] . "'";
		//$_SESSION['debug']['SQL'] = $sql;
		$result = mysql_query( $sql );
		while( $row = mysql_fetch_assoc( $result ) ) {
			$this->featured[$row['ID']] = $row;
		}
		//$_SESSION['debug']['FEATURED'] = var_export($this->featured,true);
	}
	
	public function get_featured_courses() {
		$this->featured = array();
		$sql = "SELECT d.* FROM " . COURSE_COURSE_TABLE . " d JOIN (" . FILES_FOLDERS_TABLE . " f, " . FILES_CATEGORIES_TABLE . " c) ON d.folder = f.ID AND f.catID = c.ID WHERE d.publish = 'Y' AND d.featured = 'Y' AND c.link_type = 'courses'";
		if( $_SESSION['sect'] ) $sql .= " AND f.menuID = '" . $_SESSION['sect'] . "'";
		$_SESSION['debug']['SQL'] = $sql;
		$result = mysql_query( $sql );
		while( $row = mysql_fetch_assoc( $result ) ) {
			$this->featured[$row['ID']] = $row;
		}
		$_SESSION['debug']['FEATURED'] = var_export($this->featured,true);
	}
	
	public function get_uploads( $limit = 8, $start = 0, $cat = null ) {
		$user_id = $_SESSION['usr_id'];
		/* $types = $this->get_file_types();
		if( $cat ) {
			$categories = array($cat);
		} else {
			$catsql = "SELECT title FROM " . FILES_CATEGORIES_TABLE . " WHERE uploads_panel = 'Y' ORDER BY priority, ID";
			$catresult = mysql_query($catsql);
			$categories = array();
			while( $catrow = @mysql_fetch_assoc($catresult) ) {
				$categories[] = $catrow['title'];
			}
		} */
		/* foreach( $categories as $category ) {
			if($category=='Courses'){
				continue;
			}
		    $_SESSION['debug']['GETUPLOADS'] = '';
			if( $category == 'Videos' ) {
				$sql = "SELECT f.catID, c.title AS category, f.destination, d.ID, v.* FROM " . SEARCH_VIDEO_TABLE . " v JOIN (" . FILES_FOLDERS_TABLE . " f, " . FILES_CATEGORIES_TABLE . " c, " . FILES_DOCUMENTS_TABLE . " d) ON d.folder = f.ID AND f.catID = c.ID AND d.filename = v.vID WHERE c.title = '" . $category . "' ORDER BY d.ID DESC";
			} else {
				$sql = "SELECT f.catID, c.title AS category, f.destination, d.* FROM " . FILES_DOCUMENTS_TABLE . " d JOIN (" . FILES_FOLDERS_TABLE . " f, " . FILES_CATEGORIES_TABLE . " c) ON d.folder = f.ID AND f.catID = c.ID WHERE d.cID='$user_id' AND  c.title = '" . $category . "' ORDER BY d.ID DESC";
                $_SESSION['debug']['GETUPLOADS'] .= $sql.'<br>';
            }
			$result = mysql_query($sql);
			$key = 0; $i = 0;
			while( $row = @mysql_fetch_assoc($result) ) {
				if( $category == 'Videos' && $key >= $start && $i < ceil($limit/4*3) ) {
					$this->uploads[$category][$key] = $row;
					$i++;
				} elseif( $key >= $start && $i < $limit ) {
					$path = 'content/' . $this->categories[$row['catID']]['destination'] . '/' . $row['destination'] . '/' . $row['filename'];
					if( is_file( $path ) ) {
						$this->uploads[$category][$key] = $row;
						$file_info = pathinfo( $path );
						$ext = substr( $file_info['extension'], 0, 3 );
						$this->uploads[$category][$key]['ext'] = $ext;
						$this->uploads[$category][$key]['path'] = $path;
						$this->uploads[$category][$key]['type'] = $types[$ext] ? $types[$ext] : false;
						if( $category == 'Pictures' ) {	
							$this->uploads[$category][$key]['thumb'] = $path;
						} else {
							$thumb_path = 'content/' . $this->categories[$row['catID']]['destination'] . '/' . $row['destination'] . '/thumbs/' . $file_info['filename'] . '.png';
							$this->uploads[$category][$key]['thumb'] = file_exists( $thumb_path ) ? $thumb_path : false;
						}
						$i++;
					}
				}
				$key++;
			}
			if( $key == 0 ) $this->uploads[$category] = $this->get_upload_special($category);
			
		}
		$this->uploads['counts'][$category] = $key; */
		
		//get courses normally
		$sql = "SELECT * FROM crs_course WHERE oID='$user_id' ORDER BY title ";
		$result = mysql_query($sql);
		$this->uploads['Courses']=array();
		while($row = mysql_fetch_assoc($result)){
			$this->uploads['Courses'][$row['ID']] = $row;
		}

		//get quizzes normally
		$sql = "SELECT * FROM quiz_quiz WHERE oID='$user_id'";
		$result = mysql_query($sql);
		$this->uploads['Quizzes']=array();
		while($row = mysql_fetch_assoc($result)){
			$this->uploads['Quizzes'][$row['ID']] = $row;
		}
			
		$_SESSION['debug']['UPLOADS'] = var_export($this->uploads,true);
	}
	
	private function get_upload_special( $upload_type ) {
		$arr = array();
		switch( $upload_type ) {
			case 'Courses':
				$sql = "SELECT ID, title, publish FROM " . COURSE_COURSE_TABLE . " WHERE oID = " . $_SESSION['usr_id'] . " ORDER BY ID DESC";
				$result = @mysql_query($sql);
				if( !$result ) return $arr;
				while( $row = @mysql_fetch_assoc($result) ) {
					$arr[$row['ID']] = $row;
				}
				break;
			case 'Quizzes':
				$sql = "SELECT ID, title, publish FROM " . QUIZ_QUIZ_TABLE . " WHERE oID = " . $_SESSION['usr_id'] . " ORDER BY ID DESC";
				$result = @mysql_query($sql);
				if( !$result ) return $arr;
				while( $row = @mysql_fetch_assoc($result) ) {
					$arr[$row['ID']] = $row;
				}
				break;
		}
		return $arr;
	}
	
	private function get_file_types() {
		$arr = array(
					'audio'			=> array( 'mp3', 'wav' ),
					'img'			=> array( 'bmp', 'jpg','gif','png' ),
					//'imgother'	=> array( 'tiff', 'eps' ),
					'msword'		=> array( 'doc', 'dot' ),
					'msexcel'		=> array( 'xls', 'xlt', 'xla' ),
					'mspowerpoint'	=> array( 'ppt', 'pot', 'ppa', 'pps' ),
					'msvisio'		=> array( 'vsd', 'vss', 'vst' ),
					'msaccess'		=> array( 'acc', 'mdb', 'snp' ),
					'msproject'		=> array( 'mpp' ),
					'msonenote'		=> array( 'one' ),
					'msoutlook'		=> array( 'pst', 'vcs' ),
					'mspublisher'	=> array( 'pub', 'puz' ),
					'adreader'		=> array( 'pdf' )
				);
		foreach( $arr as $type => $extensions ) {
			foreach( $extensions as $val ) {
				$types[$val] = $type;
			}
		}
		return $types;
	}
	
	public function convert_duration( $duration ) {
		$dur_h = floor( $duration / 3600 );
		$dur_m = floor( ( $duration - $runtime_h * 3600 ) / 60 );
		$dur_s = $duration - ( $dur_h * 3600 + $dur_m * 60 );
		$runtime = ( $dur_h > 0 ) ? $dur_h . 'h ' : '';
		$runtime .= ( $dur_h == 0 && $dur_m == 0 ) ? '' : $dur_m . 'm ';
		$runtime .= $dur_s . 's';
		return $runtime;
	}
	
	public function convert_date( $date ) {
		$datetime = explode( ' ', $date );
		$d = explode( '-', $datetime[0] );
		$t = explode( ':', $datetime[1] );
		return date( 'F j, Y', mktime( $t[0], $t[1], $t[2], $d[1], $d[2], $d[0] ) );
	}

	public function get_folders($user_id){
		$sql = "SELECT ff.*,pm.name AS section_name FROM fls_folders ff INNER JOIN panel_menu pm ON ff.menuID=pm.ID ";
		if($_SESSION['usr_level']!=8){
			$sql .=" WHERE ff.oID='$user_id'";
		}
		$result = mysql_query($sql);
		$folders = array();
		while($row = mysql_fetch_assoc($result)){
			$folders[] = $row;
		}
		return $folders;
	}

	public function count_courses_inside_folder($folder_id){
		$sql = "SELECT COUNT(*) AS total FROM crs_course WHERE folder='$folder_id'";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		return $row['total'];
	}
}
?>