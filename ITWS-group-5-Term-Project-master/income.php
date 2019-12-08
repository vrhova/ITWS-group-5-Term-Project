<?php 
  include('includes/init.inc.php'); // include the DOCTYPE and opening tags
  include('includes/functions.inc.php'); // functions
?>
<title>Spending</title>   

<?php 
  include('includes/head.inc.php'); 
  // include global css, javascript, end the head and open the body
?>

<h1>Spending</h1>
 
<?php include('includes/menubody.inc.php'); ?>

<?php
  $dbOk = false;
  
  /* Create a new database connection object, passing in the host, username,
     password, and database to use. The "@" suppresses errors. */
  @ $db = new mysqli('localhost', 'root', '1234', 'x');
  
  if ($db->connect_error) {
    echo '<div class="messages">Could not connect to the database. Error: ';
    echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
  } else {
    $dbOk = true; 
  }

  // Now let's process our form:
  // Have we posted?
  $havePost = isset($_POST["save"]);
  
  // Let's do some basic validation
  $errors = '';
  if ($havePost) {
    
    // Get the output and clean it for output on-screen.
    // First, let's get the output one param at a time.
    // Could also output escape with htmlentities()
    $category = htmlspecialchars(trim($_POST["category"]));  
    $total = htmlspecialchars(trim($_POST["total"]));
    
    $focusId = ''; // trap the first field that needs updating, better would be to save errors in an array
    
    if ($category == 'default') {
        $errors .= '<li>Please select a category</li>';
      }

    if ($total == '') {
      $errors .= '<li>Amount Spent may not be blank</li>';
      if ($focusId == '') $focusId = '#total';
    }
  
    if ($errors != '') {
      echo '<div class="messages"><h4>Please correct the following errors:</h4><ul>';
      echo $errors;
      echo '</ul></div>';
      echo '<script type="text/javascript">';
      echo '  $(document).ready(function() {';
      echo '    $("' . $focusId . '").focus();';
      echo '  });';
      echo '</script>';
    } else { 
      if ($dbOk) {
        // Let's trim the input for inserting into mysql
        // Note that aside from trimming, we'll do no further escaping because we
        // use prepared statements to put these values in the database.
        $categoryForDb = trim($_POST["category"]);  
        $totalForDb = trim($_POST["total"]);
        
        // Setup a prepared statement. Alternately, we could write an insert statement - but 
        // *only* if we escape our data using addslashes() or (better) mysqli_real_escape_string().
        $insQuery = "update income set count=count + 1,total=total+? where category = ?";
        $statement = $db->prepare($insQuery);
        // bind our variables to the question marks
        $statement->bind_param("ss",$totalForDb,$categoryForDb);
        // make it so:
        $statement->execute();
        
        // give the user some feedback
        echo '<div class="messages"><h4>Success: Income Updated.</h4>';
        echo '$' . $total . ' added to ' . $category . '</div>';
        
        // close the prepared statement obj 
        $statement->close();
      }
    } 
  }
?>

<h3>Edit Income</h3>
<form id="addForm" name="addForm" action="income.php" method="post" onsubmit="return validate(this);">
  <fieldset> 
    <div class="formData">
        
        <div class="dropdown">
            <select name="category">
                <option value="default">Category</option>
                <option value="Paycheck">Paycheck</option>
                <option value="Gift">Gift</option>
                <option value="Found">Found</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <label class="field" for="total">Amount Gained: $</label>
        <div class="value"><input type="number" step="0.01" min="0" size="60" value="<?php if($havePost && $errors != '') { echo $total; } ?>" name="total" id="total"/></div>
      
        <input type="submit" value="save" id="save" name="save"/>
    </div>
  </fieldset>
</form>

<h3>Income</h3>
<table id="incomeTable">
<?php
  if ($dbOk) {

    $query = 'select * from income order by category';
    $result = $db->query($query);
    $numRecords = $result->num_rows;
    
    echo '<tr><th>Category:</th><th>Total:</th><th>Times Recieved:</th><th>Clear:</th></tr>';
    for ($i=0; $i < $numRecords; $i++) {
      $record = $result->fetch_assoc();
      if ($i % 2 == 0) {
        echo "\n".'<tr id="' . $record['id'] . '"><td>';
      } else {
        echo "\n".'<tr class="odd" id="' . $record['id'] . '"><td>';
      }
      echo htmlspecialchars($record['category']);
      echo '</td><td>';
      echo htmlspecialchars('$' . $record['total']);
      echo '</td><td>';
      echo htmlspecialchars($record['count']);
      echo '</td><td>';
      echo '<img src="resources/delete.png" class="clear" width="16" height="16" alt="clear"/>';
      echo '</td></tr>';
      // Uncomment the following three lines to see the underlying 
      // associative array for each record.
      /*echo '<tr><td colspan="3" style="white-space: pre;">';
      print_r($record);
      echo '</td></tr>';*/
    }
    
    $result->free();
    
    // Finally, let's close the database
    $db->close();
  }
  
?>
</table>

<?php include('includes/foot.inc.php'); 
  // footer info and closing tags
?>
