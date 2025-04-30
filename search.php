<?php
session_start();
require_once("user_class.php");
require_once("functions/select-products.php");
require_once("functions/pagination.php");
require_once("functions/cat-name-by-id.php");

define("NAVIGATION", true);
define("ADMIN_PANEL", true);
define("MODAL", true);

$user = new User();

if (!$user->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$conn = (new Database())->dbConnect();

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($keyword)) {
    $sql .= " AND (
        prod_name LIKE :kw1 OR 
        prod_spec LIKE :kw2 OR 
        prod_desc LIKE :kw3 OR 
        prod_code LIKE :kw4 OR 
        prod_upc_img LIKE :kw5
    )";
    $wildKeyword = '%' . $keyword . '%';
    $params = [
        ':kw1' => $wildKeyword,
        ':kw2' => $wildKeyword,
        ':kw3' => $wildKeyword,
        ':kw4' => $wildKeyword,
        ':kw5' => $wildKeyword
    ];
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>검색 결과</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="wrapper">
    <?php
      if ($user->isLoggedIn() && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1) {
        require_once('admin-panel.php');
      }
    ?>
    <div class="main-page">
      <?php 
        if (!$user->isLoggedIn()) {
          require_once('navigations/basic_nav.php');
        } else if ($_SESSION['user_type'] == 1) {
          require_once('navigations/admin_nav.php');
        } else {
          $user_id = $_SESSION['user_session'];
          require_once('navigations/user_nav.php');
        }
      ?>

      <div class="container mt-4">
          <h1 class="text-center mb-4">검색 결과</h1>
          <div class="text-center mb-3">
              <a href="index.php" class="btn btn-secondary">← 메인으로 돌아가기</a>
          </div>

          <?php if (count($results) > 0): ?>
              <div class="row">
                  <?php renderProducts($results, $keyword); ?>
              </div>
          <?php else: ?>
              <div class="alert alert-warning text-center">검색 결과가 없습니다.</div>
          <?php endif; ?>
      </div>

      <footer>
        <div class="footer text-center">
          <div class="footer-social">
            <a href="#"><i class="fab fa-facebook-square"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter-square"></i></a>
            <a href="#"><i class="fab fa-youtube-square"></i></a>
          </div>
        </div>
      </footer>
    </div>
  </div>
</body>
</html>
