<link href="courseware/css/course.css" rel="stylesheet" type="text/css">

<link href="courseware/css/skin.css" rel="stylesheet" type="text/css">

<div id="body_fade"></div>

<?

if( $_SESSION['page'] == 'editor' ) {

	?><div id="crs_edithead">COURSE/QUIZ EDITOR</div><?

} else {

	if( $req['id'] ) $_SESSION['course'] = $req['id'];

	include 'inc/modules/account.php';

}

?>


<div class="contain">
<div class="image-banner">
    <div onclick="location.href='/sharetopic';" style="cursor: pointer;width:200px;height:70px;float:left"></div>
        
    </div>