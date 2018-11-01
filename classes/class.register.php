<?

class Register {

	private $password;

	private $confirm_password;

	private $message;

	private $redirect_url;

	private $user_id;

	

	public function __construct() {

        $this->password = trim(md5($_POST['pwd']));

        $this->confirm_password = trim(md5($_POST['confirm_pwd']));

    }

	

	public function validate_uname() {

		$result = mysql_query("SELECT * FROM " . USER_PROFILE_TABLE . " WHERE Username = '" . mysql_real_escape_string($_POST['username']) . "'");

        $user = @mysql_fetch_assoc($result);

		if( @mysql_num_rows( $user ) > 0 ) {

			$this->message = 'The selected username is already in use.  Please try a different username.';

			return false;

		} else {

			return true;

		}

	}

	

	private function get_reg_fields() {

		$arr = array(

					'First_Name'	=> 'fname',

					'Last_Name'		=> 'lname',

					'Email'			=> 'email',

					'plastipak_site'			=> 'plastipak_site'

					);

		return $arr;

	}

	

	public function register_new() {

		if( $this->validate_uname() ) {

			//check which type of user is registering
			$role = $_POST['role'];
			if($role=='contributor'){
				$level  = 2;
				$status = 0;
			}
			else if($role=='admin'){
				$level  = 8;
				$status = 0;
			}
			else{
				$level  = 1;
				$status = 1;
			}


			$sql = "INSERT INTO " . USER_ID_TABLE . " (Username, Pwd, Level, Status) VALUES ('" . mysql_real_escape_string($_POST['username']) . "','" . $this->password . "','$level','$status')";

			$result = mysql_query( $sql );

			if( !$result ) {

				$this->message = 'There was an error writing the new user to the database.';

				return false;

			}

			$result = mysql_query( "SELECT ID FROM " . USER_ID_TABLE . " WHERE Username = '" . mysql_real_escape_string($_POST['username']) . "'" );

			$row = mysql_fetch_assoc($result);

			$this->user_id = $row['ID'];

			$fields = $this->get_reg_fields();

			$f = implode( ',', array_keys( $fields ) );

			$v = array();

			foreach( $fields as $input ) {

				$v[] = $_POST[$input];

			}

			$sql = "INSERT INTO " . USER_PROFILE_TABLE . " ( ID," . $f . ") VALUES ('" . $this->user_id . "','" . implode( "','", $v ) . "')";

			$result = mysql_query( $sql );

			if( !$result ) {
				$this->message = 'There was an error adding profile information to the database for the new user.';
echo "sql: $sql";
				return false;

			}
			if($level==1){
				$this->message = "Successfully registered. You can login to access the content.";
				$this->redirect_url = "page=login";
				
			}
			else{
				$this->message = "Successfully registered. You will be approved by an Administrator then you will be able to login.";
				$this->redirect_url = "page=login&view=register-success";
			}
			
			return true;

		}

		return false;

	}

	

	public function auto_login() {

		$_SESSION['usr_id'] = $this->user_id;

		$_SESSION['usr_username'] = $_POST['username'];

		$_SESSION['usr_name'] = $_POST['fname'] . ' ' . $_POST['lname'];

		$_SESSION['usr_level'] = 1;

		//if ( $this->remember ) {

			setcookie("tig-vl_username", $_POST['username'], time()+365*24*60*60);

		//}

		/**

		 * set access level

		 */

		 $sec_levels = array( 1 => 'lv_user', 2 => 'lv_contributor', 4 => 'lv_leader', 8 => 'lv_admin' );

		foreach ( $sec_levels as $bit => $level ) {

			$_SESSION[$level] = false;

			if ( $bit & $_SESSION['usr_level'] ) {

				$_SESSION[$level] = true;

			}

		}

		setcookie("tig-vl_status", time()+12*60*60, time()+24*60*60);



		/**

		 * log login into .txt file

		 */

		 return $this->_log_user_login();

	}

	

	public function get_message() {

        return $this->message;

	}
	public function get_redirect_url() {

        return $this->redirect_url;

    }



    private function _log_user_login() {

        if ($handle = @fopen("logs/log.txt",'a+')) {

            $log_content = date("F j, Y, g:i a").", "."host ip: ".$_SERVER['REMOTE_ADDR'].", ". $_SESSION["usr_name"]."\r\n";

            if ( fwrite($handle, $log_content) === false ) {

                $this->message = 'Unable to write to system log.';

                return false;

            }

            return true;

        } else {

            $this->message = 'Unable to log user into system.';

            return false;

        }

    }

}

?>