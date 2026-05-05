<?php
$conn = new mysqli('localhost','root','','kartifydb');
if(!$conn){
    echo "Error!: {$conn->connect_error}";
}
?>