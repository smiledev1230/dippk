<div class="acc_head">CONTRIBUTORS<div id="backtolist" class="fright hidden">BACK TO LIST</div></div>
<?
/* if( $_SESSION['usr_name'] == 'Chris Larkin' || $_SESSION['demo'] ) { */
if(false) {

?>
<div id="contributors_all">
<div class="contributor_box_lg fleft">
    <div class="contributor_lg fleft">
    	<img src="img/user/9.jpg">
        <div class="follow">UNFOLLOW</div>
        <div class="follow_check"></div>
    </div>
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
            <div class="follow_check hidden"></div>
        </div>
        <div class="contributor_detail fleft">
            <div class="name">Demo User 1</div>
            <div class="division">Human Resources</div>
            <div class="comment">Active Since:<br>August 2013</div>
        </div>
    </div>
    <div class="cspace fleft"></div>
    <div class="contributor_box_sm  fleft">
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
    <div class="cspace fleft"></div>
<div class="contributor_box_sm  fleft">
    <div class="contributor_sm fleft">
    	<img src="img/user/demo7.gif">
        <div class="follow">FOLLOW</div>
        <div class="follow_check hidden"></div></div>
    <div class="contributor_detail fleft">
        <div class="name">Demo User 7</div>
    	<div class="division">Information Technology</div>
    	<div class="comment">Active Since:<br>August 2013</div>
    </div>
</div>
<div class="cspace fleft"></div>
<div class="contributor_box_sm  fleft">
    <div class="contributor_sm fleft">
    	<img src="img/user/demo1.gif">
        <div class="follow">FOLLOW</div>
        <div class="follow_check hidden"></div></div>
    <div class="contributor_detail fleft">
        <div class="name">Demo User 8</div>
    	<div class="division">Information Technology</div>
    	<div class="comment">Active Since:<br>August 2013</div>
    </div>
</div>
</div>
<div class="fclear"></div>
<div class="panel_break"></div>
<div class="contributor_box_lg fleft ">
    <div class="contributor_lg fleft">
    	<img src="img/user/7.jpg">
        <div class="follow">UNFOLLOW</div>
        <div class="follow_check"></div></div>
    <div class="contributor_detail fleft">
        <div class="name">John Smith</div>
    	<div class="division">Information Technology</div>
    	<div class="division">Team Leader</div>
        <div class="comment">Active Since:<br>May 2013</div>
    </div>
</div>
<div class="contributor_team ">
<div class="cspace fleft"></div>
<div class="contributor_box_sm fleft">
    <div class="contributor_sm fleft">
    	<img src="img/user/demo5.gif">
        <div class="follow">FOLLOW</div>
        <div class="follow_check hidden"></div></div>
    <div class="contributor_detail fleft">
        <div class="name">Demo User 5</div>
    	<div class="division">Information Technology</div>
    	<div class="comment">Active Since:<br>August 2013</div>
    </div>
</div>
<div class="cspace fleft"></div>
<div class="contributor_box_sm fleft">
    <div class="contributor_sm fleft">
    	<img src="img/user/demo6.gif">
        <div class="follow">FOLLOW</div>
        <div class="follow_check hidden"></div></div>
    <div class="contributor_detail fleft">
        <div class="name">Demo User 6</div>
    	<div class="division">Information Technology</div>
    	<div class="comment">Active Since:<br>August 2013</div>
    </div>
</div>
<div class="cspace fleft"></div>
<div class="contributor_box_sm  fleft">
    <div class="contributor_sm fleft">
    	<img src="img/user/demo7.gif">
        <div class="follow">FOLLOW</div>
        <div class="follow_check hidden"></div></div>
    <div class="contributor_detail fleft">
        <div class="name">Demo User 7</div>
    	<div class="division">Information Technology</div>
    	<div class="comment">Active Since:<br>August 2013</div>
    </div>
</div>
<div class="cspace fleft"></div>
<div class="contributor_box_sm  fleft">
    <div class="contributor_sm fleft">
    	<img src="img/user/demo1.gif">
        <div class="follow">FOLLOW</div>
        <div class="follow_check hidden"></div></div>
    <div class="contributor_detail fleft">
        <div class="name">Demo User 8</div>
    	<div class="division">Information Technology</div>
    	<div class="comment">Active Since:<br>August 2013</div>
    </div>
</div>
</div>
<div class="fclear"></div>
<?
	
} else {

$contributors = new Contributors();
?>
<div id="contributors_all">
<?php 
foreach( $contributors->teams as $c ) {
	?>
            <a href="?view=contributor&id=<?=$c['leader']['ID'];?>">
                <div class="contributor_box_lg fleft">
                    <div class="contributor_lg fleft">
                        <?php 
                            $user_pic = 'img/user/' . $c['leader']['ID'] . '.' . $c['leader']['Image_Ext'];
                            $profile_pic = ( file_exists( $user_pic ) ) ? $user_pic : 'img/avatar_lg.png';
                        ?>
                        <img src="<?php echo $profile_pic;?>">
                        <div class="follow">UNFOLLOW</div>
                        <div class="follow_check"></div>
                    </div>
                    <div class="contributor_detail fleft">
                        <div class="name"><?php echo $c['leader']['First_Name'];?> <?php echo $c['leader']['Last_Name'];?> </div>
                        <div class="division"><?php echo $c['name'];?> </div>
                        <div class="division">Team Leader</div>
                        <div class="comment">Active Since:<br><?php echo date('F Y',strtotime($c['leader']['created_at'])); ?></div>
                    </div>
                </div>
            </a>
            <?php if(isset($c['contributors'])):?>
                <?php foreach($c['contributors'] as $contributor):?>
                    <?php 
                        $user_pic = 'img/user/' . $contributor['ID'] . '.' . $contributor['Image_Ext'];
                        $profile_pic = ( file_exists( $user_pic ) ) ? $user_pic : 'img/avatar_lg.png';
                    ?>
                    <a href="?view=contributor&id=<?=$contributor['ID'];?>">
                        <div class="contributor_box_lg fleft">
                            <div class="contributor_lg fleft">
                                <img src="<?php echo $profile_pic;?>">
                                <div class="follow">FOLLOW</div>
                                <div class="follow_check hidden"></div>
                            </div>
                            <div class="contributor_detail fleft">
                                <div class="name"><?php echo $contributor['First_Name'];?> <?php echo $contributor['Last_Name'];?></div>
                                <div class="division"><?php echo $c['name'];?></div>
                                <div class="comment">Active Since:<br><?php echo date('F Y',strtotime($contributor['created_at'])); ?></div>
                            </div>
                        </div>
                    </a>
                <?php endforeach;?>
            <?php endif;?>
            <div class="fclear"></div>
            <div class="panel_break"></div>
        
    <?
}
?>
</div><!-- contributors_all -->
<?php 
}
?>
<?php if(!$_SESSION['lv_contributor']):?>
<div class="ctitle">WOULD YOU LIKE TO BECOME A CONTRIBUTOR?</div>
<div class="ctext">If you would like to become a contributor to this site then please <a class="link_contact">contact your administrator</a> to begin the process.</div>
<?php endif;?>
<div id="contributors_single" class="hidden">
<? include 'inc/modules/contributor-demo.php'; ?>
</div>