<div class="crs_help_title">Need Help?</div>

<div>Fill out the form below for assistance.</div>

<form id="crs_help_form">

	<input type="text" class="top_ad" name="name" value="<? echo $acc->profile ? $acc->profile['First_Name'].' '.$acc->profile['Last_Name']: 'name'; ?>" required>

    <input type="email" class="top_ad" name="email" value="<? echo $acc->profile ? $acc->profile['Email']: 'email'; ?>" required>

    <textarea  name="msg" class="top_ad">your message</textarea>

    <input type="submit" class="crs_button fright top_ad helpbtn"  value="SUBMIT">

</form>