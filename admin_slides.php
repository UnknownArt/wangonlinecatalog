<?php 
    require_once("session.php");
    require_once("user_class.php");
    require_once("functions/select-categories.php");
    require_once("functions/echo-alert.php");

    define("NAVIGATION", true);
    define("ADMIN_PANEL", true);
    define("MODAL", true);

    $user = new User();

	if (isset($_SESSION['user_type']) & $_SESSION['user_type'] != 1){
		header("location: index.php");
    }

    $cat_settings_file = "cfg/featured_categories_config.json";
    $settings = json_decode(file_get_contents($cat_settings_file), true);
    
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
                        <h2 class="page-title">Slide Settings</h2>
                        <?php
                        if (isset($_GET['up_err'])) {
                            if($_GET['up_err'] == 1){
                                echoAlert("danger", "Error!", "Failed to upload the first slide image!");
                            }
                            else if($_GET['up_err'] == 2){
                                echoAlert("danger", "Error!", "Failed to upload the second slide image!");
                            }
                            else if($_GET['up_err'] == 3){
                                echoAlert("danger", "Error!", "Failed to upload the third slide image!");
                            }
                        }
                        if (isset($_GET['size_err'])) {
                            if($_GET['size_err'] == 1){
                                echoAlert("danger", "Error!", "The image for the first slide was not uploaded because the file size is too large!");
                            }
                            else if($_GET['size_err'] == 2){
                                echoAlert("danger", "Error!", "The image for the second slide was not uploaded because the file size is too large!");
                            }
                            else if($_GET['size_err'] == 3){
                                echoAlert("danger", "Error!", "The image for the third slide was not uploaded because the file size is too large!");
                            }
                        }
                        if(isset($_GET['up_err']) && $_GET['up_err'] == 0 && isset($_GET['size_err']) && $_GET['size_err'] == 0){
                            echoAlert("success", "Success!", ""All slides have been successfully updated!");
                            
                        }
                        if (isset($_GET['del_result'])) {
                            if ($_GET['del_result'] == true) {
                                echoAlert("success", "Success!", "Sllde deleted!");
                            } else {
                                echoAlert("danger", "Error!", "Failed to delete slide!");
                            }
                        }
                        ?>
                        <form action="admin/update_slides.php" method="post" enctype="multipart/form-data">
                            <h4>Slide 1:</h4>
                            <div class="slide-cfg-wrapper">
                                <div class="row justify-content-center">
                                    <img class="img-fluid img-slide-cfg mb-3" src="slides_img/slide1.jpg" alt="Slide1"
                                        onerror="this.onerror=null;this.src='slides_img/empty-slide.png';" />
                                </div>
                                <small class="form-text text-muted">Recommended size: 1920x500,
                                    max. 15MB</small>
                                <div class="form-group custom-file">
                                    <input type="file" class="form-control custom-file-input" id="slide1" name="slide1"
                                        accept="image/*">
                                    <label class="custom-file-label" for="slide1">Choose a slide file</label>
                                </div>
                                <div class="form-group">
                                    <a href="admin/delete_slide_img.php?slide_nr=1">
                                        <button type="button" class="btn btn-outline-danger btn-block">Delete Current
                                            Image</button>
                                    </a>
                                </div>
                            </div>

                            <h4>Slide 2:</h4>
                            <div class="slide-cfg-wrapper">
                                <div class="row justify-content-center">
                                    <img class="img-fluid img-slide-cfg mb-3" src="slides_img/slide2.jpg" alt="Slide1"
                                        onerror="this.onerror=null;this.src='slides_img/empty-slide.png';" />
                                </div>
                                <small class="form-text text-muted">Recommended size: 1920x500,
                                    max. 15MB</small>
                                <div class="form-group custom-file">
                                    <input type="file" class="form-control custom-file-input" id="slide2" name="slide2"
                                        accept="image/*">
                                    <label class="custom-file-label" for="slide2">Choose a slide file</label>
                                </div>
                                <div class="form-group">
                                    <a href="admin/delete_slide_img.php?slide_nr=2">
                                        <button type="button" class="btn btn-outline-danger btn-block">Delete Current
                                            Image</button>
                                    </a>
                                </div>
                            </div>

                            <h4>Slide 3:</h4>
                            <div class="slide-cfg-wrapper">
                                <div class="row justify-content-center">
                                    <img class="img-fluid img-slide-cfg mb-3" src="slides_img/slide3.jpg" alt="Slide1"
                                        onerror="this.onerror=null;this.src='slides_img/empty-slide.png';" />
                                </div>
                                <small class="form-text text-muted">Recommended size: 1920x500,
                                    max. 15MB</small>
                                <div class="form-group custom-file">
                                    <input type="file" class="form-control custom-file-input" id="slide3" name="slide3"
                                        accept="image/*">
                                    <label class="custom-file-label" for="slide3">Choose a slide file</label>
                                </div>
                                <div class="form-group">
                                    <a href="admin/delete_slide_img.php?slide_nr=3">
                                        <button type="button" class="btn btn-outline-danger btn-block">Delete Current
                                            Image</button>
                                    </a>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </form>

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
    ?>
    <?php if (isset($_GET['cat_id'])) { ?>
    <script type="text/javascript">
        $('#editCategoryModal').modal('show');
    </script>
    <?php
        }       
    ?>
    <script type="text/javascript" src="js/custom-file-input.js"></script>
</body>

</html>