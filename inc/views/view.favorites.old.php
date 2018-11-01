<div class="acc_head">MY FAVORITES</div>
<?
$mycontent = new MyContent();
if( $mycontent->get_favorites() ) {
	?>
    <div class="content_table">
    	<div class="line ctable_head">
        	<div class="fleft">SECTION</div><div class="fleft">FILE TYPE</div><div class="fleft">FILE NAME</div><div class="ctable_del fleft"></div>
        </div>
        <div class="fclear"></div>
		<?
        $firstrow = true; $i = 1;
        foreach( $mycontent->favorites as $favorite ) {
            ?>
            <div id="doc<?=$favorite['dID']?>" class="line ctable_row link_mycontent <? if( $i&1 ) echo ' zebra'; if( $firstrow ) echo ' top_15'; ?>">
                <div class="fleft"><?=$favorite['sTitle']?></div>
                <div class="fleft"><?=$favorite['cTitle']?></div>
                <div class="fleft"><?=$favorite['dTitle']?></div>
                <div class="ctable_del fleft"><a id="del<?=$favorite['dID']?>" class="fav_delete fright">X</a></div>
                <div class="fclear"></div>
            </div>
            <?
            $i++;
            $firstrow = false;
        }
    ?>
    </div><!-- content_table -->
    <?php
    } else {
        ?><img src="img/favorites_default.jpg"><?	
    }
    ?>
    
<div class="fclear"></div>