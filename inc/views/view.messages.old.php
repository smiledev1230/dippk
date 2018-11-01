8<div id="message_panel">
	<div class="acc_head">MY MESSAGES</div>
    <div class="msg_left">
    	<div class="browse_head">RECIPIENTS</div>
        <div id="user_new" class="msg_rec_box msg_new rec_active">
        	<div class="msg_point fright"></div>
            <div class="plus fright"></div>
            <div class="rec_name fright">NEW MESSAGE</div>
            <div class="fclear"></div></div>
        <div id="user_7" class="msg_rec_box">
        	<img src="img/user/7.jpg">
            <div class="rec_name fright">Chris<br>Fakename</div>
            <div class="fclear"></div></div>
        <div id="user_demo1" class="msg_rec_box">
        	<img src="img/user/demo1.gif">
            <div class="rec_name fright">John<br>Smith</div>
            <div class="fclear"></div></div>
        <div id="user_demo2" class="msg_rec_box">
        	<img src="img/user/demo2.gif">
            <div class="rec_name fright">Frank<br>Longlastname</div>
            <div class="fclear"></div></div>
        <div id="user_demo3" class="msg_rec_box">
        	<img src="img/user/demo3.gif">
            <div class="rec_name fright">Another<br>Name</div>
            <div class="fclear"></div></div>
        <div id="user_demo4" class="msg_rec_box">
        	<img src="img/user/demo4.gif">
            <div class="rec_name fright">Andan<br>Othername</div>
            <div class="fclear"></div></div>
    </div>
    <div class="msg_right">
    	<div id="convo_new" class="conversation">
            <div class="browse_head">NEW MESSAGE</div>
            <div class="rec_txt_tall fleft">TO:</div>
            <div class="rec_to_box fleft">
                <div class="rec_to rec_add fleft">
                    <div class="rec_name fleft">ADD RECIPIENT</div>
                    <div class="plus_sm fright"></div>
                </div>
                <div id="rec_before" class="fclear"></div>
            </div>
            <form id="message_new" class="acc">
            	<textarea>begin typing message here</textarea>
                <div class="fclear"></div>
                <input type="submit" id="message_post" class="fright" value="POST">
            </form>
        </div>
        <?
		$conv_users = array(
							'7'		=> 'Chris Fakename',
							'demo1'	=> 'John Smith',
							'demo2'	=> 'Frank Longlastname',
							'demo3'	=> 'Another Name',
							'demo4'	=> 'Andan Othername'
							);
		foreach( $conv_users as $cu_key => $cu_val ) {
			?>
        <div id="convo_<?=$cu_key?>" class="conversation hidden">
        	<div class="browse_head">CONVERSATION WITH <?=strtoupper($cu_val)?></div>
            <div class="convo_box">
            	<div class="line">
                	<img class="fleft" src="img/user/<?=$cu_key?>.<? echo ($cu_key == '7') ? 'jpg': 'gif'; ?>">
                    <div class="convo_point_left fleft"></div>
                    <div class="convo_text_l fleft">Hey, when do you think you can edit that video with the new content? I need to show it to a client next Thursday at noon.</div>
                </div>
                <div class="convo_date">November 7, 2013 at 1:15pm</div>
                <div class="line">
                	<img class="fright" src="<?=$profile_pic?>">
                    <div class="convo_point_right fright"></div>
                    <div class="convo_text_r fright">I’ll be able to get that finished by Wednesday.</div>
                </div>
                <div class="convo_date">November 10, 2013 at 1:15pm</div>
                <div class="line">
                	<img class="fleft" src="img/user/<?=$cu_key?>.<? echo ($cu_key == '7') ? 'jpg': 'gif'; ?>">
                    <div class="convo_point_left fleft"></div>
                    <div class="convo_text_l fleft">Thanks for all the help!</div>
                </div>
                <div class="convo_date">November 12, 2013 at 5:15pm</div>
            </div>
            <form class="convo_post acc">
            	<input type="hidden" name="profile_source" value="<?=$profile_pic?>">
            	<textarea name="message_text">begin typing reply here</textarea>
                <div class="fclear"></div>
                <input type="submit" id="message_post" class="fright" value="REPLY">
            </form>
        </div>
        	<?
		}
		?>
        <div class="fclear"></div>
    </div>
    <div class="fclear"></div>
</div>
<? include 'inc/modules/recipients.php'; ?>