<?php
	require_once('database.inc.php');
	require_once('pallet.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
  $barcode = $_REQUEST['barcode'];
	$db->openConnection();
	$pallet = $db->getPallet($barcode);
	$db->closeConnection();
?>

<html>
<head><title>Info</title><head>
<body><h1>Pallet Info for pallet <?php print $barcode ?></h1>
  Data for selected pallet: 
  <p>
	<form method=post action="booking4.php">
    <table>
      <tr>
        <td>Location:</td>
        <td><?php print $pallet->getLocation() ?></td>
      </tr><tr>
        <td>Blocked:</td>
        <td><?php print $pallet->getBlocked() ?></td>
      </tr><tr>
        <td>Prod Date:</td>
        <td><?php print $pallet->getProducedDate() ?></td>
      </tr><tr>
        <td>Prod Time:</td>
        <td><?php print $pallet->getProducedTime() ?></td>
      </tr><tr>
        <td>Cookie Name:</td>
        <td><?php print $pallet->getCookieName() ?></td>
      </tr>
    </table>
	</form>
</body>
</html>