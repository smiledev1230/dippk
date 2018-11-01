<?php 
    $message = new Message();
    $users = $message->get_users();
    //get the user id from the url
    $id = @$_GET['id'];
    if(empty($id)){
        //get the id of the first user from the users list
        if(count($users)>0){
            $id = @$users[0]['ID'];
        }
    }

    if(!empty($id)){
        //get this user's message history
        $messages = $message->get_messages($id);
        $selected_user = $message->get_user($id);
    }
?>
<div id="message_panel">
	<div class="acc_head">MY MESSAGES</div>
    <div class="msg_left">
    	<div class="browse_head">USERS</div>
        <?php foreach($users as $user):?>
        <?php 
            $user_image = !empty($user['Image_Ext'])?'img/user/'.$user['ID'].'.'.$user['Image_Ext']:'img/avatar_126x101.png';
        ?>
        <a href="?page=account&view=messages&id=<?php echo $user['ID'];?>">
            <div id="user_<?php echo $user['ID'];?>" class="msg_rec_box <?php if($id==$user['ID']){ echo 'active';}?>" >
                <img src="<?php echo $user_image;?>">
                <div class="rec_name fright">
                    <div><?php echo $user['First_Name'];?><br> <?php echo $user['Last_Name'];?></div>
                </div>
                
                <div class="fclear"></div>
            </div>
        </a>
        <?php endforeach;?>
        
    </div>
    <div class="msg_right">
    	<div id="convo_new" class="conversation">
            <div class="browse_head">NEW MESSAGE</div>
            <div class=""><strong>TO:</strong> <?php echo $selected_user['First_Name'].' '.$selected_user['Last_Name'];?> </div>
            <form id="message_new" class="acc" method="post">
                <input type="hidden" name="proc_type" value="send message">
                <input type="hidden" name="receiver_id" value="<?php echo $id;?>">
            	<textarea name="message" placeholder="Type your message here"></textarea>
                <div class="fclear"></div>
                <input type="submit" name="process" id="message_post" class="fright" value="SEND MESSAGE">
            </form>
        </div>
        <div class="fclear"></div>
        <div id="convo_7" class="conversation">
        	<div class="browse_head">CONVERSATION WITH <?php echo $selected_user['First_Name'].' '.$selected_user['Last_Name'];?></div>
            <div class="convo_box">
                <?php if(count($messages)>0):?>
                    <?php foreach($messages as $message):?>
                        <?php 
                            $user_image = !empty($message['Image_Ext'])?'img/user/'.$message['sender_id'].'.'.$message['Image_Ext']:'img/avatar_126x101.png';
                        ?>
                        <?php if($message['sender_id']!=$_SESSION['usr_id']):?>
                            <div class="line">
                                <img class="fleft" src="<?php echo $user_image;?>">
                                <div class="convo_point_left fleft"></div>
                                <div class="convo_text_l fleft"><?php echo nl2br($message['message']);?></div>
                            </div>
                        <?php else:?>
                            <div class="line">
                                <img class="fright" src="<?php echo $user_image;?>">
                                <div class="convo_point_right fright"></div>
                                <div class="convo_text_r fright"><?php echo nl2br($message['message']);?></div>
                            </div>
                        <?php endif;?>
                        <div class="convo_date"><?php echo date('F d, Y \a\t h:ia',strtotime($message['datetime']));?><!-- November 10, 2013 at 1:15pm --></div>
                    <?php endforeach;?>
                <?php else:?>
                    <p>No Messages!!!</p>
                <?php endif;?>
            </div>
        </div>
        <div class="fclear"></div>
    </div>
    <div class="fclear"></div>
</div>
<? include 'inc/modules/recipients.php'; ?>