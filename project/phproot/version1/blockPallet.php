<html>
<head><title>Login</title></head>
<body>

<h1 align="center">Search Pallet</h1>

<form method="post" action="blocked.php">
    Product:
    <input type="text" size="20" name="product" >
    <p>
    Date:
    <input type="text" size="20" name="date" >
    <p>
    Start time:
    <input type="text" size="20" name="startTime" >
    <p>
    End time:
    <input type="text" size="20" name="endTime" >
    <p>
    <input type="submit" value="Block!">
    <p>
</form>

</body>
</html>



<!--<?php
	require_once('database.inc.php');
	require_once('performance.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
  $movie = $_REQUEST['movieTitle'];
  $date = $_REQUEST['performanceDate'];
	$db->openConnection();
	$performance = $db->getPerformance($movie, $date);
	$db->closeConnection();
?>

<html>
<head><title>Booking 3</title><head>
<body><h1>Booking 3</h1>
	Current user: <?php print $userId ?>
	<p>
  Data for selected performance: 
  <p>
	<form method=post action="booking4.php">
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
    <input type="hidden" name="movieTitle" value="<?php echo $performance->getMovie(); ?>" >
    <input type="hidden" name="performanceDate" value="<?php echo $performance->getDate(); ?>" >
		<input type=submit value="Book ticket">
	</form>
</body>
</html>

-->
