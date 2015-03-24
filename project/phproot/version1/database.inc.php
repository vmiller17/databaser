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


  public function blockPallets($product, $date, $startTime, $endTime) {
    $this->conn->beginTransaction();
    // zicvic: Do to all that we need here or do we need a "for update"?
    $sqlBefore = "select barcode from pallets where cookieName = ? 
    and producedDate = ? 
    and blocked = 1
    and producedTime >= ? and producedTime <= ?";
    $resultBefore = $this->executeQuery($sqlBefore, array($product, $date, $startTime, $endTime));

    foreach ($resultBefore as $row) {
      $palletsBefore[] = $this->getPallet($row['barcode']);
    }
    // zicvic: Problem when a pallet is allready blocked.

    //$sql = "update pallets set blocked = 1 where cookieName = ? and producedDate = ? and producedTime > ? and producedTime < ?";
    //$result = $this->executeUpdate($sql, array($product, $date, $startTime, $endTime));

    $sqlAfter = "select barcode from pallets where cookieName = ? 
    and producedDate = ? 
    and blocked = 1
    and producedTime >= ? and producedTime <= ?";
    $resultAfter = $this->executeQuery($sqlAfter, array($product, $date, $startTime, $endTime));

    foreach ($resultAfter as $row) {
      $palletsAfter[] = $this->getPallet($row['barcode']);
    }

    $diff = count($palletsAfter)-count($palletsBefore);

    if (! $result == 1) {
      $this->conn->rollback();
      //return -1;
    } else {
      $this->conn->commit();
      //return $barcodes;
    }

    return array($diff, $palletsAfter);

  }


  public function getPalletsProduct($product) {

    $sql = "select barcode from pallets where cookieName = ?";
    $result = $this->executeQuery($sql, array($product));

    foreach ($result as $row) {
      $barcode = $row['barcode'];
      $pallets[] = $this->getPallet($barcode);
    }

    return $pallets;

  }


  public function getPalletsInterval($date, $startTime, $endTime) {

    $sql = "select barcode from pallets where producedDate = ? 
    and producedTime > ? and producedTime < ?";
    $result = $this->executeQuery($sql, array($date, $startTime, $endTime));

    foreach ($result as $row) {
      $barcode = $row['barcode'];
      $pallets[] = $this->getPallet($barcode);
    }

    return $pallets;

  }


  public function getBlocked() {

    $sql = "select barcode from pallets where blocked = 1";
    $result = $this->executeQuery($sql);

    foreach ($result as $row) {
      $barcode = $row['barcode'];
      $pallets[] = $this->getPallet($barcode);
    }

    return $pallets;

  }
	


  public function getProducts() {

    $sql = "select name from CookieTypes";
    $result = $this->executeQuery($sql);

    foreach ($result as $row) {
      $cookieName = $row['name'];
      $cookieNames[] = $cookieName;
    }

    return $cookieNames;

  }

  public function getAllBarcodes() {

    $sql = "select barcode from pallets";
    $result = $this->executeQuery($sql);

    foreach ($result as $row) {
      $barcodes[] = $row['barcode'];
    }

    return $barcodes;

  }

  public function getAllProdDates() {

    $sql = "select distinct producedDate from pallets";
    $result = $this->executeQuery($sql);

    foreach ($result as $row) {
      $dates[] = $row['producedDate'];
    }

    return $dates;

  }

  /**
   * A more general search method
   * 
   *
   * @param 
   * @return barcodes
   */
  public function generalSearch($barcode,$location,$blocked,$date,$startTime,$endTime,$cookieName) {

    $and = "";

    if ($barcode != "--All--") {
      $SQLbarcode = $and." barcode = "."?";
      $and = " and ";
      $addon[] = $SQLbarcode;
      $params[] = $barcode;
      //print $barcode;
    }

    if ($location != "--All--") {
      $SQLlocation = $and." location = "."?";
      $and = " and ";
      $addon[] = $SQLlocation;
      $params[] = $location;
      print $location;
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
      //print $blocked;
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
  
    if (count($addon) < 1) {
      $sql = "select barcode from pallets";
    } else {
      $sql = "select barcode from pallets where";
      //$sql = "select barcode from pallets where";
      foreach ($addon as $add) {
        $sql = $sql.$add;
      }
    }

    
    $result = $this->executeQuery($sql, $params);
    //$result = $this->executeQuery($sql);

    foreach ($result as $row) {
      $pallets[] = $this->getPallet($row['barcode']);
    }

    return $pallets;

  }


  public function getAllLocations() {

    $sql = "select location from Locations";
    $result = $this->executeQuery($sql);

    foreach ($result as $row) {
      $locations[] = $row['location'];
    }

    return $locations;

  }

}
?>
