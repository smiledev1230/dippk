<?
switch( $_POST['process'] ) {
	case 'REPLY':
		$clean = strip_tags( $_POST['msg'], '<a>' );
		if( $_POST['vid_include'] == 'y' ) {
			$videolink = '<br><br><a href="' . $_POST['vid_source'] . '">' . $_POST['vid_title'] . '</a>';
		}
		$clean .= $videolink;
		?>
		<div class="line">
			<img class="fright" src="<?=$_POST['profile']?>">
			<div class="convo_point_right fright"></div>
			<div class="convo_text_r fright"><?=$clean?></div>
		</div>
		<div class="convo_date"><?=date("F j, Y \a\t g:ia", time())?></div>
        <?
		break;
	case 'SHARE':
		
		break;
	case 'SEND MESSAGE':
		$message = new Message();
		$message->send_message();
		header('Location:'.$_SERVER['HTTP_REFERER']);
		break;
}
?>