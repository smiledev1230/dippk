<!-- <div id="feature"><img class="link_forum" src="img/help_banner.png"></div> -->
<?
$support = new Support();
$faq = $support->get_faq();
$tips = $support->get_tips();
//$resources = $support->get_resources();
?>
<div id="main"><div class="wide_content">
	<div class="wide_half fleft">
        <div class="acc_head">FAQ</div>
        <div class="acc_subhead">Explore our Frequently Asked Questions to learn more about ShareTopic.</div>
        <div id="faq" class="top_ad">
        	<?
			foreach( $faq as $row ) {
				?>
                <div class="faq_question"><?=$row['question']?></div>
				<div class="faq_answer"><?=$row['answer']?></div>
				<?
			}
			?>
        </div>
    </div>
    <div class="wide_half fright">
    	<div class="acc_head">CONTACT US</div>
        <div class="acc_subhead">Please fill out the form below.</div>
        <form id="contact_form" class="acc" action="index.php" method="post">
        	<label>Name</label><input name="name" type="text" value="<?=$acc->profile['First_Name'].' '.$acc->profile['Last_Name']?>" required>
            <label>Email</label><input name="email" type="email" value="<?=$acc->profile['Email']?>" required>
            <label>Phone</label><input name="phone" type="text" value="<?=$acc->profile['Phone']?>">
            <textarea name="message" required placeholder="Please explain how we can help you."></textarea>
            <input name="process" type="submit" class="btn fright" value="CONTACT">
        </form>
    </div>
    <div class="fclear"></div>
    <div class="acc_head" style="display:none">TIPS</div>
    <div id="tips">
        <?
		$firstrow = true;
        foreach( $tips as $row ) {
            ?>
            <div class="tip_title<? if( $firstrow ) echo ' top_15'; ?>"><?=strtoupper($row['title'])?></div>
            <div class="tip_detail"><?=$row['tip']?></div>
            <?
			$firstrow = false;
        }
        ?>
    </div>
    <?php /*?><div class="acc_head">RESOURCES</div>
    <div id="resources">
        <table class="resources" cellpadding="0" cellspacing="0" border="0">
            <?
            $cell = 1;
            foreach( $resources as $row ) {
                if( $cell == 1 ) { ?><tr><? }
                ?><td><a href="<?=$row['target']?>"><?=$row['name']?></a></td><?
                if( $cell == 4 ) {
                    ?></tr><?
                    $cell = 1;
                } else {
                    $cell++;
                }
            }
            ?>
        </table>
    </div><?php */?><br>
<br>

</div></div>