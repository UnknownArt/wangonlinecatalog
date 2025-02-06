<?php 
    require_once("session.php");
    require_once("user_class.php");
    require_once("functions/pagination.php");
    require_once("functions/echo-alert.php");

    define("NAVIGATION", true);
    define("ADMIN_PANEL", true);
    define("MODAL", true);

    $user = new User();

    if (isset($_SESSION['user_type']) & $_SESSION['user_type'] != 1){
        header("location: index.php");
    }       

    $page = (int)(!isset($_GET["page"]) ? 1 : $_GET["page"]);
    if($page <= 0) 
        $page = 1;

    $per_page = 10;
    $start_point = ($page * $per_page) - $per_page;
    $statement = "users ORDER BY user_id ASC";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />

    <title>Product Catalog</title>

    <meta name="description" content="Online Product Catalog" />
    <meta name="keywords" content="catalog, producgts, online" />

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="X-Ua-Compatible" content="IE=edge,chrome=1" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css" />
    <!--[if lt IE 9]>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
    <script defer src="https://use.fontawesome.com/releases/v5.8.1/js/all.js"
        integrity="sha384-g5uSoOSBd7KkhAMlnQILrecXvzst9TdC09/VM+pjDTCM+1il8RHz5fKANTFFb+gQ" crossorigin="anonymous">
    </script>
    <script src="js/sticky-nav.js"></script>
</head>

<body>
    <div class="wrapper">

        <?php
            if($user->isLoggedIn() && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1){
                require_once('admin-panel.php');
            }
        ?>

        <div class="main-page">
            <!-- <header>
                <div class="jumbotron text-center">

                    <?php
                        if($user->isLoggedIn() && $_SESSION['user_type'] == 1){
                        require_once('admin-panel-button.php');
                    }
                    ?>

                    <h1>Product Catalog</h1>

                </div>
            </header> -->

            <?php 
            if(!$user->isLoggedIn()){
              require_once('navigations/basic_nav.php');
            }
            else if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1){
              require_once('navigations/admin_nav.php');
            }
            else 
              require_once('navigations/user_nav.php');
          ?>

            <main>
                <div class="main-content">
                    <div class="container">
                        <h2 class="page-title">User Management</h2>
                        <?php
                            if(isset($_GET['name_error'])){
                                echoAlert("danger", "Error!", "A User with the give name already exists!");
                            }
                            else if(isset($_GET['del_result'])){
                                if($_GET['del_result'] == true){
                                    echoAlert("success", "Success!", "The user has been deleted!");
                                }
                                else{
                                    echoAlert("danger", "Error!", "Failed to delete the user!");
                                }
                            }
                            else if(isset($_GET['update_result'])){
                                if($_GET['update_result'] == true){
                                    echoAlert("success", "Success!", "User data has been updated!");
                                }
                                else{
                                    echoAlert("danger", "Error!", "Failed to update user data!");
                                }
                            }          
                        ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="align-middle" style="width: 10%">ID</th>
                                        <th class="align-middle" style="width: 30%">Username</th>
                                        <th class="align-middle" style="width: 35%">Email Address</th>
                                        <th class="align-middle" style="width: 15%">Rodzaj konta</th>
                                        <th 
                                        <!--translate to english-->
                                        Rodzaj konta
                                        
                                        
                                        <th class="align-middle" style="width: 5%">Edit</th>
                                        <th class="align-middle" style="width: 5%">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        try {         
                                            $result =  $user->runQuery("SELECT user_id, user_name, user_email, type_id FROM {$statement} LIMIT {$start_point},{$per_page}");                             
                                            $result->execute();  
                                            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                    <tr>
                                        <td class="text-center align-middle">
                                            <?php echo $row['user_id']; ?>
                                        </td>
                                        <td class="align-middle">
                                            <?php echo $row['user_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['user_email']; ?>
                                        </td>
                                        <td class="align-middle">
                                            <?php 
                                                if($row['type_id'] == 1) echo "Administrator";
                                                else echo "Użytkownik"; 
                                            ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php 
                                                $id = $row['user_id'];
                                                if ($id == 1){
                                                    echo "<span class=\"link-disabled\"><a class=\"admin-panel-table-icon\" href=\"#\">
                                                            <i class=\"fas fa-edit\"></i>
                                                        </a></span>";
                                                }
                                                else if (isset($_GET['page']) && intval($_GET['page']) != 0) {
                                                    echo "<a class=\"admin-panel-table-icon\" href=\"admin_users.php?page=$page&user_id=$id\">
                                                            <i class=\"fas fa-edit\"></i>
                                                        </a>";
                                                }
                                                else {
                                                    echo "<a class=\"admin-panel-table-icon\" href=\"admin_users.php?user_id=$id\">
                                                            <i class=\"fas fa-edit\"></i>
                                                        </a>";
                                                }           
                                            ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php 
                                                $type = "usr";
                                                if($id == 1){
                                                    echo "<span class=\"link-disabled\"><a class=\"admin-panel-table-icon\" href=\"#\">
                                                       <i class=\"fas fa-trash-alt\"></i>
                                                   </a></span>"; 
                                                }
                                                else{
                                                    echo "<a class=\"admin-panel-table-icon\" onclick=\"return confirm('Czy na pewno chcesz usunąć tego użytkownika?')\" 
                                                         href=\"admin/delete.php?id=$id&type=$type\">
                                                            <i class=\"fas fa-trash-alt\"></i>
                                                        </a>";
                                                }
                                            ?>
                                        </td>
                                    </tr>

                            <?php
                                    }
                                }
                                catch (PDOException $e){
                                    echo $e->getMessage();
                                }
                            ?>

                                </tbody>
                            </table>
                        </div>
                        <?php 
                            echo pagination($user, $statement, $per_page, $page, $url='?');
                        ?>
                    </div>
                </div>
            </main>

            <footer>
                <div class="footer text-center">
                    <div class="footer-social">
                        <a href="#"><i class="footer-social-icon fab fa-facebook-square"></i></a>
                        <a href="#"><i class="footer-social-icon fab fa-instagram"></i></a>
                        <a href="#"><i class="footer-social-icon fab fa-twitter-square"></i></a>
                        <a href="#"><i class="footer-social-icon fab fa-youtube-square"></i></a>
                    </div>
                </div>
            </footer>
        </div>

    </div>

    <?php
        if($user->isLoggedIn() && $_SESSION['user_type'] == 1){
            echo '<script src="js/admin-panel.js" type="text/javascript"></script>';
        }

        if(isset($_GET['user_id']) && intval($_GET['user_id']) != 0){
            require_once('modals/user-edit-modal.php');?>
    <script type="text/javascript">
        $('#editUserModal').modal('show');
    </script><?php
        }
    ?>

</body>

</html>