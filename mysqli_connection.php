<?php 
$sqli = mysqli_connect("localhost","root","12345678","new_un");

if(mysqli_connect_errno()){
    echo "error" . mysqli_connect_error();
}
?>