<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
  $movie = $_REQUEST['movieTitle'];
  $date = $_REQUEST['performanceDate'];
	$db->openConnection();
  //$reservationNbr = 5;
  $reservationNbr = $db->makeReservation($date, $movie, $userId);
	$db->closeConnection();
?>

<html>
<head><title>Booking 4</title><head>
<body><h1>Booking 4</h1>
  One ticket booked. 
  Booking number: <?php print $reservationNbr ?>
	<p>
	<p>
	<form method=post action="booking3.php">
    <input type="hidden" name="movieTitle" value="<?php echo $movie; ?>" >
    <input type="hidden" name="performanceDate" value="<?php echo $date; ?>" >
		<input type=submit value="Add one more reservation">
	</form>
	<form method=post action="booking1.php">
		<input type=submit value="New Booking">
	</form>
</body>
</html>
