<?
if ( is_object($recover)) {
	 ?><div class="message"><?=$recover->message?></div><?
}
?>
<form id="recover_form" class="side" action="index.php" method="post">
    <input type="hidden" name="view" value="recover">
    <input type="hidden" name="update_type" value="reset pwd">
    <label class="fleft">Email:</label>
    <input id="email" name="email" class="fright" type="email" value="<?
        if( $_COOKIE["tig-vl_username"] ) {
            echo $_COOKIE["tig-vl_username"];
        } elseif( $_POST['user']) {
            echo $_POST['user'];
        } else {
            echo 'Email Address';
        }
        ?>" required>
    <div class="fclear">
        <input id="btn_recover" class="fright top_15" type="submit" name="process" value="RESET PASSWORD">
        <a class="link_login fright top_15">Return to Login Screen</a>
    </div>
</form>