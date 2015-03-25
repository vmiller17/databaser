<?php
require_once('pallet.inc.php');
date_default_timezone_set('Europe/Stockholm');

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
   * @param time The production time
   * @param name The cookie type name
   * @return barcode if a pallet is produced. -1 if not.
   */
  public function producePallet($date, $time, $name) {
    if (strtotime($date) == false | strtotime($time) == false) {
      return -1;
    }
    $this->conn->beginTransaction();

    // Get list of ingredients
    $sql = "select ingredientName, quantity from Recipes where cookieName = ?";
    $result = $this->executeQuery($sql, array($name));

    // Update ingredient list
    foreach ($result as $line) {
      $sql = "update Ingredients set quantity = quantity - ? where name = ?";
      $result2 = $this->executeUpdate($sql, array($line['quantity'],$line['ingredientName']));
      if (! $result2 == 1) {
        $this->conn->rollback();
        return -1;
      }
    }

    // Create the pallet
    $sql = "insert into Pallets(location,blocked,producedDate,producedTime,cookieName) values('Freezer',0,?,?,?)";
    $result = $this->executeUpdate($sql, array($date, $time, $name));

    if (! $result == 1) {
      $this->conn->rollback();
      return -1;
    }

    // Retrieve barcode
    $sql = "select max(barcode) from Pallets";
    $result = $this->executeQuery($sql);

    // Commit changes
    $this->conn->commit();
    return $result[0][0];
  }



  /**
   * Blocks pallets within a certain time intervall on a specific date,
   * containing a specific product.
   *
   * @param product The product to be blocked
   * @param date The production date
   * @param startTime The start time of the intervall
   * @param endTime The start end of the intervall
   * @return barcode if a pallet is produced. -1 if not.
   */
  public function blockIntervall($product, $date, $startTime, $endTime) {
    if (strtotime($date) == false | strtotime($startTime) == false | strtotime($endTime) == false) {
      return -1;
    }
    
    // See how many pallets that are already blocked
    $sqlBefore = "select barcode from pallets where cookieName = ? 
    and producedDate = ? 
    and blocked = 1
    and producedTime >= ? and producedTime <= ?";
    $resultBefore = $this->executeQuery($sqlBefore, array($product, $date, $startTime, $endTime));

    foreach ($resultBefore as $row) {
      $palletsBefore[] = $this->getPallet($row['barcode']);
    }

    // Updating the database
    $this->conn->beginTransaction();
    $sql = "update pallets set blocked = 1 where cookieName = ? and producedDate = ? and producedTime >= ? and producedTime <= ?";
    $result = $this->executeUpdate($sql, array($product, $date, $startTime, $endTime));

    // Rollback if someting went wrong
    if (! $result == 1) {
      $this->conn->rollback();
    } else {
      $this->conn->commit();
    }

    // How many pallets are blocked after update
    $sqlAfter = "select barcode from pallets where cookieName = ? 
    and producedDate = ? 
    and blocked = 1
    and producedTime >= ? and producedTime <= ?";
    $resultAfter = $this->executeQuery($sqlAfter, array($product, $date, $startTime, $endTime));

    foreach ($resultAfter as $row) {
      $palletsAfter[] = $this->getPallet($row['barcode']);
    }

    // Number of changed values
    $diff = count($palletsAfter)-count($palletsBefore);

    return array($diff, $palletsAfter);

  }

	/**
   * Get all products available in the database
   *
   * @return cookieNames The names of all the cookies available
   */
  public function getProducts() {

    $sql = "select name from CookieTypes";
    $result = $this->executeQuery($sql);

    foreach ($result as $row) {
      $cookieName = $row['name'];
      $cookieNames[] = $cookieName;
    }

    return $cookieNames;

  }

  /**
   * Get all barcodes available in the database
   *
   * @return barcodes The barcodes of all the pallets
   */
  public function getAllBarcodes() {

    $sql = "select barcode from pallets";
    $result = $this->executeQuery($sql);

    foreach ($result as $row) {
      $barcodes[] = $row['barcode'];
    }

    return $barcodes;

  }


  /**
   * Get all production dates available in the database
   *
   * @return dates All the dates when something was produced
   */
  public function getAllProdDates() {

    $sql = "select distinct producedDate from pallets";
    $result = $this->executeQuery($sql);

    foreach ($result as $row) {
      $dates[] = $row['producedDate'];
    }

    return $dates;

  }

  /**
   * Get all possible locations for a pallet
   *
   * @return locations The possible locations for a pallet
   */
  public function getAllLocations() {

    $sql = "select location from Locations";
    $result = $this->executeQuery($sql);

    foreach ($result as $row) {
      $locations[] = $row['location'];
    }

    return $locations;

  }

  /**
   * A more general search method
   * 
   * @param barcode The barcode to be search. "--All--" if all included
   * @param location The location wanted. "--All--" if all included
   * @param blocked True or False. "--All--" if all included
   * @param date The wanted date to be searched. "--All--" if all included
   * @param startTime The start time of a time intervall
   * @param endTime The end time of a time intervall
   * @param cookieName The name of the wanted product
   * @return barcodes
   */
  public function generalSearch($barcode,$location,$blocked,$date,$startTime,$endTime,$cookieName) {

    $and = "";

    if ($barcode != "--All--") {
      $SQLbarcode = $and." barcode = "."?";
      $and = " and ";
      $addon[] = $SQLbarcode;
      $params[] = $barcode;
    }

    if ($location != "--All--") {
      $SQLlocation = $and." location = "."?";
      $and = " and ";
      $addon[] = $SQLlocation;
      $params[] = $location;
    }

    if ($blocked != "--All--") {
      $SQLblocked = $and." blocked = "."?";
      $and = " and ";
      $addon[] = $SQLblocked;
      if ($blocked == "Yes") {
        $params[] = 1;
      } else {
        $params[] = 0;
      }
    }

    $SQLtime = $and." producedTime >= "."?"." and producedTime <= "."?";
    $and = " and ";
    $addon[] = $SQLtime;
    $params[] = $startTime;
    $params[] = $endTime;

    if ($cookieName != "--All--") {
      $SQLcookieName = $and." cookieName = "."?";
      $and = " and ";
      $addon[] = $SQLcookieName;
      $params[] = $cookieName;
      //print $cookieName;
    }
  
    // Create final sql query
    $sql = "select barcode from pallets where";
    foreach ($addon as $add) {
      $sql = $sql.$add;
    }

    // Execute Query
    $result = $this->executeQuery($sql, $params);

    // Get Pallets
    foreach ($result as $row) {
      $pallets[] = $this->getPallet($row['barcode']);
    }

    return $pallets;

  }

}
?>
