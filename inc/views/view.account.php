<?php if (isset($_SESSION['profile_error'])):?>
    <div class="error-message" style="margin-top:10px;">
        <?php echo $_SESSION['profile_error']; 
            if(time() > ($_SESSION['profile_update_set_time']+10)){
                unset($_SESSION['profile_error']);

            }
            
        ?>
    </div>
<?php endif;?>
<?php if (isset($_SESSION['profile_success'])):?>
    <div class="success-message" style="margin-top:10px;">
        <?php echo $_SESSION['profile_success']; 
            if (time() > ($_SESSION['profile_update_set_time']+10)) {
                unset($_SESSION['profile_success']);
            }
         ?>
    </div>
<?php endif;?>
<div id="acc_info" class="fleft">
	<div class="acc_head">INFORMATION OVERVIEW</div>
    <div class="acc_subhead margin_left"><?=strtoupper($acc->profile['First_Name'].' '.$acc->profile['Last_Name'])?></div>
    <div class="margin_left"><?=$acc->profile['Email']?></div>
    <?
    if( $acc->profile['Phone'] ) { ?><div class="margin_left"><?=$acc->profile['Phone']?></div><? }
	if( $acc->profile['Address'] ) { ?><div class="margin_left"><?=$acc->profile['Address']?></div><? }
    if( $acc->profile['Address_2'] ) { ?><div class="margin_left"><?=$acc->profile['Address_2']?></div><? }
	?>
    <?php 
        $address_array = array();
        if(!empty($acc->profile['City'])){
            $address_array[] = $acc->profile['City'];
        }

        if(!empty($acc->profile['State']) || !empty($acc->profile['Postal_Code'])){
            $address_array[] = $acc->profile['State'].' '.$acc->profile['Postal_Code'];
        }  
        $address = implode(', ',$address_array);
    ?>
    <div class="margin_left"><?=$address;?></div>
    <div class="margin_left"><?=$acc->profile['Country']?></div>
</div>
<div id="acc_pic" class="fleft">
	<div class="acc_head">PROFILE PICTURE</div>
	<img class="upload_img fleft" src="<?=$profile_pic?>">
    <div class="upload_section fleft">
    	<div>Change your profile picture by selecting a file below.</div>
        <?
		if ( is_object($ch_img)) {
			?><span class="message"><?=$ch_img->message?></span><?
		}
		?>
		<form class="acc" enctype="multipart/form-data" method="post" action="index.php">
		<div class="customfile-container top_ad"><label>Image File:</label><input type="file" id="image" name="image" class="fright"></div>
        <div class="fright">max file size: 1MB</div><div class="fclear"></div>
		<input class="btn fright top_space" type="submit" name="process" value="UPDATE" style="width:100%">
		<input type="hidden" name="update_type" value="upload img">
		<input type="hidden" name="page" value="account">
		</form>
    </div>
</div>
<div id="acc_prof" class="fleft">
	<div class="acc_head">UPDATE ACCOUNT</div>
	<?
	if ( is_object($ch_profile)) {
		?><span class="message"><?=$ch_profile->message?></span><?
	}
	?>
	<form class="acc" id="profile_form" method="post" action="index.php">
    	<label>First Name</label><input name="fname" type="text" value="<?=$acc->profile['First_Name']?>" required>
        <label>Last Name</label><input name="lname" type="text" value="<?=$acc->profile['Last_Name']?>" required>
        <label>Email</label><input name="email" type="email" value="<?=$acc->profile['Email']?>" required>
		<label>Phone</label><input name="phone" type="text" value="<?=$acc->profile['Phone']?>">
        <label>Address 1</label><input name="address" type="text" value="<?=$acc->profile['Address']?>">
        <label>Address 2</label><input name="address_2" type="text" value="<?=$acc->profile['Address_2']?>">
        <label>City</label><input name="city" type="text" value="<?=$acc->profile['City']?>" required>
        <label>State</label><input name="state" type="text" value="<?=$acc->profile['State']?>">
        <label>Zip Code</label><input name="zip" type="text" value="<?=$acc->profile['Postal_Code']?>">
        <input class="btn top_ad" type="submit" name="process" value="UPDATE" style="width:340px">
		<input type="hidden" name="update_type" value="account info">
		<input type="hidden" name="page" value="account">
	</form>
</div>
<div id="acc_pwd" class="fleft">
	<div class="acc_head">CHANGE PASSWORD</div>
    <?
    if ( is_object($ch_pwd)) {
        ?><span class="message"><?=$ch_pwd->message?></span><?
    }
    ?>
    <form class="acc" id="pwd_form" method="post" action="index.php">
        <label>Current Password</label><input name="pwd" type="password" required>
        <label>New Password</label><input name="pwd_new" id="pwd_new" type="password" required>
        <label>Confirm Password</label><input name="pwd_cnew" id="pwd_cnew" type="password" required>
        <input class="btn top_ad" type="submit" name="process" class="fright" value="UPDATE" style="width:340px">
        <input type="hidden" name="update_type" value="change pwd">
        <input type="hidden" name="page" value="account">
    </form>
</div>