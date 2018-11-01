<?php 
    $id = $_GET['id'];
    if(!is_numeric){
        header("Location:?view=contributors");
        exit;

    }
    $cont = new Contributors();
    $entity = $cont->get_contributor_details($id);
    if(!$entity){
        header("Location:?view=contributors");
        exit;
    }

    $courses = $cont->get_contributor_courses($id);
    $powepoints = $cont->get_contributor_powerpoints($id);
    $videos = $cont->get_contributor_videos($id);

?>
<div class="contributor_box_lg half_content fleft"></div>
<div class="fclear"></div>
<div class="acc_head">UPLOAD HISTORY</div>
<div class="tab_bar">
    <div id="tab1" class="tab active">TOPIC</div>
    <div class="tab_spacer">|</div>
    <div id="tab2" class="tab">Presentations</div>
    <div class="tab_spacer">|</div>
    <div id="tab3" class="tab">Documents</div>
    <div class="tab_spacer">|</div>
    <div id="tab6" class="tab">Video</div>
    <div class="fclear"></div>
</div>
<div id="tab_content">
    <?php $i=1; foreach($courses as $course):?>
        <div class="vid_preview fleft top_ad">
            <img src="courseware/courses/<?=$course['ID'];?>/<?=$course['image'];?>">
            <div class="browse_head2"><?=$course['title'];?></div>
        </div>
        <?php if($i % 3==0):?>
            <div class="fclear"></div>
        <?php else:?>
            <div class="fspace fleft"></div>
        <?php endif;?>
    <?php $i++; endforeach;?>
    <div class="fclear"></div>
</div>
<div id="tab1_data" class="tab_data">
	<?php $i=1; foreach ($courses as $course):?>
        <div class="vid_preview fleft top_ad">
            <img src="courseware/courses/<?=$course['ID'];?>/<?=$course['image'];?>">
            <div class="browse_head2"><?=$course['title'];?></div>
        </div>
        <?php if ($i % 3==0):?>
            <div class="fclear"></div>
        <?php else:?>
            <div class="fspace fleft"></div>
        <?php endif;?>
    <?php $i++; endforeach;?>
    <div class="fclear"></div>
</div>
<div id="tab2_data" class="tab_data">
	<div class="doc link_content fleft top_ad">
        <img src="content/presentations/company/thumbs/Test_Presentation_1.png">
        <div class="mspowerpoint small"></div>
        <div id="fav95" class="btn_fav"><div class="tt_img hidden"></div></div>
    	<div class="subhead"><a class="link_content_direct">PRESENTATION 1</a></div>
    	<div class="doc-path hidden">content/presentations/company/Test_Presentation_1.pptx</div>
	</div>
    <div class="fspace_wide fleft"></div>
    <div class="doc link_content fleft top_ad">
    	<img src="content/presentations/company/thumbs/Test_Presentation_2.png">
    	<div class="mspowerpoint small"></div>
        <div id="fav96" class="btn_fav"><div class="tt_img hidden"></div></div>
    	<div class="subhead"><a class="link_content_direct">PRESENTATION 2</a></div>
    	<div class="doc-path hidden">content/presentations/company/Test_Presentation_2.pptx</div>
    </div>
    <div class="fspace_wide fleft"></div>
    <div class="doc link_content fleft top_ad">
    	<img src="content/presentations/company/thumbs/Test_Presentation_1.png">
    	<div class="mspowerpoint small"></div>
        <div id="fav97" class="btn_fav btn_active link_favorites"></div>
        <div class="subhead"><a class="link_content_direct">PRESENTATION 3</a></div>
    	<div class="doc-path hidden">content/presentations/company/Test_Presentation_1.pptx</div>
    </div>
    <div class="fspace_wide fleft"></div>
    <div class="doc link_content fleft top_ad">
    	<img src="content/presentations/company/thumbs/Test_Presentation_2.png">
    	<div class="mspowerpoint small"></div>
        <div id="fav98" class="btn_fav"><div class="tt_img hidden"></div></div>
        <div class="subhead"><a class="link_content_direct">PRESENTATION 4</a></div>
    	<div class="doc-path hidden">content/presentations/company/Test_Presentation_2.pptx</div>
    </div>
    <div class="fclear"></div><div class="fclear"></div>
</div>
<div id="tab3_data" class="tab_data">
	<div class="doc link_content fleft top_ad">
    	<img src="content/documents/performance_evaluations/thumbs/Test_Doc_1.png">
    	<div class="msword small"></div>
        <div id="fav1" class="btn_fav"><div class="tt_img hidden"></div></div>
        <div class="subhead"><a class="link_content_direct">WORD DOC 1</a></div>
    	<div class="doc-path hidden">content/documents/performance_evaluations/Test_Doc_1.docx</div>
    </div>
    <div class="fspace_wide fleft"></div>
    <div class="doc link_content fleft top_ad">
    	<img src="content/documents/performance_evaluations/thumbs/Test_Doc_2.png">
    	<div class="msword small"></div>
        <div id="fav2" class="btn_fav"><div class="tt_img hidden"></div></div>
        <div class="subhead"><a class="link_content_direct">WORD DOC 2</a></div>
    	<div class="doc-path hidden">content/documents/performance_evaluations/Test_Doc_2.docx</div>
    </div>
    <div class="fspace_wide fleft"></div>
    <div class="doc link_content fleft top_ad">
    	<img src="content/documents/performance_evaluations/thumbs/Test_PDF_1.png">
    	<div class="adreader small"></div>
        <div id="fav3" class="btn_fav"><div class="tt_img hidden"></div></div>
    	<div class="subhead"><a class="link_content_direct">PDF FILE 1</a></div>
    	<div class="doc-path hidden">content/documents/performance_evaluations/Test_PDF_1.pdf</div>
    </div>
    <div class="fspace_wide fleft"></div>
    <div class="doc link_content fleft top_ad">
    	<img src="content/documents/performance_evaluations/thumbs/Test_PDF_2.png">
    	<div class="adreader small"></div>
        <div id="fav4" class="btn_fav"><div class="tt_img hidden"></div></div>
        <div class="subhead"><a class="link_content_direct">PDF FILE 2</a></div>
    	<div class="doc-path hidden">content/documents/performance_evaluations/Test_PDF_2.pdf</div>
   </div>
    <div class="fclear"></div><div class="fclear"></div>
</div>
<div id="tab6_data" class="tab_data">
    <?php $i=1; foreach($videos as $video):?>
        <div id="vid-<?=$video['vID'];?>" class="vid_preview link_albumvid fleft top_ad">
            <img src="<?php echo $video['vThumbnail'];?>">
            <div class="subhead"><?php echo $video['vTitle'];?></div>
        </div>
    <?php if ($i % 3==0):?>
            <div class="fclear"></div>
        <?php else:?>
            <div class="fspace fleft"></div>
        <?php endif;?>
    <?php $i++; endforeach;?>
    <div class="fclear"></div>
    
</div>