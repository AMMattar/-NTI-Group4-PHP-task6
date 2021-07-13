<?php
session_start();
$server = 'localhost';
$dbName = 'nti_test_group4';
$dbUser = 'root';
$dbPassword = '';

$con = mysqli_connect($server, $dbUser, $dbPassword, $dbName);

if($con){
    //echo ' connection done';
}else{
    die('error message'.mysqli_connect_error());
}

?>