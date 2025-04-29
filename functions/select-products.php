<?php


function selectProducts($conn, $cat_id, $sort_type, $start_point, $per_page){
    $query = "";
    switch($sort_type){
        case "new":
            $query = "SELECT * FROM products WHERE category_id = :cat ORDER BY add_date DESC LIMIT :s_point, :p_page";
            break;
        case "az":
            $query = "SELECT * FROM products WHERE category_id = :cat ORDER BY prod_name ASC LIMIT :s_point, :p_page";
            break;
        case "za":
            $query = "SELECT * FROM products WHERE category_id = :cat ORDER BY prod_name DESC LIMIT :s_point, :p_page";
            break;
        case "pasc":
            $query = "SELECT * FROM products WHERE category_id = :cat ORDER BY prod_price ASC LIMIT :s_point, :p_page";
            break;
        case "pdesc":
            $query = "SELECT * FROM products WHERE category_id = :cat ORDER BY prod_price DESC LIMIT :s_point, :p_page";
            break;
        default:
            echo "Niepoprawny rodzaj sortowania";
            return;
            break;
    }

    try {
        $stmt =  $conn->runQuery($query);
        $stmt->bindParam(":cat", $cat_id);
        $stmt->bindParam(":s_point", $start_point);
        $stmt->bindParam(":p_page", $per_page);
        $stmt->execute();
        $counter = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $prod_id = $row['prod_id'];
            $prod_name = $row['prod_name'];
            $prod_desc = $row['prod_desc'];
            $prod_price = $row['prod_price'];
            $prod_img = $row['prod_img'];
            $prod_upc_img = $row['prod_upc_img'];
            $prod_spec = $row['prod_spec'];
            $prod_code = $row['prod_code'];



            if($counter == 0)
                echo "<div class='row'>";   
            echo "
            <div class='col-xl-3 col-lg-6 col-md-6'>
            <!-- Header Section -->
            <div class='card-header'>
                <div class='row align-items-center'>
                    <div class='col-5 text-left border-right'>
                        <h6 class='mb-0'>$prod_code</h6>
                    </div>
                    <div class='col-7 text-left'>
                    <h6 class='font-weight-bold mb-0' style='white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>$prod_name</h6>
                    </div>
                </div>
                </div>
                <div class='card-body'>
                    <p class='text-center mb-0'>$prod_desc</p>
                </div>
               
                <a href='product_details.php?prod_id=$prod_id'>
                               
                        <div class='card card-product'>
                            <img class='card-img-top' src='$prod_img' alt='$prod_name' />
                            <div class='text-center'>
                            <p class='text-center mb-0'>$prod_spec</p>
                            </div>     
                            
                            <?php if($prod_upc_img != ''){ ?> 
                              <img class='card-img-top' src='$prod_upc_img' alt='$prod_upc_img' /> 
                            <?php } ?>

                            <div class='card-img-overlay'>
                                <div class='card-title row'>
                                    <h5 class='col-6' text-center>$prod_name</h5>
                                </div>
                                <div class='text-center'>
                                    <a href='product_details.php?prod_id=$prod_id'><button class='btn btn-outline-white' type='button'>See more..</button></a>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                ";
            if($counter == 3){
                echo "</div>";
                $counter = 0;
                continue;
            }
            $counter++;
        }
        if($counter != 0)
            echo "</div>";
    } catch (PDOException $e) {
        echo $e->getMessage();
    }    
}

function selectLatestProducts($conn){
    try {
        $stmt =  $conn->runQuery("SELECT * FROM products ORDER BY add_date DESC LIMIT 3");
        $stmt->execute();
        $counter = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $prod_id = $row['prod_id'];
            $prod_name = $row['prod_name'];
            $prod_desc = $row['prod_desc'];
            $prod_price = $row['prod_price'];
            $prod_img = $row['prod_img'];
            if($counter == 2){
                echo "<div class='col-lg-4 col-md-6 offset-md-3 offset-lg-0'>";
            }
            else{
                echo "<div class='col-lg-4 col-md-6'>";
            }
            echo "
                <a href='product_details.php?prod_id=$prod_id'>
                    <div class='card card-product'>
                        <img class='card-img-top' src='$prod_img' alt='$prod_name' />
                        <div class='card-img-overlay'>
                            <div class='card-title row'>
                            <h5 class='col-6'>$prod_name</h5>
                            <h5 class='col-6 text-right'>$prod_price </h5>
                            </div>
                            <div class='text-center'>
                                <a href='product_details.php?prod_id=$prod_id'><button class='btn btn-outline-white' type='button'>See more..</button></a>
                            </div>
                        </div>
                    </div>
                </a>
                </div>";
            $counter++;
        }
    }
    catch (PDOException $e){
        echo $e->getMessage();
    }
}

function selectProductDetails($conn, $prod_id){
    try {
        $query = "SELECT prod_name, prod_desc, prod_img, prod_price, category_id, cat_name, prod_upc_img, prod_spec,prod_code 
                    FROM products
                    INNER JOIN categories ON category_id = cat_id
                    WHERE prod_id = :id";
        $stmt =  $conn->runQuery($query);
        $stmt->bindParam(":id", $prod_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    catch (PDOException $e){
        echo $e->getMessage();
    }
}

?>