<?php
  require_once('database.inc.php');

  session_start();
  $db = $_SESSION['db'];
  $date = $_REQUEST['date'];
  $time = $_REQUEST['time'];
  $name = $_REQUEST['name'];

  $db->openConnection();
    $barcode = $db->producePallet($date, $time, $name);
  $db->closeConnection();
?>

<html>
<head><title>Pallet Creation Results</title><head>
<body><h1>Pallet Creation Results</h1>
<p>
<?php if ($barcode == -1) {
  print 'Pallet creation failed!';
} else {
  print 'Created pallet with barcode ';
  print $barcode; 
  print '!';
}?>
</p>
</body>
</html>
