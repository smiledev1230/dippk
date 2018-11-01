<?
$req['id'] = '2575717';
$vimeo = new Vimeo();
$videos = $vimeo->get_album($req['id']);
?>
<div class="acc_head">COMMENTS ON MY MEDIA</div>
<div id="reply_form" class="hidden">
    <form class="reply_hold indent">
        <input type="hidden" name="profile_source" value="<?=$profile_pic?>">
        <input type="hidden" name="full_name" value="<?=$acc->profile['First_Name'].' '.$acc->profile['Last_Name']?>">
        <input type="text" name="reply_comment" class="comment">
        <input type="submit" class="btn_comment fright" name="post_comment" value="ADD REPLY">
        <div class="fclear"></div>
    </form>
</div>
<?
$firstrow = true;
foreach( $videos['videos'] as $video ) {
	$spacing = $firstrow ? 'top_space' : 'top_ad';
	$firstrow = false;
	?>
    <div id="vid-<?=$req['id']?>:<?=$video->id?>" class="watched <?=$spacing?> link_albumvid">
        <img src="<?=$video->thumbnails->thumbnail[0]->_content?>" class="fright">
        <div class="line"><div class="browse_head2"><?=strtoupper($video->title)?></div></div>
        <div class="line">Comment made: <span class="comment">3 days ago</span></div>
        <div class="line">Total comments on this video: <span class="comment">3 <strong>(0 new)</strong></span></div>
        <div class="line"><img src="img/user/7.jpg" class="profile fleft"><span class="comment">This is my comment about how awesome this video is. I want to share it with everyone.</span></div>
        <div class="line">You replied 2 days ago.</div>
        <div class="line line_links"><a class="js_reply">ADD REPLY</a> | <a class="link_comment_panel">GO TO VIDEO COMMENTS</a></div>
    </div>
    <div class="fclear"></div>
    <?
}
?><div class="acc_head">MY PERSONAL COMMENT HISTORY</div>
<?
$firstrow = true;
foreach( $videos['videos'] as $video ) {
	$spacing = $firstrow ? 'top_space' : 'top_ad';
	$firstrow = false;
	?>
    <div id="vid-<?=$req['id']?>:<?=$video->id?>" class="watched <?=$spacing?> link_albumvid">
        <img src="<?=$video->thumbnails->thumbnail[0]->_content?>" class="fleft">
        <div class="line"><div class="browse_head2"><?=strtoupper($video->title)?></div></div>
        <div class="line">Comment made: <span class="comment">2 days ago</span></div>
        <div class="line">Total comments on this video: <span class="comment">3 <strong>(0 new)</strong></span></div>
        <div class="line">Last comment made: <span class="comment">I am commenting on your comment about how amazing this video is.</span></div>
    </div>
    <div class="fclear"></div>
    <?
}
?>