<div class="acc_head">RECENTLY VIEWED</div>
<?
$mycontent = new MyContent();
if( $mycontent->get_history() ) {
	?>
    <div class="content_table">
    	<?php foreach( $mycontent->history as $row ):?>
        <a href="?page=album&id=<?php echo $row['vID']; ?>">
            <div class="a-video">
                <img src="<?php echo $row['vThumbnail'];?>" alt="">
                <div class="v-title">
                    <?php echo $row['vTitle'];?>
                </div>
                <div class="v-watched-time">
                    <strong> Watched on <em> <?php echo date('F d, Y \a\t h:i A',strtotime($row['Date_Watched'])) ;?></em> </strong>
                </div>
            </div>
        </a>
        <?php endforeach;?>
    </div><!-- content_table -->
    <?php
    } else {
        ?><img src="img/history_default.jpg"><?	
    }
    ?>
<div class="fclear"></div>