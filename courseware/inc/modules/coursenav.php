<div id="crs_nav" class="tlight">

<?
if(isset($re['view']) && $req['view']=='chapter' && !isset($req['chapter'])){
	$req['chapter']=1;
}
if(isset($re['view']) && $req['view']=='chapter' && !isset($req['part'])){
	$req['part']=1;
}
if( $_SESSION['page'] != 'editor' ) {

	?><div class="crs_progress"><div class="crs_progress_fill"></div><span class="crs_progress_text"><?=$crs->get_current_chapter_progress($_SESSION['course'],$req['chapter'],$req['part']);?></span></div><?

}

?>

<div class="scrolldiv">

<?

$selected_lesson = $req['chapter'];
$l_num = 1; $l_active_set = false; $c_active_set = false;

foreach( $crs->lessons as $l_id => $l ) {
	if($l_num==$selected_lesson){
		$l_active_set=true;
	}
	else{
		$l_active_set=false;
	}
	?>

    <div class="crs_lesson">

    	<div class="crs_lesson_title<? if( $l_active_set /* && !$l['complete'] */ ) { echo ' active'; $l_active_set = $l_num; }?>">

    		<div class="crs_icon_<?

            	if( $l['complete'] && $_SESSION['page'] != 'editor' ) {

					echo 'check-green';

				} elseif( $l_active_set == $l_num ) {

					echo 'minus';

				} else {

					echo 'plus';

				} ?> fleft"></div>

            <div class="crs_icontext fleft link_crs_chapter <?  if( $_SESSION['page'] == 'editor' || $l['complete'] || $l_active_set == $l_num ) {

				echo ' link_crs_lesson';

			} ?>" id="lsn-<?=$l_num?>" data-chapter="1" data-lesson="<?=$l_num?>">C<?=$l_num?>: <?=$l['title']?></div>

            <div class="fclear"></div>

        </div>

        <?

		if( !$l['complete'] ) {

			$c_num = 1;

			?><div class="data<? if( $l_active_set == $l_num ) echo ' active'; ?>"><?

			foreach( $l['chapters'] as $c ) {
				if($l_active_set && $c_num == $req['part']){
					$c_active_set=true;
				}
				else{
					$c_active_set=false;
				}

				?><div id="chp-<?=$c_num?>" data-chapter='<?=$c_num?>' data-lesson="<?=$l_num?>" class="crs_lesson_chapter<?

                	 echo ' link_crs_chapter';

					if( $req['part'] == $c_num && $c_active_set )  {

						echo ' active';

						$c_active_set = $c_num;

					}

					if( $c_num == 1 ) echo ' first'; ?>">P<?=$c_num?>: <?=$c['title']?></div><?

				$c_num++;

			}

			if( $crs->quizzes['lesson'][$l_id] ) {

				?>

                <div id="quiz-<?=$crs->quizzes['lesson'][$l_id]['ID']?>" class="crs_lesson_chapter link_crs_quiz">

                QUIZ: <?=$crs->quizzes['lesson'][$l_id]['title']?>

                </div>

				<?

			}

			?></div><?

		}

		?>

    </div>

    <?

	$l_num++;

}

?>

</div>

</div>