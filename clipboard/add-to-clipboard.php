<?php
    require_once('../user_class.php');
    $user = new User();
    $response = array();

    if(isset($_POST['prodId']) && isset($_POST['userId'])){
        try{
            $stmt = $user->runQuery("INSERT INTO clipboard (user_id, prod_id, quantity) VALUES (:u_id, :p_id, 1)");
            $stmt->bindParam("u_id", $_POST['userId']);
            $stmt->bindParam("p_id", $_POST['prodId']);
            if($stmt->execute()){
                $response['status'] = "success";
                $response['text'] = "The product has been added to the clipboard!";
            }
        }
        catch(PDOException $e){
            $response['status'] = "error";
            $response['text'] = "The product is already in the clipboard!";
        }
        echo json_encode($response);
    }
?>
