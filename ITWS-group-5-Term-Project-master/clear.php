<?php
  /* Delete an actor */
  
  /* Create a new database connection object, passing in the host, username,
     password, and database to use. The "@" suppresses errors. */
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
      // get our id and cast as an integer
      $id = (int) $_POST["id"];
      
      // Setup a prepared statement. 
      $query = "update spending set count=0,total=0.00 where id = ?";
      $statement = $db->prepare($query);
      // bind our variable to the question mark
      $statement->bind_param("i",$id);
      // make it so:
      $statement->execute();
      
      // return a json object that indicates success
      $success = array('errors'=>false,'message'=>'Clear successful');
      echo json_encode($success);
      
      // close the prepared statement obj and the db connection
      $statement->close();
      $db->close();
    }
  }
?>
