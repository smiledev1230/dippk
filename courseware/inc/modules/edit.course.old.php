<?

if( $crs->course['tagline'] != '' ) $modules[] = 'tagline';

if( $crs->course['description'] != '' ) $modules[] = 'desc';

if( $crs->course['image'] != '' ) $modules[] = 'thumb';

?>

<div class="crs_head_lg">

	<span id="crs_actv_title"><?=$crs->course['title']?></span>

    <form id="crs_edit_title" method="post" class="hidden">

    	<input type="hidden" name="process" value="edit">

        <input type="hidden" name="id" value="<?=$crs->course['ID']?>">

    	<input type="text" name="title" value="<?=$crs->course['title']?>">

        <input type="submit" class="crs_button top_15" name="proc_type" value="CHANGE COURSE TITLE">

    </form>

</div>

<div class="crs_head_sm">

	<span id="crs_actv_tagline"><?=$crs->course['tagline']?></span>

    <form id="crs_edit_tagline" method="post" class="hidden">

    	<input type="hidden" name="process" value="edit">

        <input type="hidden" name="id" value="<?=$crs->course['ID']?>">

    	<input type="text" name="tagline" value="<?=$crs->course['tagline']?>">

        <input type="submit" class="crs_button top_15" name="proc_type" value="CHANGE TAGLINE">

    </form>

</div>

<span id="crs_edit_thumb" class="hidden">

<form method="post" class="top_ad">

    <input type="hidden" name="process" value="edit">

    <input type="hidden" name="id" value="<?=$crs->course['ID']?>">

    <div class="customfile-container">

        <label>Thumbnail (recommend 226x170, max 50kB)</label>

        <input type="file" id="thumbnail" name="thumbnail">

    </div>

    <input type="submit" class="crs_button fleft" name="proc_type" value="CHANGE THUMBNAIL">

</form>

<div class="fclear"></div>

</span>

<div class="crs_text_lg crs_text_wrap top_ad">

	<img id="crs_actv_thumb" src="courseware/courses/<?=$crs->course['ID'].'/'.$crs->course['image']?>" class="fleft">

    <span id="crs_actv_desc">

        <div id="lsn_scroll" class="crs_text_lg"><?=$crs->course['description']?></div>

	</span>

	<form id="crs_edit_desc" method="post" class="hidden">

    	<input type="hidden" name="process" value="edit">

        <input type="hidden" name="id" value="<?=$crs->course['ID']?>">

        <textarea name="description"><?=$crs->course['description']?></textarea>

    	<input type="submit" class="crs_button fright top_ad" name="proc_type" value="CHANGE DESCRIPTION">

    </form>

</div>