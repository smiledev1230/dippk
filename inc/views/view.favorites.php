<div class="acc_head">MY FAVORITES</div>
<?
$mycontent = new MyContent();
if( $mycontent->get_favorites() ) {
	?>
    <div class="content_table">
		<?
        foreach( $mycontent->favorites as $row ):?>
        <a href="?page=album&id=<?php echo $row['vID']; ?>">
            <div class="a-video">
                <img src="<?php echo $row['vThumbnail'];?>" alt="">
                <div class="v-title">
                    <?php echo $row['vTitle'];?>
                </div>
            </div>
        </a>
        <?php endforeach;?>
    </div><!-- content_table -->
    <?php
    } else {
        ?><img src="img/favorites_default.jpg"><?	
    }
    ?>
    
<div class="fclear"></div>