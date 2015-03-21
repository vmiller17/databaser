<?php
class Pallet {
  private $barcode;
  private $location;
  private $blocked;
  private $producedDate;
  private $producedTime;
  private $cookieName;

  public function __construct($date, $movie, $theaterName, $availableSeats) {
    $this->barcode = $badcode;
    $this->location = $location;
    $this->blocked = $blocked;
    $this->producedDate = $producedDate;
    $this->producedTime = $producedTime;
    $this->cookieName = $cookieName;
  }

  public function getBarcode() {
    return $this->barcode;
  }

  public function getLocation() {
    return $this->location;
  }

  public function getBlocked() {
    return $this->blocked;
  }

  public function getProducedDate() {
    return $this->producedDate;
  }

  public function getProducedTime() {
    return $this->producedTime;
  }

  public function getCookieName() {
    return $this->cookieName;
  }

}


?>
