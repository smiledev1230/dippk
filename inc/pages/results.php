<div id="main">
    <div class="acc_head">Search Results for "<?=$req['search']?>"</div>
    <?
	if( $req['search'] != '' && $req['search'] != 'begin your media search here' ) {
		$search = new Search();
		if( $search->get_results( $req['search'] ) ) {
			$_SESSION['debug']['RESULTS'] = var_export($search->results,true);
			?>
            <div id="browse" class="fleft">
                <?
				$first_type = true; unset($active_type);
                foreach( $search->result_type as $result_type ) {
                    ?><div id="rnav_<?=$result_type?>" class="result_nav<? if( $first_type ) echo ' active'; ?>"><?=ucfirst($result_type)?></div><?
					if( $first_type ) $active_type = $result_type;
					$first_type = false;
                }
                ?>
            </div>
            <div id="content_panel" class="fleft">
                <?
                foreach( $search->results as $result_type => $results ) {
					switch( $result_type ) {
						case 'videos':
							?>
							<div id="res_<?=$result_type?>" class="result_section<? if( $result_type == $active_type ) echo ' active'; ?> scrolldiv">
							<?
							$i = 0; $firstrow = true; $more = false;
							foreach( $results as $key => $rs ) {
								$video = $search->result_info['videos'][$rs];
								?>
								<div id="vid-<?=$video['ID']?>" class="vid_result link_albumvid fleft top_ad">
									<img src="<?=$video['vThumbnail']?>">
									<div class="fleft"><?=$search->convert_duration( $video['vDuration'] )?></div><?
									if( file_exists( 'videos/' . $video['vDescription'] . '.wmv' ) ) {
										?><div class="btn_wmv fright"></div><?
									}
									if( file_exists( 'videos/' . $video['vDescription'] . '.mp4' ) ) {
										?><div class="btn_mp4 fright"></div><?
									}
									?>
									<div class="subhead fclear"><?=strtoupper( str_replace( VIMEO_PREFIX, '', $video['aTitle'] ) )?></div>
									<div><?=$video['vTitle']?></div>
									<div class="download_name hidden"><?=$video['vDescription']?></div>
									</div>
								<?
								if( $i == 2 ) {
									?><div class="fclear"></div><?
								} else {
									?><div class="rspace fleft"></div><?
								}
								$i++;
								if( $i == 3 ) {
									$i = 0;
									$firstrow = false;
								}
							}
							if( $i < 3 ) {
								?><div class="fclear"></div><?
							}
							?></div><?
							break;
						default:
							$_SESSION['debug']['RES_'.$result_type.'_INFO'] = var_export($search->result_info[$result_type],true);
							?>
							<div id="res_<?=$result_type?>" class="result_section<? if( $result_type == $active_type ) echo ' active'; ?> scrolldiv">
							<?
							$i = 0; $more = false;
							foreach( $results as $key => $rs ) {
								$doc = $search->result_info[$result_type][$rs];
								?>
								<div id="doc-<?=$key?>" class="doc_preview doc link_content fleft top_ad">
									<div class="doc_image">
									<?
									if( $doc['thumb'] ) {
										?>
										<img src="<?=$doc['thumb']?>">
										<?
										if( $doc['type'] == 'unknown' || $doc['type'] == 'img' || $doc['type'] == 'audio' ) {
											?><div class="extension small"><?=$doc['ext']?></div><?
										} else {
											?><div class="<?=$doc['type']?> small"></div><?
										}
									} elseif( $doc['type'] == 'img' ) {
										?>
										<img src="<?=$doc['path']?>">
										<div class="extension small"><?=$doc['ext']?></div>
										<?
									} else {
										?>
										<div class="<?=$doc['type']?>"></div>
										<?
										if( $doc['type'] == 'unknown' || $doc['type'] == 'audio' ) {
											?><div class="extension small"><?=$doc['ext']?></div><?
										}
									}
									?>
									</div>
									<div class="doc_title">
										<a class="link_content_direct"><?=str_replace( array('_','-'), ' ', $doc['title'] )?></a>
									</div>
									<div class="doc-path hidden"><?=$doc['path']?></div>
								</div>
								<?
								if( $i == 3 ) {
									?><div class="fclear"></div><?
								} else {
									?><div class="rspace_wide fleft"></div><?
								}
								$i++;
								if( $i == 4 ) {
									$i = 0;
								}
							}
							if( $i < 3 ) {
								?><div class="fclear"></div><?
							}
							?></div><?
						
					}
				}
				?>
                <div class="fclear"></div>
            </div>
            <?
		} else {
			?>Sorry, no results found.  Please try another search or browse from the available menus.<br><br><?
		}
	} else {
		?>
        <div class="wide_content">
		<div id="search2">
			<div id="searchbar2" class="fright">
				<input type="text" id="search_txt2" value="begin search by typing here ...">
			</div>
			<div class="i_search2 fleft"></div>
		</div>
		<div class="subhead">There were no keywords entered into the search box.  Enter your search keywords into the search bar above and press enter or click the magnifying glass to view results.</div><br>
        </div>
		<?
	}
	?>
    <div class="fclear"></div>
</div>