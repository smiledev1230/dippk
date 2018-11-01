<div id="account" class="contain">
<?
if( $_SESSION['usr_id'] ) {
	$acc = new Account();
	$acc->get_profile_data();
	$acc->get_menu_data();
	if( !$_SESSION['view'] ) $_SESSION['view'] = 'myaccount';
	$user_pic = 'img/user/' . $acc->profile['ID'] . '.' . $acc->profile['Image_Ext'];
	$profile_pic = ( file_exists( $user_pic ) ) ? $user_pic : 'img/avatar_lg.png';
	?>
    <a class="link_logoff fright">Log Out</a>
	<div class="divider fright"></div>
	<a class="link_store fright">Store</a>
	<div class="divider fright"></div>
	<a class="link_myaccount fright">My Account</a>
	<div class="divider fright"></div>
	<a class="link_mycontent fright">My Content</a>
	<div class="divider fright"></div>
	<a class="link_userpanel fright i_user account_drop"><?=$acc->profile['First_Name'].' '.$acc->profile['Last_Name']?></a>
    <div class="divider fright"></div>
	<a class="link_home fright">Home</a>
    </div>
    <div id="account_panel" class="hidden"><div class="contain">
    	<table class="apanel" cellpadding="0" cellspacing="0" border="0">
        	<thead><th colspan="2" class="link_myaccount">Account</th><td class="apanel_space"></td><th colspan="2" class="link_mycontent">Content</th></thead>
            <tbody>
            	<tr>
                	<td><img class="apanel_profile top_space" src="<?=$profile_pic?>"></td>
                    <td><ul>
                    	<li><a class="link_logoff">Log Out</a></li>
                        <li><a class="link_myaccount">Update Profile</a></li>
                        <li><a class="link_password">Change Password</a></li>
                        </ul></td>
                    <td class="apanel_space"></td>
                    <td><ul>
                    	<li><a class="link_favorites">Favorites</a></li>
                        <li><a class="link_watchlist">Watchlist</a></li>
                        <li><a class="link_history">History</a></li>
                        </ul></td>
                    <td class="wide">
						<?
						$mycontent = new MyContent();
						if( $mycontent->get_last_watched() ) {
							?>
							<div id="crsvid-<?=$mycontent->lastwatch['AlbumID']?>:<?=$mycontent->lastwatch['VideoID']?>" class="watched top_space link_coursevid">
								<img src="<?=$mycontent->lastwatch['thumbnail']?>" class="fleft">
								<div class="text_sm fleft">Last watched: </div><div class="browse_head fleft"><?=$mycontent->lastwatch['video_title']?></div>	     
							</div>
							<?
							$_SESSION['debug']['LAST'] = var_export( $mycontent->lastwatch, true );
						}
						?></td></tr>
            </tbody>
        </table>
    </div></div>
    <?
} else {
	?><a class="link_login fright">Login</a><?
}
?>
</div>