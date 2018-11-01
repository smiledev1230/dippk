<?php
class Platform {
	private $server;
	private $path;
	public $environment;
	
	public function __construct() {
        $this->server = $_SERVER['HTTP_HOST'];
		$this->path = dirname(__FILE__);
		$_SESSION['debug']['PATH'] = dirname(__FILE__);
		$this->_load_platform_vars();
    }
	
	private function _load_platform_vars() {
		if( strpos( $this->path, 'pmprojx' ) ) {
			$this->environment = 'development';
		} elseif( strpos( $this->path, 'fm0omky9ojw2/public_html/sharetopic' ) ) {
			$this->environment = 'production';
		}
	}

}
?>