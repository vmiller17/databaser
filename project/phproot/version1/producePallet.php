<?php
  require_once('database.inc.php');

  session_start();
  $db = $_SESSION['db'];
  $date = $_REQUEST['date'];
  $time = $_REQUEST['time'];
  $name = $_REQUEST['name'];

  $db->openConnection();
    $pallet = $db->producePallet($date, $time, $name);
  $db->closeConnection();
  header("Location: simulate.php");
?>

<html>
<head><title>Nothing</title><head>
<body><h1>...</h1>
</body>
</html>
