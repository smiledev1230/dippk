<div class="crs_sidenav">

<div class="title">QUIZ STATS</div>

<div class="data active">

	<div id="crs_nav" class="tlight">

        <div class="tleft blue">No. of Questions:<span class="fright"><?=count($quiz_questions)?></span></div>

        <div class="tleft blue top_space">Required Passing Score:<span class="fright"><?=$crs->quiz['passing']?>%</span></div>
        <div class="tleft blue top_space">You Scored:<span class="fright"><?php echo round($quiz_result['percentage']).'%';?></span></div>
	</div>

</div>

</div>