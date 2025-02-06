<?php 
  if(!defined("NAVIGATION"))
    header('Location: ../index.php');
  require_once("functions/rows-count.php");
  $query = "clipboard WHERE user_id=$user_id";
?>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <a class="navbar-brand" href="index.php"><img src="img/logo.png" width="50" height="50"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="mainNavbar">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a href="index.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item dropdown">
        <a class="dropdown-toggle nav-link" data-toggle="dropdown" href="#">Products</a>
        <div class="dropdown-menu">
          <?php
              require_once("nav-select-categories.php");
              selectNavCategories($user);
            ?>
        </div>
      </li>
      <!-- <li class="nav-item"><a href="#" class="nav-link">About Us</a></li> -->
      <!-- <li class="nav-item"><a href="#" class="nav-link">Contact</a></li> -->
    </ul>
    <ul class="navbar-nav navbar-right">
      <li class="nav-item">
        <a href="clipboard.php" class="nav-link">
          <i class="far fa-star"></i> Clipboard 
          <span id="clipAmount" class="badge badge-info"><?php echo countRows($user, $query); ?></span>
        </a>
      </li>
      <li class="nav-item">
        <a href="logout.php?logout=true" class="nav-link"><i class="fas fa-sign-out-alt"></i> Log Out</a>
      </li>
    </ul>
  </div>
</nav>