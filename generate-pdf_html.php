<?php
    require_once("session.php");
    require_once("vendor/autoload.php");
    require_once("user_class.php");
    use Dompdf\Dompdf;

    $user = new User();
    $dompdf = new Dompdf();

    $content = '
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
        html {
            font-family: DejaVu Sans;
            text-align: center;
        }

        .catalog-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }

        .product-card {
            width: 250px;
            border: 1px solid #bfbfbf;
            padding: 10px;
            text-align: center;
            background-color: #f9f9f9;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
            position: relative;
        }

        .product-card img {
            max-width: 100px;
            height: 100px;
            margin-bottom: 10px;
        }


        .product-card .prod-upc-img {
            max-width: auto;
            height: 50px;
            margin-bottom: 10px;
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        .product-name {
            font-size: 18px;
            font-weight: regular;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 180px;
        }


        .product-spec {
            font-size: 16px;
            font-weight: regular;
            margin-bottom: 5px;
        }

        .product-desc {
            font-size: 14px;
            font-weight: bold;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .product-price, .product-quantity, .product-value {
            font-size: 16px;
            margin-bottom: 3px;
        }

        .product-footer {
            margin-top: 10px;
        }

        .total-section {
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
            background-color: #333;
            color: white;
            padding: 10px;
            display: inline-block;
        }

        </style>

        <h1><img src="/img/logo.png" width="50px" height="50px"></h1>
        <h5>Wang Globalnet Catalog</h5>

        <div class="catalog-container">';

    if (isset($_SESSION['user_session'])) {
        try {
            $stmt =  $user->runQuery("SELECT id, prod_img, prod_name, prod_price, quantity, prod_code,prod_desc,prod_upc_img,prod_spec 
                                FROM clipboard
                                INNER JOIN products ON clipboard.prod_id = products.prod_id
                                WHERE clipboard.user_id = :id");
            $stmt->bindParam(":id", $_SESSION['user_session']);
            $stmt->execute();
            $total = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $total_value = number_format((float)$row['prod_price'] * $row['quantity'], 2, '.', '');
                $content .= '
                    <div class="product-card">
                        <div class="product-header">
                            <span>'.$row['prod_code'].'</span>
                            <span class="product-name">'.$row['prod_name'].'</span>
                        </div>
                        <div class="product-desc"> '.$row['prod_desc'].'</div>
                        <img src="'.$row['prod_img'].'" alt="Product Image">
                        <div class="product-spec">'.$row['prod_spec'].'</div>
                        <img class="prod-upc-img" src="'.$row['prod_upc_img'].'" alt="UPC Image">
                    </div>';
                $total += $row['prod_price'] * $row['quantity'];
            }
            $content .= '</div>
            <div class="total-section"></div>';
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }

    echo $content;
?>
