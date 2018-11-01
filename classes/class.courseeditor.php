<?
class CourseEditor extends Upload {
	public $lessondir;
	public $basetitle;
	
	public function get_edit_options( $type, $modules ) {
		switch( $type ) {
			case 'chapter':
				$arr = array(
							'lesson' => 'Change Lesson Title',
							'title' => 'Change Chapter Title'
						);
				if( in_array( 'text', $modules ) ) {
					$arr['text'] = 'Change Chapter Text';
				} else {
					$arr['text'] = 'Add Chapter Text';
				}
				if( in_array( 'video', $modules ) ) {
					$arr['video'] = 'Change/Remove Video';
				} else {
					$arr['video'] = 'Add Video';
				}
				if( in_array( 'multi', $modules ) ) {
					$arr['doc'] = 'Change/Remove Document';
				} else {
					$arr['doc'] = 'Add Document';
				}
				break;
			case 'course':
				$arr = array( 'title' => 'Change Course Title' );
				if( in_array( 'tagline', $modules ) ) {
					$arr['tagline'] = 'Change Course Tagline';
				} else {
					$arr['tagline'] = 'Add Course Tagline';
				}
				if( in_array( 'desc', $modules ) ) {
					$arr['desc'] = 'Change Course Description';
				} else {
					$arr['desc'] = 'Add Course Description';
				}
				if( in_array( 'thumb', $modules ) ) {
					$arr['thumb'] = 'Change Course Thumbnail';
				} else {
					$arr['thumb'] = 'Add Course Thumbnail';
				}
				break;
			case 'quiz':
				$arr = array(
							'question'	=> 'Change/Delete Question',
							'type'		=> 'Change Question Type',
							'answers'	=> 'Change Answers'
						);
				if( in_array( 'video', $modules ) ) {
					$arr['video'] = 'Change/Remove Video';
				} elseif( in_array( 'image', $modules ) ) {
					$arr['image'] = 'Change/Remove Image';
				} else {
					$arr['video'] = 'Add Video';
					$arr['image'] = 'Add Image';
				}
				break;
		}
		return $arr;
	}
	
	private function get_path_info( $chapter ) {
		$sql = "SELECT l.course, c.lesson, c.title FROM " . COURSE_CHAPTER_TABLE . " c JOIN " . COURSE_LESSON_TABLE . " l ON c.lesson = l.ID WHERE c.ID = " . $chapter;
		$result = @mysql_query( $sql );
		if( !$result = @mysql_query( $sql ) ) {
			$this->message = 'Database error. Unable to determine correct course and lesson.';
			return false;
		}
		$row = @mysql_fetch_assoc( $result );
		$this->lessondir = 'courseware/courses/' . $row['course'] . '/' . $row['lesson'];
		$this->basetitle = str_replace( ' ', '_', $row['title'] );
		return $row;
	}
	
	public function change_title() {
		if( !$ids = $this->get_path_info( $_POST['chapterid'] ) ) return false;
		$sql = "UPDATE " . COURSE_CHAPTER_TABLE . " SET title = '" . $_POST['title'] . "' WHERE ID = " . $_POST['chapterid'] . " LIMIT 1";
		if( !$result = @mysql_query( $sql ) ) {
			$this->message = 'Error writing new chapter title to database.';
			return false;
		}
		if( $handle = opendir($this->lessondir) ) {
			while( false !== ( $file = readdir($handle) ) ) {
				$_SESSION['debug']['FILE_RENAME'] .= substr( $file, 0, strlen($ids['title']) ).':';
				if( substr( $file, 0, strlen($ids['title']) ) == $this->basetitle ) {
					$oldname = $this->lessondir . '/' . $file;
					$newbase = str_replace( ' ', '_', $_POST['title'] );
					$newname = $this->lessondir . '/' . str_replace( $this->basetitle, $newbase, $file );
					$_SESSION['debug']['FILE_RENAME'] .= $oldname . ' => ' . $newname;
					if( !rename( $oldname, $newname ) ) {
						$this->message = 'Error.  Failed to rename associated files.';
						return false;
					}
				}
				$_SESSION['debug']['FILE_RENAME'] .= '<br>';
			}
		}
		closedir($handle);
		return true;
	}
	
	public function change_text() {
		if( !$ids = $this->get_path_info( $_POST['chapterid'] ) ) return false;
		$txtname = $this->lessondir . '/' . $this->basetitle . '.txt';
		$_SESSION['debug']['TXTNAME'] = $txtname;
		if( !$handle = fopen( $txtname, 'w' ) ) {
			 $this->message = 'Unable to open text file.';
			 return false;
		}
		if( fwrite( $handle, $_POST['text'] ) === FALSE ) {
			$this->message = 'Unable to update text file.';
			return false;
		}
		fclose($handle);
		return true;
	}
	
	public function change_video() {
		if( $_POST['video'] == '' ) {
			$this->message = 'Please select a video.';
			return false;
		}
		$video = ( $_POST['video'] == 'remove' ) ? 'NULL': $_POST['video'];
		$sql = "UPDATE " . COURSE_CHAPTER_TABLE . " SET video = " . $video . " WHERE ID = " . $_POST['chapterid'] . " LIMIT 1";
		if( !$result = @mysql_query( $sql ) ) {
			$this->message = 'Error changing chapter video.';
			return false;
		}
	}
	
	public function change_doc() {
		if( !$ids = $this->remove_doc(true) ) return false;
		$lesson_path = $this->lessondir . '/';
		if( !$this->convert_asset( $lesson_path, $ids['course'], $ids['title'] ) ) return false;
		$sql = "UPDATE " . COURSE_CHAPTER_TABLE . " SET type = 'multi' WHERE ID = " . $_POST['chapterid'] . " LIMIT 1";
		if( !$result = @mysql_query( $sql ) ) {
			$this->message = 'Error changing chapter type for document handling.';
			return false;
		}
	}
	
	public function remove_doc( $replace = false ) {
		if( !$ids = $this->get_path_info( $_POST['chapterid'] ) ) return false;
		$path = $this->lessondir . '/' . str_replace( ' ', '_', $ids['title'] );
		$extension = 'jpg';
		$page_num = 1;
		while( file_exists( $path.'('.$page_num.').'.$extension ) ) {
			unlink( $path.'('.$page_num.').'.$extension );
			$page_num++;
		}
		if( !$replace ) {
			$sql = "UPDATE " . COURSE_CHAPTER_TABLE . " SET type = 'text' WHERE ID = " . $_POST['chapterid'] . " LIMIT 1";
			if( !$result = @mysql_query( $sql ) ) {
				$this->message = 'Error changing chapter type to text/video only.';
				return false;
			}
		}
		return $ids;
	}
	
	public function change_lesson() {
		$sql = "UPDATE " . COURSE_LESSON_TABLE . " SET title = '" . $_POST['title'] . "' WHERE ID = " . $_POST['lessonid'] . " LIMIT 1";
		if( !$result = @mysql_query( $sql ) ) {
			$this->message = 'Error writing new lesson title to database.';
			return false;
		}
		return true;
	}
	
	public function change_course() {
		if( $_POST['title'] ) {
			$set_field = 'title';
			$set_value = $_POST['title'];
		} elseif( $_POST['tagline'] ) {
			$set_field = 'tagline';
			$set_value = $_POST['tagline'];
		} elseif( $_POST['description'] ) {
			$set_field = 'description';
			$set_value = $_POST['description'];
		} else {
			$this->message = 'Error determining update type.';
			return false;
		}
		$sql = "UPDATE " . COURSE_COURSE_TABLE . " SET " . $set_field . " = '" . $set_value . "' WHERE ID = " . $_POST['id'];
		if( !$result = @mysql_query( $sql ) ) {
			$this->message = 'Error updating course in database.';
			return false;
		}
		return true;
	}
	
	public function change_thumbnail() {
		$dir = 'courseware/courses/' . $_POST['id'] . '/';
		mkdir( $dir );
		$img_ext = $_FILES['thumbnail']['size'] > 0 ? $this->upload_file('thumb','thumbnail',$dir): false;
		if( $_FILES['thumbnail']['size'] > 0 && !$img_ext ) {
			//utilize the message generated by upload_file()
			return false;
		} elseif( $img_ext ) {
			$sql = "UPDATE " . COURSE_COURSE_TABLE . " SET image = '" . $_FILES['thumbnail']['name'] . "' WHERE ID = " . $_POST['id'] . " LIMIT 1";
			$_SESSION['debug']['UPDATE COURSE'] = $sql;
			@mysql_query($sql);
		}
		return true;
	}
}
?>