<?
class Account extends Login {
	public $profile;
	public $message;
	public $menu = array();
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_menu_data() {
		$this->menu['names'] = array(
							'account'	=> 'Account',
							//'dashboard'	=> 'Dashboard',
							//'billing'	=> 'Billing',
							'messages'	=> 'Messages',
							'uploads'	=> 'My Content',
							/* 'comments'	=> 'Comments', */
							'history'	=> 'History',
							'quiz_history'	=> 'Quiz History',
							'watchlist'	=> 'Watchlist',
							'favorites'	=> 'Favorites',
							
							'help'		=> 'Help'
						);
		$this->menu['targets'] = array(
							'account'	=> 'myaccount',
							//'billing'	=> 'billing',
							/* 'comments'	=> 'comments', */
							//'dashboard'	=> 'mycontent',
							'favorites'	=> 'favorites',
							'help'		=> 'help',
							'history'	=> 'history',
							'quiz_history'	=> 'quiz_history',
							'messages'	=> 'messages',
							'uploads'	=> 'uploads',
							'watchlist'	=> 'watchlist',
						);
		if( $_SESSION['lv_user'] ){
			unset( $this->menu['names']['uploads'] );
		} 
		if( $_SESSION['lv_leader'] || $_SESSION['lv_admin'] ) {
			$this->menu['names']['admin'] = 'Admin';
			$this->menu['targets']['admin'] = 'admin';
		}

		if( $_SESSION['lv_admin'] ) {
			$this->menu['names']['users'] = 'Users';
			$this->menu['targets']['users'] = 'users';
		}
	}
	
	private function get_profile_fields() {
		$arr = array(
					'fname'		=> 'First_Name',
					'lname'		=> 'Last_Name',
					'email'		=> 'Email',
					'phone'		=> 'Phone',
					'company'	=> 'Company',
					'address'	=> 'Address',
					'address_2'	=> 'Address_2',
					'city'		=> 'City',
					'state'		=> 'State',
					'zip'		=> 'Postal_Code',
					'country'	=> 'Country'
					);
		return $arr;
	}
	
	public function get_profile_data() {
		$result = mysql_query( "SELECT i.Username, p.* FROM " . USER_PROFILE_TABLE . " p JOIN " . USER_ID_TABLE . " i ON p.ID = i.ID WHERE p.ID = " . $_SESSION['usr_id'] );
		$this->profile = mysql_fetch_assoc( $result );
	}
	
	public function update_profile() {
		$fields = $this->get_profile_fields();
		$set_arr = array();
		foreach( $fields as $fkey => $fval ) {
			$set_arr[] = $fval . "='" . mysql_real_escape_string( $_POST[$fkey] ) . "'";
		}
		$sql = "UPDATE " . USER_PROFILE_TABLE . " SET " . implode( ',', $set_arr ) . " WHERE ID = " . $_SESSION['usr_id'];
		if( @mysql_query( $sql ) ) {
			$this->message = 'Your account has been updated.';
			return true;
		} else {
			$this->message = 'There was an error updating the profile.';
			return false;
		}
	}
	
	public function change_password() {
		if ( $_POST['pwd_new'] != $_POST['pwd_cnew'] ) {
			$this->message = 'New Password fields do not match.';
			return false;
		} else {
			$pwd = trim(md5($_POST['pwd']));
			$pwd_new = trim(md5($_POST['pwd_new']));
			$result = @mysql_query("SELECT Pwd FROM " . USER_ID_TABLE . " WHERE ID = " . $_SESSION['usr_id'] );
			$_SESSION['debug']['PWD_SQL'] = "SELECT Pwd FROM " . USER_ID_TABLE . " WHERE ID = " . $_SESSION['usr_id'];
			$row = @mysql_fetch_assoc( $result );
			$_SESSION['debug']['PWD'] = 'raw['.$_POST['pwd'].']<br>pwd['.$pwd.']<br>row['.$row['Pwd'].']';
			if( $row['Pwd'] == $pwd ) {
				$sql = "UPDATE " . USER_ID_TABLE . " SET Pwd = '" . mysql_real_escape_string($pwd_new) . "', `Reset` = 0 WHERE ID = " . $_SESSION['usr_id'] . " LIMIT 1";
				if( @mysql_query($sql) ) {
					$this->message = 'Your password has been updated.';
					return true;
				} else {
					$this->message = 'There was an error updating the password.';
					return false;
				}
			} else {
				$this->message = 'The current password is incorrect.';
				return false;
			}
		}
	}
	
	public function reset_password() {
		$result = @mysql_query("SELECT ID FROM " . USER_PROFILE_TABLE . " WHERE Email = '" . $_POST['email'] . "'");
		$count = @mysql_num_rows( $result );
		if( $count == 1 ) {
			$row = mysql_fetch_assoc( $result );
			//Generate password
			$odd = 'AbcdEfghjkmnpqrstUvwxYz234567';
			$even = 'aBCDeFGHJKMNPQRSTuVWXyZ89@#$%';
			$password = '';
			$alt = time() % 2;
			for ($i = 0; $i < 10; $i++) {
				if ($alt == 1) {
					$password .= $odd[(rand() % strlen($odd))];
					$alt = 0;
				} else {
					$password .= $even[(rand() % strlen($even))];
					$alt = 1;
				}
			}
			$encrypt = mysql_real_escape_string(trim(md5($password)));
			//Send email
			$to      = $_POST['email'];
			$subject = 'Account notification from ' . SITE_NAME;
			$message = "As requested your password has been reset.\r\n\r\n";
			$message .= "Temporary Password: " . $password . "\r\n\r\n";
			$message .= "You will be required to change the password when you login.\r\n";
			if( @mail($to, $subject, $message) ) {
				if( @mysql_query("UPDATE " . USER_ID_TABLE . " SET Pwd = '" . $encrypt . "', `Reset` = 1 WHERE ID = " . $row['ID']) ) {
					$_SESSION['debug']['RECOVER'] = "UPDATE " . USER_ID_TABLE . " SET Pwd = '" . $encrypt . "', `Reset` = '1' WHERE ID = " . $row['ID'];
					$this->message = "A new password has been sent to the email address provided.<br>Please allow several minutes for it to arrive.";
					return true;
				} else {
					$this->message = "There was an error writing the temporary password to the database.";
					return false;
				}
			} else {
				$this->message = 'There was an error sending the email with the new password.';
				return false;
			}			
		} elseif( $count > 1 ) {
			$this->message = 'Database error.  Multiple matches found for email address.';
			return false;
		} else {
			$this->message = 'Email not found.  Please register below or try another email address.';
			return false;
		}
	}
	
	public function update_image() {
		define( 'MAX_FILE_SIZE', 1024000 );
		define( 'UPLOAD_DIR', 'img/user/' );
		$permitted = array( 'image/gif', 'image/jpeg', 'image/pjpeg', 'image/png' );
		if($_FILES['image']['size']==0){
			$this->message = "Please select an image to upload.";
			return false;
		}
		if(!in_array( $_FILES['image']['type'], $permitted )){
			$this->message = "Please select a valid image file. Only PNG, JPG and GIF types are allowed to upload.";
			return false;
		}
		if($_FILES['image']['size'] > MAX_FILE_SIZE){
			$this->message = "The file is too big. Upto 1MB image file is allowed to upload.";
			return false;
		}
		if( in_array( $_FILES['image']['type'], $permitted ) && $_FILES['image']['size'] > 0 && $_FILES['image']['size'] <= MAX_FILE_SIZE ) {
			$fname_arr = explode( '.', $_FILES['image']['name'] );
			$ext_key = count( $fname_arr ) - 1;
			$filename = $_SESSION['usr_id'] . '.' . $fname_arr[$ext_key];
			switch( $_FILES['image']['error'] ) {
				case 0:
					if( move_uploaded_file( $_FILES['image']['tmp_name'], UPLOAD_DIR . $filename ) ) {
						$sql = "UPDATE " . USER_PROFILE_TABLE . " SET Image_Ext = '" . $fname_arr[$ext_key] . "' WHERE ID = " . $_SESSION['usr_id'];
						if( @mysql_query($sql) ) {
							$this->message = "Your profile picture has been updated.";

							return true;
						} else {
							$this->message = "File uploaded but error writing to database.";
						}
					} else {
						$this->message = "Error0 uploading file. Please try again.";
					}
					break;
				case 3:
				case 6:
				case 7:
				case 8:
					$this->message = "Error uploading file. Please try again.";
				break;
				case 4:
					$this->message = "You didn't select a file to be uploaded.";
			}
		} else {
			$this->message = "The file is too big.";
		}
		return false;
	}
}
?>