<div class="acc_head">Folders</div>
<?php 
    $docs = new Documents();
    $folders = $docs->get_folders($_SESSION['usr_id']);
?>
<?php if (count($folders)):?>
    <div class="message-box"></div>
    <table class="table">
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

<script>
    /* $(".delete-folder").on("click",function(){
        var confirm = window.confirm("Do you really want to delete this?");
        if(confirm){
            var id = $(this).data('id');
            $this = $(this);
            $.ajax({
                url:'index.php',
                method:'post',
                dataType:'json',
                data:{
                    process:'DELETE FOLDER',
                    proc_type:'delete folder',
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
        
    }) */

</script>