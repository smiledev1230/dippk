<div class="tleft blue">TOPIC PROGRESS</div>

<div class="crs_progress"><div class="crs_progress_fill"></div><span class="crs_progress_text"><?=$crs->progress['course']?></span></div>

<div class="panel_divider"></div>

<?

$l_num = 1;

foreach( $crs->lessons as $l ) {

	?>

    <div class="crs_lesson_title">

        <div class="crs_icon_check<? if( $l['complete'] == 'Y' ) echo '-green'; ?> fleft"></div>

        <div class="crs_icontext fleft link_crs_lesson" id="lsn-<?=$l_num?>">C<?=$l_num?>: <?=$l['title']?></div>

        <div class="fclear"></div>

    </div>

    <?

	$l_num++;

}

if( $crs->course['certificate'] ) {

	?>

	<div class="crs_lesson_title">

		<div class="crs_icon_check fleft"></div>

		<div class="crs_icontext fleft">Course Certificate</div>

		<div class="fclear"></div>

	</div>

	<?

}

if( $req['view'] != 'results' ) {

	?>

	<div class="panel_divider"></div>

	<div class="tleft blue"><a class="link_crs_results">GO TO MY RESULTS PAGE</a></div>

	<?

}

?>