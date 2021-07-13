<?php

require './connection_NTI.php';
$id = $_GET['id'];
$id = filter_var($id,FILTER_SANITIZE_NUMBER_INT);
$message = '';
if(filter_var($id, FILTER_VALIDATE_INT)){
    $sql = 'delete from test_crud where id='.$id;
    $op = mysqli_query($con,$sql);
    if($op){
        $message = 'content deleted';
    }else{
        $message = 'error, please try again';
    }
}else{
    $message ='Invalid id';
}

$_SESSION['message'] = $message;

header("Location: index_task.php");
?>