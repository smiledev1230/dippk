<?php 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    $upload = new Upload();
    $_SESSION['course'] = $req['id'];
    include "classes/class.course.php";
    $crs = new Course();
    
?>
<h1>EDIT TOPIC</h1>
<?php if(isset($_SESSION['error'])):?>
    <div class="error-message">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif;?>
<?php if(isset($_SESSION['success'])):?>
    <div class="success-message">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif;?>
<form enctype="multipart/form-data" method="post" class="mycontent">
    <input type="hidden" name="course_id" value="<?php echo $crs->course['ID'];?>">
    <input type="hidden" name="ctab" value="course">
    <input type="hidden" name="proc_type" value="update topic">
    <input type="hidden" name="page" value="account">
    <input type="hidden" name="view" value="uploads">
    <span class="fleft">
        <label>Topic Title</label><input type="text" name="title" value="<?php echo $crs->course['title'];?>">
        <label>Topic Tagline</label><input type="text" name="tagline" value="<?php echo $crs->course['tagline'];?>">
        <label>Topic Description</label><textarea name="description"><?php echo $crs->course['description'];?></textarea>
    </span>
    <span class="fright">
        <label>Choose a Section</label>
        <?php $options = $upload->get_sections();?>
        <select name="section" class="input-control"  id="section-dropdown" required>
            <option value="">Select Section</option>
            <?
                foreach( $options as $key => $val ) {
                    ?><option value="<?php echo $key;?>" <? if( $key == $crs->course['menuID'] ) echo ' selected'; ?> value="<?=$key?>"><?=$val?></option><?
                }
            ?>
        </select>
        <label>Choose a Folder</label>
        <?php $options = $upload->get_folders();?>
        <select name="folder" class="input-control" id="folder-dropdown" required>
            <option value="">Select Folder</option>
            <?
                foreach( $options as $key => $val ) {
                    ?><option value="<?php echo $key;?>" <? if( $key == $crs->course['folder'] ) echo ' selected'; ?> value="<?=$key?>"><?=$val?></option><?
                }
            ?>
        </select>
        
        <div class="customfile-container">
            <label>Thumbnail (recommend 226x170, max 50kB)</label>
            <input type="file" id="thumbnail" name="thumbnail">
        </div>
        <?php if(!empty($crs->course['image'])):?>
                <label>Current Thumbnail:</label>
                <img src="courseware/courses/<?php echo $crs->course['ID'].'/'.$crs->course['image'];?>" alt="" style="max-width:100%;">
                <br>
                <strong>Note:</strong> Select new thumbnail only if you want to replace this.
                <br>
                <br>
        <?php endif;?>
        
        <label>Featured Item?</label>
        <input type="radio" name="featured" value="Y" <?php if($crs->course['featured']=='Y'){ echo "checked";}?>><span class="radio_text">Yes</span>
        <input type="radio" name="featured" value="N" <?php if($crs->course['featured']=='N'){ echo "checked";}?>><span class="radio_text">No</span>
        <input type="submit" name="process" class="button fright" value="UPDATE TOPIC">
    </span>
</form>
<div class="clearfix"></div>
<hr>
<?php if(isset($crs->lessons) && count($crs->lessons)>0):?>
    <h2>Chapters</h2>
    <table class="c-table">
            <tr>
                <th>Chapter Title</th>
                <th>Chapter Order</th>
                <th style="width:150px;" class="text-center">Action</th>
            </tr>
            <?php $i=1; foreach($crs->lessons as $lesson): ?>
            <tr>
                <td><?php echo $lesson['title'];?></td>
                <td><input type="text" class="display_order" tabindex="<?php echo $i;?>" data-id="<?php echo $lesson['ID']; ?>" value="<?php echo $lesson['display_order'];?>"></td>
                <td class="text-center">
                    <a href="?page=account&action=edit&type=chapter&id=<?php echo $lesson['ID']; ?>">Edit</a> |
                    <span class="delete-btn delete-lesson" data-id="<?php echo $lesson['ID']; ?>">Delete</span>
                </td>
            </tr>
            <?php $i++; endforeach;?>
    </table>
    
<?php endif;?>
<script>
    $(".delete-chapter").on("click",function(){
        var confirm = window.confirm("Do you really want to delete this?");
        if(confirm){
            var id = $(this).data('id');
            $this = $(this);
            $.ajax({
                url:'index.php',
                method:'post',
                dataType:'json',
                data:{
                    process:'DELETE CHAPTER',
                    proc_type:'delete chapter',
                    id:id
                },
                success:function(data){
                    if(data.success){
                        $(".message-box").html('<div class="success-message">'+data.message+'</div>');
                        $this.parent().parent().remove();
                    }
                    else{
                        $(".message-box").html('<div class="error-message">' + data.message + '</div>');
                    }
                    
                },
                error:function(err){
                    console.log(err);
                }

            })
        }
        
    })

    $(".delete-lesson").on("click",function(){
        var confirm = window.confirm("Do you really want to delete this?");
        if(confirm){
            var id = $(this).data('id');
            $this = $(this);
            $.ajax({
                url:'index.php',
                method:'post',
                dataType:'json',
                data:{
                    process:'DELETE LESSON',
                    proc_type:'delete lesson',
                    id:id
                },
                success:function(data){
                    if(data.success){
                        $(".message-box").html('<div class="success-message">'+data.message+'</div>');
                        $this.parent().parent().remove();
                    }
                    else{
                        $(".message-box").html('<div class="error-message">' + data.message + '</div>');
                    }
                    
                },
                error:function(err){
                    console.log(err);
                }

            })
        }
    })

    $(".display_order").on("blur",function(){
        var display_order = $(this).val();
        var id = $(this).data('id');
        $.ajax({
                url:'index.php',
                method:'post',
                dataType:'json',
                data:{
                    process:'UPDATE LESSON ORDER',
                    proc_type:'update lesson order',
                    id:id,
                    display_order:display_order
                },
                error:function(err){
                    console.log(err);
                }

            })
    })


</script>