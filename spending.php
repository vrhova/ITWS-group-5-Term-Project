<?php 
  include('includes/init.inc.php'); 
  include('includes/functions.inc.php'); 
?>
<title>Spending</title>   

<?php 
  include('includes/head.inc.php'); 
?>

<h1>Spending</h1>
 
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
        
        $insQuery = "update spending set count=count + 1,total=total+? where category = ?";
        $statement = $db->prepare($insQuery);
        $statement->bind_param("ss",$totalForDb,$categoryForDb);
        $statement->execute();
        
        echo '<div class="messages"><h4>Success: Spending Updated.</h4>';
        echo '$' . $total . ' added to ' . $category . '</div>';
        
        $statement->close();
      }
    } 
  }
?>

<h3>Edit Spending</h3>
<form id="addForm" name="addForm" action="spending.php" method="post" onsubmit="return validate(this);">
  <fieldset> 
    <div class="formData">
        
        <div class="dropdown">
            <select name="category">
                <option value="Category">Category</option>
                <option value="Groceries">Groceries</option>
                <option value="Eating out">Eating out</option>
                <option value="Clothing">Clothing</option>
                <option value="Gas">Gas</option>
                <option value="Entertainment">Entertainment</option>
                <option value="Self care">Self care</option>
                <option value="Bills">Bills</option>
                <option value="Repairs">Repairs</option>
                <option value="Personal">Personal</option>
            </select>
        </div>

        <label class="field" for="total">Amount Spent: $</label>
        <div class="value"><input type="number" step="0.01" min="0" size="60" value="<?php if($havePost && $errors != '') { echo $total; } ?>" name="total" id="total"/></div>
      
        <input type="submit" value="save" id="save" name="save"/>
    </div>
  </fieldset>
</form>

<h3>Spending</h3>
<table id="spendingTable">
<?php
  if ($dbOk) {

    $query = 'select * from spending order by category';
    $result = $db->query($query);
    $numRecords = $result->num_rows;
    
    echo '<tr><th>Category:</th><th>Total:</th><th>Times Spent:</th><th>Clear:</th></tr>';
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
    }
    
    $result->free();
    
    $db->close();
  }
  
?>
</table>

