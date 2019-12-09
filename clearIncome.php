<?php
  @ $db = new mysqli('localhost', 'root', '1234', 'x');
  
  if ($db->connect_error) {
    $connectErrors = array(
      'errors' => true,
      'errno' => mysqli_connect_errno(),
      'error' => mysqli_connect_error()
    );
    echo json_encode($connectErrors);
  } else {
    if (isset($_POST["id"])) {
      $id = (int) $_POST["id"];
      
      $query = "update income set count=0,total=0.00 where id = ?";
      $statement = $db->prepare($query);
      $statement->bind_param("i",$id);
      $statement->execute();
      
      $success = array('errors'=>false,'message'=>'Clear successful');
      echo json_encode($success);
      
      $statement->close();
      $db->close();
    }
  }
?>
