<div class="acc_head">WATCHLIST</div>
<?
$mycontent = new MyContent();
if( $mycontent->get_watchlist() ) {
	$firstrow = true;
	foreach( $mycontent->watchlist as $row ) {
		?>
		<a href="?page=album&id=<?php echo $row['vID']; ?>">
            <div class="a-video">
                <img src="<?php echo $row['vThumbnail'];?>" alt="">
                <div class="v-title">
                    <?php echo $row['vTitle'];?>
                </div>
            </div>
        </a>
		<?
	}
} else {
	?><img src="img/watchlist_default.jpg"><?	
}
?>