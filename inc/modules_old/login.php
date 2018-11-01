<?
if ( is_object($login)) {
	 ?><div class="message"><?=$login->message?></div><?
}
?>
<form id="login_form" class="side" action="index.php" method="post">
    <input name="user" type="text" value="<?
        if( $_COOKIE["tig-vl_username"] ) {
            echo $_COOKIE["tig-vl_username"];
        } elseif( $_POST['user']) {
            echo $_POST['user'];
        } else {
            echo 'Username';
        }
        ?>">
    <input class="top_15" name="fakepwd" id="fakepwd" value="Password">
    <input class="top_15 hidden" name="pwd" id="pwd" type="password">
    <div class="top_15"><a class="link_recover">Forgot Password?</a><input id="btn_login" class="fright" type="submit" name="process" value="LOGIN"></div>
</form>