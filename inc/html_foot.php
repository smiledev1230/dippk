<a href="#" class="backtotop">Back to Top</a>
<?
/* DEBUG section */
if( $_SESSION['debug'] && ( $platform->environment == 'development' || $_SESSION['usr_username'] == 'clarkin' ) ) {
	?><div id="debug"><?
	foreach( $_SESSION['debug'] as $key => $var ) {
		echo '[',$key,']<br />',$var,'<br /><br />';
	}
	?></div><?
}
?>

<script src="js/jquery.validate.min.js"></script>
<script src="js/froogaloop.min.js"></script>
<script src="js/jquery.mCustomScrollbar.min.js"></script>
<script src="js/jquery.lightbox-0.5.min.js"></script>
<script type="text/javascript" src="http://malsup.github.com/chili-1.7.pack.js"></script>
<script type="text/javascript" src="http://malsup.github.com/jquery.cycle.all.js"></script>
<script type="text/javascript" src="http://malsup.github.com/jquery.easing.1.3.js"></script>
<script src="js/main.js"></script>
<script src="js/imagepanner.js"></script>
<?
if( in_array( $_SESSION['page'], $course_pages ) ) {
	?><script type="text/javascript" src="courseware/js/course.js"></script><?	
}
?>
</body>
</html>