<?
$_SESSION['demo'] = true;
$req = array_merge($_GET, $_POST);
if( $req['sect'] ) {
	if( $req['sect'] == 'false' ) {
		$_SESSION['sect'] = false;
	} else {
		$_SESSION['sect'] = $req['sect'];
	}
}
if( $req['page'] ) {
	$_SESSION['page'] = strtolower( str_replace( ' ', '', $req['page'] ) );
	// set section for direct video links
	if( $req['id'] && $req['page'] == 'album' ) {
		$docs = new Documents();
		$_SESSION['sect'] = $docs->get_section_number($req['id']);
	}
} else {
	if( $_SESSION['usr_id'] ) {
		$_SESSION['page'] = $_SESSION['sect'] ? 'section': 'home';
		/*if( !$_SESSION['albums'] ) {
			$vimeo = new Vimeo();
			$_SESSION['albums'] = $vimeo->build_album_array();
		}*/
	} else {
		$_SESSION['page'] = 'login';
	}
}
$_SESSION['view'] = ( $req['view'] ) ? $req['view'] : false;
	
$_SESSION['debug']['GET'] = var_export( $_GET, TRUE );
$_SESSION['debug']['POST'] = var_export( $_POST, TRUE );
$_SESSION['debug']['SESSION'] = var_export( array_diff_key( $_SESSION, array( 'debug' => '' ) ), TRUE );
?>