<?php 
    $gdrive = new Gdrive();
?>
<div class="google-connect-container">
    <?php if(empty($gdrive->access_token)):?>
        <a class="button" href="oauthgoogle.php">Connect Google Drive</a>
    <?php else:?>
        Already Connected to the Google Drive.
    <?php endif;?>
</div>
