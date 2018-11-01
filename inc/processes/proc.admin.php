<?
$admin = new Admin();
switch( $_POST['proc_type'] ) {
	case 'ADD CONTRIBUTOR':
		$admin->team_add_contributor();
		break;
	case 'ADD NEW SECTION':
		$admin->add_section();
		break;
	case 'ADD SECTION':
		$admin->team_add_section();
		break;
	case 'DENY':
		$admin->deny_level();
		break;
	case 'GRANT':
		$admin->grant_level();
		break;
	case 'REMOVE CONTRIBUTOR':
		$admin->team_rem_contributor();
		break;
	case 'REMOVE SECTION':
		$admin->team_rem_section();
		break;
	case 'DELETE SECTION':
		$admin->delete_section();
		break;
	case 'UPDATE':
		$admin->update_team();
		break;
}
$bypass = false;
?>