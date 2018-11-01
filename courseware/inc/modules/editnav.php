<?
$edit_options = $editor->get_edit_options($edit_type, $modules);
?>
<div class="crs_sideedit">
	<div class="title">EDIT OPTIONS</div>
    <div class="data active">
    	<div id="crs_edit" class="tlight">
			<ul>
            	<?
				foreach( $edit_options as $link => $opt ) {
					?><li><a class="crs_edit_<?=$link?>"><?=$opt?></a></li><?
				}
				?>
            </ul>
        </div>
    </div>
</div>
<div class="crs_sidenav top_ad">
	<?
	switch( $viewname ) {
		case 'course':
			?>
            <div class="title">COURSE NAVIGATION</div>
            <div class="data active">
                <div id="crs_nav" class="tlight">
                <div class="scrolldiv">
                <div data-id="crs-<?=$_SESSION['course']?>" class="crs_lesson_title link_crs_edit active">
                    <div class="crs_icontext fleft"><?=$crs->course['title']?></div>
                    <div class="fclear"></div>
                </div>
                <?
                $l_num = 1; $l_active_set = false; $c_active_set = false;
                foreach( $crs->lessons as $l ) {
                    ?>
                    <div class="crs_lesson">
                        <div data-id="lsn-<?=$_SESSION['course'].':'.$l_num?>" class="crs_lesson_title<? if( !$l_active_set && !$l['complete'] && $edit_type != 'course' ) { echo ' active'; $l_active_set = $l_num; }?>">
                            <div class="crs_icon_<?
                                if( $l_active_set == $l_num ) {
                                    echo 'minus';
                                } else {
                                    echo 'plus';
                                } ?> fleft"></div>
                            <div class="crs_icontext fleft" id="lsn-<?=$l_num?>">L<?=$l_num?>: <?=$l['title']?></div>
                            <div class="fclear"></div>
                        </div>
                        <?
                        $c_num = 1;
                        ?><div class="data<? if( $l_active_set == $l_num ) echo ' active'; ?>"><?
                        foreach( $l['chapters'] as $c ) {
                            ?><div data-id="chp-<?=$_SESSION['course'].':'.$c_num?>" class="crs_lesson_chapter link_crs_editc<?
                                if( $req['chapter'] == $c_num || ( !$req['chapter'] && !$c_active_set && $edit_type != 'course' ) ) {
                                    echo ' active';
                                    $c_active_set = $c_num;
                                }
                                if( $c_num == 1 ) echo ' first'; ?>">Ch<?=$c_num?>: <?=$c['title']?></div><?
                            $c_num++;
                        }
                        ?>
                        </div>
                    </div>
                    <?
                    $l_num++;
                }
                ?>
                </div>
                </div>
            </div>
            <?
			break;
		case 'quiz':
			?>
            <div class="title">REQUIRED QUESTIONS</div>
            <div class="data active">
                <div id="crs_nav" class="tlight">
                <div class="scrolldiv">
                	<ol>
						<?
						$startpoint = count( $questions );
                        foreach( $questions as $key => $q ) {
                            if( $q['required'] == 'Y' ) {
                                ?>
                                <li id="qst-<?=$crs->quiz['ID'].':'.$q['ID']?>" class="tleft link_crs_editq">
									<?
									if( strlen( $q['question'] ) > 50 ) {
                                    	echo substr( $q['question'],0,50).'...';
									} else {
										echo $q['question'];
									}
									?>
                                </li>
								<?
                            } else {
                                $startpoint = $key;
                                break;
                            }
                        }
                        ?>
                    </ol>
                </div>
                </div>
            </div>
            <div class="title separate">QUESTION POOL</div>
            <div class="data">
                <div id="crs_nav" class="tlight">
                <div class="scrolldiv">
                	<ol>
                    	<?
						for( $i = $startpoint; $i < count($questions); $i++ ) {
							?><li id="qst-<?=$crs->quiz['ID'].':'.$questions[$i]['ID']?>" class="tleft link_crs_editq"><?=substr( $questions[$i]['question'],0,50)?>...</li><?
						}
						?>
                    </ol>
                </div>
                </div>
            </div>
            <?
			break;
	}
	?>
</div>