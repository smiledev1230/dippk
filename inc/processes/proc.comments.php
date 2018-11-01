<?
$bypass = true;
$clean = strip_tags( $_POST['msg'], '<a>' );
switch( $_POST['process'] ) {
	case 'POST COMMENT':
		?><div class="comment_item"><?
		break;
	case 'POST REPLY':
		?><div class="fclear"></div>
        <div class="comment_reply"><?
		break;
	case 'ADD REPLY':
		$bypass = false;
		$_SESSION['page'] = 'account';
		$_SESSION['view'] = 'comments';
		break;
}
if( $bypass ) {
	?>
		<img src="<?=$_POST['profile']?>" class="profile fleft">
		<div class="comment_point fleft"></div>
		<div class="comment_box fleft"><?=$clean?></div>
		<div class="comment_sub fclear">
			<div class="comment_name fleft"><?=strtoupper($_POST['name'])?></div>
			<div class="comment_date fleft">Today - <?=date("g:ia", time())?></div>
			<div class="comment_option fright"><a class="js_reply">REPLY</a> | <a class="js_remove">DELETE</a></div>
		</div>
	</div>
	<script type="text/javascript">activate_comments();</script>
	<?
}