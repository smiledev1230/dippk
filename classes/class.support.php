<?
class Support {
	public $message;
	private $content;
	private $subject;
	private $target;
	
	public function __construct() {
		$this->target = 'chris@one-echo.com';
	}
	
	public function send_form( $type ) {
		switch( $type ) {
			case 'contact':
				if( !$this->compile_contact_form() ) return false;
				break;
			case 'request':
				if( !$this->compile_request_form() ) return false;
				break;
		}
		if( $this->send_mail() ) {
			return true;
		} else {
			return false;
		}
	}
	
	private function send_mail() {
		if( @mail( $this->target, $this->subject, $this->content ) ) {
			$this->message = 'The email was sent successfully.';
			return true;
		} else {
			$this->message = 'There was an error sending the email.';
			return false;
		}
	}
	
	private function compile_contact_form() {
		$fields = array(
						'name'			=> 'Name',
						'phone'			=> 'Phone',
						'email'			=> 'Email',
						'message'		=> 'Message'
						);
		$this->subject = 'Contact Us from ' . SITE_NAME;
		foreach( $_POST as $key => $val ) {
			switch( $key ) {
				case 'process':
					break;
				case 'message':
					$this->content .= strtoupper($fields[$key]) . ":\r\n" . $val . "\r\n";
					break;
				default:
					$this->content .= strtoupper($fields[$key]) . ": " . $val . "\r\n";
			}
		}
		return true;
	}
	
	public function get_faq() {
		$arr = array();
		$result = @mysql_query("SELECT * FROM " . HELP_FAQ_TABLE . " ORDER BY ordinal, ID ASC");
		while( $row = @mysql_fetch_assoc( $result ) ) {
			$arr[] = $row;
		}
		return $arr;
	}
	
	public function get_tips() {
		$arr = array();
		$result = @mysql_query("SELECT * FROM " . HELP_TIPS_TABLE . " ORDER BY ordinal, ID ASC");
		while( $row = @mysql_fetch_assoc( $result ) ) {
			$arr[] = $row;
		}
		return $arr;
	}
	
	public function get_resources() {
		$arr = array();
		$result = @mysql_query("SELECT * FROM " . HELP_RESOURCES_TABLE . " ORDER BY name ASC");
		while( $row = @mysql_fetch_assoc( $result ) ) {
			$arr[] = $row;
		}
		return $arr;
	}
	
}
?>