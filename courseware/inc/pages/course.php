<?

$crs = new Course();

$viewname = $req['view'] ? $req['view'] : 'welcome';

$view_head = array(

					'welcome'	=> array(

										'head'		=> strtoupper($crs->course['title']),

										'headnav'	=> 'ABOUT THIS TOPIC'

									),

					

					'lesson'	=> array(

										'head'		=> 'TRAINING TOPIC: '.strtoupper($crs->course['title']),

										'headnav'	=> 'BEGIN TOPIC'

					),
					'results'	=> array(

										'head'		=> 'TOPIC OVERVIEW: '.strtoupper($crs->course['title']),

										'headnav'	=> 'MY RESULTS'

									),

				);

?>

<div class="crs_head" style="margin-top:0px"><?=$view_head[$viewname]['head']?></div>

<?

if( !$req['quiz'] ) {

	?>

    <div class="crs_headnav">

        <?

        $firstrow = true;

        foreach( $view_head as $view_key => $view_data ) {

            if( !$firstrow ) {

                ?><span class="fleft">&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</span><?

            }

            ?>

            <a class="fleft link_crs_<? echo $view_key;

                if( $view_key == $viewname ) echo ' active'; ?>"><?=$view_head[$view_key]['headnav']?></a>

            <?

            $firstrow = false;

        }

        ?>

        <div class="fclear"></div>

    </div>

    <?

}

?>

<div id="main">
<div class="crs_left">

	<?

	include 'courseware/inc/views/' . $viewname . '.php';

	?>

</div>

<div class="crs_right">

	<? include 'courseware/inc/modules/sidenav.php'; ?>

</div>

<div class="fclear"></div>

</div>