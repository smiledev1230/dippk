<div id="message_panel" class="hidden">
	<div class="msg_left">
    	<div class="browse_head">SUGGESTED RECIPIENTS</div>
        <div id="suggest" class="btm_space">click to add</div>
        <div id="user_7" class="share_rec_box">
        	<img src="img/user/7.jpg">
            <div class="rec_name fright">Chris<br>Fakename</div>
            <div class="fclear"></div></div>
        <div id="user_demo1" class="share_rec_box">
        	<img src="img/user/demo1.gif">
            <div class="rec_name fright">John<br>Smith</div>
            <div class="fclear"></div></div>
        <div id="user_demo2" class="share_rec_box">
        	<img src="img/user/demo2.gif">
            <div class="rec_name fright">Frank<br>Longlastname</div>
            <div class="fclear"></div></div>
        <div id="user_demo3" class="share_rec_box">
        	<img src="img/user/demo3.gif">
            <div class="rec_name fright">Another<br>Name</div>
            <div class="fclear"></div></div>
        <div id="user_demo4" class="share_rec_box">
        	<img src="img/user/demo4.gif">
            <div class="rec_name fright">Andan<br>Othername</div>
            <div class="fclear"></div></div>
    </div>
    <div class="msg_right">
    	<div id="convo_new" class="conversation">
            <div class="browse_head">MESSAGE</div>
            <div class="rec_txt_tall fleft">TO:</div>
            <div class="rec_to_box fleft">
                <div class="rec_to rec_add fleft">
                    <div class="rec_name fleft">ADD RECIPIENT</div>
                    <div class="plus_sm fright"></div>
                </div>
                <div id="rec_before" class="fclear"></div>
            </div>
            <form id="share" class="acc">
            	<input type="hidden" name="video_title" value="<?=$course['title'].': '.$video_title?>">
                <input type="hidden" name="video_source" value="index.php?page=course&id=<?=$req['id']?>&v=<?=$req['v']?>">
                <textarea name="message_text">Check out this video!

***<?=$course['title'].': '.$video_title?>***</textarea>
                <div class="fclear"></div>
                <input type="submit" id="message_post" class="fright" value="SHARE">
            </form>
        </div>
        <div class="fclear"></div>
    </div>
    <div class="fclear"></div>
</div>
<? include 'inc/modules/recipients.php'; ?>