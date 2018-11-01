<div class="message-box">
</div>
<?
$docs = new Documents();
$limit = 8; $start = 0;
$docs->get_uploads($limit,$start);
$docs->get_featured_courses();
?>
<div class="tab_bar top_ad">
	<?
	$tab = 1;
	foreach( $docs->uploads as $doc_type => $doc_files ) {
		if( $doc_type != 'counts' ) {
			if($doc_type=='Courses'){
				$doc_type ='Topics';
			}
			?><div id="tab<?=$tab?>" class="tab<? if( $tab == 1 ) echo ' active'; ?> tab_btn"><?=$doc_type?></div><?
			$tab++;
		}
	}
    ?>
	<div id="tab<?=$tab?>" class="tab tab_btn">Folders</div>
    <div class="fclear"></div>
</div>
<div id="tab_content" data-id="tab1" class="initialize"></div>
<?
$tab = 1;
foreach( $docs->uploads as $doc_type => $doc_files ) {
	if( $doc_type != 'counts' ) {
		?><div id="tab<?=$tab?>_data" class="tab_data"><?
			switch( $doc_type ) {
				case 'Courses':
				case 'Quizzes':
					?><div class="section"><?
					$firstrow = true;
					$i = 0;
					foreach( $doc_files as $key => $val ) {
						?>
						<div data-id="<?=$doc_type.':'.$key?>" data-key="<?php echo $key;?>" data-type="<?php echo $doc_type;?>" class="line subsection<? if( ~$i&1 ) echo ' zebra'; if( $firstrow ) { echo ' top_ad'; $firstrow = false; } ?>">
                        	<?
							if( in_array( $key, array_keys( $docs->featured ) ) ) {
								?><div id="ffeat<?=$key?>" class="btn_feature inline btn_active feature_remove fleft"></div><?
							} else {
								?><div id="ffeat<?=$key?>" class="btn_feature inline fleft"><div class="tt_img hidden"></div></div><?
							}
							?>
							<span class="fleft name" style="max-width:60%"><?=$val['title']?></span>
							<a class="js_delete fright blue">Delete</a>
							<a class="js_edit fright blue">Edit</a>
							<?
							if( $val['publish'] == 'Y' ) {
								?>
								<a class="fright js_publish blue">Published</a>
								<?
							} else {
								?>
								<a class="fright js_publish blue">Unpublished</a>
								<?
							}
							?>
							<div class="fclear"></div>
						</div>
						<?
						$i++;
					}
					?></div><?
					break;
				default:
					include 'inc/modules/uploads.view.docs.php';
			}
		?></div><?
		$tab++;
	}
}
?>
<?php 
    $docs = new Documents();
    $folders = $docs->get_folders($_SESSION['usr_id']);
?>
<div id="tab<?=$tab?>_data" class="tab_data">
	<div class="section"  style="margin-top:15px;padding-left:0px;">
		<?php if (count($folders)):?>
			<div class="message-box"></div>
			<table class="table" >
				<thead>
					<tr>
						<th>Folder Name</th>
						<th>Section Name</th>
						<th>Topics</th>
						<th style="text-align:center;">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($folders as $row): $total_courses = $docs->count_courses_inside_folder($row['ID']);?>
						<tr>
							<td><?php echo $row['title'];?></td>
							<td><?php echo $row['section_name'];?></td>
							<td><?php echo $total_courses; ?></td>
							<td style="text-align:center;">
								<?php if($total_courses==0):?>
									<span class="delete-folder pointer " data-id="<?php echo $row['ID'];?>">
										Delete
									</span>
									
								<?php else:?>
									*
								<?php endif;?>
							</td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
			<p style="margin-top:15px;">
			<small><strong>*NOTE:</strong>  You can only delete folders that do not contain an existing Topic. If you want to delete a folder which has an existing Topic, please delete all the Topics under that folder first. You can make edits to folders and Topics in <a href="index.php?page=account&view=uploads" style="color:blue">My Content</a>.</small>
			</p>
		<?php else:?>
		<h3>You have not created any folder yet!!!</h3>
		<?php endif;?>		
	</div>
</div>