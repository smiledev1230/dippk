<?php 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    $upload = new Upload();
    include "classes/class.course.php";
    $crs = new Course();
    $lesson = $crs->get_lesson_by_id($req['id']);
?>
<h1>EDIT CHAPTER</h1>
<hr>
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
<br>
<?php if($lesson):?>
    <form enctype="multipart/form-data" method="post" class="mycontent">
        <input type="hidden" name="lesson_id" value="<?php echo $lesson['ID'];?>">
        <input type="hidden" name="proc_type" value="update chapter">
        <input type="hidden" name="page" value="account">
        <input type="hidden" name="view" value="uploads">
        <label>Chapter Title</label><input type="text" name="title" value="<?php echo $lesson['title'];?>">
        <input type="submit" name="process" class="button fright" value="UPDATE CHAPTER">
    </form>
    <div class="clearfix"></div>
    
<?php endif;?>
<?php $chapters = $crs->get_lesson_chapters($req['id']);?>
<h2>Parts</h2>
<table class="c-table">
    <tr>
        <th>Part Title</th>
        <th>Order</th>
        <th style="width:150px;" class="text-center">Action</th>
    </tr>
    <?php $i=1;  foreach($chapters as $chapter):?>
        <tr>
            <td><?php echo $chapter['title'];?></td>
            <td><input type="text" class="display_order" tabindex="<?php echo $i;?>" data-id="<?php echo $chapter['ID']; ?>" value="<?php echo $chapter['display_order'];?>"></td>
            <td class="text-center">
                <a href="?page=account&action=edit&type=part&id=<?php echo $chapter['ID']; ?>">Edit</a> |
                <span class="delete-btn delete-chapter" data-id="<?php echo $chapter['ID']; ?>">Delete</span>
            </td>
        </tr>
    <?php $i++; endforeach;?>
</table>
<div class="clearfix"></div>
<a class="button" style="color:#fff;margin-top:20px;display:inline-block;" href="?page=account&action=edit&type=topic&id=<?php echo $_SESSION['course'];?>">Back</a>
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

    $(".display_order").on("blur",function(){
        var display_order = $(this).val();
        var id = $(this).data('id');
        $.ajax({
                url:'index.php',
                method:'post',
                dataType:'json',
                data:{
                    process:'UPDATE CHAPTER ORDER',
                    proc_type:'update chapter order',
                    id:id,
                    display_order:display_order
                },
                error:function(err){
                    console.log(err);
                }

            })
    })
</script>