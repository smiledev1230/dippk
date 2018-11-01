<?php
/**
 * Login Class
 *
 * Used by login.php for functions
 */
class Login {
    public $user;
    public $sec_levels;
    public $change_password_form;
    private $user_id;
    private $password;
    private $new_password;
    private $confirm_password;
    private $change_pwd;
    private $remember;
    public $message;

    public function __construct() {
        $this->user_id = $_POST['user'];
        $this->change_password = false;
        $this->password = trim(md5($_POST['pwd']));
        $this->new_password = $_POST['new_pwd'];
        $this->confirm_password = $_POST['confirm_pwd'];
        $this->change_pwd = $_POST['change_pwd'];
        $this->remember = $_POST['remember'];
        $this->sec_levels = array( 1 => 'lv_user', 2 => 'lv_contributor', 4 => 'lv_leader', 8 => 'lv_admin' );
    }

    public function validate_password() {
        $result = mysql_query("SELECT * FROM " . USER_ID_TABLE . " WHERE Username = '" . mysql_real_escape_string($_POST['user']) . "'");
        $user = mysql_fetch_assoc($result);
        if ( ( $this->password == $user['Pwd'] ) && $user['Reset'] == 1 ) {
            $this->message = 'Password is the default password.  Please change your password.';
            $this->change_password = true;
			return false;
        }
		return true;
    }

    public function login_user() {
        $result = mysql_query("SELECT i.*, p.First_Name, p.Last_Name FROM " . USER_ID_TABLE . " i JOIN " . USER_PROFILE_TABLE . " p ON i.ID = p.ID WHERE i.Username = '" . mysql_real_escape_string($this->user_id) . "'");
        $this->user = mysql_fetch_assoc($result);
		$_SESSION['debug']['USER DATA'] = var_export( $this->user, TRUE );

        if ( (trim($this->user['Pwd']) == $this->password) && ($this->user['Level'] > 0 )) {

            if($this->user['Status']==0){
                $this->message = 'Your account is not approved. Please contact the administrater for the assistance.';
                return false;
            }
            if($this->user['Status']==2){
                $this->message = 'Your account is blocked. Please contact the administrater for the assistance.';
                return false;
            }

            $_SESSION['usr_id'] = $this->user['ID'];
			$_SESSION['usr_username'] = $this->user['Username'];
            $_SESSION['usr_name'] = $this->user['First_Name'] . ' ' . $this->user['Last_Name'];
            $_SESSION['usr_level'] = $this->user['Level'];
            //if ( $this->remember ) {
                setcookie("ppkel_username", $this->user['Username'], time()+365*24*60*60);
            //}
            /**
             * set access level
             */
            foreach ( $this->sec_levels as $bit => $level ) {
                $_SESSION[$level] = false;
                if ( $bit & $_SESSION['usr_level'] ) {
                    $_SESSION[$level] = true;
                }
            }
            $_SESSION['logged_in_message'] = 'You are signed in as an End User.';
            if($this->user['Level']==2){
                $_SESSION['logged_in_message'] = 'You are signed in as a Content Contributor.';
            }
            else if($this->user['Level']==4){
                $_SESSION['logged_in_message'] = 'You are signed in as a Team Leader.';
            }
            else if($this->user['Level']==8){
                $_SESSION['logged_in_message'] = 'You are signed in as an Administrator.';
            }
            setcookie("ppkel_status", time()+12*60*60, time()+24*60*60);
            /**
             * log login into .txt file
             */
             return $this->_log_user_login();
        } else {
            $this->message = 'Invalid username/password.';
            return false;
        }
    }

    public function get_message() {
        return $this->message;
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


