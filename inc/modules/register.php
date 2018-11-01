<div class="login"><div class="reg_box">
<img src="img/logo_full.png"><br>&nbsp;<br>
<?
if ( is_object($register)) {
	 ?><div class="message"><?=$register->get_message()?></div><?
}
?>
<div>
	<form class="acc" id="reg_form" method="post" action="index.php">
		<input type="hidden" name="role" value="<?php echo $_GET['type'];?>">
		<div class="fleft"><label>Username</label><input id="username" name="username" type="text" tabindex="1" required></div>
		<div class="fright"><label>Plastipak Site</label><input id="plastipak_site" name="plastipak_site" type="text" tabindex="2" required></div>

		<div class="fclear"></div>
		<div class="fleft"><label>First Name</label><input id="fname" name="fname" type="text" tabindex="3" required></div>
		<div class="fright"><label>Last Name</label><input id="lname" name="lname" type="text" tabindex="4" required></div>

		<div class="fclear"></div>
		<div class="fleft"><label>Email</label><input id="email" name="email" type="email" tabindex="5" required></div>
		<div class="fright"><label>Confirm Email</label><input id="conf_email" name="conf_email" type="email" tabindex="6" required></div>

		<div class="fclear"></div>
		<div class="fleft"><label>Password</label><input id="pwd" name="pwd" type="password" tabindex="7" required></div>
		<div class="fright"><label>Confirm Password</label><input id="conf_pwd" name="conf_pwd" type="password" tabindex="8" required></div>
		
		
        
		<div class="fclear"></div>
		<div class="fright"><input name="agree" class="check" type="checkbox" tabindex="15" required><label class="long">I agree to the <a class="link_terms">Terms and Conditions</a></label>
		<div class="fclear"></div>
		<input class="btn fright" type="submit" tabindex="16" name="process" class="fright" value="REGISTER">
	</form>
    <div class="fclear"></div>
</div>





