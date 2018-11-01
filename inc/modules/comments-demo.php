<div id="comment_panel"<? if( $_GET['show'] != 'comments' ) echo ' class="hidden"'; ?>>
	<div class="comment_new">
        <img src="<?=$profile_pic?>" class="profile_main fleft">
        <div class="comment_point fleft"></div>
        <form id="comment_post">
        	<input type="hidden" name="profile_source" value="<?=$profile_pic?>">
        	<input type="hidden" name="full_name" value="<?=$acc->profile['First_Name'].' '.$acc->profile['Last_Name']?>">
        	<input type="text" name="new_comment" class="comment fleft">
        	<input type="submit" class="btn_comment fright" name="post_comment" value="POST COMMENT">
        </form>
        <div class="comment_sub fclear">
            <div class="comment_option fright">PRESS ENTER TO SUBMIT</div>
        </div>
    </div>
    <div class="panel_break"></div>
    <div class="comment_left">
        <div id="reply_form" class="hidden">
        	<form class="reply_hold">
            	<input type="hidden" name="profile_source" value="<?=$profile_pic?>">
        		<input type="hidden" name="full_name" value="<?=$acc->profile['First_Name'].' '.$acc->profile['Last_Name']?>">
        		<input type="text" name="reply_comment" class="comment">
        		<input type="submit" class="btn_comment fright" name="post_comment" value="POST REPLY">
                <div class="fclear"></div>
            </form>
        </div>
        <div class="comment_item">
            <img src="img/user/7.jpg" class="profile fleft">
            <div class="comment_point fleft"></div>
            <div class="comment_box fleft">This is my comment about how awesome this video is.  I want to share it with everyone.</div>
            <div class="comment_sub fclear">
                <div class="comment_name fleft">JOHN SMITH</div>
                <div class="comment_date fleft">3 days ago</div>
                <div class="comment_option fright"><a class="js_reply">REPLY</a></div>
            </div>
        </div>
        <div class="fclear"></div>
        <div class="comment_reply">
            <img src="img/user/8.jpg" class="profile fleft">
            <div class="comment_point fleft"></div>
            <div class="comment_box fleft">I am commenting on your comment about how amazing this video is.</div>
            <div class="comment_sub fclear">
                <div class="comment_name fleft">TEST USER</div>
                <div class="comment_date fleft">2 days ago</div>
                <div class="comment_option fright"><a class="js_reply">REPLY</a> | <a class="js_remove">DELETE</a></div>
            </div>
        </div>
        <div class="fclear"></div>
        <div class="comment_item">
            <img src="img/user/7.jpg" class="profile fleft">
            <div class="comment_point fleft"></div>
            <div class="comment_box fleft">This is my comment about how awesome this video is.  I want to share it with everyone.</div>
            <div class="comment_sub fclear">
                <div class="comment_name fleft">JOHN SMITH</div>
                <div class="comment_date fleft">1 week ago</div>
                <div class="comment_option fright"><a class="js_reply">REPLY</a></div>
            </div>
        </div>
    </div>
    <div class="comment_right">
    	<div id="contributor_panel">
            <div class="browse_head2">TOP CONTRIBUTORS</div>
            <div class="contributor_preview fleft" title="Test User">
                <div class="contributor_sm link_contributors"><img src="img/user/9.jpg"></div>
                <div class="follow">FOLLOW</div>
                <div class="follow_check"></div>
            </div>
            <div class="fspace fleft"></div>
            <div class="contributor_preview fleft" title="John Smith">
                <div class="contributor_sm link_contributors"><img src="img/user/7.jpg"></div>
                <div class="follow">FOLLOW</div>
                <div class="follow_check"></div>
            </div>
            <div class="fspace fleft"></div>
            <div class="contributor_preview fleft" title="Demo User 1">
                <div class="contributor_sm link_contributors"><img src="img/user/demo1.gif"></div>
                <div class="follow">FOLLOW</div>
                <div class="follow_check hidden"></div>
            </div>
            <div class="fclear"></div>
            <div class="contributor_preview fleft" title="Demo User 2">
                <div class="contributor_sm link_contributors"><img src="img/user/demo2.gif"></div>
                <div class="follow">FOLLOW</div>
                <div class="follow_check hidden"></div>
            </div>
            <div class="fspace fleft"></div>
            <div class="contributor_preview fleft" title="Demo User 3">
                <div class="contributor_sm link_contributors"><img src="img/user/demo3.gif"></div>
                <div class="follow">FOLLOW</div>
                <div class="follow_check hidden"></div>
            </div>
            <div class="fspace fleft"></div>
            <div class="contributor_preview fleft" title="Demo User 4">
                <div class="contributor_sm link_contributors"><img src="img/user/demo4.gif"></div>
                <div class="follow">FOLLOW</div>
                <div class="follow_check hidden"></div>
            </div>
            <div class="fclear"></div>
            <div class="contributor_preview fleft" title="Demo User 5">
                <div class="contributor_sm link_contributors"><img src="img/user/demo5.gif"></div>
                <div class="follow">FOLLOW</div>
                <div class="follow_check hidden"></div>
            </div>
            <div class="fspace fleft"></div>
            <div class="contributor_preview fleft" title="Demo User 6">
                <div class="contributor_sm link_contributors"><img src="img/user/demo6.gif"></div>
                <div class="follow">FOLLOW</div>
                <div class="follow_check hidden"></div>
            </div>
            <div class="fspace fleft"></div>
            <div class="contributor_preview fleft" title="Demo User 7">
                <div class="contributor_sm link_contributors"><img src="img/user/demo7.gif"></div>
                <div class="follow">FOLLOW</div>
                <div class="follow_check hidden"></div>
            </div>
            <div class="fclear"></div>
            <div class="contributor_preview fleft" title="Demo User 8">
                <div class="contributor_sm link_contributors"><img src="img/user/demo8.gif"></div>
                <div class="follow">FOLLOW</div>
                <div class="follow_check hidden"></div>
            </div>
            <div class="fspace fleft"></div>
            <div class="contributor_preview fleft" title="Demo User 9">
                <div class="contributor_sm link_contributors"><img src="img/user/demo9.gif"></div>
                <div class="follow">FOLLOW</div>
                <div class="follow_check hidden"></div>
            </div>
            <div class="fspace fleft"></div>
            <div class="contributor_preview fleft" title="Demo User 10">
                <div class="contributor_sm link_contributors"><img src="img/user/demo10.gif"></div>
                <div class="follow">FOLLOW</div>
                <div class="follow_check hidden"></div>
            </div>
            <div class="fclear"></div>
        </div>
    </div>
    <div class="fclear"></div>
</div>