<?php
require_once('pallet.inc.php');

class Database {
	private $host;
	private $userName;
	private $password;
	private $database;
	private $conn;
	
	/**
	 * Constructs a database object for the specified user.
	 */
	public function __construct($host, $userName, $password, $database) {
		$this->host = $host;
		$this->userName = $userName;
		$this->password = $password;
		$this->database = $database;
	}
	
	/** 
	 * Opens a connection to the database, using the earlier specified user
	 * name and password.
	 *
	 * @return true if the connection succeeded, false if the connection 
	 * couldn't be opened or the supplied user name and password were not 
	 * recognized.
	 */
	public function openConnection() {
		try {
			$this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", 
					$this->userName,  $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			$error = "Connection error: " . $e->getMessage();
			print $error . "<p>";
			unset($this->conn);
			return false;
		}
		return true;
	}
	
	/**
	 * Closes the connection to the database.
	 */
	public function closeConnection() {
		$this->conn = null;
		unset($this->conn);
	}

	/**
	 * Checks if the connection to the database has been established.
	 *
	 * @return true if the connection has been established
	 */
	public function isConnected() {
		return isset($this->conn);
	}
	
	/**
	 * Execute a database query (select).
	 *
	 * @param $query The query string (SQL), with ? placeholders for parameters
	 * @param $param Array with parameters 
	 * @return The result set
	 */
	private function executeQuery($query, $param = null) {
		try {
			$stmt = $this->conn->prepare($query);
			$stmt->execute($param);
			$result = $stmt->fetchAll();
		} catch (PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $result;
	}
	
	/**
	 * Execute a database update (insert/delete/update).
	 *
	 * @param $query The query string (SQL), with ? placeholders for parameters
	 * @param $param Array with parameters 
	 * @return The number of affected rows
	 */
	private function executeUpdate($query, $param = null) {
		try {
      
			$stmt = $this->conn->prepare($query);
			$stmt->execute($param);
      $count = $stmt->rowCount();
		} catch (PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}

		return $count;
	}
	
  /**
   * Returns all information for a pallet.
   *
   * @param barcode The pallet's barcode
   * @return pallet object
   */
  public function getPallet($barcode) {
    $sql = "select location, blocked, producedDate, producedTime, cookieName from Pallets where barcode = ? ";
    $result = $this->executeQuery($sql, array($barcode) ); 
    $result = $result[0];
    return new Pallet( $barcode, $result['location'] , $result['blocked'] , $result['producedDate'] , $result['producedTime'], $result['cookieName']);
  }

  /**
   * Produces a pallet
   *
   * @param date The production date
   * @param movie The production time
   * @param name The cookie type name
   * @return barcode if a pallet is produced. -1 if not.
   */
  public function producePallet($date, $time, $name) {
    $this->conn->beginTransaction();

    $sql = "insert into Pallets(location,blocked,producedDate,producedTime,cookieName) values('freezer',0,?,?,?)";
    $result = $this->executeUpdate($sql, array($date, $time, $name));

    if (! $result == 1) {
      $this->conn->rollback();
      return -1;
    }


    // // Update ingredients...
    // $sql = "update Ingredients set quantity = quantity - ? where name = ?";
    // $result = $this->executeUpdate($sql, array($date, $movie));

    // if (! $result == 1) {
    //   $this->conn->rollback();
    //   return -1;
    // }


    $this->conn->commit();

    return $result[0][0];
  }


  public function blockPallets($product, $date, $startTime, $endTime) {
    $this->conn->beginTransaction();

    $sql1 = "select barcode from pallets where cookieName = ? 
    and producedDate = ? 
    and producedTime > ? and producedTime < ?";
    $result1 = $this->executeQuery($sql1, array($product, $date, $startTime, $endTime));

    foreach ($result1 as $row) {
      $barcodes[] = $row['barcode'];
    }
    // Is this needed or how is the results returned?

    echo $barcodes[0];
    $sql2 = "update pallets set blocked = 1 where cookieName = ? and producedDate = ? and producedTime > ? and producedTime < ?";
    $result2 = $this->executeUpdate($sql2, array($product, $date, $startTime, $endTime));

    if (! $result2 == 1) {
      $this->conn->rollback();
      return -1;
    } else {
      $this->conn->commit();
      return $barcodes;
    }

  }


// 	/**
// 	 * Check if a user with the specified user id exists in the database.
// 	 * Queries the Users database table.
// 	 *
// 	 * @param userId The user id 
// 	 * @return true if the user exists, false otherwise.
// 	 */
// 	public function userExists($userId) {
// 		$sql = "select username from Users where username = ?";
// 		$result = $this->executeQuery($sql, array($userId));
// 		return count($result) == 1; 
// 	}


//	/**
//	 * Makes a reservation for a movie performance
//	 *
//	 * @param date The date
//	 * @param movie The movie title
//	 * @param username The username
//	 * @return reservationNumber if a reservation is made. -1 if not.
//	 */
//	public function makeReservation($date, $movie, $username) {
//    $this->conn->beginTransaction();
//	  $sql = "select bookings,nbrOfSeats from Performances,Theaters where date = ? and movieTitle = ? and Performances.theaterName = Theaters.name";
//	  $result = $this->executeQuery($sql, array($date, $movie)); 
//	  $result= $result[0];
//    $result = $result['nbrOfSeats'] - $result['bookings'];
//
//    if ($result <= 0) {
//      $this->conn->rollback();
//      return -1;
//    }
//
//
//
//
//		$sql = "insert into Reservations(userUsername, performanceDate, performanceMovieTitle) values(?,?,?)";
//		$result = $this->executeUpdate($sql, array($username, $date, $movie));
//
//    if (! $result == 1) {
//      $this->conn->rollback();
//      return -1;
//    }
//
//
//		$sql = "update Performances set bookings = bookings + 1 where date = ? and movieTitle = ?";
//		$result = $this->executeUpdate($sql, array($date, $movie));
//
//    if (! $result == 1) {
//      $this->conn->rollback();
//      return -1;
//    }
//
//		$sql = "select max(resNbr) from Reservations where userUsername = ? and performanceDate = ? and performanceMovieTitle = ?";
//		$result = $this->executeQuery($sql, array($username, $date, $movie));
//
//    if (! $result[0] > 0) {
//      $this->conn->rollback();
//      return -1;
//    }
//
//    $this->conn->commit();
//    
//		return $result[0][0];
//	}

//	/**
//   * Gets the list of movies that are available for viewing.
//	 *
//	 * @return list of movies available
//	 */
//	public function getMovieNames() {
//		$sql = "select title from Movies";
//		$result = $this->executeQuery($sql);
//
//    foreach($result as $row) {
//      $movieNames[] = $row['title'];
//    }
//
//		return $movieNames; 
//	}

//	/**
//   * Returns all information for a performance.
//   *
//   * @param movieName Title of the performance's movie
//   * @param date Date of the performance
//	 * @return performance object
//	 */
//	public function getPerformance($movieName, $date) {
//		$sql = "select theaterName, bookings, nbrOfSeats from Performances,Theaters where movieTitle = ? and date = ? and Performances.theaterName = Theaters.name";
//		$result = $this->executeQuery($sql, array($movieName, $date) ); 
//    $result = $result[0];
//		return new Performance( $date, $movieName, $result['theaterName'], $result['nbrOfSeats']-$result['bookings']);
//	}
  
//	/**
//   * Returns all performances concerning a certain movie title.
//   *
//   * @param movieName Title of the performance's movie
//	 * @return list containing all information regarding the performance
//	 */
//	public function getPerformanceDates($movieName) {
//		$sql = "select date from Performances where movieTitle = ?";
//		$result = $this->executeQuery($sql, array($movieName));
//
//    foreach($result as $row) {
//      $dates[] = $row['date'];
//    }
//
//		return $dates; 
//	}

}
?>
