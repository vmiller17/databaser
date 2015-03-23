<?php
	require_once('database.inc.php');
	require_once('pallet.inc.php');

	session_start();
	$db = $_SESSION['db'];
  	$product = $_REQUEST['product'];
  	$date = $_REQUEST['date'];
  	$startTime = $_REQUEST['startTime'];
  	$endTime = $_REQUEST['endTime'];
	$db->openConnection();
	$blocked = $db->blockPallets($product, $date, $startTime, $endTime);
	$db->closeConnection();
?>

<html>
<head><title>Blocked Pallets</title><head>
<body><h1>Number of blocked pallets</h1>
<?php
print count($blocked);
print ' pallets blocked: ';
?>
<table>
<?php
foreach ($blocked as $barcode ) {
	?>
	<tr><td> Barcode: </td>
		<td><?php print $barcode ?></td></tr>
	<?php
}
?>
</table>

</body>
</html>

<!--
<table>
      <tr>
        <td>Movie:</td>
        <td><?php print $performance->getMovie() ?></td>
      </tr><tr>
        <td>Date:</td>
        <td><?php print $performance->getDate() ?></td>
      </tr><tr>
        <td>Theater:</td>
        <td><?php print $performance->getTheaterName() ?></td>
      </tr><tr>
        <td>Free seats:</td>
        <td><?php print $performance->getAvailableSeats() ?></td>
      </tr>
    </table>
    <p>
-->