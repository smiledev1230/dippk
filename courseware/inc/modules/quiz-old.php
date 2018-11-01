<?

$crs->get_quiz($req['quiz']);

$questions = $crs->get_question();

$_SESSION['debug']['CRS QUIZ'] = var_export($crs->quiz,true);

$q_num = $crs->quiz['current'] + 1;

$q_active = $questions[$crs->quiz['current']];

?>

<div class="crs_head_lg">QUIZ: <?=$crs->quiz['title']?></div>

<div class="acc_head">Question <?=$q_num?> of <?=count($questions)?></div>

<?

switch( $q_active['media_type'] ) {

	case 'image':

		

		break;

	case 'video':

		$vimeo = new Vimeo();

		$active_video = $vimeo->get_video($q_active['media_data']);

		$iframe_id = $active_video->width . 'x' . $active_video->height . 'x' . $q_active['media_data'];

		$video_title = $active_video->title;

		$video_url = $vimeo->player_url( $q_active['media_data'], true, $iframe_id );

		?><div class="chp_vim"><iframe id="<?=$iframe_id?>" src="<?=$video_url?>" frameborder="0" allowfullscreen></iframe></div><?

		break;

}

?>

<div class="subhead"><?=$q_active['question']?></div>

<form class="quiz_answer top_ad" method="post">

	<input type="hidden" name="quiz" value="<?=$req['quiz']?>">

	<input type="hidden" name="question" value="<?=$q_active['ID']?>">

	<input type="hidden" name="type" value="<?=$q_active['type']?>">

	<?

    switch( $q_active['type'] ) {

        case 'multiple':

            $correct = 0;

            foreach( $q_active['answers'] as $a ) {

                if( $a['correct'] == 'Y' ) $correct++;

            }

            foreach( $q_active['answers'] as $a ) {

                if( $correct > 1 ) {

                    ?><div><input type="checkbox" name="answer[]" value="<?=$a['ID']?>"><span class="radio_text"><?=$a['answer_option']?></span></div><?

                } else {

                    ?><div><input type="radio" name="answer" value="<?=$a['ID']?>"><span class="radio_text"><?=$a['answer_option']?></span></div><?

                }

            }

            break;

        case 'text':

            ?><input type="text" name="answer"><?

            if( $q_active['answers'][0]['casesensitive'] == 'Y' ) {

                echo 'Answer is case sensitive.';

            }

            break;

        case 'truefalse':

            ?><input type="radio" name="answer" value="True"><span class="radio_text">True</span>

            <input type="radio" name="answer" value="False"><span class="radio_text">False</span><?

            break;

    }

    ?>

    <input type="submit" class="crs_button" name="process" value="SUBMIT">

</form>