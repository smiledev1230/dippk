<div id="main-menu" class="contain">
		<img src="img/logo_trans.png" class="link_home fleft">
        <div id="account_bar">
<?

if( $_SESSION['usr_id'] ) {

	$acc = new Account();

	$acc->get_profile_data();

	if( !$_SESSION['view'] ) $_SESSION['view'] = 'account';

	$user_pic = 'img/user/' . $acc->profile['ID'] . '.' . $acc->profile['Image_Ext'];

	$profile_pic = ( file_exists( $user_pic ) ) ? $user_pic : 'img/avatar_lg.png';

	$mycontent = new MyContent();

	$content_counts = $mycontent->get_counts();

	$message = new Message();

	//check if we are in message page
    if (isset($req['view']) && $req['view']=='messages' && isset($req['id'])) {
        $message->mark_messages_read($req['id'], $_SESSION['usr_id']);
    }
	
	$unread_messages = $message->get_unread_messages($_SESSION['usr_id']);
	
	

	?>
	<div class="fright message-icon-holder">
		<i class="fa fa-envelope message-icon">
			<?php if(count($unread_messages)>0):?>
			<span><?php echo count($unread_messages);?></span>
			<?php endif;?>
		</i>
		<div class="notification-messages">
			<ul>
				<?php if(count($unread_messages)>0):?>
					<?php foreach($unread_messages as $msg):?>
					<?php $user_image = !empty($msg['Image_Ext'])?'img/user/'.$msg['ID'].'.'.$msg['Image_Ext']:'img/avatar_126x101.png';?>

					<a href="?page=account&view=messages&id=<?php echo $msg['ID'];?>">
						<li>
							<div class="user-image">
								<img src="<?php echo $user_image;?>" alt="">
							</div>
							<div class="user-message">
								<div class="name"><?php echo $msg['First_Name'].' '.$msg['Last_Name'];?></div>
								<div class="u-message"><?php echo $message->truncate_message($msg['message']);?></div>
							</div>
							<div class="message-sent-time">
								<?php echo $message->format_message_date($msg['datetime']);?>
							</div>
						</li>
					</a>
					<?php endforeach;?>
				<?php else:?>
					<li>
						<div>No Unread Messages</div>
					</li>
				<?php endif;?>
			</ul>
			<a href="?page=account&view=messages" class="see-all-link">See All</a>
		</div>
	</div>
	
	<div class="divider fright"></div>
    <a class="link_logoff fright">Log Out</a>

	<div class="divider fright"></div>
	<?php 
	if( $_SESSION['lv_contributor']) {

		?>

        <a href="?page=account&view=uploads" class="fright">My Content</a>

        <div class="divider fright"></div>

        <?

	}
	?>
    <?

	if( $_SESSION['lv_leader'] || $_SESSION['lv_admin'] ) {

		?>

        <a class="link_admin fright">Admin</a>

        <div class="divider fright"></div>

        <?

	}
	else{?>

		<a href="?page=account" class="fright">Account</a>
		<div class="divider fright"></div>

	<?php }

	/*<a class="link_store fright">Store</a>

	<div class="divider fright"></div>*/

	?>

	<a class="link_help fright">Help</a>

	<div class="divider fright"></div>

	<? /*<a class="link_forum fright">Forum</a>

	<div class="divider fright"></div>*/ ?>

	<a class="link_main_contributors fright">Contributors</a>

	<div class="divider fright"></div>

	<a class="link_main_courses fright">Topics</a>

	<div class="divider fright"></div>

	

	<a href="http://mediacenter.plastipak.com/index.php?site=portal" target="_blank" class="fright">Media Center</a>

	

    </div>

    

    <?

} else {

	?><a href="?sect=false" class="link_login fright">Login</a>
	
	
	</div><?

}


?>
</div>

<script>
	$(".message-icon").on("click",function(){
		$(".notification-messages").toggle();
	});
</script>