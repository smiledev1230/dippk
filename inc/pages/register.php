<div id="main">
	<div id="browse" class="fleft">
    	<img src="img/ads/ad-full_180x290.jpg">
        <img src="img/ads/ad-full_180x290.jpg" class="top_ad">
    </div>
	<div id="content_panel" class="fleft">
    	<div class="acc_head">ACCOUNT INFORMATION</div>
        <div class="acc_subhead">please fill out the form below to register your account</div>
        <?
		if ( is_object($reg)) {
			?><span class="message"><?=$reg->get_message()?></span><?
		}
		?>
        <form class="acc" id="reg_form" method="post" action="index.php">
        	<div class="fleft"><label>Username</label><br><input name="username" type="text" minlength="6" required></div>
            <div class="fright"><label>Email</label><br><input id="email" name="email" type="email" required></div>
            <div class="fclear"></div>
            <div class="fleft"><label>First Name</label><br><input name="fname" type="text" required></div>
            <div class="fright"><label>Confirm Email</label><br><input id="conf_email" name="conf_email" type="email" required></div>
            <div class="fclear"></div>
            <div class="fleft"><label>Last Name</label><br><input name="lname" type="text" required></div>
            <div class="fright"><label>Password</label><br><input id="pwd" name="pwd" type="password" required></div>
            <div class="fclear"></div>
            <div class="fleft"><label>Phone Number</label><br><input name="phone" type="text"></div>
            <div class="fright"><label>Confirm Password</label><br><input id="conf_pwd" name="conf_pwd" type="password" required></div>
            <div class="fclear"></div>
            <div class="fright"><input name="agree" class="check" type="checkbox"><label>I agree to the Terms and Conditions</label>
            <div class="fclear"></div>
            <input class="btn fright" type="submit" name="process" class="fright" value="REGISTER">
        </form>
    </div>
    <div class="fclear"></div>
</div>