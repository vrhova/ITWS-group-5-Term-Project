<?php 
  include('includes/init.inc.php'); 
  include('includes/functions1.inc.php'); 
?>
<title>Income</title>   

<?php 
  include('includes/head.inc.php'); 
?>

<h1>Income</h1>
 
<?php include('includes/menubody.inc.php'); ?>

<?php
  $dbOk = false;
  
  @ $db = new mysqli('localhost', 'root', '1234', 'x');
  
  if ($db->connect_error) {
    echo '<div class="messages">Could not connect to the database. Error: ';
    echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
  } else {
    $dbOk = true; 
  }

  $havePost = isset($_POST["save"]);
  
  $errors = '';
  if ($havePost) {
    
    $category = htmlspecialchars(trim($_POST["category"]));  
    $total = htmlspecialchars(trim($_POST["total"]));
    
    $focusId = ''; 
    
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
  
        $categoryForDb = trim($_POST["category"]);  
        $totalForDb = trim($_POST["total"]);
        
        
        $insQuery = "update income set count=count + 1,total=total+? where category = ?";
        $statement = $db->prepare($insQuery);
   
        $statement->bind_param("ss",$totalForDb,$categoryForDb);
    
        $statement->execute();
        
      
        echo '<div class="messages"><h4>Success: Income Updated.</h4>';
        echo '$' . $total . ' added to ' . $category . '</div>';
        
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
                <option value="Category">Category</option>
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
      echo '<img src="resources/delete.png" class="clearIncome" width="16" height="16" alt="clear"/>';
      echo '</td></tr>';
    }
    
    $result->free();
    
    $db->close();
  }
  
?>
</table>

