<?php
    if(!defined("MODAL"))
        header('Location: ../index.php');

    try {
        $result =  $user->runQuery("SELECT user_name, user_email, type_id FROM users WHERE user_id = :id");
        $result->bindParam(":id", $_GET['user_id']);
        $result->execute();
        $edit_user_row = $result->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
?>

<div class="modal fade" id="editUserModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">


            <div class="modal-header">
                <h4 class="modal-title">Edit User</h4>
                <!--translate to english-->

                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>


            <div class="modal-body">
                <form method="post" action="admin/update_user.php?user_id=<?php echo $_GET['user_id']; ?>">
                    <div class="form-group">
                        <label for="userName">Username:</label>
                        <input type="text" class="form-control" id="userName" name="userName"
                            value="<?php echo $edit_user_row['user_name']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="userEmail">E-mail:</label>
                        <input type="text" class="form-control" id="userEmail" name="userEmail"
                            value="<?php echo $edit_user_row['user_email']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="userType">Account Type:</label>
                        <select class="form-control" id="userType" name="userType">
                            <option value="1" <?php if($edit_user_row['type_id'] == 1) echo " selected"; ?> >
                                Administrator</option>
                            <option value="2" <?php if($edit_user_row['type_id'] == 2) echo " selected"; ?> >
                                Użytkownik</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>

        </div>
    </div>
</div>
