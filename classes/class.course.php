<?

class Course {

	public $course = array();

	public $lessons = array();

	public $quizzes = array();

	public $videos = array();

	public $question;
	

	public $quiz;

	public $progress = array();

	public $other;

	

	public function __construct() {

		if( $_SESSION['page'] != 'editor' ) {

			if( $_SESSION['quiz_in_progress'] && !$_REQUEST['quiz'] ) $_SESSION['quiz_in_progress'] = false;

			$this->build_course_nav();

			$this->get_course();

			$this->get_quizzes();

			$this->get_other();

		}

	}

	

	public function build_course_nav() {

		$complete_arr = $this->get_user_complete();

		$this->get_lessons();

		foreach( $this->lessons as $key => $lesson_arr ) {

			$this->get_chapters($key);
			//check if the lesson is completed
			$user_id = $_SESSION['usr_id'];
			$sql = "SELECT * FROM crs_lesson_complete WHERE lesson='$key' AND UserID='$user_id'";
			$result = mysql_query($sql);
			if(mysql_num_rows($result)>0){
				$this->lessons[$key]['complete'] = true;
			}
			else{
				$this->lessons[$key]['complete'] = false;
			}
			//$this->lessons[$key]['complete'] = in_array( $key, $complete_arr['lesson'] ) ? true: false;

		}

		$l_complete = 0; $this->progress['lesson'] = false;

		foreach( $this->lessons as $key => $l ) {

			if( $l['complete'] ) {

				$l_complete++;

			} elseif( !$this->progress['lesson'] ) {

				$this->progress['lesson'] = array( 'num' => $l['ID'], 'title' => $l['title'] );

			}

		}

		$this->progress['course'] = round( $l_complete / count($this->lessons) * 100 ) . '%';

	}

	

	public function set_course( $view ) {

		switch( $view ) {

			case 'quiz':

				$sql = "SELECT course FROM " . QUIZ_QUIZ_TABLE . " WHERE ID = " . $_GET['id'];

				$result = @mysql_query( $sql );

				if( $result ) {

					$row = @mysql_fetch_assoc( $result );

					$_SESSION['course'] = $row['course'];

				} else {

					return false;

				}

				break;

			default:

				$_SESSION['course'] = $_GET['id'];

		}

		return $_SESSION['course'];

	}

	

	public function get_course() {

		$sql = "SELECT c.ID, c.title, c.tagline, c.description, c.image, c.folder, c.featured, f.menuID, c.OID AS contributor_id, results_image FROM " . COURSE_COURSE_TABLE . " c INNER JOIN fls_folders f ON c.folder=f.ID WHERE c.ID = " . $_SESSION['course'];

		$result = @mysql_query( $sql );

		if( $result ) {

			$this->course = @mysql_fetch_assoc( $result );

			//$this->course['certificate'] = true;

			//count pdf, powerpoint and word file
			//pdf first
			$pdf_sql = "SELECT COUNT(*) AS total_records  FROM crs_asset WHERE course =".$_SESSION['course']." AND filetype='pdf' ";
			$result = @mysql_query( $pdf_sql);
			$pdf_result = @mysql_fetch_assoc( $result );

			//word
			$word_sql = "SELECT COUNT(*) AS total_records  FROM crs_asset WHERE course =".$_SESSION['course']." AND (filetype='doc' OR filetype='docx')  ";
			$result = @mysql_query( $word_sql);
			$word_result = @mysql_fetch_assoc( $result );

			//powerpoint
			$powerpoint_sql = "SELECT COUNT(*) AS total_records  FROM crs_asset WHERE course =".$_SESSION['course']." AND (filetype='ppt' OR filetype='pptx')  ";
			$result = @mysql_query( $powerpoint_sql);
			$powerpoint_result = @mysql_fetch_assoc( $result );

			//videos now
			$video_sql = "SELECT COUNT(*) AS total_records FROM crs_chapter ch INNER JOIN crs_lesson cl ON ch.lesson=cl.ID  WHERE cl.course= ".$_SESSION['course']." AND ch.video !='' ";

			$result = @mysql_query( $video_sql);
			$video_result = @mysql_fetch_assoc( $result );

			//get all the quizes related to this course
			$quiz_sql = "SELECT COUNT(*) AS total_records FROM quiz_quiz WHERE   publish='Y' AND course= ".$_SESSION['course'];
			$result = @mysql_query($quiz_sql);
            $quiz_result = @mysql_fetch_assoc($result);

			

			$this->course['assets'] = array(

				'Lessons'		=> array( 'num' => count($this->lessons), 'icon' => 'lesson' ),

				'Quizzes'		=> array( 'num' => $quiz_result['total_records'], 'icon' => 'quiz' ),

				'Videos'		=> array( 'num' => $video_result['total_records'], 'icon' => 'video' ),

				'PowerPoints'	=> array( 'num' => $powerpoint_result['total_records'], 'icon' => 'powerpoint' ),

				'PDFs'			=> array( 'num' => $pdf_result['total_records'], 'icon' => 'pdf' ),

				'Word Docs'		=> array( 'num' => $word_result['total_records'], 'icon' => 'word' )

			);

			$_SESSION['debug']['COURSE'] = var_export($this->course,true);

			return true;

		}

		return false;

	}

	

	public function get_quizzes() {

		$sql = "SELECT ID, purpose, lesson, title FROM " . QUIZ_QUIZ_TABLE . " WHERE publish = 'Y' AND course = " . $_SESSION['course'] . " ORDER BY lesson ASC";

		$result = @mysql_query( $sql );

		if( $result ) {

			while( $row = @mysql_fetch_assoc( $result ) ) {

				$this->quizzes[$row['purpose']][$row['lesson']] = $row;

			}

		}

		$_SESSION['debug']['QUIZZES'] = var_export($this->quizzes,true);

	}

	

	public function get_quiz( $id ) {

		$sql = "SELECT * FROM " . QUIZ_QUIZ_TABLE . " WHERE ID = " . $id;

		$_SESSION['debug']['GET_QUIZ'] = $sql;

		$result = @mysql_query( $sql );

		if( $result ) $this->quiz = @mysql_fetch_assoc( $result );

	}

	public function get_quiz_questions( $quiz_id ) {

		$sql = "SELECT * FROM quiz_question WHERE quizID = " . $quiz_id." ORDER BY display_order ASC, ID";

		$_SESSION['debug']['GET_QUIZ'] = $sql;

		$result = @mysql_query( $sql );
		$arr = array();
		while($row = mysql_fetch_assoc( $result )){
			$arr[] = $row;
		}
		return $arr;

	}

	public function get_question_only($id) {
		$sql = "SELECT * FROM quiz_question WHERE ID='$id'";
		$_SESSION['debug']['GET_QUIZ'] = $sql;

		$result = @mysql_query( $sql );

		if( $result ) $this->question = @mysql_fetch_assoc( $result );

	}
	

	public function get_question() {

		if( $_SESSION['page'] == 'editor' ) {

			$questions = $this->build_quiz();

		} elseif( $_SESSION['quiz_in_progress'] ) {

			$questions = $this->retrieve_quiz();

			if( !$questions ) $questions = $this->build_quiz();

			$_SESSION['quiz_in_progress'] = true;

		} else {

			$questions = $this->build_quiz();

			$_SESSION['quiz_in_progress'] = true;

		}

		$_SESSION['debug']['QUESTIONS'] = var_export($questions,true);

		return $questions;

	}

	

	private function retrieve_quiz() {

		$questions = array();

		$sql = "SELECT ID, questions, answers, current FROM " . QUIZ_PROGRESS_TABLE . " WHERE user = " . $_SESSION['usr_id'] . " AND quizID = " . $this->quiz['ID'] . " ORDER BY timestamp DESC LIMIT 1";

		//$_SESSION['debug']['RETRIEVE'] = $sql;

		$result = @mysql_query( $sql );

		if( $result ) {

			$row = @mysql_fetch_assoc( $result );

			$q_arr = explode( ',', $row['questions'] );

			$this->quiz['current'] = $row['current'];

			$sql = "SELECT * FROM " . QUIZ_QUESTION_TABLE . " WHERE ID IN (" . $row['questions'] . ")";

			//$_SESSION['debug']['RETRIEVE'] .= '<br>'.$sql;

			if( $result = @mysql_query( $sql ) ) {

				while( $row = @mysql_fetch_assoc( $result ) ) {

					$q_id = $row['ID'];

					$q_key = array_search( $q_id, $q_arr );

					$questions[$q_key] = $row;

				}

				ksort( $questions );

			} else {

				return false;

			}

			foreach( $questions as $key => $q ) {

				$sql = "SELECT * FROM " . QUIZ_ANSWERS_TABLE . " WHERE questionID = " . $q['ID'] . " ORDER BY ID ASC";

				//$_SESSION['debug']['RETRIEVE'] .= '<br>'.$sql;

				$result = @mysql_query( $sql );

				if( $result ) {

					while( $row = @mysql_fetch_assoc( $result ) ) {

						$questions[$key]['answers'][] = $row;

					}

				} else {

					return false;

				}

			}

			return $questions;

		} else {

			return false;

		}

	}

	

	private function build_quiz() {

		$this->quiz['current'] = 0;

		$sql = "SELECT * FROM " . QUIZ_QUESTION_TABLE . " WHERE quizID = " . $this->quiz['ID'] . " ORDER BY ID ASC";

		$result = @mysql_query( $sql );

		if( $result ) {

			$required = array(); $pool = array();

			while( $row = @mysql_fetch_assoc( $result ) ) {

				if( $row['required'] == 'Y' ) {

					$required[] = $row;

				} else {

					$pool[] = $row;

				}

			}

		} else {

			return 'Error retrieving quiz questions.';

		}

		$questions = $required;

		if( $_SESSION['page'] == 'editor' ) {

			foreach( $pool as $pool_item ) {

				$questions[] = $pool_item;

			}

			foreach( $questions as $key => $q ) {

				if( $q['ID'] == $_GET['question'] ) $this->quiz['current'] = $key;

				$sql = "SELECT * FROM " . QUIZ_ANSWERS_TABLE . " WHERE questionID = " . $q['ID'] . " ORDER BY ID ASC";

				$result = @mysql_query( $sql );

				if( $result ) {

					while( $row = @mysql_fetch_assoc( $result ) ) {

						$questions[$key]['answers'][] = $row;

					}

				} else {

					return 'Error retrieving answer options.[id=' . $q['ID'] . ']';

				}

			}

		} else {

			if( $this->quiz['max'] > 0 ) {

				if( count( $required ) < $this->quiz['max'] ) {

					$need = $this->quiz['max'] - count( $required );

					shuffle( $pool );

					$i = 1;

					foreach( $pool as $pool_item ) {

						$questions[] = $pool_item;

						if( $i == $need ) break;

						$i++;

					}

				}

			} else {

				foreach( $pool as $pool_item ) {

					$questions[] = $pool_item;

				}

			}

			if( $this->quiz['random'] == 'Y' ) shuffle( $questions );

			$q_arr = array();

			foreach( $questions as $key => $q ) {

				$sql = "SELECT * FROM " . QUIZ_ANSWERS_TABLE . " WHERE questionID = " . $q['ID'] . " ORDER BY ID ASC";

				$result = @mysql_query( $sql );

				if( $result ) {

					while( $row = @mysql_fetch_assoc( $result ) ) {

						$questions[$key]['answers'][] = $row;

					}

					$q_arr[] = $q['ID'];

				} else {

					return 'Error retrieving answer options.[id=' . $q['ID'] . ']';

				}

			}

			$sql = "INSERT INTO " . QUIZ_PROGRESS_TABLE . " (user, quizID, questions) VALUES (" . $_SESSION['usr_id'] . ", " . $this->quiz['ID'] . ", '" . implode( ',', $q_arr ) . "')";

			$result = @mysql_query( $sql );

			if( $result ) {

				$this->quiz['progress'] = mysql_insert_id( $result );

			} else {

				return 'Error recording quiz data to database.';

			}

		}

		return $questions;

	}

	

	private function get_lessons() {

		$sql = "SELECT ID, title, display_order FROM " . COURSE_LESSON_TABLE . " WHERE course = " . $_SESSION['course']." ORDER BY display_order, ID ASC";

		$result = @mysql_query( $sql );

		if( $result ) {

			while( $row = @mysql_fetch_assoc( $result ) ) {

				$this->lessons[$row['ID']] = $row;

			}

			return true;

		}

		return false;

	}

	

	private function get_chapters($lesson) {
		$sql = "SELECT cr.ID, cr.title, cr.type, cr.video, cr.image, cr.asset,a.pdf_name, cr.content, cr.asset_name FROM " . COURSE_CHAPTER_TABLE . " cr LEFT JOIN crs_asset a ON a.ID=cr.asset  WHERE cr.lesson = " . $lesson." ORDER BY cr.display_order, cr.ID ASC";
		//$sql = "SELECT ID, title, type, video,image FROM " . COURSE_CHAPTER_TABLE . " WHERE lesson = " . $lesson;

		$result = @mysql_query( $sql );

		$i = 1;

		if( $result ) {

			while( $row = @mysql_fetch_assoc( $result ) ) {

				$this->lessons[$lesson]['chapters'][$i] = $row;

				$this->lessons[$lesson]['chapters'][$i]['key'] = $i;

				$i++;

			}

			return true;

		}

		return false;

	}

	

	public function get_other() {

		//get the folder id of current topic and select other topics from that folder
		$course_id = $_SESSION['course'];

		$sql = "SELECT folder FROM crs_course WHERE ID='$course_id'";
		$result = mysql_query($sql);
		$row = mysql_fetch_row($result);
		$folder = $row[0];

		$sql = "SELECT cc.ID,cc.title, cc.description FROM crs_course cc WHERE cc.folder='$folder' AND cc.ID !='$course_id'";
		$result = mysql_query($sql);
		while($row = mysql_fetch_assoc($result)){
			$this->other[] = $row;
		}

		

	}

	

	private function get_user_complete() {

		$arr = array();

		$sql = "SELECT * FROM " . COURSE_COMPLETE_TABLE . " WHERE user IN (" . $_SESSION['usr_id'] . ", 0)";

		$result = @mysql_query( $sql );

		if( $result ) {

			while( $row = @mysql_fetch_assoc( $result ) ) {

				$arr[$row['type']][] = $row['id'];

			}

			return $arr;

		}

		return false;

	}

	public function get_lesson($lesson){
		$k=1;
		foreach($this->lessons as $key=> $l){
			if($k==$lesson){
				return $l;
			}
			$k++;
		}
	}

	public function get_lesson_by_id($lesson){
		$sql = "SELECT * FROM crs_lesson WHERE ID='$lesson'";
		$result = mysql_query($sql);
		if($result){
			return mysql_fetch_assoc($result);
		}
		return false;
	}

	public function get_chapter($chapter_id){
		$sql = "SELECT c.*,l.course,cr.folder,f.menuID FROM crs_chapter c 
				INNER JOIN crs_lesson l ON c.lesson=l.ID 
				INNER JOIN crs_course cr ON cr.ID=l.course
				INNER JOIN fls_folders f ON f.ID=cr.folder
				WHERE c.ID='$chapter_id'";
		$result = mysql_query($sql);
		if($result){
			return mysql_fetch_assoc($result);
		}
		return false;

	}

	public function is_favorite_video($video_id){
		$user_id = $_SESSION['usr_id'];
		$sql = "SELECT * FROM usr_favorites WHERE UserID='$user_id' AND VideoID='$video_id' ";
		$result = mysql_query($sql);
		if(mysql_num_rows($result)>0){
			return true;
		}
		else{
			return false;
		}

	}

	public function is_watchlist_video($video_id){
		$user_id = $_SESSION['usr_id'];
		$sql = "SELECT * FROM usr_watchlist WHERE UserID='$user_id' AND VideoID='$video_id' ";
		$result = mysql_query($sql);
		if(mysql_num_rows($result)>0){
			return true;
		}
		else{
			return false;
		}
	}

	public function add_video_history($video_id){
		//first delete the old record and add new with new date
		$user_id = $_SESSION['usr_id'];
		//delete
		$sql = "DELETE FROM usr_history WHERE UserID='$user_id' AND VideoID='$video_id'";;
		$result = mysql_query($sql);

		//insert
		$sql = "INSERT INTO usr_history (UserID,VideoID) VALUES ('$user_id','$video_id')";;
		$result = mysql_query($sql);
	}
	
	
	

	public function completeCourse($course){
		$user_id = $_SESSION['usr_id'];
		$sql = "SELECT * FROM crs_course_complete WHERE UserID='$user_id' AND course='$course'";
		$result = mysql_query($sql);
		if(mysql_num_rows($result)==0){
			mysql_query("INSERT INTO crs_course_complete (UserID,course) VALUES('$user_id','$course')");
		}
	}

	public function completeLesson($course,$lesson){
		$user_id = $_SESSION['usr_id'];
		$sql = "SELECT * FROM crs_lesson_complete WHERE UserID='$user_id' AND lesson='$lesson'";
		$result = mysql_query($sql);
		if(mysql_num_rows($result)==0){
			mysql_query("INSERT INTO crs_lesson_complete (UserID,course,lesson) VALUES('$user_id','$course','$lesson')");
		}
	}

	public function completeChapter($course,$lesson,$chapter){
		$user_id = $_SESSION['usr_id'];
		$sql = "SELECT * FROM crs_chapter_complete WHERE UserID='$user_id' AND chapter='$chapter'";
		$result = mysql_query($sql);
		if(mysql_num_rows($result)==0){
			mysql_query("INSERT INTO crs_chapter_complete (UserID,course,lesson,chapter) VALUES('$user_id','$course','$lesson','$chapter')");
		}
	}

	public function get_chapter_lesson_ids($lesson_no){
		$i=1;
		
		foreach($this->lessons as $lesson)		{
			if($i==$lesson_no){
				$selected_lesson = $lesson;
			}
			$i++;	
		}
		$total_chapters = count($selected_lesson['chapters']);
		$selected_chapter = $selected_lesson['chapters'][$total_chapters];
		return array('lesson_id'=>$selected_lesson['ID'],'chapter_id'=>$selected_chapter['ID']);
	}

	public function get_lesson_chapters($lesson){
		$sql = "SELECT * FROM crs_chapter WHERE lesson='$lesson' ORDER BY display_order ASC, ID";

		$result = @mysql_query( $sql );

		if( $result ) {

			while( $row = @mysql_fetch_assoc( $result ) ) {

				$arr[] = $row;

			}

			return $arr;

		}
	}

	public function get_current_user_courses(){
		$user_id = $_SESSION['usr_id'];
		$sql = "SELECT * FROM " . COURSE_COURSE_TABLE . " WHERE oID='$user_id' ";		
		$result = @mysql_query( $sql );
		if( $result ) {
			while( $row = @mysql_fetch_assoc( $result ) ) {
				$arr[] = $row;
			}
			return $arr;
		}
		return array();
	}

	public function get_current_user_quizzes(){
		$user_id = $_SESSION['usr_id'];
		$sql = "SELECT * FROM quiz_quiz WHERE oID='$user_id' ";		
		$result = @mysql_query( $sql );
		if( $result ) {
			while( $row = @mysql_fetch_assoc( $result ) ) {
				$arr[] = $row;
			}
			return $arr;
		}
		return array();
	}

	public function get_question_answers($question_id){
		$sql = "SELECT * FROM quiz_answers WHERE questionID = " . $question_id." ORDER BY ID ASC";

		$_SESSION['debug']['GET_ANSWERS'] = $sql;

		$result = @mysql_query( $sql );
		$arr = array();
		while($row = mysql_fetch_assoc( $result )){
			$arr[] = $row;
		}
		return $arr;		
	}

	public function course_has_quiz($course){
		$sql= "SELECT * FROM quiz_quiz WHERE purpose='course' AND course='$course' AND publish='Y'";
		$result = mysql_query($sql);
		if($result){
			$row = @mysql_fetch_assoc( $result );
			return $row['ID'];
		}
		return false;

	}

	public function lesson_has_quiz($lesson){
		$sql= "SELECT * FROM quiz_quiz WHERE purpose='lesson' AND lesson='$lesson'  AND publish='Y'";
		$result = mysql_query($sql);
		if($result){
			$row = @mysql_fetch_assoc( $result );
			return $row['ID'];
		}
		return false;

	}
	public function chapter_has_quiz($chapter){
		$sql= "SELECT * FROM quiz_quiz WHERE purpose='chapter' AND chapter='$chapter'  AND publish='Y'";
		$result = mysql_query($sql);
		if($result){
			$row = @mysql_fetch_assoc( $result );
			return $row['ID'];
		}
		return false;

	}

	public function get_quiz_result($result_id){
		$sql = "SELECT * FROM quiz_result WHERE ID='$result_id'";
		$result = mysql_query($sql);
		$row = @mysql_fetch_assoc( $result );
		return $row;

	}

	public function is_last_lesson_of_course($course_id,$lesson_id){
		$sql = "SELECT * FROM crs_lesson WHERE course='$course_id' ORDER BY display_order DESC, ID DESC LIMIT 1";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		if($row['ID']==$lesson_id){
			return true;
		}
		return false;
	}

	public function is_last_chapter_of_lesson($lesson_id,$chapter_id){
		$sql = "SELECT * FROM crs_chapter WHERE lesson='$lesson_id' ORDER BY display_order DESC, ID DESC LIMIT 1";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		if($row['ID']==$chapter_id){
			return true;
		}
		return false;
	}
	public function get_next_lesson_number($course_id,$lesson_id){
		$sql = "SELECT * FROM crs_lesson WHERE course='$course_id' ORDER BY display_order ASC, ID ASC";
		$result = mysql_query($sql);
		$lesson=1;
		while($row = mysql_fetch_assoc($result)){
			$lesson++;
			if($row['ID']==$lesson_id){
				break;
			}
		}
		return $lesson;
	}

	public function get_next_chapter_number($lesson_id,$chapter_id){
		$sql = "SELECT * FROM crs_chapter WHERE lesson='$lesson_id' ORDER BY display_order ASC, ID ASC";
		$result = mysql_query($sql);
		$chapter=1;
		while($row = mysql_fetch_assoc($result)){
			$chapter++;
			if($row['ID']==$chapter_id){
				break;
			}
		}
		return $chapter;
	}

	//this is will return the quiz id othwerwise return false
	public function get_chapter_quiz_id($chapter_id){
		$sql ="SELECT * FROM quiz_quiz WHERE chapter='$chapter_id' AND purpose='chapter'";
		$result = mysql_query($sql);
		if (mysql_num_rows($result)>0) {
			$row = mysql_fetch_assoc($result);
			return $row['ID'];
		} else {
			return false;
		}

	}

	public function get_current_chapter_progress($course_id,$lesson_no,$chapter_no){
		if(!isset($_GET['view'])){
			return "0%";
		}

		$this->get_lessons();
		if(empty($lesson_no)){
			$lesson_no=1;
		}
		if(empty($chapter_no)){
			$chapter_no=1;
		}
		$total_chapters_upto_current_lesson =0;
		$total_chapters = 0;
		$i=1;
		foreach($this->lessons as $lesson){
			$this->get_chapters($lesson['ID']);
			$chapters = $this->lessons[$lesson['ID']]['chapters'];
			$total_chapters = $total_chapters + count($chapters);
			if($i<=$lesson_no){

				if($i==$lesson_no){
					$total_chapters_upto_current_lesson = $total_chapters_upto_current_lesson + $chapter_no;
				}
				else{
					$total_chapters_upto_current_lesson  = $total_chapters_upto_current_lesson + count($chapters);
				}
				
			}
			$i++;
		}
		$total_percent = intval($total_chapters_upto_current_lesson/$total_chapters*100);
		return $total_percent."%";
	}	

	//this will take current lesson and chapter as arguments and will return an array of previous lesson and chapter
	public function get_previous_chapter_no($current_lesson,$current_chapter){
		if($current_lesson==1 && $current_chapter==1){
			return false;
		}
		
		if( $current_lesson>1 && $current_chapter==1){
			//get the last chapter of previous lesson
			$count=0;
			foreach($this->lessons as $lesson){
				$count ++;
				if($count==($current_lesson-1)){
					break;
				}
			}
			$lesson_id = $lesson['ID'];
			//now get chapters
			$chapters = $this->get_chapters($lesson_id);
			$total_chapters = count($chapters);
			$previous_lesson = $current_lesson -1;
			return array('lesson'=>$previous_lesson,'chapter'=>($total_chapters),'type'=>'Chapter');
		}
		if($current_lesson>1 && $current_chapter>1){
			return array('lesson'=>$current_lesson,'chapter'=>($current_chapter-1),'type'=>'Part');
		}
		return false;
	}
}

?>