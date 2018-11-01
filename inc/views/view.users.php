<?php 
    $admin = new Admin();
    $users = $admin->get_users();
?>
<?php if (isset($_SESSION['error'])):?>
    <div class="error-message" style="margin-top:10px;">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif;?>
<?php if (isset($_SESSION['success'])):?>
    <div class="success-message" style="margin-top:10px;">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif;?>
<h1>Users</h1>
<table class="c-table" style="margin-top:20px;">
    <thead>
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php foreach($users as $user):?>
            <tr>
                <td><?php echo $user['First_Name'].' '.$user['Last_Name'];?></td>
                <td><?php echo $user['Username'];?></td>
                <td><?php echo $user['Email'];?></td>
                <td>
                    <?php if($user['Status']==0): ?>
                        Not Approved
                    <?php endif;?>
                    <?php if($user['Status']==1): ?>
                        Active
                    <?php endif;?>
                    <?php if($user['Status']==2): ?>
                        Blocked
                    <?php endif;?>
                </td>
                <td>
                    <?php if($user['Status']==0): ?>
                        <span class="approve-user pointer" data-id='<?php echo $user['ID'];?>'>
                            Approve User
                        </span>
                    <?php endif;?>

                    <?php if($user['Status']==1): ?>
                        <span class="block-user pointer" data-id='<?php echo $user['ID'];?>'>
                            Block User
                        </span>
                    <?php endif;?>

                    <?php if($user['Status']==2): ?>
                        <span class="unblock-user pointer" data-id='<?php echo $user['ID'];?>'>
                        Unblock User
                        </span>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
    </thead>
</table>