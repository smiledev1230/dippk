<div class="crs_sidenav">

	<?

	if( $req['quiz'] ) {

		include 'courseware/inc/modules/quizstats.php';

	} else {

		?>

        <div class="title">TOPIC NAVIGATION</div>

        <div class="data<? if( $req['view'] != 'results' ) echo ' active'; ?>"><? include 'courseware/inc/modules/coursenav.php'; ?></div>

        <div class="title separate">TOPIC COMPLETION</div>

        <div class="data<? if( $req['view'] == 'results' ) echo ' active'; ?>"><? include 'courseware/inc/modules/courseprogress.php'; ?></div>

        <?

        if( $req['view'] == 'results' ) {

            

        }

        ?>

        <div class="title separate">HOW TO NAVIGATE A TOPIC</div>

        <div class="data">

            <a href="courseware/img/popup_howto.jpg" class="lightbox"><img src="courseware/img/howto_preview.png"></a>

        </div>

        <div class="title separate">SYMBOL LEGEND</div>

        <div class="data">

            <span class="crs_legend">

                <div class="line">

                    <div class="crs_icon_check fleft"></div>

                    <div class="crs_icontext fleft">Chapter/Quiz Not Completed</div>

                    <div class="fclear"></div></div>

                <div class="line top_15">

                    <div class="crs_icon_check-green fleft"></div>

                    <div class="crs_icontext fleft">Chapter/Quiz Completed</div>

                    <div class="fclear"></div></div>

                <div class="line top_15">

                    <div class="crs_icon_exclamation fleft"></div>

                    <div class="crs_icontext fleft">Quiz Failed</div>

                    <div class="fclear"></div></div>

                <div class="line top_15">

                    <div class="crs_icon_plus fleft"></div>

                    <div class="crs_icontext fleft">Open: More Info Available</div>

                    <div class="fclear"></div></div>

                <div class="line top_15">

                    <div class="crs_icon_minus fleft"></div>

                    <div class="crs_icontext fleft">Close: Hide Info</div>

                    <div class="fclear"></div></div>

                <div class="fclear"></div>

            </span>

        </div>

        <?

	}

	?>

</div>