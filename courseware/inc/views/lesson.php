<style>
.chp_vim iframe{
  width: 100% !important;
}
</style>
<?
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

if(empty($req['chapter'])){
	$req['chapter']=1;
}
$lesson = $req['chapter'] ? $crs->get_lesson($req['chapter']) : $crs->lessons[$crs->progress['lesson']['num']];

$contributor_id = $crs->course['contributor_id'];
// if this is not the first part then mark it's previous chapter as completed
if(isset($req['part']) && $req['part']>1){
	$previous_part = $req['part'] -1;
	$previous_chapter = $lesson['chapters'][$previous_part]['ID'];
	//mark this chapter as completed
	$crs->completeChapter($crs->course['ID'],$lesson['ID'],$previous_chapter);
}

//if this is not the first lesson then mark the first chapter or the previous lesson completed and also the lesson as comleted
if($req['chapter']>1 && $req['part']==1){
	$last_chapter_lesson_ids = $crs->get_chapter_lesson_ids($req['chapter']-1);
	$crs->completeChapter($crs->course['ID'],$last_chapter_lesson_ids['lesson_id'],$last_chapter_lesson_ids['chapter_id']);
	$crs->completeLesson($crs->course['ID'],$last_chapter_lesson_ids['lesson_id']);
}

if( $req['quiz'] ) {

	include 'courseware/inc/modules/quiz.php';

} else {

	//$chapter_num = $req['chapter'] ? $req['chapter'] : 1;
	$chapter_num = $req['part'] ? $req['part'] : 1;
	$lesson_num = $req['chapter'] ? $req['chapter'] : 1;

	$chapter_prev = $lesson['chapters'][$chapter_num-1] ? $lesson['chapters'][$chapter_num-1] : false;

	$chapter_next = $lesson['chapters'][$chapter_num+1] ? $lesson['chapters'][$chapter_num+1] : false;

	$chapter = $lesson['chapters'][$chapter_num];


	$_SESSION['debug']['LESSON'] = var_export( $lesson, true );

	$_SESSION['debug']['CHAPTER'] = var_export( $chapter, true );

	?>
	
	<div class="crs_head_lg">
		<div class="pull-left">
		<?

		echo "<span class='circle'>".$lesson_num."</span>" . ' ' . $chapter['title'];

		if( $chapter['type'] == 'multi' ) {

			?><a class="expand crs_text_sm blue fright">expand >>></a><?

		}

		?>
		</div>
		<div class="pull-right">
			<?php if($crs->course['contributor_id']==$_SESSION['usr_id']):?>
				<a  class="link-btn" href="?page=account&action=edit&type=topic&id=<?php echo $crs->course['ID'] ?>" style="font-size: 15px;height: 25px;padding-top: 5px;">EDIT TOPIC</a> 
			<?php endif;?>
		</div>
	</div>
	<div class="clearfix"></div>

	<div id="lsn_container" style="margin-top:15px;">

	<?

	if( $chapter['video'] ) {
		//check if this video is in watchlist/favorites
		$favorite = $crs->is_favorite_video($chapter['video']);
		$watchlist = $crs->is_watchlist_video($chapter['video']);

		//add this video to user's history
		$crs->add_video_history($chapter['video']);

		$vimeo = new Vimeo();

		$active_video = $vimeo->get_video($chapter['video']);

		$iframe_id = $active_video->width . 'x' . $active_video->height . 'x' . $chapter['video'];

		$video_title = $active_video->title;

		$video_url = $vimeo->player_url( $chapter['video'], true, $iframe_id );

		?>
		<div class="chp_vim"><iframe style="width:100% !important;" id="<?=$iframe_id?>" src="<?=$video_url?>" frameborder="0" allowfullscreen></iframe></div>
		<div id="ffeat<?php  echo $chapter['video'];?>" title="<?php if($watchlist){ echo 'Remove from Watchlist';} else { echo 'Add to Watchlist';}?>" class="btn_watch inline <?php if($watchlist){ echo 'btn_active link_watchlist';}?> " style="top:0px;float:left;margin-right:10px;cursor:pointer;"><div class="tt_img hidden"></div></div>
		<div id="ffeat<?php echo $chapter['video'];?>"  title="<?php if($favorite){ echo 'Remove from Favorites';} else { echo 'Add to Favorites';}?>" class="btn_fav inline <?php if($favorite){ echo 'btn_active link_favorites';}?>" style="top:0px;float:left;margin-right:10px;cursor:pointer;"><div class="tt_img hidden"></div></div>
		<div class="clearfix"></div>
		<hr>
		<?

	}
	?>
	<?php 
		if(!empty($chapter['asset_name'])){
			$path = 'content/pdf/'.$crs->course['ID']."/".$lesson['ID']."/".$chapter['ID']."/".$chapter['asset_name'];
			?>
			<embed src="<?php echo $path;?>" width="100%" height="800px" />
			<?php 
		}
	?>
	
	<?php
	if( $chapter['type'] != 'text' ) include 'courseware/loaders/' . $chapter['type'] . '.php';

	$filepath = 'courseware/courses/' . $crs->course['ID'] . '/' . $lesson['ID'] . '/' . str_replace( ' ', '_', $chapter['title'] ) . '.txt';

	if( $data = @file_get_contents($filepath) ) {

		?><div id="lsn_scroll" class="crs_text_lg"><?

			$data = str_replace( "\r\n", '<br>', $data );

			$data = str_replace( array("\r","\n"), '<br>', $data );

			echo $data;

		?></div><?

	}

	?>

	</div>

	<!-- <div class="lsn_nav top_ad">CURRENTLY READING<br>P<?=$chapter_num?>: <?=$chapter['title']?></div> -->

	<?
	//check if this has previous part or chapter
	$previous_chapter = $crs->get_previous_chapter_no($req['chapter'],$req['part']);
	//check if this chapter, part or topic has quizzes
	if( $previous_chapter ) {
		?>
		
		<div id="chp_prev-<?=$previous_chapter['chapter']?>" data-lesson="<?php echo $previous_chapter['lesson']; ?>" data-chapter="<?=$previous_chapter['chapter']?>" class="lsn_nav_btn link_crs_chapter fleft"><div class="arrow_left fleft"></div>&nbsp;&nbsp;Previous <?=$previous_chapter['type']?></div><?

	}
	//check if this part has any questions
	$chapter_quiz = $crs->get_chapter_quiz_id($chapter['ID']);
	if($chapter_quiz){?>
		<div id="quiz_<?php echo $chapter_quiz;?>" class="lsn_nav_btn link_crs_quiz fright">TAKE QUIZ&nbsp;<div class="arrow_right fright"></div></div>
	<?php }

	else if( $chapter_next ) {
		//if there is next chapter then check if this chapter has any quiz available
		$quiz_id = $crs->chapter_has_quiz($req['chapter']);
		if(!$quiz_id){

		
		?>

		<div id="chp_next-<?=$chapter_next['key']?>" data-lesson="<?php echo $req['chapter']; ?>" data-chapter="<?=$chapter_next['key']?>" class="lsn_nav_btn link_crs_chapter fright">NEXT: <? echo strlen( $chapter_next['title'] ) > 16 ? substr( $chapter_next['title'], 0, 14 ) . '...' : $chapter_next['title']; ?>&nbsp;&nbsp;<div class="arrow_right fright"></div></div>
		
		<?
		}
		else{
			?>
				<div id="quiz_<?php echo $quiz_id;?>" class="lsn_nav_btn link_crs_quiz fright">TAKE QUIZ&nbsp;<div class="arrow_right fright"></div></div>
			<?php 
		}

	}
	else{
		//check if this is the last part or there is next chapter
		$total_chapters = count($crs->lessons);
		if(!empty($req['chapter'])){
			$current_chapter = $req['chapter'];
		}
		else{
			$current_chapter = 1;
		}

		if($current_chapter < $total_chapters){ 
			$quiz_id = $crs->lesson_has_quiz($lesson['ID']);
			?>
			<?php if(!$quiz_id):?>
				<div id="" data-chapter="1" data-lesson="<?php echo $current_chapter+1; ?>"  class="lsn_nav_btn link_crs_chapter fright">Next Chapter &nbsp;&nbsp;<div class="arrow_right fright"></div></div>
			<?php else:?>
				<div id="quiz_<?php echo $quiz_id;?>" class="lsn_nav_btn link_crs_quiz fright">TAKE QUIZ&nbsp;<div class="arrow_right fright"></div></div>
			<?php endif;?>
				
		<?php }
		else if($current_chapter == $total_chapters){ 
			$quiz_id = $crs->course_has_quiz($crs->course['ID']);
			?>
			<?php if(!$quiz_id):?>
				<div id="" data-chapter="<?php echo $chapter['ID']; ?>" data-lesson="<?php echo $lesson['ID']; ?>" data-course='<?php echo $crs->course['ID'];?>'  class="lsn_nav_btn complete_topic fright">Finish Topic</div>
			<?php else:?>
				<div id="quiz_<?php echo $quiz_id;?>" class="lsn_nav_btn link_crs_quiz fright">TAKE QUIZ&nbsp;<div class="arrow_right fright"></div></div>
			<?php endif;?>
			
		<?php }
	 }
	
}

?>
<?php if($_SESSION['usr_id'] !=$contributor_id):?>
	<div class="clearfix"></div>
	<div class="link-btn pointer" style="margin-top:40px;margin-bottom:10px;" id="message-contributor-btn">Send a message to the Content Contributor</div>
	<div class="process-message">
	</div>
	<div class="message-contributor-container" style="display:none;">
		<textarea id="message-contributor-input" style="width:100%;height:200px;margin-top:15px;padding:5px;">Topic: <?php echo $crs->course['title']."\n\n";?>Chapter: <?php echo $lesson['title']."\n\n";?>Part: <?php echo $chapter['title']."\n\n";?></textarea>
		<button id="message-contributor-send-btn" class="btn pointer">Send</button>
		<input type="hidden" id="receiver_id" value="<?php echo $contributor_id; ?>">
	</div>
<?php endif;?>