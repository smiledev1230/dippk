<?

class Upload extends Scribd {

	public $message = false;

	const CATEGORY = 6; // category ID for courses

	

	public function __construct() {
		parent::__construct( SCRIBD_API_KEY, SCRIBD_SECRET );
	}

	

	/*public function __call( $method, $args ) {

		$this->scribd->$method($args[0]);

	}*/

	

	public function change_publish_state() {

		switch( $_POST['type'] ) {

			case 'Courses':

				$table = COURSE_COURSE_TABLE;

				break;

			case 'Quizzes':

				$table = QUIZ_QUIZ_TABLE;

				break;

		}

		$publish = ($_POST['proc_type'] == 'publish') ? 'Y': 'N';

		$sql = "UPDATE " . $table . " SET publish = '" . $publish . "' WHERE ID = " . $_POST['id'] . " LIMIT 1";

		if( !@mysql_query($sql) ) {

			$this->message = 'ERROR: Failed to update publish status.';

			return false;

		}

		if( $_POST['type'] == 'Courses' && $_POST['proc_type'] == 'unpublish' ) {

			$sql = "UPDATE " . QUIZ_QUIZ_TABLE . " SET publish = 'N' WHERE course = " . $_POST['id'];

			if( !@mysql_query($sql) ) {

				$this->message = 'ERROR: Failed to unpublish related quizzes.';

				return false;

			}

		}

		return true;

	}

	

	public function get_sections() {

		$arr = array();

		$sql = "SELECT ID, name FROM " . PANEL_MENU_TABLE . " ORDER BY name";

		$result = @mysql_query( $sql );

		while( $row = @mysql_fetch_assoc( $result ) ) {

			$arr[$row['ID']] = $row['name'];

		}

		if( isset($_SESSION['course']) ) {

			$sql = "SELECT c.folder, f.menuID FROM " . COURSE_COURSE_TABLE . " c JOIN " . FILES_FOLDERS_TABLE . "f ON c.folder = f.ID WHERE c.ID = " . $_SESSION['ucourse'] . " LIMIT 1";

			$result = @mysql_query( $sql );

			$row = @mysql_fetch_assoc( $result );

			$_SESSION['usection'] = $row['menuID'];

			$_SESSION['ufolder'] = $row['folder'];

		}

		return $arr;

	}

	

	public function get_categories() {

		$arr = array();

		$sql = "SELECT ID, title FROM " . FILES_CATEGORIES_TABLE . " WHERE uploads_panel = 'Y' AND link_type NOT IN ('courses','quizzes','videos') ORDER BY title";

		$result = @mysql_query( $sql );

		while( $row = @mysql_fetch_assoc( $result ) ) {

			$arr[$row['ID']] = $row['title'];

		}

		return $arr;

	}

	

	public function get_folders() {

		$arr = array();

		$sql = "SELECT ID, title FROM " . FILES_FOLDERS_TABLE;

		if( @$_SESSION['usection'] ) $sql .= " WHERE menuID = " . $_SESSION['usection'];

		$sql .= " ORDER BY title";

		$result = @mysql_query( $sql );

		while( $row = @mysql_fetch_assoc( $result ) ) {

			$arr[$row['ID']] = $row['title'];

		}

		return $arr;

	}

	

	public function get_courses( $user = false ) {

		$arr = array();

		$sql = "SELECT ID, title FROM " . COURSE_COURSE_TABLE;

		if( $user ) {

			$sql .= " WHERE oID = " . $_SESSION['usr_id'];

		} elseif( isset($_SESSION['ufolder']) ) {

			$sql .= " WHERE folder = " . $_SESSION['ufolder'];

		}

		$sql .= " ORDER BY title";

		$result = @mysql_query( $sql );

		while( $row = @mysql_fetch_assoc( $result ) ) {

			$arr[$row['ID']] = $row['title'];

		}

		return $arr;

	}

	

	public function get_lessons( $user = false ) {

		$arr = array();

		$sql = "SELECT ID, title FROM " . COURSE_LESSON_TABLE;

		if( $user ) {

			$sql .= " WHERE oID = " . $_SESSION['usr_id'];

		} elseif( isset($_SESSION['ufolder']) ) {

			$sql .= " WHERE folder = " . $_SESSION['ufolder'];

		}

		$sql .= " ORDER BY title";

		$result = @mysql_query( $sql );

		while( $row = @mysql_fetch_assoc( $result ) ) {

			$arr[$row['ID']] = $row['title'];

		}

		return $arr;

	}

	

	public function get_assets( $course = false ) {

		if( $course ) $_SESSION['ucourse'] = $course;

		$arr = array();

		if( isset($_SESSION['ucourse']) ) {

			$sql = "SELECT ID, title FROM " . COURSE_ASSET_TABLE . " WHERE course = " . $_SESSION['ucourse'] . " ORDER BY title";

			$result = @mysql_query( $sql );

			while( $row = @mysql_fetch_assoc( $result ) ) {

				$arr[$row['ID']] = $row['title'];

			}

		}

		return $arr;

	}

	

	public function get_folder_files() {

		$arr = array();

		if( $_SESSION['ufolder'] ) {

			$sql = "SELECT ID, filename FROM " . FILES_DOCUMENTS_TABLE . " WHERE folder = " . $_SESSION['ufolder'] . " ORDER BY filename";

			$result = @mysql_query( $sql );

			while( $row = @mysql_fetch_assoc( $result ) ) {

				$arr[$row['ID']] = $row['filename'];

			}

		}

		return $arr;

	}

	

	private function build_doc_path( $id ) {

		$sql = "SELECT c.destination AS `category`, f.destination AS `folder`, d.filename FROM " . FILES_DOCUMENTS_TABLE . " d JOIN (" . FILES_FOLDERS_TABLE . " f, " . FILES_CATEGORIES_TABLE . " c) ON d.folder = f.ID AND f.catID = c.ID WHERE d.ID = '" . $id . "'";

		$result = @mysql_query( $sql );

		$row = @mysql_fetch_assoc( $result );

		$arr = array(

					'path'	=> 'content/' . $row['category'] . '/' . $row['folder'] . '/' . $row['filename'],

					'name'	=> $row['filename']

				);

		return $arr;

	}

	

	public function get_videos() {

		$arr = array();

		$sql = "SELECT vID, vTitle FROM " . SEARCH_VIDEO_TABLE . " ORDER BY vTitle";

		$result = @mysql_query( $sql );

		while( $row = @mysql_fetch_assoc( $result ) ) {

			$arr[$row['vID']] = $row['vTitle'];

		}

		return $arr;

	}

	

	public function get_select_options( $proc_call ) {

		$arr = array();

		switch( $proc_call ) {

			case 'get_folder':

			case 'get_folderc':

				if( $_POST['add_option'] == 'true' ) $arr['new'] = 'ADD NEW FOLDER';

				$category = false;

				if( $_POST['category'] ) {

					$category = ($_POST['category'] == 'self') ? self::CATEGORY: $_POST['extra'];

				} elseif( $_POST['extra'] ) {

					if( $_POST['extra'] == 'self' ) {

						$category = self::CATEGORY;

					} else {

						$sql = "SELECT ID FROM " . FILES_CATEGORIES_TABLE . " WHERE title = '" . $_POST['extra'] . "'";

						$result = @mysql_query($sql);

						if( $result ) $row = mysql_fetch_assoc($result);

						$category = $row['ID'];

					}

				}

				$sql = "SELECT ID, title FROM " . FILES_FOLDERS_TABLE . " WHERE menuID = " . $_POST['selected'];

				if( $category ) $sql .= " AND catID = " . $category;

				//return $_POST;

				$result = @mysql_query( $sql );

				while( $row = @mysql_fetch_assoc( $result ) ) {

					$arr[$row['ID']] = $row['title'];

				}

				break;

			case 'get_course':

				$sql = "SELECT ID, title FROM " . COURSE_COURSE_TABLE . " WHERE oID = " . $_SESSION['usr_id'];

				//return $sql;

				$result = @mysql_query( $sql );

				while( $row = @mysql_fetch_assoc( $result ) ) {

					$arr[$row['ID']] = $row['title'];

				}

				break;

			case 'get_lesson':

				if( $_POST['add_option'] == 'true' ) $arr['new'] = 'ADD NEW LESSON';

				$sql = "SELECT ID, title FROM " . COURSE_LESSON_TABLE . " WHERE course = " . $_POST['selected'];

				//return $sql;

				$result = @mysql_query( $sql );

				while( $row = @mysql_fetch_assoc( $result ) ) {

					$arr[$row['ID']] = $row['title'];

				}

				break;

			case 'get_asset':

				$sql = "SELECT ID, title FROM " . COURSE_ASSET_TABLE . " WHERE course = " . $_POST['selected'] . " ORDER BY title";

				$result = @mysql_query( $sql );

				while( $row = @mysql_fetch_assoc( $result ) ) {

					$arr[$row['ID']] = $row['title'];

				}

				break;

			case 'get_folderfile':

				$sql = "SELECT ID, filename FROM " . FILES_DOCUMENTS_TABLE . " WHERE folder = " . $_POST['selected'] . " ORDER BY filename";

				$result = @mysql_query( $sql );

				while( $row = @mysql_fetch_assoc( $result ) ) {

					$arr[$row['ID']] = $row['filename'];

				}

				break;

			case 'get_quiz':

				$sql = "SELECT ID, title FROM " . QUIZ_QUIZ_TABLE . " WHERE course = " . $_POST['selected'] . " AND oID = " . $_SESSION['usr_id'] . " ORDER BY title";

				//return array($sql);

				$result = @mysql_query( $sql );

				while( $row = @mysql_fetch_assoc( $result ) ) {

					$arr[$row['ID']] = $row['title'];

				}

				break;

				

		}

		return $arr;

	}

	

	private function get_type_param($type) {

		$arr = array();

		switch( $type ) {

			case 'audio':

				$arr['max'] = 51200000;

				$arr['dir'] = 'content/audio/';

				$arr['permit'] = array( 'audio/mpeg', 'audio/vnd.wave', 'audio/mp4' );

				break;

			case 'content':

				$arr['max'] = 51200000;

				$arr['dir'] = 'content/documents/';

				$arr['permit'] = array(

						'audio/mpeg',

						'audio/vnd.wave',

						'audio/mp4',

						'application/pdf',

						'application/msword',

						'application/vnd.openxmlformats-officedocument.wordprocessingml.document',

						'application/rtf',

						'text/plain',

						'text/html',

						'image/gif',

						'image/jpeg',

						'image/pjpeg',

						'image/png',

						'application/vnd.ms-powerpoint',

						'application/vnd.openxmlformats-officedocument.presentationml.presentation'

					);

				break;

			case 'doc':

				$arr['max'] = 51200000;

				$arr['dir'] = 'content/documents/';

				$arr['permit'] = array( 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/rtf', 'text/plain', 'text/html' );

				break;

			case 'image':

				$arr['max'] = 1024000;

				$arr['dir'] = 'content/pictures/';

				$arr['permit'] = array( 'image/gif', 'image/jpeg', 'image/pjpeg', 'image/png' );

				break;

			case 'pres':

				$arr['max'] = 51200000;

				$arr['dir'] = 'content/presentations/';

				$arr['permit'] = array( 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation' );

				break;

			case 'thumb':

				$arr['max'] = 51200;

				$arr['dir'] = 'content/pictures/';

				$arr['permit'] = array( 'image/gif', 'image/jpeg', 'image/pjpeg', 'image/png' );

				break;

			case 'text':

				$arr['max'] = 512000;

				$arr['dir'] = 'content/documents/';

				$arr['permit'] = array( 'text/plain', 'text/html' );

			default:

				return false;

		}

		return $arr;

	}

	

	public function upload_file( $type, $id, $dir = false, $title = false ) {

		$param = $this->get_type_param($type);

		if( !$dir ) $dir = $param['dir'];

		if( $param ) {

			define( 'MAX_FILE_SIZE', $param['max'] );

			define( 'UPLOAD_DIR', $dir );

		} else {

			$this->message = 'Upload type invalid.';

			$_SESSION['debug']['ADD CONTENT'] .= '<br>UTYPE ERROR';

			return false;

		}
		if( !is_dir($dir) ) mkdir( $dir, 0777, true);

		if( in_array( $_FILES[$id]['type'], $param['permit'] ) && $_FILES[$id]['size'] > 0 && $_FILES[$id]['size'] <= MAX_FILE_SIZE ) {

			$fname_arr = explode( '.', $_FILES[$id]['name'] );

			$ext_key = count( $fname_arr ) - 1;

			$newname = $title ? str_replace( ' ', '_', $title ) . '.' . $fname_arr[$ext_key]: $_FILES[$id]['name'];

			switch( $_FILES[$id]['error'] ) {

				case 0:

					if( file_exists( UPLOAD_DIR . $newname ) ) {

						$base_name = substr( UPLOAD_DIR . $newname, 0, strlen( $newname ) - strlen( $fname_arr[$ext_key] ) - 1 );

						for( $i=1; $i<100; $i++ ) {

							if( !file_exists( $base_name . '(' . $i . ').' . $fname_arr[$ext_key] ) ) {

								$dest_file = $base_name . '(' . $i . ').' . $fname_arr[$ext_key];

								break;

							}

						}

					} else {
						$dest_file = UPLOAD_DIR . $newname;

					}	
					if( @move_uploaded_file( $_FILES[$id]['tmp_name'], $dest_file ) ) {

						return $dest_file;

					} else {

						$_SESSION['debug']['ADD CONTENT'] .= '<br>UCOPY ERROR';

						$this->message = "Error0 uploading file. Please try again.";

					}

					break;

				case 3:

				case 6:

				case 7:

				case 8:

					$this->message = "Error uploading file. Please try again.";

					$_SESSION['debug']['ADD CONTENT'] .= '<br>U3-8 ERROR';

				break;

				case 4:

					$this->message = "You didn't select a file to be uploaded.";

					$_SESSION['debug']['ADD CONTENT'] .= '<br>U4 ERROR';

			}

		} else {

			$this->message = "The file is too big.";

			$_SESSION['debug']['ADD CONTENT'] .= '<br>USIZE ERROR';

		}

		return false;

	}

	

	private function build_imagick( $src, $dest, $name = false ) {

		$file_info = pathinfo( $src );

		if( !$name ) $name = $file_info['filename'];

		$im = new imagick($src);

		$im->setResolution(400,400);

		//$im = $im->flattenImages(); worked on pdf from doc but only 1 page on pdf from ppt

		$im->setImageFormat('jpg');

		$number = $im->getnumberimages();

		for( $i=0; $i<$number; ++$i ) {

			$im->readImage("{$src}[".$i."]");

			//$im->scaleImage(800,0);

			if( !$im->writeImage($dest.$name.'('.($i+1).').jpg') ) {

				$this->message = 'Error converting file to course asset.';

				return false;

			}

		}

		$im->clear(); 

		$im->destroy();

		return true;

	}

	

	public function add_content() {
		if( $_POST['newfolder'] ) {

			$folder_id = $this->add_folder();

			if( !$folder_id ) return false;

			$folder_name = $_POST['newfolder'];

		} else {

			$folder_id = $_POST['folder'];

			$sql = "SELECT title FROM " . COURSE_LESSON_TABLE . " WHERE ID = " . $lesson_id;

			$result = @mysql_query( $sql );

			$row = @mysql_fetch_assoc( $result );

			$lesson_name = $row['title'];

		}

		echo $sql = "INSERT INTO " . FILES_DOCUMENTS_TABLE . " (folder, cID, title, description, filename, featured, tags) VALUES ( '" . $folder_id . "', '" . $_SESSION['usr_id'] . "', '" . $_POST['title'] . "', '" . $_POST['description'] . "', '" . $_FILES['uploadfile']['name'] . "', '" . $_POST['featured'] . "', '" . $_POST['tags'] . "' )";

		echo "<pre>";
		print_r($_FILES);
		print_r($_POST);
		exit;

		$_SESSION['debug']['ADD CONTENT'] = $sql;

		$result = @mysql_query( $sql );

		if( $result ) {

			$sql = "SELECT c.destination AS Category, f.destination AS Folder FROM " . FILES_FOLDERS_TABLE . " f JOIN " . FILES_CATEGORIES_TABLE . " c ON f.catID = c.ID WHERE f.ID = " . $folder_id;

			$_SESSION['debug']['ADD CONTENT'] .= '<br>'.$sql;

			$dir_result = @mysql_query( $sql );

			$dir_row = @mysql_fetch_assoc( $dir_result );

			$dir = 'content/' . $dir_row['Category'] . '/' . $dir_row['Folder'] . '/';

			$_SESSION['debug']['ADD CONTENT'] .= '<br>'.$dir;

			if( $filepath = $this->upload_file('content', 'uploadfile', $dir, $_POST['title']) ) {

				if( $_POST['course'] != '' ) {

					switch( $dir_row['Category'] ) {

						case 'documents':

						case 'presentations':

							$this->add_asset( $filepath, $_POST['course'], $_POST['asset'] );

							$_SESSION['debug']['ADD CONTENT'] .= '<br>ADD ASSET';

							break;

						default:

							$fname_arr = explode( '.', $filepath );

							$ext_key = count( $fname_arr ) - 1;

							$ext = $fname_arr[$ext_key];
							$new_path = 'courseware/courses/' . $_POST['course'] . '/assets/';
							$newfile = 'courseware/courses/' . $_POST['course'] . '/assets/' . str_replace( ' ', '_', $_POST['asset'] ) . '.' . $ext;

							$_SESSION['debug']['ADD CONTENT'] .= '<br>COPY ASSET';

							if(!file_exists($new_path)){
								echo "I am here";
								mkdir($new_path, 0777, true);
							}

							if ( !@copy( $filepath, $newfile) ) {
								$this->message = 'Error copying document to course asset.';

								$_SESSION['debug']['ADD CONTENT'] .= '<br>ERROR ASSET COPY';

							}
							else{
								//add the data to the assets table
							}

					}

				} else {

					$this->message = 'File added successfully!';

				}

			} else {

				//utilize the message generated by upload_file()

				$_SESSION['debug']['ADD CONTENT'] .= '<br>UPLOAD ERROR'.$this->message;

				return false;

			}

			$_SESSION['debug']['ADD CONTENT'] .= '<br>SUCCESS'.$this->message;

			return true;

		} else {
			$this->message = 'Failed to write document to database.';

			$_SESSION['debug']['ADD CONTENT'] .= '<br>DB ERROR'.$this->message;

			return false;

		}

	}

	

	public function add_folder() {

		$sql = "INSERT INTO " . FILES_FOLDERS_TABLE . " ( menuID, catID, title, destination ) VALUES ( '" . $_POST['section'] . "', '" . $_POST['extra'] . "', '" . $_POST['newfolder'] . "', '" . strtolower( str_replace( ' ', '_', $_POST['newfolder'] ) ) . "' )";

		$_SESSION['debug']['ADD FOLDER'] = $sql;

		$result = @mysql_query( $sql );

		if( $result ) {

			$folder_id = mysql_insert_id();

			return $folder_id;

		} else {

			$this->message = 'Failed to write new folder to database.';

			return false;

		}

	}

	

	public function add_asset($file, $course, $title) {

		$fname_arr = explode( '.', $file );

		$ext_key = count( $fname_arr ) - 1;

		$doc_type = $fname_arr[$ext_key];

		$access = "private";

		$rev_id = null;

		if( $upload_response = parent::upload($file, $doc_type, $access, $rev_id) ) {

			$sql = "INSERT INTO " . COURSE_ASSET_TABLE . " (course, title, filetype, scribd_id, scribd_key, scribd_sec) VALUES ( '" . $course . "', '" . $title . "', '" . $doc_type . "', '" . $upload_response['doc_id'] . "', '" . $upload_response['access_key'] . "', '" . $upload_response['secret_password'] . "' )";

			$result = @mysql_query( $sql );

			if( $result ) {

				$asset_id = mysql_insert_id();

				$assets_dir = 'courseware/courses/' . $course . '/assets';

				if( !is_dir( $assets_dir ) ) mkdir( $assets_dir );

				$_SESSION['debug']['CONVERSION'] = '';

				$conversion = 'PROCESSING'; $i = 0;

				while( $conversion != 'DONE' ) {

					if( $i>0 ) sleep(1);

					$conversion = parent::getConversionStatus( $upload_response['doc_id'] );

					$_SESSION['debug']['CONVERSION'] .= $i . ':' . $conversion . '<br>';

					$i++;

				}

				$download_response = parent::getDownloadUrl( $upload_response['doc_id'], 'pdf' );

				$oldfile = $download_response['download_link'];

				$newfile = $assets_dir . '/' . $asset_id . '.pdf';

				if ( copy( $oldfile, $newfile) ) {

					return $asset_id;

				}else{

					$this->message = 'Error converting document to course asset.';

					$_SESSION['debug']['ADD CONTENT'] .= '<br>ERROR CONVERTING';

					return false;

				}

			} else {

				$this->message = 'Error writing asset to database.';

				$_SESSION['debug']['ADD CONTENT'] .= '<br>ERROR ASSET DB';

				return false;

			}

		} else {

			$this->message = 'Error uploading document to asset conversion tool.';

			$_SESSION['debug']['ADD CONTENT'] .= '<br>ERROR SCRIBD UPLOAD:' . "$file, $doc_type, $access, $rev_id";

			return false;

		}

	}

	

	public function convert_asset( $lesson_path, $course, $title ) {

		$asset_path = false;

		if( $_POST['asset'] != '' ) {

			$asset_path = 'courseware/courses/' . $course . '/assets/' . $_POST['asset'] . '.pdf';

		} elseif( $_POST['document'] != '' ) {

			$doc = $this->build_doc_path( $_POST['document'] );

			$_POST['asset'] = $this->add_asset( $doc['path'], $course, $doc['name'] );

			$asset_path = 'courseware/courses/' . $course . '/assets/' . $_POST['asset'] . '.pdf';

		} else {

			$this->message = 'No asset or document selected.';

			return false;

		}

		if( $asset_path ) {

			if( $this->build_imagick( $asset_path, $lesson_path, str_replace(' ','_',$title) ) ) {

				return true;

			} else {

				$this->message = 'Unable to convert asset to protected image for course viewing.';

				return false;

			}

		}

	}

	

	public function convert_text_file( $file, $destination ) {

		$fname_arr = explode( '.', $file );

		$ext_key = count( $fname_arr ) - 1;

		$doc_type = $fname_arr[$ext_key];

		$access = "private";

		$rev_id = null;

		if( $upload_response = parent::upload($file, $doc_type, $access, $rev_id) ) {

			$_SESSION['debug']['CONVERSION'] = '';

			$conversion = 'PROCESSING'; $i = 0;

			while( $conversion != 'DONE' ) {

				if( $i>0 ) sleep(1);

				$conversion = parent::getConversionStatus( $upload_response['doc_id'] );

				$_SESSION['debug']['CONVERSION'] .= $i . ':' . $conversion . '<br>';

				$i++;

			}

			$download_response = parent::getDownloadUrl( $upload_response['doc_id'], 'txt' );

			$oldfile = $download_response['download_link'];

			$newfile = $destination . '.txt';

			if ( copy( $oldfile, $newfile ) ) {

				unlink( $file );

				return true;

			}else{

				$this->message = 'Error renaming text document.';

				return false;

			}

		} else {

			$this->message = 'Error uploading document to conversion tool.';

			return false;

		}

	}

	

	public function add_course() {

		if( $_POST['newfolder'] ) {

			$sql = "SELECT ID FROM " . FILES_CATEGORIES_TABLE . " WHERE link_type = 'courses' LIMIT 1";

			$result = @mysql_query( $sql );

			$row = @mysql_fetch_assoc( $result );

			$_POST['extra'] = $row['ID'];

			$folder_id = $this->add_folder();

			if( !$folder_id ) return false;

			$folder_name = $_POST['newfolder'];

		} else {

			$folder_id = $_POST['folder'];

			$sql = "SELECT title FROM " . FILES_FOLDERS_TABLE . " WHERE ID = " . $folder_id;

			$result = @mysql_query( $sql );

			$row = @mysql_fetch_assoc( $result );

			$folder_name = $row['title'];

		}

		$sql = "INSERT INTO " . COURSE_COURSE_TABLE . " ( oID, folder, title, tagline, description, featured ) VALUES ( '" . $_SESSION['usr_id'] . "', '" . $folder_id . "', '" . $_POST['title'] . "', '" . $_POST['tagline'] . "', '" . $_POST['description'] . "', '" . $_POST['featured'] . "' )";

		$_SESSION['debug']['ADD COURSE'] = $sql;

		$result = @mysql_query( $sql );

		if( $result ) {

			$course_id = mysql_insert_id();

			$_SESSION['ucourse'] = $course_id;

			$dir = 'courseware/courses/' . $course_id . '/';

			mkdir( $dir );

			$img_ext = $_FILES['thumbnail']['size'] > 0 ? $this->upload_file('thumb','thumbnail',$dir): false;

			if( $_FILES['thumbnail']['size'] > 0 && !$img_ext ) {

				//utilize the message generated by upload_file()

				return false;

			} elseif( $img_ext ) {

				$sql = "UPDATE " . COURSE_COURSE_TABLE . " SET image = '" . $_FILES['thumbnail']['name'] . "' WHERE ID = " . $course_id . " LIMIT 1";

				$_SESSION['debug']['UPDATE COURSE'] = $sql;

				@mysql_query($sql);

			}

			$this->message = 'Course added successfully!';

			return $course_id;

		} else {
			
			$this->message = mysql_error();

			return false;

		}

	}

	

	public function add_lesson() {

		$sql = "INSERT INTO " . COURSE_LESSON_TABLE . " ( course, oID, title ) VALUES ( '" . $_POST['course'] . "', '" . $_SESSION['usr_id'] . "', '" . $_POST['newlesson'] . "' )";

		$_SESSION['debug']['ADD LESSON'] = $sql;

		$result = @mysql_query( $sql );

		if( $result ) {

			$lesson_id = mysql_insert_id();

			mkdir( 'courseware/courses/'.$_POST['course'].'/'.$lesson_id );

			$_SESSION['ucourse'] = $_POST['course'];

			$_SESSION['ulesson'] = $lesson_id;

			return $lesson_id;

		} else {
			
			$this->message = 'Failed to write new lesson to database.';

			return false;

		}

	}

	

	public function add_chapter() {
		if( $_POST['newlesson'] ) {

			$lesson_id = $this->add_lesson();

			if( !$lesson_id ) return false;

			$lesson_name = $_POST['newlesson'];

		} else {

			$lesson_id = $_POST['lesson'];

			$sql = "SELECT title FROM " . COURSE_LESSON_TABLE . " WHERE ID = " . $lesson_id;

			$result = @mysql_query( $sql );

			$row = @mysql_fetch_assoc( $result );

			$lesson_name = $row['title'];

		}

		$fields = array(

						'lesson'	=>	$lesson_id,

						'title'		=>	$_POST['title'],

						'type'		=>	'text'

					);

		if( $_POST['video'] != '' ) {

			$fields['video'] = $_POST['video'];

			$fields['type'] = 'multi';

		}

		$temp_path = 'courseware/temp/';

		$lesson_path = 'courseware/courses/' . $_POST['course'] . '/' . $lesson_id . '/';

		/* if( !$this->convert_asset( $lesson_path, $_POST['course'], $_POST['title'] ) ) {

			return false;

		} else {

			$fields['type'] = 'multi';

		} */ //this needs to be properly handled

		$txtname = $lesson_path . str_replace( ' ', '_', $_POST['title'] ) . '.txt';

		if( $_FILES['txtfile']['size'] > 0 ) {

			if( $this->upload_file( 'doc', 'txtfile', $temp_path ) ) {

				$oldname = $temp_path . $_FILES['txtfile']['name'];

			} else {

				$this->message = 'Failed to upload text file.';

				return false;

			}

			if( substr( $_FILES['txtfile']['name'], -4 ) == '.txt' ) {

				if( !rename( $oldname, $txtname ) ) {

					$this->message = 'Failed to rename uploaded text file.';

				}

			} else {

				$destination = $lesson_path . str_replace( ' ', '_', $_POST['title'] );

				if( !$this->convert_text_file( $oldname, $destination ) ) {

					$this->message = 'Failed to convert file for plain text.';

				}

			}

		} elseif( $_POST['text'] != '' ) {

			if( !$handle = fopen( $txtname, 'w' ) ) {

				 $this->message = "Unable to create TXT file.";

				 return false;

			}

			if( fwrite( $handle, $_POST['text'] ) === FALSE ) {

				$this->message = "Unable to write TXT file.";

				return false;

			}

			fclose($handle);

		}

		$sql = "INSERT INTO " . COURSE_CHAPTER_TABLE . " ( oID, " . implode( ', ', array_keys( $fields ) ) . " ) VALUES ( '" . $_SESSION['usr_id'] . "', '" . implode( "', '", $fields ) . "')";

		$_SESSION['debug']['ADD CHAPTER'] = $sql;

		if( !$result = @mysql_query( $sql ) ) {
			$this->message = 'Error writing chapter to database.';

			return false;

		}

		return true;

	}

	

	public function add_quiz() {

		if( $_POST['purpose'] == 'lesson' && $_POST['lesson'] == '' ) {

			$this->message = 'ERROR: Quiz Purpose is set to Lesson Completion but no Lesson is selected.';

			return false;

		} elseif( !is_numeric( $_POST['passing'] ) ) {

			$this->message = 'ERROR: Passing percentage only accepts numeric characters.';

			return false;

		}

		$fields = array(

					'oID'		=>	$_SESSION['usr_id'],

					'course'	=>	$_POST['course'],

					'purpose'	=>	$_POST['purpose'],

					'lesson'	=>	$_POST['lesson'],

					'title'		=>	$_POST['title'],

					'max'		=>	$_POST['max'],

					'random'	=>	$_POST['random'],

					'passing'	=>	$_POST['passing']

				);

		if( !is_numeric( $_POST['max'] ) ) $_POST['max'] = 0;

		$sql = "INSERT INTO " . QUIZ_QUIZ_TABLE . " ( " . implode( ', ', array_keys( $fields ) ) . " ) VALUES ( '" . implode( "', '", $fields ) . "')";

		$_SESSION['debug']['ADD QUIZ'] = $sql;

		if( !$result = @mysql_query( $sql ) ) {

			$this->message = 'Error writing quiz to database.';

			return false;

		}

		$this->message = 'Quiz added successfully!';

		return true;

	}

	

	public function add_question() {

		$fields = array(

					'quizID'		=>	$_POST['quiz'],

					'type'			=>	$_POST['type'],

					'required'		=>	$_POST['required'],

					'question'		=>	$_POST['question'],

					'media_type'	=>	$_POST['mediatype'],

					'explanation'	=>	$_POST['explanation']

				);

		switch( $_POST['mediatype'] ) {

			case 'image':

				if( $_POST['image'] != '' ) {

					$fields['media_data'] = $_POST['image'];

				} else {

					$this->message = 'ERROR: Included media type is image but no image was selected.';

					return false;

				}

				break;

			case 'video':

				if( $_POST['video'] != '' ) {

					$fields['media_data'] = $_POST['video'];

				} else {

					$this->message = 'ERROR: Included media type is video but no video was selected.';

					return false;

				}

				break;

			default:

				$fields['media_data'] = '';

		}

		$sql = "INSERT INTO " . QUIZ_QUESTION_TABLE . " ( " . implode( ', ', array_keys( $fields ) ) . " ) VALUES ( '" . implode( "', '", $fields ) . "')";

		$_SESSION['debug']['ADD QUESTION'] = $sql;

		if( !$result = @mysql_query( $sql ) ) {

			$this->message = 'Error writing question to database.';

			return false;

		}

		$question_id = mysql_insert_id();

		$answer = $this->add_answer( $_POST['type'], $question_id );

		if( !$answer ) {

			$sql = "DELETE FROM " . QUIZ_QUESTION_TABLE . " WHERE ID = " . $question_id;

			@mysql_query($sql);

			return false;

		}

		$this->message = 'Question added successfully!';

		return true;

	}

	

	public function add_answer( $type, $question ) {

		switch( $type ) {

			case 'text':

				if( !$_POST['answer_text'] ) {

					$this->message = 'ERROR: No text answer provided.';

					return false;

				}

				$sql = "INSERT INTO " . QUIZ_ANSWERS_TABLE . " (questionID,answer_option,casesensitive,correct) VALUES (" . $question . ",'" . $_POST['answer_text'] . "','" . $_POST['case'] . "','Y')";

				$_SESSION['debug']['ADD ANSWERS'] = $sql;

				if( !@mysql_query( $sql ) ) {

					$this->message = 'ERROR: Unable to write answer to database.';

					return false;

				}

				break;

			case 'truefalse':

				if( !$_POST['answer_truefalse'] ) {

					$this->message = 'ERROR: No true/false answer provided.';

					return false;

				}

				$sql = "INSERT INTO " . QUIZ_ANSWERS_TABLE . " (questionID,answer_option,correct) VALUES (" . $question . ",'" . $_POST['answer_truefalse'] . "','Y')";

				$_SESSION['debug']['ADD ANSWERS'] = $sql;

				if( !@mysql_query( $sql ) ) {

					$this->message = 'ERROR: Unable to write answer to database.';

					return false;

				}

				break;

			case 'multiple':

				$sql = "INSERT INTO " . QUIZ_ANSWERS_TABLE . " (questionID,answer_option,correct) VALUES ";

				$correct = false;

				$i = 1;

				while( $_POST['multi_'.$i] ) {

					if( $_POST['correct_multi_'.$i] == $i ) {

						$answer_correct = 'Y';

						$correct = true;

					} else {

						$answer_correct = 'N';

					}

					if( $i > 1 ) $sql .= ",";

					$sql .= "(" . $question . ",'" . $_POST['multi_'.$i] . "','" . $answer_correct . "')";

					$i++;

				}

				$_SESSION['debug']['ADD ANSWERS'] = $sql;

				if( !@mysql_query( $sql ) ) {

					$this->message = 'ERROR: Unable to write answers to database.';

					return false;

				}

				break;

			default:

				$this->message = 'ERROR: Invalid question type.';

				return false;

		}

		return true;

	}

	

}

?>