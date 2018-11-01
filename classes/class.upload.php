<?

class Upload extends Gdrive {

	public $message = false;

	const CATEGORY = 6; // category ID for courses

	

	public function __construct() {
		parent::__construct();
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

		$publish = ($_POST['proc_type'] == 'Unpublished') ? 'Y': 'N';

		$sql = "UPDATE " . $table . " SET publish = '" . $publish . "' WHERE ID = " . $_POST['id'] . " LIMIT 1";

		if( !@mysql_query($sql) ) {

			$this->message = 'ERROR: Failed to update publish status.';

			return false;

		}

		if( $_POST['type'] == 'Courses' && $_POST['proc_type'] == 'Published' ) {

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

			$sql = "SELECT c.folder, f.menuID FROM " . COURSE_COURSE_TABLE . " c JOIN " . FILES_FOLDERS_TABLE . "f ON c.folder = f.ID WHERE c.ID = " . @$_SESSION['ucourse'] . " LIMIT 1";

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

	

	public function get_folders($section = NULL) {

		$arr = array();

		if($section==NULL){
			$section = $_SESSION['usection'];
		}

		$sql = "SELECT ID, title FROM " . FILES_FOLDERS_TABLE;

		if( !empty($section) ) $sql .= " WHERE menuID = " . $section;

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

	public function get_courses_by_folder( $folder =NULL ) {

		$arr = array();
		$user_id = $_SESSION['usr_id'];
		$sql = "SELECT ID, title FROM " . COURSE_COURSE_TABLE." WHERE oID='$user_id'";

		if( !empty($folder) ) {

			$sql .= " AND folder = " . $folder;

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

	public function get_lessons_by_course( $course = NULL ) {

		$arr = array();

		$sql = "SELECT ID, title FROM " . COURSE_LESSON_TABLE;

		if( !empty($course )) {

			$sql .= " WHERE course = " . $course;

		}

		$sql .= " ORDER BY title";

		$result = @mysql_query( $sql );

		while( $row = @mysql_fetch_assoc( $result ) ) {

			$arr[$row['ID']] = $row['title'];

		}

		return $arr;

	}

	public function get_chapters_by_lesson( $lesson = NULL ) {

		$arr = array();

		$sql = "SELECT ID, title FROM " . COURSE_CHAPTER_TABLE;

		if( !empty($lesson )) {

			$sql .= " WHERE lesson = " . $lesson;

		}

		$sql .= " ORDER BY display_order, title";

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

	

	public function get_folder_files($folder = NULL) {

		$arr = array();
		if(!empty($folder)){
			$_SESSION['ufolder'] = $folder;
		}
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
		if( !file_exists($dir) ) mkdir( $dir, 0777, true);

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

						//upload this to gdrive too if it is a word or powerpoint
						$ext = strtolower($fname_arr[$ext_key]);
						if($ext=='ppt' || $ext=='pptx' || $ext=='doc' || $ext=='docx'){
							parent::upload($dest_file,$newname,$ext);
						}
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
		if( isset($_POST['newfolder']) ) {

			$folder_id = $this->add_folder();

			if( !$folder_id ) return false;

			$folder_name = $_POST['newfolder'];

		} else {

			$folder_id = $_POST['folder'];

			$sql = "SELECT title FROM " . COURSE_LESSON_TABLE . " WHERE ID = " . @$lesson_id;

			$result = @mysql_query( $sql );

			$row = @mysql_fetch_assoc( $result );

			$lesson_name = $row['title'];

		}

		$sql = "INSERT INTO " . FILES_DOCUMENTS_TABLE . " (folder, cID, title, description, filename, featured, tags) VALUES ( '" . $folder_id . "', '" . $_SESSION['usr_id'] . "', '" . $_POST['title'] . "', '" . $_POST['description'] . "', '" . $_FILES['uploadfile']['name'] . "', '" . $_POST['featured'] . "', '" . $_POST['tags'] . "' )";
		
		$_SESSION['debug']['ADD CONTENT'] = $sql;

		$result = @mysql_query( $sql );

		if( $result ) {
			$last_insert_id = mysql_insert_id();
			$sql = "SELECT c.destination AS Category, f.destination AS Folder FROM " . FILES_FOLDERS_TABLE . " f JOIN " . FILES_CATEGORIES_TABLE . " c ON f.catID = c.ID WHERE f.ID = " . $folder_id;

			$_SESSION['debug']['ADD CONTENT'] .= '<br>'.$sql;

			$dir_result = @mysql_query( $sql );

			$dir_row = @mysql_fetch_assoc( $dir_result );

			$dir = 'content/' . $dir_row['Category'] . '/' . $dir_row['Folder'] . '/';

			$_SESSION['debug']['ADD CONTENT'] .= '<br>'.$dir;

			if( $filepath = $this->upload_file('content', 'uploadfile', $dir, $_POST['title']) ) {
				//update the gdrive id
				$pdf_name ='';
				if(isset($_SESSION['gdrive_id'])){
					$gdrive_id = $_SESSION['gdrive_id'];
					$file_name = $_SESSION['file_name'];
					$sql = "UPDATE " . FILES_DOCUMENTS_TABLE . " SET gdrive_id='$gdrive_id',pdf_name='$file_name' WHERE ID='$last_insert_id' ";
					$result = @mysql_query( $sql );	
					$pdf_name = $file_name;
					unset($_SESSION['gdrive_id']);
					unset($_SESSION['file_name']);
				}
				if( $_POST['course'] != '' ) {

					$fname_arr = explode( '.', $filepath );

					$ext_key = count( $fname_arr ) - 1;

					$ext = $fname_arr[$ext_key];
					$new_path = 'courseware/courses/' . $_POST['course'] . '/assets/';
					$newfile = 'courseware/courses/' . $_POST['course'] . '/assets/' . str_replace( ' ', '_', $_POST['asset'] ) . '.' . $ext;

					$_SESSION['debug']['ADD CONTENT'] .= '<br>COPY ASSET';

					if(!file_exists($new_path)){
						mkdir($new_path, 0777, true);
					}

					if ( !@copy( $filepath, $newfile) ) {
						$this->message = 'Error copying document to course asset.';

						$_SESSION['debug']['ADD CONTENT'] .= '<br>ERROR ASSET COPY';

					}
					else{
						//add the data to the assets table
						$sql = "INSERT INTO crs_asset (title, filetype,filepath, course,pdf_name) VALUES ('" . $_POST['title'] . "','" .$ext . "','".$newfile."','".$_POST['course']."','".$pdf_name."')";
						mysql_query( $sql );
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
		$user_id = $_SESSION['usr_id'];
		$sql = "INSERT INTO " . FILES_FOLDERS_TABLE . " ( menuID, catID, title, destination,oID ) VALUES ( '" . $_POST['section'] . "', '" . $_POST['extra'] . "', '" . $_POST['newfolder'] . "', '" . strtolower( str_replace( ' ', '_', $_POST['newfolder'] ) ) . "','$user_id' )";

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
		if($_POST['section']=='new_section'){
			if(empty($_POST['newsection'])){
				$this->message = 'Enter the new section name';

				return false;
			}
			else{
				$section_name = $_POST['newsection'];
				//make sure the name is not duplicated
				$sql = "SELECT * FROM panel_menu WHERE name='$section_name'";
				$result = mysql_query($sql);
				if(mysql_num_rows($result)>0){
					$row = $mysql_fetch_assoc($result);
					$_POST['section'] = $row['ID'];
				}
				else{
					$sql = "INSERT INTO panel_menu(titleID,name) VALUES(12,'$section_name')";
					mysql_query($sql);
					$last_insert_id = mysql_insert_id();
					$_POST['section'] = $last_insert_id;
				}
			}
		}

		if($_POST['folder']=='add_new_folder') {

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

		$featured = $_POST['featured'];

		//check if this is published, unpublished or draft
		if($_POST['action']=='Publish'){
			$publish='Y';
			$draft = 'N';
		}
		else if($_POST['action']=='Unpublish'){
			$publish='N';
			$draft = 'N';
		}
		else{
			$publish='N';
			$draft = 'Y';
		}
		

		$sql = "INSERT INTO " . COURSE_COURSE_TABLE . " ( oID, folder, title, tagline, description, featured,publish,draft ) VALUES ( '" . $_SESSION['usr_id'] . "', '" . $folder_id . "', '" . $_POST['title'] . "', '" . $_POST['tagline'] . "', '" . $_POST['description'] . "', '" . $featured . "','$publish','$draft' )";

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

			//upload image if present
			if( $_FILES['imagefile']['size'] > 0 ) {
				//just allow jpg, png and gif files only
			
				$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
				$detectedType = exif_imagetype($_FILES['imagefile']['tmp_name']);
				$pathinfo = pathinfo($_FILES['imagefile']['name']);
				$newname = $pathinfo['filename'];
				$extension = $pathinfo['extension'];
				if(in_array($detectedType, $allowedTypes)){
					if( file_exists( $dir . $newname.'.'.$extension ) ) {

						for( $i=1; $i<100; $i++ ) {

							if( !file_exists( $newname . '(' . $i . ').' . $extension ) ) {

								$filename = $newname . '(' . $i . ').' . $extension;
								break;

							}

						}

					} else {
						$filename = $newname.'.'.$extension;

					}

					move_uploaded_file($_FILES['imagefile']['tmp_name'],$dir.$filename);
					$image = $filename;

					//update this name
					$sql="UPDATE ".COURSE_COURSE_TABLE." SET results_image='$image' WHERE ID='$course_id'";
					mysql_query($sql);
				}
			}

			$this->message = 'Topic added successfully. Please add chapter or part now.';

			return $course_id;

		} else {
			
			$this->message = mysql_error();

			return false;

		}

	}

	public function update_course() {
		
		if($_POST['folder']=='add_new_folder') {

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

		$sql = "UPDATE " . COURSE_COURSE_TABLE . " SET title='".$_POST['title']."', tagline='".$_POST['tagline']."', description='". $_POST['description']."', folder='".$folder_id."', featured='".$_POST['featured']."' WHERE ID='".$_POST['course_id']."'";

		$_SESSION['debug']['UPDATE COURSE'] = $sql;

		$result = @mysql_query( $sql );

		if( $result ) {

			$course_id = $_POST['course_id'];

			$_SESSION['ucourse'] = $course_id;

			if(isset($_FILES['thumbnail']) && $_FILES['thumbnail']['size']>0){
				
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
			}

			$this->message = 'Course updated successfully!';

			return $course_id;

		} else {
			
			$this->message = mysql_error();

			return false;

		}

	}

	

	public function add_lesson() {

		$sql = "INSERT INTO " . COURSE_LESSON_TABLE . " ( course, oID, title ) VALUES ( '" . $_POST['course'] . "', '" . $_SESSION['usr_id'] . "', '" . mysql_escape_string($_POST['newlesson']) . "' )";

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

		
		if( isset($_POST['lesson']) && $_POST['lesson']=='new') {

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

						'title'		=>	mysql_escape_string($_POST['title']),
						'asset'		=>	$_POST['asset'],

						'type'		=>	'text',
						'content'		=>	mysql_escape_string($_POST['content']),


					);

		if( $_POST['video'] != '' ) {

			$fields['video'] = $_POST['video'];

			$fields['type'] = 'multi';

		}

		$temp_path = 'courseware/temp/';

		$lesson_path = 'courseware/courses/' . $_POST['course'] . '/' . $lesson_id . '/';

		if(!file_exists($lesson_path)){
			mkdir($lesson_path,0777,true);
		}

		/* if( !$this->convert_asset( $lesson_path, $_POST['course'], $_POST['title'] ) ) {

			return false;

		} else {

			$fields['type'] = 'multi';

		} */ //this needs to be properly handled

		$txtname = $lesson_path . str_replace( ' ', '_', $_POST['title'] ) . '.txt';

		if( !$handle = fopen( $txtname, 'w' ) ) {

				$this->message = "Unable to create TXT file.";

				return false;

		}

		if( fwrite( $handle, $_POST['content'] ) === FALSE ) {

			$this->message = "Unable to write TXT file.";

			return false;

		}

		fclose($handle);


		$sql = "INSERT INTO " . COURSE_CHAPTER_TABLE . " ( oID, " . implode( ', ', array_keys( $fields ) ) . " ) VALUES ( '" . $_SESSION['usr_id'] . "', '" . implode( "', '", $fields ) . "')";

		$_SESSION['debug']['ADD CHAPTER'] = $sql;

		if( !$result = @mysql_query( $sql ) ) {
			$this->message = 'Error writing chapter to database.';
			return false;
		}

		$chapter_id = mysql_insert_id();
		//now check if we have 
		if(isset($_FILES['uploadfile']) && $_FILES['uploadfile']['size']>0){
			
			$upload_dir = "content/documents/". $_POST['course'] . '/' . $lesson_id . '/'.$chapter_id.'/';
			$pdf_upload_dir = "content/pdf/". $_POST['course'] . '/' . $lesson_id . '/'.$chapter_id.'/';
			$file_url = $this->upload_file_for_chapter($upload_dir,$pdf_upload_dir);
			//delete file url
			@unlink($file_url);

			//update the file name in the database
			$file_name = $_SESSION['file_name'];
			$sql = "UPDATE " . COURSE_CHAPTER_TABLE . " SET asset_name='$file_name' WHERE ID='$chapter_id' ";
			$result = @mysql_query( $sql );	
			unset($_SESSION['gdrive_id']);
			unset($_SESSION['file_name']);
		}

		$this->message= "Successfully added the new chapter/part";
		return true;


	}

	

	public function add_quiz() {
		
		if( $_POST['purpose'] == 'chapter' && $_POST['chapter'] == '' ) {

			$this->message = 'ERROR: Quiz Purpose is set to Part Completion but no Part is selected.';

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
					'chapter'	=>	$_POST['chapter'],

					'title'		=>	$_POST['title'],

					'passing'	=>	$_POST['passing']

				);

		$sql = "INSERT INTO " . QUIZ_QUIZ_TABLE . " ( " . implode( ', ', array_keys( $fields ) ) . " ) VALUES ( '" . implode( "', '", $fields ) . "')";

		$_SESSION['debug']['ADD QUIZ'] = $sql;

		if( !$result = @mysql_query( $sql ) ) {
			$this->message = 'Error writing quiz to database.';

			return false;

		}

		$this->message = 'Quiz added successfully! Add questions now.';

		return true;

	}

	public function update_quiz() {
		
		if( $_POST['purpose'] == 'chapter' && $_POST['chapter'] == '' ) {

			$this->message = 'ERROR: Quiz Purpose is set to Part Completion but no Part is selected.';

			return false;

		} elseif( !is_numeric( $_POST['passing'] ) ) {

			$this->message = 'ERROR: Passing percentage only accepts numeric characters.';

			return false;

		}


		$ID		=	$_POST['ID'];
		$course		=	$_POST['course'];

		$purpose	=	$_POST['purpose'];

		$lesson		=	$_POST['lesson'];
		$chapter	=	$_POST['chapter'];

		$title		=	$_POST['title'];

		$passing	=	$_POST['passing'];


		$sql = "UPDATE quiz_quiz SET course='$course', purpose='$purpose', lesson='$lesson', chapter='$chapter', title='$title', passing='$passing' WHERE id='$ID'";

		$_SESSION['debug']['UPDATE QUIZ'] = $sql;

		if( !$result = @mysql_query( $sql ) ) {
			$this->message = 'Error writing quiz to database.';

			return false;

		}

		$this->message = 'Quiz updated successfully!';

		return true;

	}

	

	public function add_question() {

		$fields = array(

					'quizID'		=>	$_POST['quiz'],

					'question_type'	=>	$_POST['question_type'],

					'question'		=>	$_POST['question'],

					'explanation'	=>	$_POST['explanation']

				);

		$sql = "INSERT INTO " . QUIZ_QUESTION_TABLE . " ( " . implode( ', ', array_keys( $fields ) ) . " ) VALUES ( '" . implode( "', '", $fields ) . "')";

		$_SESSION['debug']['ADD QUESTION'] = $sql;

		if( !$result = @mysql_query( $sql ) ) {

			$this->message = 'Error writing question to database.';

			return false;

		}

		$question_id = mysql_insert_id();

		$answer = $this->add_answer( $_POST['question_type'], $question_id );

		if( !$answer ) {

			$sql = "DELETE FROM " . QUIZ_QUESTION_TABLE . " WHERE ID = " . $question_id;

			@mysql_query($sql);

			return false;

		}

		$this->message = 'Question added successfully!';

		return true;

	}

	public function update_question() {

		$question_id	=	$_POST['ID'];

		$quizID			=	$_POST['quiz'];

		$question_type	=	$_POST['question_type'];

		$question		=	$_POST['question'];

		$explanation	=	$_POST['explanation'];

		$sql = "UPDATE quiz_question SET quizID='$quizID', question_type='$question_type', question='$question', explanation='$explanation' WHERE ID='$question_id'";

		$_SESSION['debug']['UPDATE QUESTION'] = $sql;

		if( !$result = @mysql_query( $sql ) ) {

			$this->message = 'Error writing question to database.';

			return false;

		}

		//delete old answers then add again
		$sql = "DELETE FROM quiz_answers WHERE questionID='$question_id'";
		mysql_query($sql);

		$answer = $this->add_answer( $_POST['question_type'], $question_id );

		if( !$answer ) {

			$sql = "DELETE FROM " . QUIZ_QUESTION_TABLE . " WHERE ID = " . $question_id;

			@mysql_query($sql);

			return false;

		}

		$this->message = 'Question updated successfully!';

		return true;

	}

	

	public function add_answer( $type, $question ) {

		switch( $type ) {

			case 'TF':

				if( !$_POST['answer_truefalse'] ) {

					$this->message = 'ERROR: No true/false answer provided.';

					return false;

				}

				$sql = "INSERT INTO quiz_answers (questionID,answer_option,correct) VALUES (" . $question . ",'" . $_POST['answer_truefalse'] . "','Y')";

				$_SESSION['debug']['ADD ANSWERS'] = $sql;

				if( !@mysql_query( $sql ) ) {

					$this->message = mysql_error();

					return false;

				}

				break;

			case 'MC':

				$sql = "INSERT INTO quiz_answers (questionID,answer_option,correct) VALUES ";

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

	public function update_lesson()	{
		$title = mysql_escape_string($_POST['title']);
		$id = $_POST['lesson_id'];
		$sql = "UPDATE crs_lesson SET title='$title' WHERE ID='$id'";
		mysql_query($sql);
	}

	public function update_chapter()	{
		$id = $_POST['chapter_id'];
		$title = mysql_escape_string($_POST['title']);
		$video = $_POST['video'];
		$lesson = $_POST['lesson'];
		$asset = $_POST['asset'];

		$temp_path = 'courseware/temp/';

		$lesson_path = 'courseware/courses/' . $_POST['course'] . '/' . $lesson . '/';

		if(!file_exists($lesson_path)){
			mkdir($lesson_path,0777,true);
		}

		/* if( !$this->convert_asset( $lesson_path, $_POST['course'], $_POST['title'] ) ) {

			return false;

		} else {

			$fields['type'] = 'multi';

		} */ //this needs to be properly handled

		$txtname = $lesson_path . str_replace( ' ', '_', $_POST['title'] ) . '.txt';
		
		
			
		if( !$handle = fopen( $txtname, 'w' ) ) {
				$this->message = "Unable to create TXT file.";
				exit;
				return false;

		}
		if( fwrite( $handle, $_POST['content'] ) === FALSE ) {
			$this->message = "Unable to write TXT file.";
			exit;
			return false;

		}
		fclose($handle);

			//if old title is changed then rename the file to the new one
			/* $sql = "SELECT * FROM crs_chapter WHERE ID='$id'";
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);
			$old_title = $row['title'];
			if($old_title != $title){
				$location = $lesson_path . str_replace( ' ', '_', $old_title ) . '.txt';
				if(file_exists($location)){
					$newname = $lesson_path . str_replace( ' ', '_', $title ) . '.txt';
					rename($location,$newname);
				}
			} */

			//upload image if present
		/* if( $_FILES['imagefile']['size'] > 0 ) {
			//print_r($_FILES);
			//just allow jpg, png and gif files only
			$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
			$detectedType = exif_imagetype($_FILES['imagefile']['tmp_name']);
			$pathinfo = pathinfo($_FILES['imagefile']['name']);
			$newname = $pathinfo['filename'];
			$extension = $pathinfo['extension'];
			if(in_array($detectedType, $allowedTypes)){
				if( file_exists( $lesson_path . $newname.'.'.$extension ) ) {

					for( $i=1; $i<100; $i++ ) {

						if( !file_exists( $newname . '(' . $i . ').' . $extension ) ) {

							$filename = $newname . '(' . $i . ').' . $extension;
							break;

						}

					}

				} else {
					$filename = $newname.'.'.$extension;

				}

				move_uploaded_file($_FILES['imagefile']['tmp_name'],$lesson_path.$filename);
				$image = $filename;
			}
		} */
		/* if(isset($image)){
			$sql = "UPDATE crs_chapter SET title='$title', video='$video',lesson='$lesson',asset='$asset', image='$image',content='$content' WHERE ID='$id'";
		}
		else{ */
		$sql = "UPDATE crs_chapter SET title='$title', video='$video',lesson='$lesson',asset='$asset',content='$content' WHERE ID='$id'";
		//}
		
		mysql_query($sql);

		//check if we have to upload any file
		if(isset($_FILES['uploadfile']) && $_FILES['uploadfile']['size']>0){
			
			$upload_dir = "content/documents/". $_POST['course'] . '/' . $lesslessonon_id . '/'.$id.'/';
			$pdf_upload_dir = "content/pdf/". $_POST['course'] . '/' . $lesson . '/'.$id.'/';
			$file_url = $this->upload_file_for_chapter($upload_dir,$pdf_upload_dir);
			//delete file url
			@unlink($file_url);

			//update the file name in the database
			$file_name = $_SESSION['file_name'];
			$sql = "UPDATE " . COURSE_CHAPTER_TABLE . " SET asset_name='$file_name' WHERE ID='$id' ";
			$result = @mysql_query( $sql );	
			unset($_SESSION['gdrive_id']);
			unset($_SESSION['file_name']);
		}
		$this->message = "Sucessfully updated the Part";
		return true;

	}

	public function deleteCourse($id){
		//delete records from the following tables
		/* 
			1. crs_chapters
			2. crs_lessons
			3. crs_course
			4. crs_asset
		 */

		//delete assets
		$sql = "DELETE FROM crs_asset WHERE course='$id'";
		mysql_query($sql);


		//delete chapters
		$sql = "SELECT * FROM  crs_lesson WHERE course='$id'";
		$result = mysql_query($sql);
		while($row = mysql_fetch_assoc($result)){
			$lesson = $row['ID'];
			$sql = "DELETE FROM crs_chapter WHERE lesson='$lesson'";
			mysql_query($sql);
		}

		//delete lessons
		$sql = "DELETE FROM crs_lesson WHERE course='$id'";
		mysql_query($sql);

		$sql = "DELETE FROM crs_course WHERE ID='$id'";
		mysql_query($sql);

	}

	public function deleteChapter($id){
		$sql = "DELETE FROM crs_chapter WHERE ID='$id'";
		mysql_query($sql);
	}

	public function deleteLesson($id){
		//delete chapters
		$sql = "DELETE FROM crs_chapter WHERE lesson='$id'";
		mysql_query($sql);

		//delete lessons
		$sql = "DELETE FROM crs_lesson WHERE ID='$id'";
		mysql_query($sql);
	}

	public function deleteQuestion($id){

		//delete questions
		$sql = "DELETE FROM quiz_question WHERE ID='$id'";
		mysql_query($sql);

		//delete answers
		$sql = "DELETE FROM quiz_answer WHERE quizID='$id'";
		mysql_query($sql);

		
	}

	public function deleteQuiz($id){

		//delete answers
		$sql = "SELECT * FROM  quiz_question WHERE quizID='$id'";
		$result = mysql_query($sql);
		while($row = mysql_fetch_assoc($result)){
			$questionID = $row['ID'];
			$sql = "DELETE FROM quiz_answers WHERE questionID='$questionID'";
			mysql_query($sql);
		}

		//delete lessons
		$sql = "DELETE FROM quiz_question WHERE quizID='$id'";
		mysql_query($sql);

		$sql = "DELETE FROM quiz_quiz WHERE ID='$id'";
		mysql_query($sql);

		
	}


	public function update_lesson_order(){
		$id = $_POST['id'];
		$display_order = $_POST['display_order'];

		$sql = "UPDATE crs_lesson SET display_order='$display_order' WHERE ID='$id'";
		mysql_query($sql);
	}

	public function update_chapter_order(){
		$id = $_POST['id'];
		$display_order = $_POST['display_order'];

		$sql = "UPDATE crs_chapter SET display_order='$display_order' WHERE ID='$id'";
		mysql_query($sql);
	}

	public function update_question_order(){
		$id = $_POST['id'];
		$display_order = $_POST['display_order'];

		$sql = "UPDATE quiz_question SET display_order='$display_order' WHERE ID='$id'";
		mysql_query($sql);
	}


	public function upload_file_for_chapter($upload_dir,$pdf_upload_dir) {

			if( !file_exists($upload_dir) ) mkdir( $upload_dir, 0777, true);
			if( !file_exists($pdf_upload_dir) ) mkdir( $pdf_upload_dir, 0777, true);
			

			define( 'UPLOAD_DIR', $upload_dir );

			$fname_arr = explode( '.', $_FILES['uploadfile']['name']);

			$ext_key = count( $fname_arr ) - 1;

			$title = $_POST['title'];

			$newname = $title ? str_replace( ' ', '_', $title ) . '.' . $fname_arr[$ext_key]: $_FILES['uploadfile']['name'];

			switch( $_FILES['uploadfile']['error'] ) {

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
					/*  */
					if( @move_uploaded_file( $_FILES['uploadfile']['tmp_name'], $dest_file ) ) {

						//upload this to gdrive too if it is a word or powerpoint
						$ext = strtolower($fname_arr[$ext_key]);
						if($ext=='ppt' || $ext=='pptx' || $ext=='doc' || $ext=='docx'){
							parent::upload($dest_file,$newname,$ext,$pdf_upload_dir);
						}
						return $dest_file;

					} else {

						$_SESSION['debug']['ADD CONTENT'] .= '<br>UCOPY ERROR';

						$this->message = "Error0 uploading file. Please try again.";

						return '';

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

		
	}

	public function answer_question(){
		$quiz_id = $_POST['quiz'];
		$questions = $_POST['question'];

		//check which answers are correct and which are wrong
		$sql = "SELECT * FROM quiz_quiz WHERE ID='$quiz_id'";
		$result = mysql_query($sql);
		$quiz = mysql_fetch_assoc($result);
		

		//now get all the question of this answer
		$sql = "SELECT * FROM quiz_question WHERE quizID='$quiz_id'";
		$result = mysql_query($sql);
		$questions_from_database = array();
		while($row = mysql_fetch_assoc($result)){
			$questions_from_database[] = $row;
		}

		// now let's go through the questions and check it the user has answered it correctly or not
		$corrent_answers =0;
		$wrong_answers = array(); //we will keep track of all the wrong answerd questions
		foreach($questions_from_database as $qb){
			$question_id = $qb['ID'];

			//check if there is answer for this question or not
			if(isset($questions[$question_id])){
				
				// now check which type of question is this, then check answer accordingly
				if($qb['question_type']=='TF'){
					//get this questions's answer
					$sql = "SELECT * FROM quiz_answers WHERE questionID='$question_id'";
					$result = mysql_query($sql);
					$answer = mysql_fetch_assoc($result);
					
					if($answer['answer_option']==$questions[$question_id]){
						$corrent_answers++;
					}
					else{
						$wrong_answers[] = $question_id;
					}
				}
				else{
					//check for multiple choice questions. Get correct answers only
					$sql = "SELECT * FROM quiz_answers WHERE questionID='$question_id' AND correct='Y'";
					$result = mysql_query($sql);
					$answers = array();
                    while($row = mysql_fetch_assoc($result)){
						$answers[] = $row;
					}
					//if the user has give same number of correct answers as expected then proceed other wise consider it a wrong answer
					if(count($questions[$question_id]) == count($answers)){
						// now check if the give answers are correct or not
						$total_answers = count($answers);
						$total_correct_answers = 0;
						foreach($answers as $answer){
							if(in_array($answer['ID'],$questions[$question_id])){
								$total_correct_answers++;
							}
						}

						//now if all are correct then increase the correct_answers by 1
						if($total_answers == $total_correct_answers){
							$corrent_answers++;
						}
						else{
							$wrong_answers[] = $question_id;

						}
					}
					else{
						$wrong_answers[] = $question_id;
					}
				}
			}
		}

		//now calculate the percentage
		$percentage = $corrent_answers/count($questions_from_database)*100;
		
		//check if this user has passed or not
		$result = 'fail';
		if($percentage >= $quiz['passing']){
			$result = 'pass';
		}
		//now add this test result to the database
		$user_id = $_SESSION['usr_id'];
		$dateTaken = date('Y-m-d H:i:s');
		$sql = "INSERT INTO quiz_result(quizID,oID,percentage,result,dateTaken) VALUES('$question_id','$user_id','$percentage','$result','$dateTaken')";
		mysql_query($sql);
		$result_id = mysql_insert_id();

		//now need to redirec to another page. Store some data in the session for later use
		$_SESSION['wrong_answers'] = $wrong_answers;
		$_SESSION['questions'] = $questions;

		$redirect_url = "?page=topic&view=lesson&quiz=".$quiz_id."&quiz_page=result&result_id=".$result_id;

		return $redirect_url;

	}

	public function deleteFolder(){
		$folder_id = $_POST['id'];
		$user_id = $_SESSION['usr_id'];

		//first make sure this folder can be deleted by this user or not. For that it should be either owner or admin
		if($_SESSION['usr_level']!=8){
			$sql = "SELECT * FROM fls_folders WHERE oID='$user_id' AND ID='$folder_id'";
			$result = mysql_query($sql);
			if(mysql_num_rows($result)==0){
				return array('success'=>false,'message'=>"You are not allowed to delete this folder. ");
			}
		}

		//now check if this topic has any topic or not. If yes not allowed to delete this
		$sql = "SELECT * FROM crs_course WHERE folder='$folder_id'";
		$result = mysql_query($sql);
		if(mysql_num_rows($result)>0){
			return array('success'=>false,'message'=>"This folder has ".mysql_num_rows($result)." topic(s) that's why this can't be deleted. To delete this please delete all the topics inside this folder first. ");
		}

		//everything looks good now. Let's delete this folder now
		$sql = "DELETE FROM fls_folders WHERE ID='$folder_id'";
		mysql_query($sql);
		return array('success'=>true,'message'=>'Folder was successfully deleted');

	}

	

}

?>