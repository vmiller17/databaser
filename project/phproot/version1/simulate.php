<?php
    require_once('database.inc.php');    
    session_start();
    $db = $_SESSION['db'];
    $db->openConnection();
    $cookieNames = $db->getProducts();
    $db->closeConnection();
?>

<html>
<head><title>Produce Pallet</title><head>
<body><h1>Produce Pallet</h1>
  <p>
    <form method=post action="producePallet.php">
      <p>Cookie type to be produced: </p>
      <p><select name="name">
            <?php
            foreach ($cookieNames as $name) {
                ?>
                <option selected><?php print $name ?></option>
                <?php
            }
            ?>
        </select> </p> 
      <p>Date to be produced: </p>
      <p><input type="text" size="20" name="date"></p>
      <p>Time to be produced: </p>
      <p><input type="text" size="20" name="time"></p>    
      <p><input type="submit" value="Prouce pallet"></p>
    </form>
  </p>
</body>
</html>