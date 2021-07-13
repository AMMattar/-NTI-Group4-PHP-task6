<?php
require './connection_NTI.php';

$id = $_GET['id'];
$id = filter_var($id,FILTER_SANITIZE_NUMBER_INT);

//to delete photo
$sql2 = 'select * from test_crud where id ='.$id;
$op2 = mysqli_query($con, $sql2);
$data2 = mysqli_fetch_assoc($op2);

$photo = $data2['image'];


$message = '';
if(!filter_var($id, FILTER_VALIDATE_INT)){
    $_SESSION['message'] = "Invalid Id";
    header("Location: index_task.php");
}


// cleaning input data
function cleaning($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errorMessages = array();

if($_SERVER['REQUEST_METHOD'] == "POST"){

    // if empty and validation 

    $name = cleaning($_POST['name']);
    if(empty($name)){
        $errorMessages['name']='please enter your name';
    }
    $content = cleaning($_POST['content']);
    if(empty($content)){
        $errorMessages['content']='content can\'t be empty';
    }
    //upload Images
    $tmp_path = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name']; 
    if(!empty($fileName)){
        $file_check = "jpg"; //will be array if the website allow more extensions
        $nameArray = explode("." , $fileName); 
        $fileExtension = strtolower($nameArray[1]);
        if($file_check != $fileExtension){
            $errorMessages['uploaded file'] = 'uploaded files can only be jpg';
        }else{
            $finalName = rand().time().'.'.$fileExtension;
            $disFolder = './uploads/';
            $disPath = $disFolder.$finalName;
            if(move_uploaded_file($tmp_path,$disPath)){
                unlink($disFolder.$photo);
            }else{
                $errorMessages['uploaded file']='uploaded file failed please try again';
            }
        }
        
    }else{
        $errorMessages['uploaded file'] = 'please upload your Image file';
    }
    if(count($errorMessages) == 0){
    //echo 'Valid Data';
    $sql = "update test_crud set name='$name', content='$content', image='$finalName' where id = '$id'";
    $op = mysqli_query($con, $sql);
    if($op){
        $_SESSION['message'] = 'Record Updated';
        header("Location: index_task.php");
    }else {
        $errorMessages['sqlOperation'] = 'error in executing SQL try Again';
    }
}else{
    foreach($errorMessages as $key=> $value){
        echo '* '.$key.' : '.$value.'<br>';
    }
}

}


    $sql = 'select * from test_crud where id ='.$id;
    $op = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($op);



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Register</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Edit Data</h2>
  <form  method="post"  action="edit.php?id=<?php echo $data['id'];?>"  enctype ="multipart/form-data">
 
  <div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text"  name="name" value= "<?php echo $data['name'];?>" class="form-control" id="exampleInputName" aria-describedby="" placeholder="Enter Name">
  </div>


  <div class="form-group">
    <label for="exampleInputEmail1">Content</label>
    <input type="text" name="content" value= "<?php echo $data['content'];?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Image</label>
    <input type="file"  name="image" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>
  
  <button type="submit" class="btn btn-primary">Update</button>
</form>
</div>

</body>
</html>

