<?

class Contributors {

	public $profiles = array();

	public $teams = array();
	

	public function __construct() {

		$this->get_teams();

	}

	public function get_teams(){
		$sql = "SELECT * FROM " . USER_TEAM_TABLE;

		$_SESSION['debug']['TEAM'] = $sql;

		$result = @mysql_query( $sql );
		

		$i=0;
		while($team = mysql_fetch_assoc( $result )){
			$this->teams[$i] = $team;
			$team_id = $team['ID'];
			$leader_id = $team['leader'];
			//get leader for this team
			$sql = "SELECT * FROM usr_profile WHERE ID='$leader_id'";
			$res = @mysql_query( $sql );
			$leader = mysql_fetch_assoc( $res );
			$this->teams[$i]['leader'] = $leader;
			//get contributors for this team
			$sql = "SELECT * FROM usr_contributor uc INNER JOIN usr_profile up ON uc.user=up.ID  WHERE uc.team='$team_id'";
			$res = @mysql_query( $sql );
			while($contributor = mysql_fetch_assoc( $res )){
				$this->teams[$i]['contributors'][] = $contributor;
			}
			$i++;
		}
		/* echo "<pre>";
		print_r($this->teams);
		echo "</pre>"; */
	}

	public function get_contributor_details($id){
		$sql = "SELECT up.* FROM usr_ids ui INNER JOIN usr_profile up ON ui.ID=up.ID WHERE ui.ID='$id'";
		$result = mysql_query($sql);
		if(mysql_num_rows($result)==0){
			return false;
		}
		$row =  mysql_fetch_assoc($result);
		return $row;
	}

	public function get_contributor_courses($id){
		$sql = "SELECT * FROM crs_course WHERE oID='$id' ";
		$result = mysql_query($sql);
		$courses = array();
		while($row =  mysql_fetch_assoc($result)){
			$courses[] = $row;
		}
		return $courses;
	}

	public function get_contributor_videos($id){
		$sql = "SELECT sv.* FROM crs_chapter ch INNER JOIN crs_lesson cl ON ch.lesson=cl.ID INNER JOIN crs_course cc ON cl.course=cc.ID INNER JOIN search_video sv ON ch.video=sv.vID WHERE cc.oID='$id' AND ch.video !='' ";
		$result = mysql_query($sql);
		$videos = array();
		while($row =  mysql_fetch_assoc($result)){
			$videos[] = $row;
		}
		return $videos;
	}

	public function get_contributor_powerpoints($id){
		$sql = "SELECT * FROM crs_chapter ch INNER JOIN crs_lesson cl ON ch.lesson=cl.ID INNER JOIN crs_course cc ON cl.course=cc.ID WHERE cc.oID='$id' ";
		$result = mysql_query($sql);
		$videos = array();
		while($row =  mysql_fetch_assoc($result)){
			$videos[] = $row;
		}
		return $videos;
	}
}

?>