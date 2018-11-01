<div class="acc_head">SECTION CONTRIBUTORS<div id="backtolist" class="fright hidden">BACK TO LIST</div></div>
<?
if( $_SESSION['usr_name'] == 'Chris Larkin' || $_SESSION['demo'] ) {

?>
<div id="contributors_all">
<div class="contributor_box_lg fleft">
    <div class="contributor_lg fleft">
    	<img src="img/user/9.jpg">
        <div class="follow">UNFOLLOW</div>
        <div class="follow_check"></div></div>
    <div class="contributor_detail fleft">
        <div class="name">Suyadi</div>
        <div class="division">Human Resources</div>
        <div class="division">Team Leader</div>
        <div class="comment">Active Since:<br>June 2012</div>
    </div>
</div>
<div class="contributor_team">
<div class="cspace fleft"></div>
<div class="contributor_box_sm fleft">
    <div class="contributor_sm fleft">
    	<img src="img/user/demo1.gif">
        <div class="follow">FOLLOW</div>
        <div class="follow_check hidden"></div></div>
    <div class="contributor_detail fleft">
        <div class="name">Demo User 1</div>
    	<div class="division">Human Resources</div>
    	<div class="comment">Active Since:<br>August 2013</div>
    </div>
</div>
<div class="cspace fleft"></div>
<div class="contributor_box_sm fleft">
    <div class="contributor_sm fleft">
    	<img src="img/user/demo2.gif">
        <div class="follow">FOLLOW</div>
        <div class="follow_check hidden"></div></div>
    <div class="contributor_detail fleft">
        <div class="name">Demo User 2</div>
    	<div class="division">Human Resources</div>
   		<div class="comment">Active Since:<br>August 2013</div>
    </div>
</div>
<div class="cspace fleft"></div>
<div class="contributor_box_sm top_ad fleft">
    <div class="contributor_sm fleft">
    	<img src="img/user/demo3.gif">
        <div class="follow">FOLLOW</div>
        <div class="follow_check hidden"></div></div>
    <div class="contributor_detail fleft">
        <div class="name">Demo User 3</div>
    	<div class="division">Human Resources</div>
    	<div class="comment">Active Since:<br>August 2013</div>
   	</div>
</div>
<div class="cspace fleft"></div>
<div class="contributor_box_sm top_ad fleft">
    <div class="contributor_sm fleft">
    	<img src="img/user/demo4.gif">
        <div class="follow">FOLLOW</div>
        <div class="follow_check hidden"></div></div>
    <div class="contributor_detail fleft">
        <div class="name">Demo User 4</div>
    	<div class="division">Human Resources</div>
    	<div class="comment">Active Since:<br>August 2013</div>
    </div>
</div>
</div>
<div class="fclear"></div>
<?
	
} else {

$contributors = new Contributors();
foreach( $contributors->profiles as $c ) {
	?>
    <div class="contributor_box fleft">
    	<div class="contributor"><img src="img/user/<?=$c['pic']?>"></div>
        <div class="name"></div>
        <div class="division"></div>
        <div class="comment">Active Since:<br><?=$c['activeDate']?></div>
    </div>
    <?
}

}
?>
<div class="panel_break"></div>
<div class="ctitle">WOULD YOU LIKE TO BECOME A CONTRIBUTOR?</div>
<div class="ctext">If you would like to become a contributor to this site then please <a class="link_contact">contact your administrator</a> to begin the process.</div>
</div>
<div id="contributors_single" class="hidden">
<? include 'inc/modules/contributor-demo.php'; ?>
</div>