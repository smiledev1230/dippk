<div class="crs_head_lg">My Results</div>
<?php if(!empty($crs->course['results_image'])):?>
    <img src="courseware/courses/<?=$crs->course['ID'].'/'.$crs->course['results_image']?>" class="fleft" style="max-width:100%;">
<?php else:?>
    <img src="courseware/courses/<?=$crs->course['ID'].'/'.$crs->course['image']?>" class="fleft" style="max-width:100%;">
<?php endif;?>

<div class="margin_left fleft">

	<!-- <div class="crs_subhead">Current Topic/Chapter</div> -->

    <!-- <div class="crs_text_md top_15"><strong>Topic:</strong> <?=$crs->course['title']?></div> -->

    <!-- <div class="crs_text_md top_space">Chapter: <?=$crs->progress['lesson']['title']?></div> -->

    <!-- <div class="crs_begin link_crs_lesson tcenter top_15">BEGIN CHAPTER</div> -->

</div>

<div class="panel_divider thick"></div>

<div class="crs_subhead">Completed Areas</div>

<span class="crs_text_md">

<?

$l_num = 1;

foreach( $crs->lessons as $l ) {

	?>

	<div class="crs_lesson_title<? if( $l_num == 1 ) echo ' top_space'; ?>">

		<div class="crs_icon_check<? if( $l['complete'] == 'Y' ) echo '-green'; ?> fleft"></div>

		<div class="left_space fleft">Chapter <?=$l_num?>: <?=$l['title']?><? if( $l['complete'] == 'Y' ) echo ' | <span id="'.$l_num.'" class="crs_red link_crs_lesson">Review</span>'; ?></div>

        <div class="fclear"></div>

	</div>

	<?

	$l_num++;

}

if( $crs->course['certificate'] == 'Y' ) {

	?>

	<div class="crs_lesson_title">

		<div class="crs_icon_check fleft"></div>

		<div class="left_space fleft">Certificate: <?=$section_name?> | <?=$crs->course['title']?></div>

		<div class="fclear"></div>

	</div>

	<?

}

?>

</span>

<div class="panel_divider thick"></div>
<?php if($crs->course['assets']['Quizzes']['num']>0):?>
<div class="crs_subhead">Quizzes</div>

<span class="crs_text_md">
Quizzes are <div class="crs_icon_check-green crs_icon_inline"></div> <span class="crs_green">Pass</span> or <div class="crs_icon_exclamation crs_icon_inline"></div> <span class="crs_red">Fail</span>. When you pass all quizzes for a course, you will receive a certificate for that course.  If you have failed a quiz you can <span class="crs_red">Review Answers</span> and then <span class="crs_red">Retake</span> the quiz.

<?

if( $_SESSION['course'] == 1 ) {

	?>

    <div class="crs_lesson_title top_ad">

        <div class="crs_icon_check-green fleft"></div>

        <div class="left_space fleft">The Best Steps to Hire the Best Talent</div>

        <div class="fclear"></div>

    </div>

    <div class="crs_lesson_title">

        <div class="crs_icon_exclamation fleft"></div>

        <div class="left_space fleft">Diversifying Your Talents | <span class="crs_red">Review Answers</span> | <span class="crs_red">Retake</span></div>

        <div class="fclear"></div>

    </div>

    <div class="crs_lesson_title">

        <div class="crs_icon_check fleft"></div>

        <div class="left_space fleft">Recruiting Globally</div>

        <div class="fclear"></div>

    </div>

    <?

} else {

	$firstrow = true;

	foreach( $crs->quizzes['lesson'] as $q ) {

		?>

        <div data-id="<?=$q['ID']?>" class="crs_lesson_title<? if( $firstrow ) { echo ' top_ad'; $firstrow = false; } ?>">

            <div class="crs_icon_check fleft"></div>

            <div class="left_space fleft"><?=$q['title']?></div>

            <div class="fclear"></div>

        </div>

        <?

	}

	foreach( $crs->quizzes['course'] as $q ) {

		?>

        <div data-id="<?=$q['ID']?>" class="crs_lesson_title<? if( $firstrow ) { echo ' top_ad'; $firstrow = false; } ?>">

            <div class="crs_icon_check fleft"></div>

            <div class="left_space fleft">TOPIC TEST: <?=$q['title']?></div>

            <div class="fclear"></div>

        </div>

        <?

	}

}

?>

</span>
<?php endif;?>