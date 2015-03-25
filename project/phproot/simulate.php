<?php
    require_once('database.inc.php');    
    session_start();
    $db = $_SESSION['db'];
    $db->openConnection();
    $cookieNames = $db->getProducts();
    $db->closeConnection();
    date_default_timezone_set('Europe/Stockholm');
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
      <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" >
      <p>Time to be produced: </p>
      <input type="time" name="time" value="<?php echo date('H:i:s'); ?>" >   
      <p><input type="submit" value="Prouce pallet"></p>
    </form>

    <form action="mainpage.php">
      <input type="submit" value="Back to main page">
    </form>
  </p>
</body>
</html>

