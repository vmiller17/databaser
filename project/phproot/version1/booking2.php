<?php
	require_once('database.inc.php');
	require_once('performance.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
  $movie = $_REQUEST['movieName'];
	$db->openConnection();
	$dates = $db->getPerformanceDates($movie);
	$db->closeConnection();
?>

<html>
<head><title>Booking 2</title><head>
<body><h1>Booking 2</h1>
	Current user: <?php print $userId ?><br>
	Selected movie: <?php print $movie ?>
	<p>
	Performance dates:
	<p>
	<form method=post action="booking3.php">
    <input type="hidden" name="movieTitle" value="<?php echo $movie; ?>" >
		<select name="performanceDate" size=10>
		<?php
			$first = true;
			foreach ($dates as $date) {
				if ($first) {
					print "<option selected>";
					$first = false;
				} else {
					print "<option>";
				}
				print $date;
			}
		?>
		</select>		
		<input type=submit value="Select date">
	</form>
</body>
</html>
