<?php
session_start();
include "../db.php";

if(isset($_SESSION['user_id'])) {

    if($_SESSION['user_role'] == "admin"){

        if(isset($_GET['id'])){

            $order_id = $_GET['id'];

            $sql = "DELETE FROM single_order WHERE id = '$order_id'";
            $result = mysqli_query($conn, $sql);

            if(!$result){
                header("Location: vieworder.php?msg=error");
            }
            else{
                header("Location: vieworder.php?msg=deleted");
            }

        }

    }
    else{
        echo "Go to user dashboard";
    }

}
else{
    header("Location: ../index.php");
}
?>