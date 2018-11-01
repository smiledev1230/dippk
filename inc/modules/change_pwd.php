<div class="login"><div class="login_box">
<img src="img/logo_full.png"><br>&nbsp;<br>
<?
if ( is_object($ch_pwd)) {
	?><span class="message"><?=$ch_pwd->message?></span><?
} else {
	?><span class="message">Please change your password.</span><?	
}
?>
<form class="acc" id="pwd_form" method="post" action="index.php">
	<input name="user" id="user" type="hidden" value="<?=$_POST['user']?>">
	<input name="pwd" id="pwd" type="hidden" value="<?=$_POST['pwd']?>">
	<label>New Password</label><input name="pwd_new" id="pwd_new" type="password" required>
	<label>Confirm Password</label><input name="pwd_cnew" id="pwd_cnew" type="password" required>
	<input class="btn fright top_ad" type="submit" name="process" value="CHANGE PASSWORD">
	<input type="hidden" name="page" value="login">
</form>