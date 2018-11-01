<div class="login"><div class="login_box">
<img src="img/logo_full.png"><br>&nbsp;<br>
<?
if ( is_object($login)) {
	 ?><div class="message"><?=$login->message?></div><?
}
?>
<div class="narrow">
	<form id="login_form" class="side" action="index.php" method="post">
		<label class="fleft">Username:</label>
		<input name="user" class="fright" type="text" value="" placeholder="Username">
		<div class="fclear top_15">&nbsp;</div>
		<label class="fleft">Password:</label>
		<input class="fright" name="pwd" id="pwd" type="password" placeholder="Password">
		<div class="fclear">
			<a class="link_recover fleft top_15">Forgot Password?</a>
			<input id="btn_login" class="fright top_15" type="submit" name="process" value="LOGIN">
		</div>
	</form>
</div>
<div class="panel_break"></div>
<div class="browse_head top_15">DON'T HAVE AN ACCOUNT?</div>
<input type="button" id="btn_register" class="link_register" value="REGISTER NOW"><br>