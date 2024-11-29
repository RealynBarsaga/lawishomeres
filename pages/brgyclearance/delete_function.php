<?php
include('../connection.php');

if(isset($_POST['ids'])){
    $ids = $_POST['ids'];
    foreach($ids as $id){
        $query = "DELETE FROM tblclearance WHERE id='$id'";
        mysqli_query($con, $query) or die(mysqli_error($con));
    }
}
?>
