<style>
form.mycontent div.selectOptions {
    width: 328px!important;
}
</style>
<?
if( $_SESSION['lv_leader'] || $_SESSION['lv_admin'] ) {
	if( !is_object($admin) ) $admin = new Admin();
	if( $admin->message ) {
		?><div class="message top_ad"><?=$admin->message?></div><?
	}
	$admin->build_arrays();
	?>
	<div class="acc_head">ADMIN PANEL</div>
	<div class="msg_panel">
		<strong>Contributor</strong> - permitted to upload content<br>
		<strong>Team Leader</strong> - permitted to grant/deny contributor permissions for a managed group<br>
		<strong>Administrator</strong> - permitted to grant/deny all rights and perform system functions
	</div>
	<div class="half_content top_ad fleft">
    	<form class="mycontent" method="post">
        	<input type="hidden" name="process" value="ADMIN">
            <div class="subhead btm_space">Grant Permissions</div>
            <?
            if( $_SESSION['lv_admin'] ) {
                ?>
                <label>Administrator</label>
                <div id="admin_y_selector" class="selectBox">
                    <div class="selectedBox">
                        <span class="selected"></span>
                        <div class="selectArrow"></div>
                    </div>
                    <div class="selectOptions">
                        <?
                        foreach( $admin->admins['N'] as $key => $val ) {
                            ?><span class="selectOption" value="<?=$key?>"><?=$val?></span><?
                        }
                        ?>
                    </div>
                </div>
                <input type="hidden" name="admin_y" id="admin_y_selected" value="">
                <label>Team Leader</label>
                <div id="leader_y_selector" class="selectBox">
                    <div class="selectedBox">
                        <span class="selected"></span>
                        <div class="selectArrow"></div>
                    </div>
                    <div class="selectOptions">
                        <?
                        foreach( $admin->leaders['N'] as $key => $val ) {
                            ?><span class="selectOption" value="<?=$key?>"><?=$val?></span><?
                        }
                        ?>
                    </div>
                </div>
                <input type="hidden" name="leader_y" id="leader_y_selected" value="">
                <?
            }
            ?>
            <label>Contributor</label>
            <div id="contributor_y_selector" class="selectBox">
                <div class="selectedBox">
                    <span class="selected"></span>
                    <div class="selectArrow"></div>
                </div>
                <div class="selectOptions">
                    <?
                    foreach( $admin->contributors['N'] as $key => $val ) {
                        ?><span class="selectOption" value="<?=$key?>"><?=$val?></span><?
                    }
                    ?>
                </div>
            </div>
            <input type="hidden" name="contributor_y" id="contributor_y_selected" value="">
            <input type="submit" name="proc_type" class="button fright" value="GRANT">
        </form>
		<div class="fclear"></div>
	</div>
	<div class="half_content top_ad fright">
    	<form class="mycontent" method="post">
        	<input type="hidden" name="process" value="ADMIN">
            <div class="subhead btm_space">Deny Permissions</div>
            <?
            if( $_SESSION['lv_admin'] ) {
                ?>
                <label>Administrator</label>
                <div id="admin_n_selector" class="selectBox">
                    <div class="selectedBox">
                        <span class="selected"></span>
                        <div class="selectArrow"></div>
                    </div>
                    <div class="selectOptions large">
                        <?
                        foreach( $admin->admins['Y'] as $key => $val ) {
                            ?><span class="selectOption" value="<?=$key?>"><?=$val?></span><?
                        }
                        ?>
                    </div>
                </div>
                <input type="hidden" name="admin_n" id="admin_n_selected" value="">
                <label>Team Leader</label>
                <div id="leader_n_selector" class="selectBox">
                    <div class="selectedBox">
                        <span class="selected"></span>
                        <div class="selectArrow"></div>
                    </div>
                    <div class="selectOptions large">
                        <?
                        foreach( $admin->leaders['Y'] as $key => $val ) {
                            ?><span class="selectOption" value="<?=$key?>"><?=$val?></span><?
                        }
                        ?>
                    </div>
                </div>
                <input type="hidden" name="leader_n" id="leader_n_selected" value="">
                <?
            }
            ?>
            <label>Contributor</label>
            <div id="contributor_n_selector" class="selectBox">
                <div class="selectedBox">
                    <span class="selected"></span>
                    <div class="selectArrow"></div>
                </div>
                <div class="selectOptions large">
                    <?
                    foreach( $admin->contributors['Y'] as $key => $val ) {
                        ?><span class="selectOption" value="<?=$key?>"><?=$val?></span><?
                    }
                    ?>
                </div>
            </div>
            <input type="hidden" name="contributor_n" id="contributor_n_selected" value="">
            <input type="submit" name="proc_type" class="button fright" value="DENY">
        </form>
		<div class="fclear"></div>
	</div>
    <!-- Sections -->
    <div class="half_content top_ad fleft">
    	<form class="mycontent" method="post">
        	<input type="hidden" name="process" value="ADMIN">
            <div class="subhead btm_space">Add New Section</div>
            <?
            if( $_SESSION['lv_admin'] ) {
                ?>
                <label>Section Name</label>
                <input type="text" name="section_name" id="section_name" value="">
                <?
            }
            ?>
            <input type="submit" name="proc_type" class="button fright" value="ADD NEW SECTION">
        </form>
		<div class="fclear"></div>
	</div>
	<div class="half_content top_ad fright">
    	<form class="mycontent" method="post">
        	<input type="hidden" name="process" value="ADMIN">
            <div class="subhead btm_space">Delete Sections</div>
            <?
            if( $_SESSION['lv_admin'] ) {
                $sections = $admin->get_all_sections();
                ?>
                <label>Section</label>
                <select name="section_id" id="section_id"  class="input-control" required>
                    <?php foreach($sections as $section):?>
                        <option value="<?php echo $section['ID'];?>"><?php echo $section['name'];?></option>
                    <?php endforeach;?>
                </select>
                <?
            }
            ?>
            <input type="submit" name="proc_type" class="button fright" value="DELETE SECTION">
        </form>
		<div class="fclear"></div>
	</div>
	<?
	if( $_SESSION['lv_leader'] ) {
		?>
        <div class="fclear"></div>
        <div class="acc_head">MANAGE TEAM</div>
        <form class="mycontent" method="post">
        	<input type="hidden" name="process" value="ADMIN">
            <input type="hidden" name="teamid" value="<?=$admin->team['ID']?>">
        	<label>Team Name</label>
            <div class="half_content fleft">
        		<input type="text" name="teamname" value="<?=$admin->team['name']?>">
            </div>
            <div class="half_content fright">
            	<input type="submit" name="proc_type" class="button" value="UPDATE">
            </div>
        </form>
        <div class="fclear"></div>
        <div class="msg_panel">
            Use this section to manage which sections your team appears as a contributor.
        </div>
        <div class="half_content top_ad fleft">
			<div class="subhead btm_space">Add Section</div>
            <div id="section_y_selector" class="selectBox">
                <div class="selectedBox">
                    <span class="selected"></span>
                    <div class="selectArrow"></div>
                </div>
                <div class="selectOptions">
                    <?
                    foreach( $admin->sections['N'] as $key => $val ) {
                        ?><span class="selectOption" value="<?=$key?>"><?=$val?></span><?
                    }
                    ?>
                </div>
            </div>
            <form class="mycontent" method="post">
        		<input type="hidden" name="process" value="ADMIN">
                <input type="hidden" name="teamid" value="<?=$admin->team['ID']?>">
				<input type="hidden" name="section_y" id="section_y_selected" value="">
            	<input type="submit" name="proc_type" class="button fright" value="ADD SECTION">
            </form>
        </div>
        <div class="half_content top_ad fright">
			<div class="subhead btm_space">Remove Section</div>
            <div id="section_n_selector" class="selectBox">
                <div class="selectedBox">
                    <span class="selected"></span>
                    <div class="selectArrow"></div>
                </div>
                <div class="selectOptions">
                    <?
                    foreach( $admin->sections['Y'] as $key => $val ) {
                        ?><span class="selectOption" value="<?=$key?>"><?=$val?></span><?
                    }
                    ?>
                </div>
            </div>
            <form class="mycontent" method="post">
        		<input type="hidden" name="process" value="ADMIN">
                <input type="hidden" name="teamid" value="<?=$admin->team['ID']?>">
				<input type="hidden" name="section_n" id="section_n_selected" value="">
            	<input type="submit" name="proc_type" class="button fright" value="REMOVE SECTION">
            </form>
        </div>
        <div class="fclear"></div>
        <div class="msg_panel top_ad">
            Use this section to manage which contributors are on your team.  This does not change their status as a contributor.  To do that, use the Admin Panel above.  To move a contributor from one team to another they must first be removed from their existing team before they will appear as an option to add to your team.
        </div>
        <div class="half_content top_ad fleft">
			<div class="subhead btm_space">Add Contributor to Team</div>
            <div id="member_y_selector" class="selectBox">
                <div class="selectedBox">
                    <span class="selected"></span>
                    <div class="selectArrow"></div>
                </div>
                <div class="selectOptions">
                    <?
                    foreach( $admin->members['N'] as $key => $val ) {
                        ?><span class="selectOption" value="<?=$key?>"><?=$val?></span><?
                    }
                    ?>
                </div>
            </div>
            <form class="mycontent" method="post">
        		<input type="hidden" name="process" value="ADMIN">
                <input type="hidden" name="teamid" value="<?=$admin->team['ID']?>">
				<input type="hidden" name="member_y" id="member_y_selected" value="">
            	<input type="submit" name="proc_type" class="button fright" value="ADD CONTRIBUTOR">
            </form>
        </div>
        <div class="half_content top_ad fright">
			<div class="subhead btm_space">Remove Contributor from Team</div>
            <div id="member_n_selector" class="selectBox">
                <div class="selectedBox">
                    <span class="selected"></span>
                    <div class="selectArrow"></div>
                </div>
                <div class="selectOptions">
                    <?
                    foreach( $admin->members['Y'] as $key => $val ) {
                        ?><span class="selectOption" value="<?=$key?>"><?=$val?></span><?
                    }
                    ?>
                </div>
            </div>
            <form class="mycontent" method="post">
        		<input type="hidden" name="process" value="ADMIN">
                <input type="hidden" name="teamid" value="<?=$admin->team['ID']?>">
				<input type="hidden" name="member_n" id="member_n_selected" value="">
            	<input type="submit" name="proc_type" class="button fright" value="REMOVE CONTRIBUTOR">
            </form>
        </div>
        <?
	}
	if( $_SESSION['lv_admin'] ) {
		?>
        <div class="fclear"></div>
        <div class="acc_head">SYSTEM TOOLS</div>
        <div class="subhead">Reset/Sync Video Library</div>
        <div class="half_content fleft">
            Resynchronizes video content with the search engine. It should only be used when a known video does not appear in searches.  This is a site-impacting process that should be used during a time of low site usage.
        </div>
        <div class="half_content fright">
            <input type="button" class="button slim link_dbupdate" value="RESET/SYNC">
        </div>
        <div class="fclear"></div>
        <?
	}
} else {
	?><div class="message top_ad">YOU ARE NOT AUTHORIZED TO VIEW THIS PAGE</div><?
}
?>