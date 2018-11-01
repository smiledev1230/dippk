<div id="body_fade"></div>
<? include 'inc/modules/account.php'; ?>

<div class="contain">
    <div class="image-banner">
    <div onclick="location.href='/sharetopic';" style="cursor: pointer;width:200px;height:70px;float:left"></div>
        <!-- <img src="img/banner.jpg" alt="" style="width:100%;margin-bottom:-5px;"> -->
        <?php if(isset($_GET['page']) && $_GET['page']=='help'):?>
            <div class="banner-title help-banner-title"> Need Help?</div>
            <div class="sub-title">You've come to the right place. Here you can read our FAQs or send us a message.</div>
        <?php elseif(isset($_GET['view']) && $_GET['view']=='courses'):?>
            <div class="banner-title"> Available Topics</div>
       <?php elseif(isset($_GET['view']) && $_GET['view']=='contributors'):?>
            <div class="banner-title"> Contributors</div>
        <?php endif;?>
    </div>
    <div id="browse_panel" class="invisible"><? include 'inc/modules/browse.php'; ?></div>