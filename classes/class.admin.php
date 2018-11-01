<?

class Admin {

	private $LV_ADMIN = 8;

	private $LV_LEADER = 4;

	private $LV_CONTRIBUTOR = 2;

	private $LV_USER =1;

	

	public $message = false;

	public $admins = array();

	public $leaders = array();

	public $contributors = array();

	public $members = array();

	public $team = array();

	public $sections = array();

	

	public function __construct() {
		

	}

	

	public function build_arrays() {

		if( $_SESSION['lv_leader'] ) $this->get_team();

		$this->get_account_levels();

	}

	

	public function get_account_levels() {
		
		$sql = "SELECT u.ID, u.Level, CONCAT( p.Last_Name, ', ', p.First_Name ) as Name FROM " . USER_ID_TABLE . " u JOIN " . USER_PROFILE_TABLE . " p ON u.ID = p.ID WHERE u.ID != " . $_SESSION['usr_id'];

		$result = @mysql_query($sql);
		

		while( $row = @mysql_fetch_assoc( $result ) ) {
		/* 	echo "<pre>";
			print_r($row); */
			/* if( $row['Level'] & $this->LV_ADMIN ) { */
			if( $row['Level'] == $this->LV_ADMIN ) {

				$this->admins['Y'][$row['ID']] = $row['Name'];

			} else {

				$this->admins['N'][$row['ID']] = $row['Name'];

			}

			/* if( $row['Level'] & $this->LV_LEADER ) { */
			if( $row['Level'] == $this->LV_LEADER ) {

				$this->leaders['Y'][$row['ID']] = $row['Name'];

			} else {

				$this->leaders['N'][$row['ID']] = $row['Name'];

			}

			/* if( $row['Level'] & $this->LV_CONTRIBUTOR ) { */
			if( $row['Level'] == $this->LV_CONTRIBUTOR ) {

				$this->contributors['Y'][$row['ID']] = $row['Name'];

			} else {

				$this->contributors['N'][$row['ID']] = $row['Name'];

			}

		}
		
		/* echo "<pre>";
		print_r($this->contributors);
		print_r($this->leaders);
		print_r($this->admins);
		exit; */

		//$_SESSION['debug']['GET_ADMIN'] = var_export($this->admins,true);

		//$_SESSION['debug']['GET_LEADER'] = var_export($this->leaders,true);

		//$_SESSION['debug']['GET_CONTRIB'] = var_export($this->contributors,true);

	}

	

	public function get_team() {

		$sql = "SELECT * FROM " . USER_TEAM_TABLE . " WHERE leader = " . $_SESSION['usr_id'];

		$_SESSION['debug']['TEAM'] = $sql;

		$result = @mysql_query( $sql );

		$this->team = @mysql_fetch_assoc( $result );

		$sql = "SELECT c.ID, CONCAT( p.Last_Name, ', ', p.First_Name ) as Name FROM " . USER_CONTRIBUTOR_TABLE . " c JOIN (" . USER_TEAM_TABLE . " t, " . USER_PROFILE_TABLE . " p) ON c.team = t.ID AND c.user = p.ID WHERE c.user != " . $_SESSION['usr_id'] . " AND t.leader = " . $_SESSION['usr_id'];

		///$_SESSION['debug']['TEAM_Y'] = $sql;

		$result = @mysql_query( $sql );

		while( $row = @mysql_fetch_assoc( $result ) ) {

			$this->members['Y'][$row['ID']] = $row['Name'];

		}

		$sql = "SELECT c.ID, CONCAT( p.Last_Name, ', ', p.First_Name ) as Name FROM " . USER_CONTRIBUTOR_TABLE . " c JOIN " . USER_PROFILE_TABLE . " p ON c.user = p.ID WHERE c.team IS NULL";

		//$_SESSION['debug']['TEAM_N'] = $sql;

		$result = @mysql_query( $sql );

		while( $row = @mysql_fetch_assoc( $result ) ) {

			$this->members['N'][$row['ID']] = $row['Name'];

		}

		$team_sections = str_replace( ':', '', explode( '::', $this->team['sections'] ) );

		$sql = "SELECT ID, name FROM " . PANEL_MENU_TABLE;

		$result = @mysql_query( $sql );

		while( $row = @mysql_fetch_assoc( $result ) ) {

			if( in_array( $row['ID'], $team_sections ) ) {

				$this->sections['Y'][$row['ID']] = $row['name'];

			} else {

				$this->sections['N'][$row['ID']] = $row['name'];

			}

		}

	}

	

	public function grant_level() {
		$found = 0;

		if( $_POST['admin_y'] ) {

			$found++;

			$sql = "UPDATE " . USER_ID_TABLE . " SET Level = " . $this->LV_ADMIN . " WHERE ID = " . $_POST['admin_y'];

			if( !@mysql_query( $sql ) ) {

				$this->message = 'ERROR: Database error updating admin permissions.';

				return false;

			}

		}

		if( $_POST['leader_y'] ) {

			$found++;

			$sql = "UPDATE " . USER_ID_TABLE . " SET Level = " . $this->LV_LEADER . " WHERE ID = " . $_POST['leader_y'];

			if( !@mysql_query( $sql ) ) {

				$this->message = 'ERROR: Database error updating leader permissions.';

				return false;

			}

			$sql = "INSERT INTO " . USER_TEAM_TABLE . " (leader) VALUES (" . $_POST['leader_y'] . ")";

			if( !@mysql_query( $sql ) ) {

				$this->message = 'ERROR: Failed to add team for new leader.';

				return false;

			}

		}

		if( $_POST['contributor_y'] ) {

			$found++;

			$sql = "UPDATE " . USER_ID_TABLE . " SET Level = " . $this->LV_CONTRIBUTOR . " WHERE ID = " . $_POST['contributor_y'];

			if( !@mysql_query( $sql ) ) {

				$this->message = 'ERROR: Database error updating contributor permissions.';

				return false;

			}

			$sql = "INSERT INTO " . USER_CONTRIBUTOR_TABLE . " (user) VALUES (" . $_POST['contributor_y'] . ")";

			if( !@mysql_query( $sql ) ) {

				$this->message = 'ERROR: Failed to add entry to contributor database.';

				return false;

			}

		}

		if( $found == 0 ) {

			$this->message = 'ERROR: Required information missing. Please select a name to grant privileges to.';

			return false;

		} else {

			$this->message = 'Permission(s) updated successfully.'; 

			return true;

		}

	}

	

	public function deny_level() {

		$found = 0;

		if( $_POST['admin_n'] ) {

			$found++;

			$sql = "UPDATE " . USER_ID_TABLE . " SET Level = " . $this->LV_USER . " WHERE ID = " . $_POST['admin_n'];

			if( !@mysql_query( $sql ) ) {

				$this->message = 'ERROR: Database error updating admin permissions.';

				return false;

			}

		}

		if( $_POST['leader_n'] ) {

			$found++;

			$sql = "UPDATE " . USER_ID_TABLE . " SET Level = " . $this->LV_USER . " WHERE ID = " . $_POST['leader_n'];

			if( !@mysql_query( $sql ) ) {

				$this->message = 'ERROR: Database error updating leader permissions.';

				return false;

			}

			$sql = "DELETE FROM " . USER_TEAM_TABLE . " WHERE leader = " . $_POST['leader_n'];

			if( !@mysql_query( $sql ) ) {

				$this->message = 'ERROR: Failed to remove the associated team.';

				return false;

			}

		}

		if( $_POST['contributor_n'] ) {

			$found++;

			$sql = "UPDATE " . USER_ID_TABLE . " SET Level = " . $this->LV_USER . " WHERE ID = " . $_POST['contributor_n'];

			if( !@mysql_query( $sql ) ) {

				$this->message = 'ERROR: Database error updating contributor permissions.';

				return false;

			}

			$sql = "DELETE FROM " . USER_CONTRIBUTOR_TABLE . " WHERE user =" . $_POST['contributor_n'];

			if( !@mysql_query( $sql ) ) {

				$this->message = 'ERROR: Failed to remove entry from contributor database.';

				return false;

			}

		}

		if( $found == 0 ) {

			$this->message = 'ERROR: Required information missing. Please select a name to deny privileges for.';

			return false;

		} else {

			$this->message = 'Permission(s) updated successfully.'; 

			return true;

		}

	}

	

	public function update_team() {

		if( $_POST['teamname'] == '' || !$_POST['teamid'] ) {

			$this->message = 'ERROR: Required information missing.  Please confirm the team name is not blank.';

			return false;

		} else {

			$sql = "UPDATE " . USER_TEAM_TABLE . " SET name = '" . $_POST['teamname'] . "' WHERE ID = " . $_POST['teamid'];

			$result = @mysql_query( $sql );

			if( !$result ) {

				$this->message = 'ERROR: Failed to update database.  Please try again or contact your system administrator.';

				return false;

			}

		}

		$this->team['name'] = $_POST['teamname'];

		$this->message = 'Team name updated successfully!';

		return true;

	}

	

	public function team_add_contributor() {

		if( $_POST['member_y'] == '' ) {

			$this->message = 'ERROR: Required information missing.  Please select a contributor to add.';

			return false;

		} else {

			$sql = "UPDATE " . USER_CONTRIBUTOR_TABLE . " SET team = " . $_POST['teamid'] . " WHERE ID = " . $_POST['member_y'];

			$result = @mysql_query( $sql );

			if( !$result ) {

				$this->message = 'ERROR: Failed to update database.  Please try again or contact your system administrator.';

				return false;

			}

		}

		$this->message = 'Contributor added to team successfully!';

		return true;

	}

	

	public function team_rem_contributor() {

		if( $_POST['member_n'] == '' ) {

			$this->message = 'ERROR: Required information missing.  Please select a contributor to remove.';

			return false;

		} else {

			$sql = "UPDATE " . USER_CONTRIBUTOR_TABLE . " SET team = NULL WHERE ID = " . $_POST['member_n'];

			$result = @mysql_query( $sql );

			if( !$result ) {

				$this->message = 'ERROR: Failed to update database.  Please try again or contact your system administrator.';

				return false;

			}

		}

		$this->message = 'Contributor removed from team successfully!';

		return true;

	}

	

	public function team_add_section() {
		if( $_POST['section_y'] == '' ) {

			$this->message = 'ERROR: Required information missing.  Please select a section to add.';

			return false;

		} else {

			$sql = "UPDATE " . USER_TEAM_TABLE . " SET sections = (CONCAT(sections,':" . $_POST['section_y'] . ":')) WHERE ID = " . $_POST['teamid'];

			$_SESSION['debug']['ADD_SECTION'] = $sql;

			$result = @mysql_query( $sql );

			if( !$result ) {
				
				$this->message = 'ERROR: Failed to update database.  Please try again or contact your system administrator.';

				return false;

			}

		}

		$this->message = 'Section added successfully!';

		return true;

	}

	

	public function team_rem_section() {

		if( $_POST['section_n'] == '' ) {

			$this->message = 'ERROR: Required information missing.  Please select a section to remove.';

			return false;

		} else {

			$sql = "UPDATE " . USER_TEAM_TABLE . " SET sections = (REPLACE(sections,':" . $_POST['section_n'] . ":','')) WHERE ID = " . $_POST['teamid'];

			$_SESSION['debug']['REM_SECTION'] = $sql;

			$result = @mysql_query( $sql );

			if( !$result ) {

				$this->message = 'ERROR: Failed to update database.  Please try again or contact your system administrator.';

				return false;

			}

		}

		$this->message = 'Section removed successfully!';

		return true;

	}

	public function get_all_sections(){
		$sql = "SELECT * FROM panel_menu ";
		$result = mysql_query($sql);
		$sections = array();
		while($row = mysql_fetch_assoc($result)){
			$sections[] = $row;
		}
		return $sections;
	}

	public function add_section(){
		$section_name = $_POST['section_name'];
		//make sure the name is not duplicated
		$sql = "SELECT * FROM panel_menu WHERE name='$section_name'";
		$result = mysql_query($sql);
		if(mysql_num_rows($result)>0){
			return false;
		}

		$sql = "INSERT INTO panel_menu(titleID,name) VALUES(12,'$section_name')";
		mysql_query($sql);
		return true;
	}
	
	public function delete_section(){
		$section_id = $_POST['section_id'];
		$sql = "DELETE FROM  panel_menu WHERE ID='$section_id'";
		$result = mysql_query($sql);
		return true;
	}

	public function get_users(){
		$sql = "SELECT * FROM usr_ids ui INNER JOIN usr_profile up ON ui.ID=up.ID ";
		$result = mysql_query($sql);
		$users = array();
		while($row = mysql_fetch_assoc($result)){
			$users[] = $row;
		}
		return $users;
	}

	public function approve_user(){
		$user_id = $_POST['user_id'];
		$sql="UPDATE usr_ids SET Status='1' WHERE ID='$user_id'";
		mysql_query($sql);
		$_SESSION['success'] = "The user has been approved.";
	}
	public function block_user(){
		$user_id = $_POST['user_id'];
		$sql="UPDATE usr_ids SET Status='2' WHERE ID='$user_id'";
		mysql_query($sql);
		$_SESSION['success'] = "The user has been blocked.";
	}
	public function unblock_user(){
		$user_id = $_POST['user_id'];
		$sql="UPDATE usr_ids SET Status='1' WHERE ID='$user_id'";
		mysql_query($sql);
		$_SESSION['success'] = "The user has been unblocked.";
	}

}

?>