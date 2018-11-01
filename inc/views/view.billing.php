<?
$billing_options = array( 'Visa', 'MasterCard', 'American Express', 'Discover' );
?>
<div class="acc_head">BILLING</div>
<div class="half_content fleft">
	<div id="subscription">
        <div class="subhead">Subscription Info</div>
        <div class="line fclear"><div class="subscribe_label fleft top_space">Subscription Type:</div><div class="subscribe_value fright top_space">6-Month Membership</div></div>
        <div class="line fclear"><div class="subscribe_label fleft">Expiration Date:</div><div class="subscribe_value fright"><? echo date( 'F j, Y', strtotime('+5 months') ); ?></div></div>
        <div class="line fclear"><div class="subscribe_label fleft">Next Bill Date:</div><div class="subscribe_value fright"><? echo date( 'F j, Y', strtotime('+4 days') ); ?></div></div>
        <div id="payment_active">
            <div class="line fclear"><div class="subscribe_label">Active Payment Method:</div></div>
            <div class="payment_method top_15">
                <div class="payment_card fleft"><div class="pay_visa fleft"></div>************0000 Exp: 12/2014</div>
                <div class="comment_option fright"><a class="js_edit">Edit</a></div>
                <div class="fclear"></div></div>
        </div>
        <div  id="payment_other">
        	<div class="line fclear"><div class="subscribe_label top_ad">Other Payment Methods:</div></div>
            <div class="payment_method top_15">
                <div class="payment_card fleft"><div class="pay_mc fleft"></div>************0001 <span class="red">Exp: 09/2013</span></div>
                <div class="comment_option fright"><a class="js_edit" href="#">Edit</a> | <a class="js_delete">Delete</a></div>
                <div class="comment_option js_active_container fright"><a class="js_active">Make Active</a></div>
                <div class="fclear"></div></div>
            <div class="payment_method top_15">
                <div class="payment_card fleft"><div class="pay_paypal fleft"></div>paypal_name@email.com</div>
                <div class="comment_option fright"><a class="js_edit" href="#">Edit</a> | <a class="js_delete">Delete</a></div>
                <div class="comment_option js_active_container fright"><a class="js_active">Make Active</a></div>
                <div class="fclear"></div></div>
        </div>
    </div>
</div>
<div class="half_content fright">
	<div class="subhead">Add Payment Method</div>
    <div class="payment_method top_15">
    	<form class="payment">
        	<label>Type</label><select>
					<? foreach( $billing_options as $option ) {
                        ?><option><?=$option?></option><?
                    } ?>
                </select><br>
            <label>Card Number</label><input type="password" name="cardnumber">
            <label>Expiration</label><select name="month">
            		<? for( $i = 1; $i < 13 ; $i++ ) {
						?><option><?=str_pad($i,2,'0',STR_PAD_LEFT)?></option><?
					} ?>
                </select> / <select name="year">
            		<? for( $i = 2013; $i < 2024 ; $i++ ) {
						?><option><?=$i?></option><?
					} ?>
                </select>
            <label>Cardholder</label><input type="text" name="cardholder" value="<?=$acc->profile['First_Name'].' '.$acc->profile['Last_Name']?>">
            <label>Billing Address</label>
             	<input name="address" type="text" class="wide_input" value="<?=$acc->profile['Address']?>" required>
                <input name="address_2" type="text" class="wide_input" value="<?=$acc->profile['Address_2']?>">
            <label>City</label><input name="city" type="text" value="<?=$acc->profile['City']?>" required>
            <label>State/Province</label><input name="state" type="text" value="<?=$acc->profile['State']?>" required>
            <label>Postal Code</label><input name="zip" type="text" value="<?=$acc->profile['Postal_Code']?>" required>
            <input type="button" id="btn_payment" class="fright" name="process" value="ADD PAYMENT METHOD">
        </form>
        <div class="fclear"></div>
    </div>
    
</div>